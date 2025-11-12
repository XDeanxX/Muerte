<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReunionRequest;
use App\Http\Requests\UpdateReunionRequest;
use App\Models\Concejales;
use App\Models\Estatus;
use App\Models\Reunion;
use App\Models\Solicitud;
use App\Models\Institucion;
use App\Models\Personas;
use App\Models\TipoReunion;
use App\Services\ReunionValidationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReunionController extends Controller
{
    protected $validationService;
    protected $notificationService;

    public function __construct(ReunionValidationService $validationService, NotificationService $notificationService)
    {
        $this->validationService = $validationService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reunion::with(['solicitudes', 'instituciones', 'concejales', 'estatusRelacion','tipoReunionRelacion']);
        
        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('titulo', 'like', "%{$search}%");
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_reunion', $request->input('fecha'));
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo_reunion', $request->input('tipo'));
        }
        
        $reuniones = $query->latest()->paginate(10)->onEachSide(-0.5);
        
        return view('reuniones.index', compact('reuniones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Filtrar solo solicitudes con estatus "Pendiente" (ID = 1) y NO asignadas a reunión
        $solicitudes = Solicitud::with('persona')
                                ->where('estatus', 1)
                                ->where('asignada_reunion', false)
                                ->get();
        $instituciones = Institucion::pluck('titulo', 'id');
        $concejales = Concejales::with('persona')->get();
        $tipoReunion = TipoReunion::all();
        
        return view('reuniones.create', compact('solicitudes', 'instituciones', 'concejales', 'tipoReunion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReunionRequest $request)
    {
        $validated = $request->validated();
        
        // Sanitizar hora_reunion: Extraer solo HH:MM del valor (puede venir con segundos o formato 12h)
        if (isset($validated['hora_reunion'])) {
            $horaReunion = $validated['hora_reunion'];
            // Intentar parsear y reformatear a H:i
            try {
                $horaReunion = \Carbon\Carbon::parse($horaReunion)->format('H:i');
                $validated['hora_reunion'] = $horaReunion;
            } catch (\Exception $e) {
                // Si falla, mantener el valor original
            }
        }
        
        // Calcular hora_fin temporal (hora_reunion + 4 horas) para validaciones de disponibilidad
        $horaReunion = $validated['hora_reunion'];
        $horaFinTemporal = \Carbon\Carbon::parse($horaReunion)->addHours(4)->format('H:i');
        
        // Validación 1: Fecha futura
        $fechaValidacion = $this->validationService->validarFechaFutura($validated['fecha_reunion']);
        if (!$fechaValidacion['valid']) {
            return back()
                ->withInput()
                ->withErrors(['fecha_reunion' => $fechaValidacion['mensaje']]);
        }
        
        // Validación 2: Frecuencia de asambleas (solo si tipo_reunion = 1 "Asamblea")
        if ($validated['tipo_reunion'] == 1) {
            $asambleaValidacion = $this->validationService->validarFrecuenciaAsamblea($validated['fecha_reunion']);
            if (!$asambleaValidacion['valid']) {
                return back()
                    ->withInput()
                    ->withErrors(['tipo_reunion' => $asambleaValidacion['mensaje']]);
            }
        }
        
        // Validación 3: Disponibilidad de concejales (1 reunión por día)
        if ($request->has('concejales') && !empty($request->input('concejales'))) {
            $concejalesValidacion = $this->validationService->validarDisponibilidadConcejalesPorDia(
                $request->input('concejales'),
                $validated['fecha_reunion']
            );
            
            if (!$concejalesValidacion['valid']) {
                return back()
                    ->withInput()
                    ->withErrors(['concejales' => $concejalesValidacion['mensaje']]);
            }
        }
        
        // Validate solicitudes availability (usando bloqueo temporal de 4 horas)
        $solicitudesIds = [];
        if ($request->has('solicitudes') && !empty($request->input('solicitudes'))) {
            $solicitudesIds = $request->input('solicitudes');
        } elseif ($request->filled('solicitud_id')) {
            $solicitudesIds = [$request->input('solicitud_id')];
        }
        
        if (!empty($solicitudesIds)) {
            $solicitudesValidacion = $this->validationService->validarDisponibilidadSolicitudes(
                $solicitudesIds,
                $validated['fecha_reunion'],
                $horaReunion,
                $horaFinTemporal
            );
            
            if (!$solicitudesValidacion['all_available']) {
                $conflictosInfo = collect($solicitudesValidacion['solicitudes_conflictos'])
                    ->pluck('solicitud_titulo')
                    ->join(', ');
                
                return back()
                    ->withInput()
                    ->with('warning', 'Algunas solicitudes tienen conflictos de horario: ' . $conflictosInfo)
                    ->with('solicitudes_validas', $solicitudesValidacion['solicitudes_validas'])
                    ->with('solicitudes_conflictos', $solicitudesValidacion['solicitudes_conflictos']);
            }
        }
        
        // Establecer estatus por defecto si no se proporciona
        if (!isset($validated['estatus'])) {
            $estatusDefault = Estatus::where('sector_sistema', 'reuniones')
                                    ->where('estatus', 'espera')
                                    ->value('estatus_id');
            $validated['estatus'] = $estatusDefault ?? 1; // Fallback a 1 si no se encuentra
        }
        
        // Create the reunion
        $reunion = Reunion::create($validated);

        // Attach solicitudes (many-to-many) y marcarlas como asignadas
        if (!empty($solicitudesIds)) {
            // Preparar array con valores por defecto para campos pivot
            $solicitudesSync = [];
            foreach ($solicitudesIds as $solicitudId) {
                $solicitudesSync[$solicitudId] = [
                    'citar_solicitante' => false,
                    'asistencia_solicitante' => false
                ];
            }
            
            $reunion->solicitudes()->sync($solicitudesSync);
            
            // Marcar solicitudes como asignadas a reunión
            Solicitud::whereIn('solicitud_id', $solicitudesIds)->update(['asignada_reunion' => true]);
            
            // Enviar notificaciones si está habilitado
            if ($request->has('notificar_solicitantes')) {
                $this->notificationService->notificarSolicitantesReunion($reunion, false);
            }
        }

        // Attach concejales con valor por defecto para asistencia
        if ($request->has('concejales') && !empty($request->input('concejales'))) {
            $concejalesSync = [];
            foreach ($request->input('concejales') as $concejalId) {
                $concejalesSync[$concejalId] = ['asistencia' => false];
            }
            $reunion->concejales()->sync($concejalesSync);
        }

        // Attach instituciones con valor por defecto para asistencia
        if ($request->has('instituciones') && !empty($request->input('instituciones'))) {
            $institucionesSync = [];
            foreach ($request->input('instituciones') as $institucionId) {
                $institucionesSync[$institucionId] = ['asistencia' => false];
            }
            $reunion->instituciones()->sync($institucionesSync);
        }

        // Update parent solicitud status if requested
        if ($request->filled('nuevo_estado_solicitud') && $request->filled('solicitud_id')) {
            $solicitud = Solicitud::where('solicitud_id', $request->input('solicitud_id'))->first();
            if ($solicitud) {
                $solicitud->estado_detallado = $request->input('nuevo_estado_solicitud');
                $solicitud->save();
            }
        }

        return redirect()->route('dashboard.reuniones.index')
                         ->with('success', 'Reunión creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reunion $reunion)
    {
        $reunion->load(['concejales.persona', 'instituciones', 'estatusRelacion', 'tipoReunionRelacion', 'solicitudes.persona']);
        return view('reuniones.show', compact('reunion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reunion $reunion)
    {
        // VALIDACIÓN: Verificar que la reunión NO esté finalizada
        $estatusFinalizado = Estatus::where('sector_sistema', 'reuniones')
                                     ->where('estatus', 'finalizada')
                                     ->value('estatus_id');
        
        if ($reunion->estatus == $estatusFinalizado) {
            return redirect()->route('dashboard.reuniones.show', $reunion)
                            ->with('error', 'No se puede editar porque la reunión ya está finalizada.');
        }
        
        // Filtrar solo solicitudes con estatus "Pendiente" (ID = 1) y NO asignadas a reunión
        // O las que ya están asignadas a ESTA reunión
        $solicitudes = Solicitud::with('persona')
                                ->where(function($query) use ($reunion) {
            $query->where(function($q) {
                $q->where('estatus', 1)
                  ->where('asignada_reunion', false);
            })->orWhereHas('reunionRelacion', function($q) use ($reunion) {
                $q->where('reunion_id', $reunion->id);
            });
        })->get();
        
        $instituciones = Institucion::pluck('titulo', 'id');
        $concejales = Concejales::with('persona')->get();
        $tipoReunion = TipoReunion::all();
        $estatus = Estatus::where('sector_sistema', 'reuniones')->get();
        
        $reunion->load(['concejales', 'instituciones', 'estatusRelacion', 'tipoReunionRelacion', 'solicitudes']);

        return view('reuniones.edit', compact('solicitudes', 'instituciones', 'concejales', 'tipoReunion', 'reunion', 'estatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReunionRequest $request, Reunion $reunion)
    {
        $validated = $request->validated();
        
        // Sanitizar hora_reunion: Extraer solo HH:MM del valor (puede venir con segundos o formato 12h)
        if (isset($validated['hora_reunion'])) {
            $horaReunion = $validated['hora_reunion'];
            // Intentar parsear y reformatear a H:i
            try {
                $horaReunion = \Carbon\Carbon::parse($horaReunion)->format('H:i');
                $validated['hora_reunion'] = $horaReunion;
            } catch (\Exception $e) {
                // Si falla, mantener el valor original
            }
        }
        
        // Calcular hora_fin temporal (hora_reunion + 4 horas) para validaciones de disponibilidad
        $horaReunion = $validated['hora_reunion'];
        $horaFinTemporal = \Carbon\Carbon::parse($horaReunion)->addHours(4)->format('H:i');
        
        // NOTA: Al editar, NO validamos fecha futura para permitir modificar reuniones pasadas
        // Solo se valida fecha futura al CREAR una reunión nueva
        
        // Validación: Frecuencia de asambleas (solo si tipo_reunion = 1 "Asamblea")
        if ($validated['tipo_reunion'] == 1) {
            $asambleaValidacion = $this->validationService->validarFrecuenciaAsamblea(
                $validated['fecha_reunion'],
                $reunion->id
            );
            if (!$asambleaValidacion['valid']) {
                return back()
                    ->withInput()
                    ->withErrors(['tipo_reunion' => $asambleaValidacion['mensaje']]);
            }
        }
        
        // Validación 3: Disponibilidad de concejales (1 reunión por día)
        if ($request->has('concejales') && !empty($request->input('concejales'))) {
            $concejalesValidacion = $this->validationService->validarDisponibilidadConcejalesPorDia(
                $request->input('concejales'),
                $validated['fecha_reunion'],
                $reunion->id
            );
            
            if (!$concejalesValidacion['valid']) {
                return back()
                    ->withInput()
                    ->withErrors(['concejales' => $concejalesValidacion['mensaje']]);
            }
        }
        
        // Validate solicitudes availability (usando bloqueo temporal de 4 horas)
        $solicitudesIds = [];
        if ($request->has('solicitudes') && !empty($request->input('solicitudes'))) {
            $solicitudesIds = $request->input('solicitudes');
        } elseif ($request->filled('solicitud_id')) {
            $solicitudesIds = [$request->input('solicitud_id')];
        }
        
        if (!empty($solicitudesIds)) {
            $solicitudesValidacion = $this->validationService->validarDisponibilidadSolicitudes(
                $solicitudesIds,
                $validated['fecha_reunion'],
                $horaReunion,
                $horaFinTemporal,
                $reunion->id
            );
            
            if (!$solicitudesValidacion['all_available']) {
                $conflictosInfo = collect($solicitudesValidacion['solicitudes_conflictos'])
                    ->pluck('solicitud_titulo')
                    ->join(', ');
                
                return back()
                    ->withInput()
                    ->with('warning', 'Algunas solicitudes tienen conflictos de horario: ' . $conflictosInfo);
            }
        }
        
        // Obtener solicitudes previas para desmarcarlas si fueron removidas
        $solicitudesPrevias = $reunion->solicitudes->pluck('solicitud_id')->toArray();
        
        // Update reunion data
        $reunion->update($validated);

        // Update solicitudes (many-to-many) y actualizar asignaciones
        if (!empty($solicitudesIds)) {
            // Preparar array con valores por defecto para campos pivot
            $solicitudesSync = [];
            foreach ($solicitudesIds as $solicitudId) {
                $solicitudesSync[$solicitudId] = [
                    'citar_solicitante' => false,
                    'asistencia_solicitante' => false
                ];
            }
            
            $reunion->solicitudes()->sync($solicitudesSync);
            
            // Marcar nuevas solicitudes como asignadas
            Solicitud::whereIn('solicitud_id', $solicitudesIds)->update(['asignada_reunion' => true]);
            
            // Enviar notificaciones si está habilitado (solo a nuevas solicitudes)
            if ($request->has('notificar_solicitantes')) {
                $nuevasSolicitudes = array_diff($solicitudesIds, $solicitudesPrevias);
                if (!empty($nuevasSolicitudes)) {
                    $this->notificationService->notificarSolicitantesReunion($reunion, false);
                }
            }
        } else {
            $reunion->solicitudes()->sync([]);
        }
        
        // Desmarcar solicitudes que fueron removidas
        $solicitudesRemovidas = array_diff($solicitudesPrevias, $solicitudesIds);
        if (!empty($solicitudesRemovidas)) {
            Solicitud::whereIn('solicitud_id', $solicitudesRemovidas)->update(['asignada_reunion' => false]);
        }

        // Update concejales con valor por defecto para asistencia
        if ($request->has('concejales') && !empty($request->input('concejales'))) {
            $concejalesSync = [];
            foreach ($request->input('concejales') as $concejalId) {
                $concejalesSync[$concejalId] = ['asistencia' => false];
            }
            $reunion->concejales()->sync($concejalesSync);
        } else {
            $reunion->concejales()->sync([]);
        }

        // Update instituciones con valor por defecto para asistencia
        if ($request->has('instituciones') && !empty($request->input('instituciones'))) {
            $institucionesSync = [];
            foreach ($request->input('instituciones') as $institucionId) {
                $institucionesSync[$institucionId] = ['asistencia' => false];
            }
            $reunion->instituciones()->sync($institucionesSync);
        } else {
            $reunion->instituciones()->sync([]);
        }

        // Update parent solicitud status if requested  
        if ($request->filled('nuevo_estado_solicitud') && $request->filled('solicitud_id')) {
            $solicitud = Solicitud::where('solicitud_id', $request->input('solicitud_id'))->first();
            if ($solicitud) {
                $solicitud->estado_detallado = $request->input('nuevo_estado_solicitud');
                $solicitud->save();
            }
        }

        return redirect()->route('dashboard.reuniones.index')
                         ->with('success', 'Reunión actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reunion $reunion)
    {
        // Obtener todas las solicitudes vinculadas a esta reunión
        $solicitudesIds = $reunion->solicitudes->pluck('solicitud_id')->toArray();
        
        // Resetear el flag asignada_reunion a FALSE para liberar las solicitudes
        if (!empty($solicitudesIds)) {
            Solicitud::whereIn('solicitud_id', $solicitudesIds)
                ->update(['asignada_reunion' => false]);
        }
        
        // Eliminar la reunión (soft delete)
        $reunion->delete();

        return redirect()->route('dashboard.reuniones.index')
                         ->with('success', 'Reunión eliminada exitosamente. Las solicitudes asociadas han sido liberadas.');
    }

    /**
     * Show the form for finalizing a meeting
     */
    public function finalize(Reunion $reunion)
    {
        // Verificar que la reunión ya haya pasado
        $fechaHoraReunion = \Carbon\Carbon::parse($reunion->fecha_reunion->format('Y-m-d') . ' ' . $reunion->hora_reunion);
        
        if ($fechaHoraReunion->isFuture()) {
            return redirect()->route('dashboard.reuniones.show', $reunion)
                            ->with('error', 'No se puede finalizar una reunión que aún no ha ocurrido.');
        }

        // Verificar que no esté ya finalizada
        $estatusFinalizado = Estatus::where('sector_sistema', 'reuniones')
                                     ->where('estatus', 'finalizada')
                                     ->value('estatus_id');
        
        if ($reunion->estatus == $estatusFinalizado) {
            return redirect()->route('dashboard.reuniones.show', $reunion)
                            ->with('info', 'Esta reunión ya ha sido finalizada.');
        }

        // Cargar las relaciones necesarias
        $reunion->load(['solicitudes.persona', 'concejales.persona', 'instituciones']);
        
        // Obtener los estatus disponibles para solicitudes
        $estatusSolicitudes = Estatus::where('sector_sistema', 'solicitudes')->get();

        return view('reuniones.finalize', compact('reunion', 'estatusSolicitudes'));
    }

    /**
     * Store the finalization data
     */
    public function storeFinalization(Request $request, Reunion $reunion)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'duracion_real' => 'required',
            'resolucion' => 'required|string',
            'asistencia_solicitantes' => 'array',
            'asistencia_concejales' => 'array',
            'asistencia_instituciones' => 'array',
            'decision_solicitudes' => 'array',
            'asignar_visitas' => 'array'
        ]);

        // Actualizar duracion_real y resolución de la reunión
        $reunion->update([
            'duracion_real' => $validated['duracion_real'],
            'resolución' => $validated['resolucion']
        ]);

        // Actualizar asistencia de solicitantes en la pivot table
        if (isset($validated['asistencia_solicitantes'])) {
            foreach ($validated['asistencia_solicitantes'] as $solicitudId) {
                $reunion->solicitudes()->updateExistingPivot($solicitudId, [
                    'asistencia_solicitante' => true
                ]);
            }
        }

        // Actualizar asistencia de concejales
        if (isset($validated['asistencia_concejales'])) {
            foreach ($validated['asistencia_concejales'] as $concejalId) {
                $reunion->concejales()->updateExistingPivot($concejalId, [
                    'asistencia' => true
                ]);
            }
        }

        // Actualizar asistencia de instituciones
        if (isset($validated['asistencia_instituciones'])) {
            foreach ($validated['asistencia_instituciones'] as $institucionId) {
                $reunion->instituciones()->updateExistingPivot($institucionId, [
                    'asistencia' => true
                ]);
            }
        }

        // Procesar decisiones de solicitudes y asignaciones a visitas
        $estatusAprobada = Estatus::where('sector_sistema', 'solicitudes')
                                    ->where('estatus', 'aprobada')
                                    ->value('estatus_id');
        $estatusRechazada = Estatus::where('sector_sistema', 'solicitudes')
                                     ->where('estatus', 'rechazada')
                                     ->value('estatus_id');

        foreach ($reunion->solicitudes as $solicitud) {
            $solicitudId = $solicitud->solicitud_id;
            
            // Actualizar decisión en pivot table
            if (isset($validated['decision_solicitudes'][$solicitudId])) {
                $decision = $validated['decision_solicitudes'][$solicitudId];
                
                $reunion->solicitudes()->updateExistingPivot($solicitudId, [
                    'estatus_decision' => $decision
                ]);

                // Actualizar estatus de la solicitud según la decisión
                if ($decision == 'aprobada' && $estatusAprobada) {
                    Solicitud::where('solicitud_id', $solicitudId)->update([
                        'estatus' => $estatusAprobada
                    ]);
                } elseif ($decision == 'rechazada' && $estatusRechazada) {
                    Solicitud::where('solicitud_id', $solicitudId)->update([
                        'estatus' => $estatusRechazada
                    ]);
                }
            }

            // Asignar a módulo de visitas si está marcado
            if (isset($validated['asignar_visitas']) && in_array($solicitudId, $validated['asignar_visitas'])) {
                Solicitud::where('solicitud_id', $solicitudId)->update([
                    'asignada_visita' => true
                ]);
            }
        }

        // Cambiar estatus de la reunión a "Finalizada"
        // Primero intentamos encontrar el estatus "finalizada"
        $estatusFinalizado = Estatus::where('sector_sistema', 'reuniones')
                                     ->where('estatus', 'finalizada')
                                     ->first();
        
        // Si no existe, lo creamos
        if (!$estatusFinalizado) {
            $estatusFinalizado = Estatus::create([
                'sector_sistema' => 'reuniones',
                'estatus' => 'finalizada',
                'descripcion' => 'Reunión finalizada y completada'
            ]);
        }
        
        // Actualizamos el estatus de la reunión
        $reunion->update(['estatus' => $estatusFinalizado->estatus_id]);

        return redirect()->route('dashboard.reuniones.show', $reunion)
                         ->with('success', 'Reunión finalizada exitosamente.');
    }

    /**
     * Export reuniones list to PDF
     */
    public function exportPdf(Request $request)
    {
        // Aplicar los mismos filtros que en el listado
        $query = Reunion::with(['solicitudes', 'instituciones', 'concejales', 'estatusRelacion', 'tipoReunionRelacion']);
        
        // Aplicar filtros de búsqueda si existen
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('titulo', 'like', "%{$search}%");
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_reunion', $request->input('fecha'));
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo_reunion', $request->input('tipo'));
        }
        
        $reuniones = $query->latest()->get();
        
        $pdfChunks = new \Illuminate\Support\Collection();
        $page = 1;
        
        // Dividir en chunks de 10 para mejor paginación
        $chunkedReuniones = $reuniones->chunk(10);
        
        foreach ($chunkedReuniones as $chunk) {
            $html = view('pdf.reuniones.lista-completa-reuniones', [
                'reuniones' => $chunk,
                'page' => $page,
            ])->render();
            
            $pdfChunks->push($html);
            $page++;
        }
        
        $pdf = \PDF::loadView('pdf.reuniones.reporte-base', [
            'chunks' => $pdfChunks->implode(''),
        ]);
        
        $filename = 'reporte_reuniones_' . now()->format('Ymd_His') . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    /**
     * Export reuniones list to Excel
     */
    public function exportExcel(Request $request)
    {
        // Aplicar los mismos filtros que en el listado
        $query = Reunion::with(['solicitudes', 'instituciones', 'concejales', 'estatusRelacion', 'tipoReunionRelacion']);
        
        // Aplicar filtros de búsqueda si existen
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('titulo', 'like', "%{$search}%");
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_reunion', $request->input('fecha'));
        }
        
        if ($request->filled('tipo')) {
            $query->where('tipo_reunion', $request->input('tipo'));
        }
        
        $reuniones = $query->latest()->get();
        
        // Header con información del usuario generador
        $usuarioGenerador = collect([
            [
                'ID' => 'Registro Generado el:',
                'Título' => now()->format('d-m-Y H:i'),
                'Descripción' => '', 'Fecha' => '', 'Hora' => '', 'Duración Estimada' => '',
                'Hora Finalización Real' => '', 'Tipo' => '', 'Estatus' => '',
                'Solicitudes' => '', 'Concejales' => '', 'Instituciones' => '',
                'Resolución' => ''
            ],
            [
                'ID' => 'Generado por:',
                'Título' => \Auth::user()->persona->nombre . ' ' . \Auth::user()->persona->apellido . ' (' . \Auth::user()->getRoleNameColoquela() . ')',
                'Descripción' => '', 'Fecha' => '', 'Hora' => '', 'Duración Estimada' => '',
                'Hora Finalización Real' => '', 'Tipo' => '', 'Estatus' => '',
                'Solicitudes' => '', 'Concejales' => '', 'Instituciones' => '',
                'Resolución' => ''
            ],
            // Fila vacía para separación
            [
                'ID' => '', 'Título' => '', 'Descripción' => '', 'Fecha' => '', 'Hora' => '',
                'Duración Estimada' => '', 'Hora Finalización Real' => '', 'Tipo' => '',
                'Estatus' => '', 'Solicitudes' => '', 'Concejales' => '', 'Instituciones' => '',
                'Resolución' => ''
            ]
        ]);
        
        $export = $reuniones->map(function($reunion) {
            // Obtener detalles de solicitudes
            $solicitudesDetalle = $reunion->solicitudes->map(function($s) {
                return $s->solicitud_id . ' - ' . $s->titulo;
            })->implode('; ');
            
            // Obtener nombres de concejales
            $concejalesDetalle = $reunion->concejales->map(function($c) {
                return optional($c->persona)->nombre . ' ' . optional($c->persona)->apellido;
            })->implode(', ');
            
            // Obtener nombres de instituciones
            $institucionesDetalle = $reunion->instituciones->pluck('titulo')->implode(', ');
            
            return [
                'ID' => $reunion->id,
                'Título' => \Str::title($reunion->titulo),
                'Descripción' => $reunion->descripcion ?? 'N/A',
                'Fecha' => $reunion->fecha_reunion->format('d/m/Y'),
                'Hora' => \Carbon\Carbon::parse($reunion->hora_reunion)->format('g:i a'),
                'Duración Estimada' => $reunion->duracion_reunion ?? 'N/A',
                'Hora Finalización Real' => $reunion->hora_finalizacion_real ? \Carbon\Carbon::parse($reunion->hora_finalizacion_real)->format('g:i a') : 'N/A',
                'Tipo' => optional($reunion->tipoReunionRelacion)->titulo ?? 'N/A',
                'Estatus' => \Str::title(optional($reunion->estatusRelacion)->estatus ?? 'N/A'),
                'Solicitudes' => $solicitudesDetalle ?: 'N/A',
                'Concejales' => $concejalesDetalle ?: 'N/A',
                'Instituciones' => $institucionesDetalle ?: 'N/A',
                'Resolución' => $reunion->resolución ?? 'N/A'
            ];
        });
        
        $finalExport = $usuarioGenerador->concat($export);
        
        $header_style = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontBold()
            ->setBackgroundColor("EDEDED");
        
        $filename = 'reporte_reuniones_' . now()->format('Ymd_His') . '.xlsx';
        
        return (new \Rap2hpoutre\FastExcel\FastExcel($finalExport))
            ->configureOptionsUsing(function (\OpenSpout\Writer\Common\AbstractOptions $options) {
                $options->setColumnWidth(10, 1);  // ID
                $options->setColumnWidth(35, 2);  // Título
                $options->setColumnWidth(45, 3);  // Descripción
                $options->setColumnWidth(15, 4);  // Fecha
                $options->setColumnWidth(12, 5);  // Hora
                $options->setColumnWidth(18, 6);  // Duración Estimada
                $options->setColumnWidth(20, 7);  // Hora Finalización Real
                $options->setColumnWidth(20, 8);  // Tipo
                $options->setColumnWidth(15, 9);  // Estatus
                $options->setColumnWidth(50, 10); // Solicitudes
                $options->setColumnWidth(40, 11); // Concejales
                $options->setColumnWidth(35, 12); // Instituciones
                $options->setColumnWidth(50, 13); // Resolución
            })
            ->headerStyle($header_style)
            ->download($filename);
    }

    /**
     * Export individual meeting acta to PDF
     */
    public function exportActaPdf(Reunion $reunion)
    {
        // Cargar las relaciones necesarias
        $reunion->load(['solicitudes.persona', 'concejales.persona', 'instituciones', 'estatusRelacion', 'tipoReunionRelacion']);
        
        $pdf = \PDF::loadView('pdf.reuniones.acta-reunion', compact('reunion'));
        
        $filename = 'acta_reunion_' . $reunion->id . '_' . now()->format('Ymd') . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    /**
     * Notificar a un solicitante individual sobre la reunión
     */
    public function notificarSolicitante(Request $request, Reunion $reunion)
    {
        try {
            $solicitudId = $request->input('solicitud_id');
            
            // Verificar que la solicitud esté asociada a esta reunión
            $solicitud = $reunion->solicitudes()->where('solicitud_id', $solicitudId)->first();
            
            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'La solicitud no está asociada a esta reunión'
                ], 404);
            }
            
            // Enviar notificación individual usando el servicio de notificaciones
            $this->notificationService->notificarSolicitanteIndividual($reunion, $solicitud);
            
            return response()->json([
                'success' => true,
                'message' => 'Notificación enviada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

