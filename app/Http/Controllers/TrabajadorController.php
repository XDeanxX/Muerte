<?php

namespace App\Http\Controllers;
use App\Models\Personas; 
use App\Models\Trabajador;
use App\Models\Cargo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;


class TrabajadorController extends Controller
{
    // Mostrar listado de trabajadores
   public function index()
{
    // 1. Obtener todos los trabajadores con sus relaciones
    $trabajadores = Trabajador::with(['persona', 'cargo'])->get();

    // 2. Calcular cumpleaños y días restantes
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

    // 3. Ordenar por días restantes (más próximos primero)
    $trabajadores = $trabajadores->sortByDesc('dias_restantes')->values();

    // 4. Retornar vista
    return view('trabajadores.index', compact('trabajadores'));
}


    // Mostrar formulario de creación (Muestra solo la búsqueda de cédula al inicio)
    public function create()
    {
        $cargos = Cargo::orderBy('descripcion')->get();
        // Las variables personaEncontrada/iniciarRegistro se inyectarán desde buscarPersona()
        return view('trabajadores.create', compact('cargos'));
    }
    
   
public function buscarPersona(Request $request)
{
    // ... (Validación)

    $cedula = (string)$request->cedula_busqueda; 
    $persona = Personas::firstWhere('cedula', $cedula);

    $cargos = Cargo::orderBy('descripcion')->get();

    if ($persona) {
        $cedula_persona_string = (string)$persona->cedula; 
        $trabajador_existente = Trabajador::firstWhere('persona_cedula', $cedula_persona_string);
        
        if ($trabajador_existente) {
            // *** CAMBIO CLAVE: NO REDIRIGIR. CARGAR LA VISTA DIRECTAMENTE. ***
            
            $nombre_completo = $persona->nombre . ' ' . $persona->apellido;
            $mensaje = 'La persona ' . $nombre_completo . ' (Cédula: ' . $cedula . ') ya está registrada como trabajador.';

            // Cargamos la vista, pasamos la persona Y el mensaje/bandera de error
            return view('trabajadores.create', [
                'cargos' => $cargos,
                'personaEncontrada' => $persona,      // Pasa los datos de la persona
                'iniciarRegistro' => true,           // Muestra el formulario/datos
                'trabajadorExistente' => true,       // NUEVA BANDERA para la vista
                'mensajeError' => $mensaje           // Pasa el mensaje de error
            ]);
        }

        // Caso 1B: Persona existe, pero NO es trabajador (El caso de la imagen, que ya funciona)
        return view('trabajadores.create', [
            'cargos' => $cargos,
            'personaEncontrada' => $persona, 
            'iniciarRegistro' => true,      
        ]);

    } else {
        // ... (Caso 2: Persona nueva)
        return view('trabajadores.create', [
            'cargos' => $cargos,
            'iniciarRegistro' => true,
            'cedula_busqueda' => $cedula, 
        ]);
    }
}

