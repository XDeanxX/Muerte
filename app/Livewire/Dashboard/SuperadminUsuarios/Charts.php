<?php

namespace App\Livewire\Dashboard\SuperadminUsuarios;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Charts extends Component
{
    // Propiedades públicas que se envían a la vista
    public $chartData = []; 
    public $totalUsers = 0;
    public $monthsCount = 0;
    public $growthPercentage = 0; 

    /**
     * Se ejecuta cuando el componente se monta
     */
    public function mount()
    {
        $this->loadData();
    }

    /**
     * Carga todos los datos necesarios para el dashboard
     */
    public function loadData()
    {
        $this->loadChartData();
        $this->totalUsers = User::count();
        $this->monthsCount = count($this->chartData);
        $this->calculateGrowth();
    }

    /**
     * Carga los datos de usuarios agrupados por mes de creación.
     */
    public function loadChartData()
    {
        // Configurar locale en español para los nombres de meses
        setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
        
        $this->chartData = User::select(
            // Clave para ordenar correctamente (YYYY-MM)
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as order_key"), 
            
            // Etiqueta legible para el eje X (ej. 'Ene 2025')
            DB::raw("CONCAT(
                CASE DATE_FORMAT(created_at, '%m')
                    WHEN '01' THEN 'Ene'
                    WHEN '02' THEN 'Feb'
                    WHEN '03' THEN 'Mar'
                    WHEN '04' THEN 'Abr'
                    WHEN '05' THEN 'May'
                    WHEN '06' THEN 'Jun'
                    WHEN '07' THEN 'Jul'
                    WHEN '08' THEN 'Ago'
                    WHEN '09' THEN 'Sep'
                    WHEN '10' THEN 'Oct'
                    WHEN '11' THEN 'Nov'
                    WHEN '12' THEN 'Dic'
                END,
                ' ',
                DATE_FORMAT(created_at, '%Y')
            ) as label"),
            
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('order_key', 'label')
        ->orderBy('order_key', 'asc')
        ->get()
        ->toArray();

        if (empty($this->chartData)) {
            $this->chartData = [];
        }
    }

    public function calculateGrowth()
    {
        if (count($this->chartData) < 2) {
            $this->growthPercentage = 0; 
            return;
        }

        // Se usa una copia para manipular los punteros sin afectar la propiedad
        $data = $this->chartData; 

        // Obtener el último mes y el penúltimo mes
        $lastMonth = end($data);
        $previousMonth = prev($data);

        if ($previousMonth && $previousMonth['count'] > 0) {
            $growth = (($lastMonth['count'] - $previousMonth['count']) / $previousMonth['count']) * 100;
            $this->growthPercentage = round($growth, 1);
        } else if ($previousMonth && $previousMonth['count'] === 0 && $lastMonth['count'] > 0) {
            // Crecimiento infinito (de 0 a X)
            $this->growthPercentage = 100;
        } else {
            $this->growthPercentage = 0;
        }
    }

    /**
     * Método para refrescar los datos (puede ser llamado desde la vista)
     */
    public function refresh()
    {
        $this->loadData();
        
        // Emitir evento para actualizar el gráfico en Alpine.js
        $this->dispatch('chartDataUpdated', chartData: $this->chartData);
    }

    /**
     * Obtener estadísticas adicionales
     */
    public function getAverageUsersPerMonthProperty()
    {
        if ($this->monthsCount > 0) {
            return round($this->totalUsers / $this->monthsCount, 1);
        }
        return 0;
    }

    /**
     * Obtener el mes con más registros
     */
    public function getPeakMonthProperty()
    {
        if (empty($this->chartData)) {
            return null;
        }

        $peakMonth = collect($this->chartData)->sortByDesc('count')->first();
        return $peakMonth;
    }

    /**
     * Renderizar el componente
     */
    public function render()
    {
        return view('livewire.dashboard.superadmin-usuarios.charts');
    }
}