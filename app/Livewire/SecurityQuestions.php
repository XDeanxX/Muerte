<?php

namespace App\Livewire;

use App\Models\UserSecurityAnswer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SecurityQuestions extends Component
{

    public $showSecurityNotification= false;

    public function mount() {
        if (auth()->check()) {
            $user = Auth::user();
            $userId = $user->persona_cedula;

            $hasNoAnswers = UserSecurityAnswer::where('user_cedula', $userId)
                                              ->doesntExist();

            if ($hasNoAnswers) {
                $this->showSecurityNotification = true;
            }
        }
 
    }
    public function render()
    {
        return view('livewire.security-questions');
    }
}