    // Guardar nuevo trabajador (Unificado para ambos flujos)
    public function store(Request $request)
{
    // Determinar si la persona es NUEVA (si el formulario envió campos personales)
    // Usamos 'nombre' como bandera, ya que solo se envía si la persona es nueva.
    $es_nueva_persona = $request->has('nombre'); 
    $cedula = $request->cedula;

    // 1. VALIDACIÓN
    $reglas = [
        'cedula' => 'required|digits_between:7,15',
        'cargo_id' => 'required|exists:cargos,cargo_id', 
        'zona_trabajo' => 'nullable|string|max:191',
    ];

    if ($es_nueva_persona) {
        // Reglas de validación para una PERSONA NUEVA (incluye unicidad de cedula y email)
        $reglas = array_merge($reglas, [
            // El 'unique' solo se aplica si es nueva persona
            'cedula' => 'required|digits_between:7,15|unique:personas,cedula', 
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50', 
            'segundo_nombre' => 'nullable|string|max:50', 
            'segundo_apellido' => 'nullable|string|max:50',
            'nacionalidad' => 'required|string|max:2', 
            'genero' => 'required|string|max:1',     
            'nacimiento' => 'required|date',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:20', 
            'email' => 'nullable|email|max:100|unique:personas,email',
        ]);
    }
    
    // Si la persona ya existe, no se valida su unicidad de cédula/email aquí
    $request->validate($reglas);

    // 2. INICIO DE LA TRANSACCIÓN
    DB::beginTransaction();
    
    try {
        if ($es_nueva_persona) {
            // --- 2.1. GUARDAR NUEVA PERSONA ---
            $datos_persona = $request->only([
                'cedula', 'nombre', 'apellido', 'segundo_nombre', 'segundo_apellido', 
                'nacionalidad', 'genero', 'nacimiento', 'direccion', 'telefono', 'email'
            ]);
            
            // Crea la persona. La cédula es la Primary Key (PK) según tu modelo Personas.php
            $persona = Personas::create($datos_persona); 
            
        } else {
            // --- 2.1. OBTENER PERSONA EXISTENTE ---
            // Solo obtenemos la persona existente para el registro de trabajador
            $persona = Personas::findOrFail($cedula);
        }
        
        // --- 2.2. GUARDAR EN LA TABLA TRABAJADORES (ENLACE) ---
        // La clave foránea en trabajadores DEBE ser 'persona_cedula'
        Trabajador::create([
            'persona_cedula' => $persona->cedula,
            'cargo_id' => $request->cargo_id,
            'zona_trabajo' => $request->zona_trabajo,
        ]);
        
        DB::commit(); 

        $mensaje = $es_nueva_persona 
                   ? 'Trabajador y datos personales registrados exitosamente.'
                   : 'Persona existente asociada exitosamente como nuevo trabajador.';
                   
        return redirect()->route('trabajadores.index')->with('success', $mensaje);
        
    } catch (\Exception $e) {
        DB::rollBack(); 
        // Es útil registrar el error para depurar
        // \Illuminate\Support\Facades\Log::error("Error en store: " . $e->getMessage());
        return redirect()->back()->with('error', 'Error al procesar el registro. Verifique los datos o contacte a soporte: ' . $e->getMessage())->withInput();
    }
}


    // Mostrar formulario de edición
public function edit($id)
{
    $trabajador = Trabajador::with('persona')
                            ->where('persona_cedula', $id)
                            ->firstOrFail();

    // 2. Cargar cargos
    $cargos = Cargo::orderBy('descripcion')->get(); 
    
    // El resto del código se mantiene
    return view('trabajadores.edit', compact('trabajador', 'cargos'));
}


   
     
   public function update(Request $request, $persona_cedula)
    
