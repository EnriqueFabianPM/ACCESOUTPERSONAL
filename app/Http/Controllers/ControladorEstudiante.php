<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\EstudianteQR;

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

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData);
            $validatedData['Fotoqr'] = $qrCodePath;
        }

        // Create student record
        $estudiante = Estudiante::create($validatedData);

        // Send email with QR code attached
        $this->sendQRCodeByEmail($estudiante);
        return redirect()->route('estudiantes.index')->with('flash_message', 'Estudiante dado de alta exitósamente!');
    }

    public function show($identificador): View
    {
        $estudiante = Estudiante::where('identificador', $identificador)->firstOrFail();
        return view('estudiantes.show', compact('estudiante'));
    }

    public function edit($identificador): View
    {
        $estudiante = Estudiante::where('identificador', $identificador)->firstOrFail();
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Estudiante $estudiante): RedirectResponse
    {
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',   // Adjusted to image validation
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

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData);
            $validatedData['Fotoqr'] = $qrCodePath;
        }

        // Create student record
        $estudiante->update($validatedData);

        // Send email with QR code attached
        $this->sendQRCodeByEmail($estudiante);

        // Generate QR code path and update the record
        //$qrCodePath = $this->generateQRCodePath($estudiante->identificador); // Example function to generate QR code path

        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante actualizado exitósamente!');
    }

    public function destroy($identificador): RedirectResponse
    {
        $estudiante = Estudiante::where('identificador', $identificador)->firstOrFail();
        $estudiante->delete();
        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante eliminado exitósamente!');
    }

    private function saveQRCode($qrCodeData)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrCodeData));
        $qrCodePath = 'ImagenesQREstudiantes/' . time() . '_qrcode.jpg';
        file_put_contents(public_path($qrCodePath), $imageData);

        return $qrCodePath;
    }

    /**
     * Send QR code to visitante's email address.
     */
    private function sendQRCodeByEmail(Estudiante $estudiante)
    {
        $email = $estudiante->email;
        $domain = substr(strrchr($email, "@"), 1);

        if ($domain === 'gmail.com' || $domain === 'googlemail.com') {
            Mail::mailer('smtp')->to($email)->send(new EstudianteQR($estudiante->Fotoqr));
        } elseif (in_array($domain, ['outlook.com', 'hotmail.com', 'live.com'])) {
            Mail::mailer('smtp_outlook')->to($email)->send(new EstudianteQR($estudiante->Fotoqr));
        } else {
            Mail::to($email)->send(new EstudianteQR($estudiante->Fotoqr));
        }
    }
    
}