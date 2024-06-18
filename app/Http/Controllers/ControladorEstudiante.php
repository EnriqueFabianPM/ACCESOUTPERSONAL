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
            'Fotoqr' => 'nullable|image|max:2048',
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

        if ($request->hasFile('Fotoqr')) {
            $path = $request->file('Fotoqr')->store('qr_codes', 'student_images');
            $validatedData['Fotoqr'] = $path;
        }

        if ($request->hasFile('Foto')) {
            $path = $request->file('Foto')->store('student_photos', 'student_images');
            $validatedData['Foto'] = $path;
        }

        Estudiante::create($validatedData);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Student added successfully!');
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
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes,email,' . $estudiante->id,
            'Fotoqr' => 'nullable|image|max:2048',
            'Foto' => 'nullable|image|max:2048',
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);

        if ($request->hasFile('Fotoqr')) {
            $path = $request->file('Fotoqr')->store('qr_codes', 'student_images');
            $validatedData['Fotoqr'] = $path;
        }

        if ($request->hasFile('Foto')) {
            $path = $request->file('Foto')->store('student_photos', 'student_images');
            $validatedData['Foto'] = $path;
        }

        $estudiante->update($validatedData);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Student updated successfully!');
    }

    public function destroy(Estudiante $estudiante): RedirectResponse
    {
        $estudiante->delete();
        return redirect('estudiante')->with('flash_message', 'Student Deleted!');
    }
}