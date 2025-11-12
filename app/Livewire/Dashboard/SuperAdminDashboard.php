<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Models\VisitasVisita;
use App\Models\Comunidades;
use App\Models\Parroquias;
use App\Models\Cargo;
use App\Models\Categorias;
use App\Models\Estatus;
use App\Models\Institucion;
use App\Models\Role;
use App\Models\UserSecurityAnswer;
use App\Models\Trabajador;
use App\Models\SubCategorias;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SuperAdminDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'dashboard';
    public $visitas;
    public $ambitos;
    public $instituciones;
    public $roles;

    public $editingSolicitudId = null;
    public $editingSolicitudObservations = '';
    public $selectedVisitor = null;
    public $search = ''; 
    public $searchCumpleaniero = ''; 
    public $showSecurityNotification = false;

    public $comunidades;

    protected $paginationTheme = 'tailwind';



    

    public function mount()
    {
        $this->activeTab = request()->get('tab', 'dashboard');
        $this->loadData();
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

    public function loadData()
    {
        $this->visitas = VisitasVisita::with(['asistente', 'solicitud'])->orderBy('created_at', 'desc')->get();
        $this->instituciones = Institucion::all();
        $this->roles = Role::all();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function loadCumpleaneros()
    {
        $trabajadores = Trabajador::with(['persona', 'cargo'])->get();

        $hoy = Carbon::now();

        $trabajadores = $trabajadores->map(function ($trabajador) use ($hoy) {
            $persona = $trabajador->persona;

            if ($persona && $persona->nacimiento) {
                $fechaNacimiento = Carbon::parse($persona->nacimiento);
                $cumpleEsteAno = $fechaNacimiento->copy()->year($hoy->year);

                // Si ya pasó este año y no es hoy, se calcula para el próximo
                if ($cumpleEsteAno->isPast() && !$cumpleEsteAno->isSameDay($hoy)) {
                    $cumpleEsteAno->addYear();
                }

                $trabajador->proximo_cumpleanos = $cumpleEsteAno;
                $trabajador->dias_restantes = $hoy->diffInDays($cumpleEsteAno);
            } else {
                // Si no hay fecha válida, se pone al final
                $trabajador->proximo_cumpleanos = Carbon::parse('2099-01-01');
                $trabajador->dias_restantes = 9999;
            }

            return $trabajador;
        });

        return $trabajadores->sortBy('dias_restantes')->values();
    }


    


    public function changeUserRole($userId, $newRole)
    {
        $user = User::find($userId);
        if ($user) {
            $user->role = $newRole;
            $user->save();
            $this->dispatch('show-toast', [
            'message' => 'El rol de '. $user->persona_cedula . ' ha sido cambiado' ,
            'type' => 'success'
            ]);
        }else{
            $this->dispatch('error-toast', [
            'message' => 'El rol de '. $user->persona_cedula . ' no ha podido cambiar' ,
            'type' => 'success']);
        }
    }

    public function updateSolicitudStatus($solicitudId, $newStatus)
    {
      $solicitud = Solicitud::find($solicitudId);

    if ($solicitud) {
        $solicitud->estatus = $newStatus;
        $solicitud->save();
        $this->dispatch('show-toast', [
        'message' => 'El estado de la solicitud ha sido cambiado' ,
        'type' => 'success'
]);
        }
    }


    public function updatedSearch()
    {
        $this->resetPage();
    }

       public function getSolicitudes()
    {
        return Solicitud::with(['persona', 'estatusRelacion', 'subcategoriaRelacion', 'comunidadRelacion'])
                ->where(function ($query){

                    $query->where('solicitud_id' , 'like' , '%'.$this->search .'%')
                    ->orWhere('titulo' , 'like' , '%' . $this->search . '%')
                        ->orWhere('tipo_solicitud' , 'like' , '%' . $this->search . '%')
                           ->orWhereHas('persona' , function($q) {
                            $q->where('nombre' , 'like' , '%' .$this->search. '%');
                           });
                    })
                        ->orderBy('fecha_creacion', 'desc')
                        ->paginate(5 , pageName: 'solicitudPage');
    }

    public function getUsuarios(){
        return User::with('persona')
            ->where(function ($query) {
                $query->where('persona_cedula', 'like', '%' . $this->search . '%')  
                      ->orWhereHas('persona', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('persona_cedula', 'desc')
            ->paginate(5 , pageName: 'usuarioPage');
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin-dashboard', ['usuarios' => $this->getUsuarios(),
        'usuarios_recientes' => User::orderBy('created_at' , 'desc')->paginate(5,  pageName: 'usuariosRecientes'),
        'usuarios_general' => User::all(),
        'solicitudes' => $this->getSolicitudes(),
        'solicitudes_general' => Solicitud::with(['persona', 'estatusRelacion', 'subcategoriaRelacion', 'comunidadRelacion'])
            ->orderBy('fecha_creacion', 'desc')
            ->get(),
        'solicitudes_pendientes' => Solicitud::where('estatus', 1)->paginate(5),
        'cargo' => Cargo::All(),
        'estatusSolicitud' => Estatus::where('sector_sistema', 'solicitudes')->get(),
        'comunidad' => Comunidades::All(),
        'parroquia' => Parroquias::All(),
        'categorias' => Categorias::with('solicitudes')->get(),
        'cumpleaneros' => $this->loadCumpleaneros(),
        ])->layout('components.layouts.rbac');
    }

}