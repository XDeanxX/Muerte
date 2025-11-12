<?php

namespace App\Livewire\Dashboard\SuperadminUsuarios;

use Livewire\Component;

class View extends Component
{
    public $viewingUser;

    public function dispatchCedula($cedula){
        $this->dispatch('pdf' , $cedula);
    }
    public function render()
    {
        return view('livewire.dashboard.superadmin-usuarios.view');
    }
}
