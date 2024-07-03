<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ControladorVisitante extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $visitantes = Visitante::paginate(10); // Ejemplo: paginar cada 10 resultados
        return view('visitantes.index', compact('visitantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('visitantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjusted to image validation
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'motivo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:visitantes',
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);

        // Handle Fotoqr upload
        if ($request->hasFile('Fotoqr')) {
            $imagenQR = $request->file('Fotoqr');
            $nombreImagenQR = time() . '_' . $imagenQR->getClientOriginalName();
            $rutaImagenQR = $imagenQR->move(public_path('ImagenesQRVisitante'), $nombreImagenQR);
            $validatedData['Fotoqr'] = 'ImagenesQRVisitante/' . $nombreImagenQR;
        }

        Visitante::create($validatedData);

        return redirect()->route('visitantes.index')->with('flash_message', 'Visitante dado de alta exitósamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitante $visitante): View
    {
        return view('visitantes.show', compact('visitante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitante $visitante): View
    {
        return view('visitantes.edit', compact('visitante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitante $visitante): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjusted to image validation
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'motivo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:visitantes,email,' . $visitante->id,
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);

        // Handle Fotoqr upload
        if ($request->hasFile('Fotoqr')) {
            $imagenQR = $request->file('Fotoqr');
            $nombreImagenQR = time() . '_' . $imagenQR->getClientOriginalName();
            $rutaImagenQR = $imagenQR->move(public_path('ImagenesQRVisitante'), $nombreImagenQR);
            $validatedData['Fotoqr'] = 'ImagenesQRVisitante/' . $nombreImagenQR;
        }

        $visitante->update($validatedData);

        return redirect()->route('visitantes.index')->with('flash_message', 'Registro de visitante actualizado exitósamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitante $visitante): RedirectResponse
    {
        $visitante->delete();
        return redirect()->route('visitantes.index')->with('flash_message', 'Registro de visitante eliminado exitósamente!');
    }
}
