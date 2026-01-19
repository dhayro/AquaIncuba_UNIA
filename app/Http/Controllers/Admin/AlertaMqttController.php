<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertaMqtt;
use App\Models\Sensor;
use Illuminate\Http\Request;

class AlertaMqttController extends Controller
{
    public function index()
    {
        $catName = 'monitoreo';
        return view('admin.alertas.index', compact('catName'));
    }

    public function getData()
    {
        $empresaId = auth()->user()->id_empresa;
        
        $alertas = AlertaMqtt::whereHas('incubadora', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })
            ->with('sensor', 'incubadora')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [];
        foreach ($alertas as $alerta) {
            $estado = isset($alerta->activa) && $alerta->activa ? 'Activo' : 'Inactivo';
            $estadoBg = isset($alerta->activa) && $alerta->activa ? 'bg-success' : 'bg-danger';
            
            $data[] = [
                $alerta->nombre ?? 'N/A',
                $alerta->descripcion ?? '',
                '<span class="badge ' . $estadoBg . ' btn-toggle-alerta-estado" 
                        data-alerta-id="' . $alerta->id . '" style="cursor: pointer;">
                    ' . $estado . '
                </span>',
                '
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-warning btn-sm btn-edit-alerta" 
                            data-alerta-id="' . $alerta->id . '"
                            data-bs-toggle="modal" data-bs-target="#editAlertaModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                             class="feather feather-edit">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm btn-delete-alerta" 
                            data-alerta-id="' . $alerta->id . '"
                            data-alerta-nombre="' . $alerta->nombre . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                             class="feather feather-trash">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
                '
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $catName = 'monitoreo';
        $sensores = Sensor::all();
        return view('admin.alertas.create', compact('sensores', 'catName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        try {
            AlertaMqtt::create($request->all());

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Alerta creada correctamente']);
            }

            return redirect()->route('alertas.index')
                ->with('success', 'Alerta creada correctamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(AlertaMqtt $alerta)
    {
        $this->authorize($alerta);

        if (request()->wantsJson()) {
            return response()->json($alerta);
        }

        $catName = 'monitoreo';
        $sensores = Sensor::all();
        $incubadoras = \App\Models\Incubadora::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('admin.alertas.edit', compact('alerta', 'sensores', 'incubadoras', 'catName'));
    }

    public function update(Request $request, AlertaMqtt $alerta)
    {
        $this->authorize($alerta);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $alerta->update($request->all());

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Alerta actualizada correctamente']);
            }

            return redirect()->route('alertas.index')
                ->with('success', 'Alerta actualizada correctamente');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(AlertaMqtt $alerta)
    {
        $this->authorize($alerta);

        try {
            $alerta->delete();

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Alerta eliminada correctamente']);
            }

            return redirect()->route('alertas.index')
                ->with('success', 'Alerta eliminada correctamente');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function toggleEstado(AlertaMqtt $alerta)
    {
        $this->authorize($alerta);

        try {
            $alerta->activa = !($alerta->activa ?? true);
            $alerta->save();

            return response()->json([
                'success' => true,
                'activa' => $alerta->activa,
                'estado' => $alerta->activa ? 'Activo' : 'Inactivo',
                'message' => 'Estado cambiado a ' . ($alerta->activa ? 'Activo' : 'Inactivo')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Autorizar acceso a alerta
     */
    private function authorize(AlertaMqtt $alerta)
    {
        if ($alerta->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
