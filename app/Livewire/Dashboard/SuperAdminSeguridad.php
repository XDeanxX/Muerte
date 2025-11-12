<?php

namespace App\Livewire\Dashboard;
use App\Models\User;
use App\Models\SecurityQuestion;
use App\Models\UserSecurityAnswer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


use Livewire\Component;

class SuperAdminSeguridad extends Component
{
public $currentTab = 'password';
    
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';
    
    public $securityQuestions = [];
    public $selectedQuestions = [];
    public $securityAnswers = [];
    public $currentUserQuestions = [];

 
    protected $rules = [
        'current_password' => 'required',
        'new_password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[A-Z])(?=.*[!@#$%^&*()])/'
        ],
        'selectedQuestions.0' => 'required|different:selectedQuestions.1,selectedQuestions.2',
        'selectedQuestions.1' => 'required|different:selectedQuestions.0,selectedQuestions.2',
        'selectedQuestions.2' => 'required|different:selectedQuestions.0,selectedQuestions.1',
        'securityAnswers.0' => 'required|string|min:2',
        'securityAnswers.1' => 'required|string|min:2',
        'securityAnswers.2' => 'required|string|min:2',
    ];

    protected $messages = [
        'new_password.regex' => 'La contraseña debe contener al menos una mayúscula y un carácter especial (!@#$%^&*()).',
        'new_password.confirmed' => 'Las contraseñas no coinciden',
        'new_password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'current_password.required' => 'Se requiere la contraseña actual',
        'selectedQuestions.*.different' => 'Debes seleccionar preguntas diferentes.',
        'securityAnswers.*.required' => 'Todas las respuestas son obligatorias.',
        'securityAnswers.*.min' => 'Las respuestas deben tener al menos 2 caracteres.',
    ];

    public function mount()
    {
        $this->securityQuestions = SecurityQuestion::where('is_active', true)->get();
        $this->selectedQuestions = ['', '', ''];
        $this->securityAnswers = ['', '', ''];
        $this->loadCurrentSecurityQuestions();
    }

    public function loadCurrentSecurityQuestions()
    {
        $this->currentUserQuestions = UserSecurityAnswer::where('user_cedula', Auth::user()->persona_cedula)
            ->with('securityQuestion')
            ->get()
            ->map(function($answer) {
                return $answer->securityQuestion->question_text;
            });
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[!@#$%^&*()])/'
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        
        $this->dispatch('show-toast', ['message' => 'Contraseña actualizada exitosamente', 'type' => 'success']);

         $this->new_password='';
    }


    public function updateSecurityQuestions()
    {
        $this->validate([
            'selectedQuestions.0' => 'required|different:selectedQuestions.1,selectedQuestions.2',
            'selectedQuestions.1' => 'required|different:selectedQuestions.0,selectedQuestions.2',
            'selectedQuestions.2' => 'required|different:selectedQuestions.0,selectedQuestions.1',
            'securityAnswers.0' => 'required|string|min:2',
            'securityAnswers.1' => 'required|string|min:2',
            'securityAnswers.2' => 'required|string|min:2',
        ]);

        $user = Auth::user();

        UserSecurityAnswer::where('user_cedula', $user->persona_cedula)->delete();

        for ($i = 0; $i < 3; $i++) {

            $answer = strtolower(trim($this->securityAnswers[$i]));
            
            UserSecurityAnswer::create([
                'user_cedula' => $user->persona_cedula,
                'security_question_id' => $this->selectedQuestions[$i],
                'answer_hash' => Hash::make(strtolower(trim($this->securityAnswers[$i]))), 
            ]);
        }

        $this->selectedQuestions = ['', '', ''];
        $this->securityAnswers = ['', '', ''];
        $this->loadCurrentSecurityQuestions();


        $this->dispatch('show-toast', ['message' => 'Preguntas de seguridad actualizadas exitosamente', 'type' => 'success']);
    }

    
    public function render()
    {
        return view('livewire.dashboard.super-admin-seguridad')->layout('components.layouts.rbac');
    }
}
