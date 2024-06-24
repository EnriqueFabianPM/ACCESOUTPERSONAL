<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ControladorEstudiante extends Controller
{
    public function index(): View
    {
        $estudiantes = Estudiante::all();
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function create(): View
    {
        return view('estudiantes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|max:2048', // Adjust validation rules as needed
            'Foto' => 'nullable|image|max:2048',
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes',
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);
        // Handle Fotoqr upload
        if ($request->hasFile('Fotoqr')) {
            $path = $request->file('Fotoqr')->store('ImagenesQREstudiantes', 'public');
            $validatedData['Fotoqr'] = $path;
        }
    
        // Handle Foto upload
        if ($request->hasFile('Foto')) {
        $path = $request->file('Foto')->store('FotosEstudiantes', 'public');
        $validatedData['Foto'] = $path;
        }

        Estudiante::create($validatedData);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Estudiante dado de alta exitósamente!');
}


    public function show(Estudiante $estudiante): View
    {
        return view('estudiantes.show', compact('estudiante'));
    }

    public function edit(Estudiante $estudiante): View
    {
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Estudiante $estudiante): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|max:2048', // Adjust validation rules as needed
            'Foto' => 'nullable|image|max:2048',
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes,email,' . $estudiante->id,
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);
        // Handle Fotoqr upload
        if ($request->hasFile('Fotoqr')) {
            $path = $request->file('Fotoqr')->store('ImagenesQREstudiantes', 'public');
            $validatedData['Fotoqr'] = $path;
        }
    
        // Handle Foto upload
        if ($request->hasFile('Foto')) {
        $path = $request->file('Foto')->store('FotosEstudiantes', 'public');
        $validatedData['Foto'] = $path;
        }

        $estudiante->update($validatedData);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante actualizado exitósamente!');
    }

    public function destroy(Estudiante $estudiante): RedirectResponse
    {
        $estudiante->delete();
        return redirect('estudiante')->with('flash_message', 'Registro de estudiante eliminado exitósamente!');
    }
}
