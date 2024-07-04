<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Import the QrCode facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class ControladorEstudiante extends Controller
{
    public function index(): View
    {
        $estudiantes = Estudiante::paginate(10); // Ejemplo: paginar cada 10 resultados
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function create(): View
    {
        return view('estudiantes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',   // Adjusted to image validation
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes',
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);
    
        // Handle Foto upload
        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->move(public_path('FotosEstudiantes'), $nombreImagen);
            $validatedData['Foto'] = 'FotosEstudiantes/' . $nombreImagen;
        }

        // Create student record
        $estudiante = Estudiante::create($validatedData);
        // Generate QR Code
        $qrCodePath = $this->generateQRCode($estudiante->identificador);
        // Update the student record with the QR code path
        $estudiante->update(['Fotoqr' => $qrCodePath]);
        // Send QR Code via SMS (example, replace with your SMS sending logic)
        //$this->sendQRCode($estudiante->telefono, $qrCodePath);

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
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',   // Adjusted to image validation
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes,email,' . $estudiante->id,
            'entrada' => 'nullable|date',
            'salida' => 'nullable|date',
        ]);
        // Handle Foto upload
        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->move(public_path('FotosEstudiantes'), $nombreImagen);
            $validatedData['Foto'] = 'FotosEstudiantes/' . $nombreImagen;
        }

        $estudiante->update($validatedData);
        // Generate QR Code
        $qrCodePath = $this->generateQRCode($estudiante->identificador);
        // Update the student record with the QR code path
        $estudiante->update(['Fotoqr' => $qrCodePath]);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante actualizado exitósamente!');
    }

    public function destroy(Estudiante $estudiante): RedirectResponse
    {
        $estudiante->delete();
        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante eliminado exitósamente!');
    }

    /**
     * Generate QR Code and return its path.
     *
     * @param string $data
     * @return string
     */
    private function generateQRCode($data)
    {
        $url = route('estudiantes.show', ['estudiante' => $data]);
        $qrCode = QrCode::size(300)->generate($url);
        $qrCodePath = 'ImagenesQREstudiantes/' . time() . '_qrcode.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);
        return $qrCodePath;
    }

    /**
     * Send QR Code via SMS (example method, replace with your SMS sending logic).
     *
     * @param string $phone
     * @param string $qrCodePath
     */
    //private function sendQRCode($phone, $qrCodePath)
    //{
        // Example code to send QR Code via SMS
        // Replace this with your actual SMS sending logic
        // $smsService->sendSMS($phone, 'Here is your QR Code: ' . asset('storage/' . $qrCodePath));
        // Assuming asset('storage/') works with your storage setup
    //}
}