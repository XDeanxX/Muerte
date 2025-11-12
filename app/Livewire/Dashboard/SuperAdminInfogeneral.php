<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class SuperAdminInfogeneral extends Component
{

    public $currentStep='view';

    public function render()
    {
       $usuario = User::with('persona')
    ->where('persona_cedula', auth()->user()->persona_cedula)
    ->first();

        return view('livewire.dashboard.super-admin-infogeneral',['currentUser'=>$usuario])->layout('components.layouts.rbac');
    }
}
