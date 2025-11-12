<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReunionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipo_reunion = $this->input('tipo_reunion');
        
        $rules = [
            'solicitud_id' => ['nullable', 'string', 'exists:solicitudes,solicitud_id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_reunion' => ['required', 'date', 'after_or_equal:today'],
            'hora_reunion' => ['required'],
            'duracion_reunion' => ['nullable', 'string', 'max:255'],
            'tipo_reunion' => ['required', 'integer', 'exists:tipo_reunions,id'],
            'estatus' => ['nullable', 'integer', 'exists:estatus,estatus_id'],
            'nuevo_estado_solicitud' => ['nullable', 'string', 'max:255'],
        ];
        
        // Validaciones condicionales según tipo de reunión
        // Tipo 1 = Asamblea: Permite solicitudes, instituciones y concejales
        // Tipo 2 = Mesa de Trabajo: NO permite solicitudes, pero sí instituciones y concejales
        // Tipo 3 = Sesión Solemne: NO permite solicitudes, instituciones ni concejales
        
        if ($tipo_reunion == 1) {
            // Asamblea: Todo permitido
            $rules['solicitudes'] = ['nullable', 'array'];
            $rules['solicitudes.*'] = ['string', 'exists:solicitudes,solicitud_id'];
            $rules['instituciones'] = ['nullable', 'array'];
            $rules['instituciones.*'] = ['integer', 'exists:instituciones,id'];
            $rules['concejales'] = ['nullable', 'array'];
            $rules['concejales.*'] = ['string', Rule::exists('personas', 'cedula')];
        } elseif ($tipo_reunion == 2) {
            // Mesa de Trabajo: Sin solicitudes
            $rules['instituciones'] = ['nullable', 'array'];
            $rules['instituciones.*'] = ['integer', 'exists:instituciones,id'];
            $rules['concejales'] = ['nullable', 'array'];
            $rules['concejales.*'] = ['string', Rule::exists('personas', 'cedula')];
        } elseif ($tipo_reunion == 3) {
            // Sesión Solemne: Solo datos básicos
            // No se agregan reglas para solicitudes, instituciones ni concejales
        }
        
        return $rules;
    }

    public function messages(): array
    {
        return [
            'solicitud_id.exists' => 'La solicitud seleccionada no existe.',
            'solicitudes.array' => 'Las solicitudes deben ser una lista válida.',
            'solicitudes.*.exists' => 'Una o más solicitudes seleccionadas no existen.',
            'instituciones.array' => 'Las instituciones deben ser una lista válida.',
            'instituciones.*.exists' => 'Una o más instituciones seleccionadas no existen.',
            'concejales.array' => 'Los concejales deben ser una lista válida.',
            'concejales.*.exists' => 'Uno o más concejales seleccionados no existen.',
            'titulo.required' => 'El título de la reunión es obligatorio.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'tipo_reunion.required' => 'Debe seleccionar un tipo de reunión.',
            'tipo_reunion.exists' => 'El tipo de reunión seleccionado no existe.',
            'fecha_reunion.required' => 'La fecha de la reunión es obligatoria.',
            'fecha_reunion.date' => 'La fecha debe ser válida.',
            'fecha_reunion.after_or_equal' => 'La fecha debe ser hoy o posterior.',
            'hora_reunion.required' => 'La hora de la reunión es obligatoria.',
            'nuevo_estado_solicitud.max' => 'El estado no puede exceder los 255 caracteres.',
        ];
    }
}