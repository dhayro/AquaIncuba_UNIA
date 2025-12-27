<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LecturaSensor;
use App\Models\Sensor;
use App\Models\Incubadora;
use Illuminate\Http\Request;

class LecturaSensorController extends Controller
{
    public function index()
    {
        $catName = 'monitoreo';
        $empresaId = auth()->user()->id_empresa;
        
        $lecturas = LecturaSensor::whereHas('sensor')
            ->whereHas('incubadora', function ($query) use ($empresaId) {
                $query->where('id_empresa', $empresaId);
            })
            ->with(['sensor', 'incubadora'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.lecturas.index', compact('lecturas', 'catName'));
    }

    public function create()
    {
        $catName = 'monitoreo';
        $empresaId = auth()->user()->id_empresa;
        $sensores = Sensor::all();
        $incubadoras = Incubadora::where('id_empresa', $empresaId)->get();
        
        return view('admin.lecturas.create', compact('sensores', 'incubadoras', 'catName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sensor' => 'required|exists:sensores,id',
            'id_incubadora' => 'required|exists:incubadoras,id',
            'valor' => 'required|numeric',
            'timestamp' => 'nullable|date',
        ]);

        $incubadora = Incubadora::find($request->id_incubadora);
        
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        LecturaSensor::create($request->all());

        return redirect()->route('lecturas.index')
            ->with('success', 'Lectura registrada correctamente');
    }

    public function show(LecturaSensor $lectura)
    {
        $catName = 'monitoreo';
        if ($lectura->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        return view('admin.lecturas.show', compact('lectura', 'catName'));
    }

    public function edit(LecturaSensor $lectura)
    {
        $catName = 'monitoreo';
        if ($lectura->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $sensores = Sensor::all();
        $incubadoras = Incubadora::where('id_empresa', auth()->user()->id_empresa)->get();
        
        return view('admin.lecturas.edit', compact('lectura', 'sensores', 'incubadoras', 'catName'));
    }

    public function update(Request $request, LecturaSensor $lectura)
    {
        if ($lectura->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $request->validate([
            'valor' => 'required|numeric',
            'timestamp' => 'nullable|date',
        ]);

        $lectura->update($request->only('valor', 'timestamp'));

        return redirect()->route('lecturas.index')
            ->with('success', 'Lectura actualizada correctamente');
    }

    public function destroy(LecturaSensor $lectura)
    {
        if ($lectura->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $lectura->delete();

        return redirect()->route('lecturas.index')
            ->with('success', 'Lectura eliminada correctamente');
    }
}