    {

    $trabajador = Trabajador::where('persona_cedula', $persona_cedula)->with('persona')->firstOrFail();
        $persona = $trabajador->persona;
        if (!$persona) {
            return back()->with('error', 'Error: No se encontró la información personal asociada al trabajador.');
        }

        // Obtener la clave primaria de la persona (la cédula actual).
        $cedulaActual = $persona->cedula; 


        // --- 2. VALIDACIÓN CORREGIDA ---
        $request->validate([
            // Validaciones para el modelo Persona
            'cedula' => [
                'required', 
                'string', 
                'max:15',
                // 🔥 CORRECCIÓN CRÍTICA: Ignorar la CÉDULA ACTUAL, no un $personaId indefinido.
                Rule::unique('personas', 'cedula')->ignore($cedulaActual, 'cedula'),
            ],
            'nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'nacimiento' => 'required|date|before:today', 
            'telefono' => 'required|string|max:20',
            'email' => [
                'nullable', 
                'email', 
                'max:150',
                // 🔥 CORRECCIÓN CRÍTICA: Ignorar el EMAIL ACTUAL usando la CÉDULA como PK.
                Rule::unique('personas', 'email')->ignore($cedulaActual, 'cedula'),
            ],
            'direccion' => 'nullable|string|max:255',
            
            // Validaciones para el modelo Trabajador
            'zona_trabajo' => 'required|string|max:100',
            'cargo_id' => 'required|exists:cargos,cargo_id',

        ], [
            'nacimiento.before' => 'La fecha de nacimiento no puede ser en el futuro.',
            'cedula.unique' => 'La cédula ingresada ya está registrada por otra persona.',
            'email.unique' => 'El correo electrónico ya está registrado por otra persona.',
        ]); 

        // --- 3. TRANSACCIÓN ---
        DB::beginTransaction();

        try {
            // La instancia de $persona ya está cargada arriba.

            // --- 4. ACTUALIZAR MODELO PERSONA ---
            $datosPersona = $request->only([
                'cedula', 'nombre', 'segundo_nombre', 'apellido', 'segundo_apellido', 
                'nacimiento', 'telefono', 'email', 'direccion'
            ]);
            
            $persona->update($datosPersona);
            
            // --- 5. SI LA CÉDULA CAMBIÓ, ACTUALIZAR LA REFERENCIA EN TRABAJADOR ---
            // Esto es crucial si 'persona_cedula' es la clave foránea en trabajadores.
            if ($request->input('cedula') !== $cedulaActual) {
                $trabajador->persona_cedula = $request->input('cedula');
                // NOTA: Si tu modelo Trabajador tiene la columna 'id' como PK y usa 'persona_cedula'
                // como FK, esta es la forma correcta de actualizar la FK.
            }
            
            // --- 6. ACTUALIZAR MODELO TRABAJADOR ---
            $trabajador->update($request->only(['zona_trabajo', 'cargo_id']));

            DB::commit();

            // --- 7. REDIRECCIÓN CON ÉXITO ---
            return redirect()->route('trabajadores.index')->with('success', 'El trabajador y sus datos personales se han actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error("Error al actualizar trabajador: " . $e->getMessage());

            // Redirección con error
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar los datos del trabajador. Por favor, inténtelo de nuevo.');
        }
    }


  public function show($persona_cedula)
{
    $trabajador = Trabajador::with(['persona', 'cargo'])->where('persona_cedula', $persona_cedula)->firstOrFail();

    return view('trabajadores.show', compact('trabajador'));
}


public function destroy($persona_cedula)
{
    try {
        $trabajador = Trabajador::where('persona_cedula', $persona_cedula)->firstOrFail();

        // Si quieres eliminar también la persona asociada:
        if ($trabajador->persona) {
            $trabajador->persona->delete();
        }

        $trabajador->delete();

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador eliminado correctamente.');
    } catch (\Exception $e) {
        \Log::error('Error al eliminar trabajador: ' . $e->getMessage());

        return redirect()->route('trabajadores.index')
            ->with('error', 'No se pudo eliminar el trabajador. Verifica dependencias.');
    }
}


public function exportExcel()
{
    \Log::info('Método exportExcel ejecutado');

    $trabajadores = Trabajador::with(['persona', 'cargo'])->get()->map(function ($trabajador) {
        return [
            'Cédula' => $trabajador->persona->cedula ?? '',
            'Primer Nombre' => $trabajador->persona->nombre ?? '',
            'Segundo Nombre' => $trabajador->persona->segundo_nombre ?? '',
            'Primer Apellido' => $trabajador->persona->apellido ?? '',
            'Segundo Apellido' => $trabajador->persona->segundo_apellido ?? '',
            'Fecha de Nacimiento' => optional($trabajador->persona->nacimiento)->format('d-m-Y'),
            'Teléfono' => $trabajador->persona->telefono ?? 'N/A',
            'Email' => $trabajador->persona->email ?? 'N/A',
            'Dirección' => $trabajador->persona->direccion ?? 'N/A',
            'Cargo' => $trabajador->cargo->descripcion ?? 'Sin cargo',
            'Zona de trabajo' => $trabajador->zona_trabajo ?? '—',
            'Fecha de registro' => optional($trabajador->created_at)->format('d-m-Y H:i'),
        ];
    });

    return (new FastExcel($trabajadores))->download('reporte_trabajadores_' . now()->format('Ymd_His') . '.xlsx');
}
    

public function exportPdf($persona_cedula)
{
    $trabajador = Trabajador::with('persona')->where('persona_cedula', $persona_cedula)->firstOrFail();

    $pdf = Pdf::loadView('pdf.trabajador', ['trabajador' => $trabajador]);

    return $pdf->download('reporte_trabajador_' . $trabajador->persona->cedula . '.pdf');
}



public function exportarTodosPdf()
{
    $trabajadores = Trabajador::with(['persona', 'cargo'])->get();

    $pdf = Pdf::loadView('pdf.trabajadores_completo', compact('trabajadores'));

    return $pdf->download('reporte_trabajadores_' . now()->format('Ymd_His') . '.pdf');
}


}