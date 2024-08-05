<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\EstudiantesLog;
use App\Models\Log; // Import the centralized log model
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Mail\EstudianteQR;

class ControladorEstudiante extends Controller
{
    public function index(): View
    {
        $estudiantes = Estudiante::paginate(10); // Pagination for 10 results per page
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function create(): View
    {
        return view('estudiantes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes',
        ]);

        // Create student record
        $estudiante = Estudiante::create($validatedData);

        // Handle Foto upload
        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_Estudiante' . $estudiante->identificador;
            $rutaImagen = $imagen->storeAs('public/FotosEstudiantes', $nombreImagen);
            $estudiante->update(['Foto'=> 'FotosEstudiantes/' . $nombreImagen]);
        }

        // Handle QR Code
        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData, $estudiante->identificador);
            $estudiante->update(['Fotoqr' => $qrCodePath]);
        }

        // Send email with QR code attached
        $this->sendQRCodeByEmail($estudiante);

        // Get new data
        $newData = $request->all();

        // Log the activity
        $this->logEstudiantesActivity('Create', $request);
        $this->logCentralizedActivity('estudiantes', 'Create', [], $newData);

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
        // Get old data
        $oldData = Estudiante::findOrFail($estudiante->id)->toArray();
        
        $validatedData = $request->validate([
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'semestre' => 'required|string|max:255',
            'grupo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes,email,' . $estudiante->id,
        ]);

        // Handle Foto upload
        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_Estudiante' . $estudiante->identificador;
            $rutaImagen = $imagen->storeAs('public/FotosEstudiantes', $nombreImagen);
            $estudiante->update(['Foto'=> 'FotosEstudiantes/' . $nombreImagen]);
        }

        // Handle QR Code
        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData, $estudiante->identificador);
            $estudiante->update(['Fotoqr' => $qrCodePath]);
        }

        // Update student record
        $estudiante->update($validatedData);

        // Send email with QR code attached
        $this->sendQRCodeByEmail($estudiante);

        // Get new data
        $newData = $request->all();

        // Log the activity
        $this->logEstudiantesActivity('Update', $request);
        $this->logCentralizedActivity('estudiantes', 'Update', $oldData, $newData);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante actualizado exitósamente!');
    }

    public function destroy($id): RedirectResponse
    {
        $estudiante = Estudiante::findOrFail($id);
        $oldData = $estudiante->toArray();
        $estudiante->delete();

        // Log the activity
        $this->logEstudiantesActivity('Delete', request());
        $this->logCentralizedActivity('estudiantes', 'Delete', $oldData, []);

        return redirect()->route('estudiantes.index')->with('flash_message', 'Registro de estudiante eliminado exitósamente!');
    }

    public function showEntradaForm($id): View
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('estudiantes.entrada', compact('estudiante'));
    }

    public function storeEntrada(Request $request, $id): RedirectResponse
    {
        // Get old data
        $oldData = Estudiante::findOrFail($id)->toArray();
        
        $estudiante = Estudiante::findOrFail($id);
        $estudiante->entrada = now();
        $estudiante->save();

        // Get new data
        $newData = $request->all();

        // Log the activity
        $this->logEstudiantesActivity('Entrada', $request);
        $this->logCentralizedActivity('estudiantes', 'Entrada', $oldData, $newData);

        return redirect()->route('estudiantes.log')->with('flash_message', 'Entrada registrada exitósamente!');
    }

    public function showSalidaForm($id): View
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('estudiantes.salida', compact('estudiante'));
    }

    public function storeSalida(Request $request, $id): RedirectResponse
    {
        // Get old data
        $oldData = Estudiante::findOrFail($id)->toArray();
        
        $estudiante = Estudiante::findOrFail($id);
        $estudiante->salida = now();
        $estudiante->save();

        // Get new data
        $newData = $request->all();
        
        // Log the activity
        $this->logEstudiantesActivity('Salida', $request);
        $this->logCentralizedActivity('estudiantes', 'Salida', $oldData, $newData);

        return redirect()->route('estudiantes.log')->with('flash_message', 'Salida registrada exitósamente!');
    }

    protected function logEstudiantesActivity($action, $request)
    {
        EstudiantesLog::create([
            'user_id'    => Auth::id(),
            'user_email' => Auth::user()->email,
            'action'     => $action,
            'estudiante_id' => $request->input('identificador'), // Ensure this is set
            'old_data'   => json_encode($request->except('_token')), // Adjust according to what data you want to log
            'new_data'   => json_encode($request->all()),
        ]);
    }

    protected function logCentralizedActivity($tableName, $action, $oldData, $newData)
    {
        Log::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'table_name' => $tableName,
            'action' => $action,
            'record_id' => $oldData['id'] ?? null, // Use record ID if available
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    private function saveQRCode($qrCodeData, $identificador)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrCodeData));
        $qrCodePath = 'ImagenesQREstudiantes/' . time() . '_Estudiante' . $identificador . '.png';
        file_put_contents(public_path($qrCodePath), $imageData);

        return $qrCodePath;
    }

    private function sendQRCodeByEmail($estudiante)
    {
        Mail::to($estudiante->email)->send(new EstudianteQR($estudiante));
    }

    public function log()
    {
        $logs = Log::where('table', 'estudiantes')->paginate(10); // Adjust as needed
        return view('estudiantes.logs', compact('logs'));
    }
}
