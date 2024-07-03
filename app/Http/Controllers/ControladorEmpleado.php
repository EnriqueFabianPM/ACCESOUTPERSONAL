<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ControladorEmpleado extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $empleados = Empleado::paginate(10); // Ejemplo: paginar cada 10 resultados
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjusted to image validation
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',   // Adjusted to image validation
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'areatrabajo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:empleados',
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);

        // Handle Fotoqr upload
        if ($request->hasFile('Fotoqr')) {
            $imagenQR = $request->file('Fotoqr');
            $nombreImagenQR = time() . '_' . $imagenQR->getClientOriginalName();
            $rutaImagenQR = $imagenQR->move(public_path('ImagenesQREmpleados'), $nombreImagenQR);
            $validatedData['Fotoqr'] = 'ImagenesQREmpleados/' . $nombreImagenQR;
        }
    
        // Handle Foto upload
        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->move(public_path('FotosEmpleados'), $nombreImagen);
            $validatedData['Foto'] = 'FotosEmpleados/' . $nombreImagen;
        }

        Empleado::create($validatedData);

        return redirect()->route('empleados.index')->with('flash_message', 'Empleado dado de alta exitósamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado): View
    {
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado): View
    {
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjusted to image validation
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',   // Adjusted to image validation
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'areatrabajo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:empleados,email,' . $empleado->id,
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);
        // Handle Fotoqr upload
        if ($request->hasFile('Fotoqr')) {
            $imagenQR = $request->file('Fotoqr');
            $nombreImagenQR = time() . '_' . $imagenQR->getClientOriginalName();
            $rutaImagenQR = $imagenQR->move(public_path('ImagenesQREmpleados'), $nombreImagenQR);
            $validatedData['Fotoqr'] = 'ImagenesQREmpleados/' . $nombreImagenQR;
        }
    
        // Handle Foto upload
        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->move(public_path('FotosEmpleados'), $nombreImagen);
            $validatedData['Foto'] = 'FotosEmpleados/' . $nombreImagen;
        }

        $empleado->update($validatedData);

        return redirect()->route('empleados.index')->with('flash_message', 'Registro de empleado actualizado exitósamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado): RedirectResponse
    {
        $empleado->delete();
        return redirect()->route('empleados.index')->with('flash_message', 'Registro de empleado eliminado exitósamente!');
    }
}
