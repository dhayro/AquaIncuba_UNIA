<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Mostrar configuración de la empresa
     */
    public function show()
    {
        $empresa = Empresa::find(auth()->user()->id_empresa);

        return view('admin.empresa.show', [
            'empresa' => $empresa,
            'title' => 'Configuración de Empresa',
            'catName' => 'empresa',
        ]);
    }

    /**
     * Editar empresa
     */
    public function edit()
    {
        $empresa = Empresa::find(auth()->user()->id_empresa);

        return view('admin.empresa.edit', [
            'empresa' => $empresa,
            'title' => 'Editar Empresa',
            'catName' => 'empresa',
        ]);
    }

    /**
     * Actualizar empresa
     */
    public function update(Request $request)
    {
        $empresa = Empresa::find(auth()->user()->id_empresa);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|unique:empresas,rfc,' . $empresa->id,
            'correo' => 'required|email|unique:empresas,correo,' . $empresa->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $empresa->update($validated);

        return redirect()->route('empresa.show')->with('success', 'Empresa actualizada exitosamente');
    }
}
