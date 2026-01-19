<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstudioCalidadAgua;
use App\Models\Incubadora;
use App\Models\MuestraEstudio;
use Illuminate\Http\Request;

class EstudioCalidadAguaController extends Controller
{
    /**
     * Listar estudios con DataTable
     */
    public function index()
    {
        // ✅ Ejecutar automáticamente cuando se carga la página
        $this->actualizarEstudios();
        
        return view('admin.estudios.index', [
            'title' => 'Gestión de Estudios de Calidad de Agua',
            'catName' => 'estudios',
        ]);
    }

    /**
     * ✅ Actualizar automáticamente estados de estudios
     */
    private function actualizarEstudios()
    {
        try {
            $hoy = now()->format('Y-m-d');
            
            // Activar estudios planificados
            EstudioCalidadAgua::where('estado', 'planificado')
                ->whereNotNull('fecha_inicio')
                ->whereDate('fecha_inicio', '<=', $hoy)
                ->update(['estado' => 'en_progreso']);
            
            // Finalizar estudios vencidos
            EstudioCalidadAgua::where('estado', 'en_progreso')
                ->whereNotNull('fecha_fin')
                ->whereDate('fecha_fin', '<', $hoy)
                ->update(['estado' => 'finalizado']);
                
        } catch (\Exception $e) {
            // Silenciosamente si hay error
            \Log::warning('Error actualizando estudios: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos de estudios para DataTable (AJAX)
     */
    public function getEstudiosData()
    {
        try {
            // ✅ Actualizar estados cada vez que se carga la tabla
            $this->actualizarEstudios();
            $empresaId = auth()->user()->id_empresa;
            
            $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
                ->with(['incubadoras'])
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $estudios->map(function ($estudio) {
                // Generar badges para incubadoras con botón de info
                $incubadorasCount = $estudio->incubadoras->count();
                $incubadorasStr = $incubadorasCount > 0 
                    ? '<div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary">'. $incubadorasCount .' incubadora'. ($incubadorasCount > 1 ? 's' : '') .'</span>
                        <button class="btn btn-sm btn-outline-info view-incubadoras-btn" type="button" data-estudio-id="'. $estudio->id .'" title="Ver incubadoras">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </button>
                      </div>'
                    : '—';

                $estadoBadge = match(strtolower($estudio->estado)) {
                    'planificado' => '<span class="badge bg-info">Planificado</span>',
                    'en_progreso' => '<span class="badge bg-warning">En Progreso</span>',
                    'finalizado' => '<span class="badge bg-success">Finalizado</span>',
                    'cancelado' => '<span class="badge bg-danger">Cancelado</span>',
                    default => '<span class="badge bg-secondary">Desconocido</span>'
                };

                // Verificar si puede editarse (solo estado "planificado")
                $canEdit = strtolower($estudio->estado) === 'planificado';

                $fechaFin = $estudio->fecha_fin 
                    ? \Carbon\Carbon::parse($estudio->fecha_fin)->format('d/m/Y')
                    : '—';

                return [
                    'id' => $estudio->id,
                    'nombre' => $estudio->nombre,
                    'codigo_estudio' => $estudio->codigo_estudio,
                    'incubadoras' => $incubadorasStr ?: '—',
                    'fecha_inicio' => \Carbon\Carbon::parse($estudio->fecha_inicio)->format('d/m/Y'),
                    'fecha_fin' => $fechaFin,
                    'estado' => $estadoBadge,
                    'can_edit' => $canEdit,
                    'acciones' => $this->generateEstudioActions($estudio->id, $estudio->nombre, $canEdit, $estudio->estado)
                ];
            });

            // DataTables espera un objeto con propiedad 'data'
            return response()->json([
                'data' => $data->values()->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Crear nuevo estudio con múltiples incubadoras
     */
    public function create()
    {
        $empresaId = auth()->user()->id_empresa;
        $incubadoras = Incubadora::where('id_empresa', $empresaId)
            ->where('activo', true)
            ->with('sensores')
            ->get();

        if (request()->expectsJson()) {
            return response()->json(['incubadoras' => $incubadoras]);
        }

        return view('admin.estudios.create', [
            'incubadoras' => $incubadoras,
            'title' => 'Crear Estudio',
            'catName' => 'estudios',
        ]);
    }

    /**
     * Guardar nuevo estudio (AJAX)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'incubadoras' => 'required|array|min:1',
            'incubadoras.*' => 'exists:incubadoras,id',
            'numero_muestras' => 'required|integer|min:1',
            'notas' => 'nullable|string',
        ]);

        try {
            $empresaId = auth()->user()->id_empresa;
            
            // Generar código automático: EST-UNIA-YYYY-NNN
            $year = date('Y');
            $lastEstudio = EstudioCalidadAgua::where('id_empresa', $empresaId)
                ->where('codigo_estudio', 'like', "EST-UNIA-{$year}-%")
                ->orderBy('codigo_estudio', 'desc')
                ->first();
            
            // Extraer el número del último código o empezar en 001
            if ($lastEstudio) {
                preg_match('/EST-UNIA-\d{4}-(\d+)/', $lastEstudio->codigo_estudio, $matches);
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }
            
            $codigoEstudio = 'EST-UNIA-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Crear el estudio
            $estudio = EstudioCalidadAgua::create([
                'id_empresa' => $empresaId,
                'id_creado_por' => auth()->id(),
                'nombre' => $validated['nombre'],
                'codigo_estudio' => $codigoEstudio,
                'descripcion' => $validated['descripcion'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_fin' => $validated['fecha_fin'],
                'estado' => 'planificado',
                'notas' => $validated['notas'],
            ]);

            // Asignar incubadoras al estudio con sus órdenes
            foreach ($validated['incubadoras'] as $index => $incubadoraId) {
                $estudio->incubadoras()->attach($incubadoraId, [
                    'orden_posicion' => $index,
                ]);
            }

            // ✅ Generar automáticamente SensorParametroMapping
            try {
                $mappings_creados = \App\Helpers\SensorMappingHelper::generarMappingsParaEstudio($estudio->id);
                \Log::info("Creados {$mappings_creados} mappings para estudio {$estudio->id}");
            } catch (\Exception $e) {
                \Log::warning("Error generando mappings para estudio {$estudio->id}: {$e->getMessage()}");
            }

            // Crear muestras vacías basadas en numero_muestras
            for ($i = 1; $i <= $validated['numero_muestras']; $i++) {
                MuestraEstudio::create([
                    'id_estudio_calidad' => $estudio->id,
                    'codigo_muestra' => $estudio->codigo_estudio . '-M' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'numero_secuencia' => $i,
                    'fecha_hora_muestra' => now(),
                    'observacion' => null,
                    'es_valida' => true,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Estudio creado correctamente',
                'estudio_id' => $estudio->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Ver detalles del estudio (AJAX)
     */
    public function show(EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);
        
        $estudio->load(['incubadoras', 'muestrasEstudio']);

        // Siempre retorna JSON para AJAX
        return response()->json([
            'id' => $estudio->id,
            'nombre' => $estudio->nombre,
            'codigo_estudio' => $estudio->codigo_estudio,
            'descripcion' => $estudio->descripcion,
            'fecha_inicio' => \Carbon\Carbon::parse($estudio->fecha_inicio)->format('Y-m-d'),
            'fecha_fin' => $estudio->fecha_fin ? \Carbon\Carbon::parse($estudio->fecha_fin)->format('Y-m-d') : null,
            'estado' => $estudio->estado,
            'notas' => $estudio->notas,
            'incubadoras' => $estudio->incubadoras->pluck('id')->toArray(),
            'numero_muestras' => $estudio->muestrasEstudio->count(),
        ]);
    }

    /**
     * Editar estudio (AJAX)
     */
    public function edit(EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);
        
        // Verificar que el estudio está en estado "planificado"
        if ($estudio->estado !== 'planificado') {
            return response()->json([
                'error' => 'No se puede editar un estudio que está en progreso, finalizado o cancelado',
                'estado' => $estudio->estado
            ], 403);
        }
        
        // Siempre retorna JSON para AJAX
        return response()->json([
            'id' => $estudio->id,
            'nombre' => $estudio->nombre,
            'codigo_estudio' => $estudio->codigo_estudio,
            'descripcion' => $estudio->descripcion,
            'fecha_inicio' => \Carbon\Carbon::parse($estudio->fecha_inicio)->format('Y-m-d'),
            'fecha_fin' => $estudio->fecha_fin ? \Carbon\Carbon::parse($estudio->fecha_fin)->format('Y-m-d') : null,
            'estado' => $estudio->estado,
            'notas' => $estudio->notas,
            'incubadoras' => $estudio->incubadoras()->pluck('incubadoras.id')->toArray(),
            'incubadoras_ordenadas' => $estudio->incubadoras()
                ->orderBy('orden_posicion', 'asc')
                ->pluck('incubadora_id')
                ->toArray(),
        ]);
    }

    /**
     * Actualizar estudio (AJAX)
     */
    public function update(Request $request, EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);

        // Verificar que el estudio está en estado "planificado"
        if ($estudio->estado !== 'planificado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede editar un estudio que está en progreso, finalizado o cancelado'
            ], 403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'incubadoras' => 'nullable|array|min:1',
            'incubadoras.*' => 'exists:incubadoras,id',
            'notas' => 'nullable|string',
        ]);

        try {
            $estudio->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_fin' => $validated['fecha_fin'],
                'notas' => $validated['notas'],
            ]);

            // Actualizar incubadoras si se proporcionan
            if (!empty($validated['incubadoras'])) {
                $estudio->incubadoras()->detach();
                foreach ($validated['incubadoras'] as $index => $incubadoraId) {
                    $estudio->incubadoras()->attach($incubadoraId, [
                        'orden_posicion' => $index,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Estudio actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Eliminar estudio (AJAX)
     */
    public function destroy($id)
    {
        try {
            $estudio = EstudioCalidadAgua::find($id);
            
            if (!$estudio) {
                return response()->json(['error' => 'Estudio no encontrado'], 404);
            }

            $this->checkAccess($estudio);

            // Eliminar datos relacionados
            $estudio->muestrasEstudio()->each(function ($muestra) {
                $muestra->datosCrudos()->delete();
                $muestra->datosProcessados()->delete();
                $muestra->delete();
            });

            // Desasociar incubadoras
            $estudio->incubadoras()->detach();
            
            // Eliminar estudio
            $estudio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Estudio eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obtener incubadoras disponibles para un estudio (AJAX)
     */
    public function getIncubadorasDisponibles()
    {
        try {
            $empresaId = auth()->user()?->id_empresa;
            
            $incubadoras = Incubadora::where('id_empresa', $empresaId)
                ->where('activo', true)
                ->with('incubadoraSensores')
                ->get()
                ->map(function ($incubadora) {
                    return [
                        'id' => $incubadora->id,
                        'nombre' => $incubadora->nombre . ' (' . $incubadora->codigo . ')',
                        'sensores_count' => $incubadora->incubadoraSensores->count(),
                    ];
                });

            return response()->json($incubadoras);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Verificar acceso al estudio
     */
    private function checkAccess(EstudioCalidadAgua $estudio)
    {
        if ($estudio->id_empresa !== auth()->user()->id_empresa) {
            throw new \Exception('No tienes permiso para acceder a este estudio');
        }
    }

    /**
     * Obtener incubadoras de un estudio específico con detalles de sensores
     */
    public function getIncubadorasEstudio($estudioId)
    {
        $estudio = EstudioCalidadAgua::find($estudioId);
        
        if (!$estudio) {
            return response()->json(['error' => 'Estudio no encontrado'], 404);
        }

        $this->checkAccess($estudio);

        $incubadoras = $estudio->incubadoras()
            ->orderBy('orden_posicion', 'asc')
            ->get()
            ->map(function($inc) {
                // Obtener sensores con sus tipos y unidades
                $sensores = $inc->sensores()
                    ->with(['sensorTipoUnidades.tipoSensor', 'sensorTipoUnidades.unidadMedida'])
                    ->get()
                    ->map(function($sensor) {
                        // Obtener descripción de lo que mide cada sensor
                        $tiposUnidades = $sensor->sensorTipoUnidades
                            ->map(function($su) {
                                return $su->tipoSensor->nombre . ' (' . $su->unidadMedida->simbolo . ')';
                            })
                            ->join(', ');
                        
                        return [
                            'id' => $sensor->id,
                            'nombre' => $sensor->nombre ?? 'Sin nombre',
                            'tipos_unidades' => $tiposUnidades ?: 'No configurado',
                        ];
                    });

                return [
                    'id' => $inc->id,
                    'nombre' => $inc->nombre,
                    'ubicacion' => $inc->ubicacion ?? 'No especificada',
                    'sensores_count' => $sensores->count(),
                    'sensores' => $sensores,
                ];
            });

        return response()->json([
            'estudio_nombre' => $estudio->nombre,
            'incubadoras' => $incubadoras
        ]);
    }

    /**
     * ✅ Finalizar estudio manualmente
     */
    public function finalizar(EstudioCalidadAgua $estudio)
    {
        try {
            $this->checkAccess($estudio);

            // Validar que esté en progreso
            if (strtolower($estudio->estado) !== 'en_progreso') {
                return response()->json([
                    'success' => false,
                    'message' => '❌ El estudio no está en progreso. Solo se pueden finalizar estudios en progreso.'
                ], 400);
            }

            // Finalizar
            $estudio->marcarFinalizado();

            return response()->json([
                'success' => true,
                'message' => '✅ Estudio finalizado exitosamente. Estado actualizado a "Finalizado".',
                'estado' => 'finalizado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar botones de acciones para una fila (identical a sensores)
     */
    private function generateEstudioActions($estudioId, $estudioNombre, $canEdit = true, $estado = null)
    {
        $viewBtn = '<button type="button" class="btn btn-sm btn-outline-primary btn-view-estudio" data-estudio-id="' . $estudioId . '" title="Ver detalles" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>';
        
        // ✅ Determinar si está en progreso
        $enProgreso = strtolower($estado) === 'en_progreso';
        
        // Si no puede editarse O está en progreso, mostrar botón deshabilitado
        if (!$canEdit || $enProgreso) {
            $editBtn = '<button type="button" class="btn btn-sm btn-outline-secondary ms-2 disabled" disabled title="No se puede editar (estado no permite cambios)" style="padding: 0.375rem 0.75rem; opacity: 0.5; cursor: not-allowed;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>';
        } else {
            $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-estudio ms-2" data-estudio-id="' . $estudioId . '" title="Editar" style="padding: 0.375rem 0.75rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>';
        }
        
        // ✅ Botón Eliminar: SOLO activo si puede editarse (estado "planificado")
        // Igual que Editar: deshabilitado si está en progreso, finalizado, cancelado, etc.
        if (!$canEdit || $enProgreso) {
            $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger ms-2 disabled" disabled title="No se puede eliminar (estado no permite cambios)" style="padding: 0.375rem 0.75rem; opacity: 0.5; cursor: not-allowed;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>';
        } else {
            $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-estudio ms-2" data-estudio-id="' . $estudioId . '" data-estudio-nombre="' . htmlspecialchars($estudioNombre) . '" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </button>';
        }
        
        // ✅ Botón para finalizar manualmente (solo si está en progreso)
        $finalizarBtn = '';
        if ($enProgreso) {
            $finalizarBtn = '<button type="button" class="btn btn-sm btn-outline-success btn-finalizar-estudio ms-2" data-estudio-id="' . $estudioId . '" title="Finalizar estudio manualmente" style="padding: 0.375rem 0.75rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>';
        }
        
        return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn . ' ' . $finalizarBtn;
    }
}
