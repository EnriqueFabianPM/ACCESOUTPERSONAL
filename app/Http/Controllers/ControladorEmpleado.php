<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\EmpleadosLog;
use App\Models\Log; // Import the centralized log model
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Mail\EmpleadoQR;

class ControladorEmpleado extends Controller
{
    public function index(): View
    {
        $empleados = Empleado::paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create(): View
    {
        return view('empleados.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'areatrabajo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:empleados',
        ]);

        $empleado = Empleado::create($validatedData);

        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_Empleado' . $empleado->identificador;
            $rutaImagen = $imagen->storeAs('public/FotosEmpleados', $nombreImagen);
            $empleado->update(['Foto' => 'FotosEmpleados/' . $nombreImagen]);
        }

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData, $empleado->identificador);
            $empleado->update(['Fotoqr' => $qrCodePath]);
        }

        $this->sendQRCodeByEmail($empleado);

        $empleado->save();

        // Log the activity
        $this->logEmpleadosActivity('Create', $request);
        $this->logCentralizedActivity('empleados', 'Create', [], $request->all());

        return redirect()->route('empleados.index')->with('flash_message', 'Empleado dado de alta exitósamente!');
    }

    public function show($identificador): View
    {
        $empleado = Empleado::where('identificador', $identificador)->firstOrFail();
        return view('empleados.show', compact('empleado'));
    }

    public function edit($identificador): View
    {
        $empleado = Empleado::where('identificador', $identificador)->firstOrFail();
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado): RedirectResponse
    {
        $validatedData = $request->validate([
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'areatrabajo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:empleados,email,' . $empleado->id,
        ]);

        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_Empleado' . $empleado->identificador;
            $rutaImagen = $imagen->storeAs('public/FotosEmpleados', $nombreImagen);
            $empleado->update(['Foto' => 'FotosEmpleados/' . $nombreImagen]);
        }

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData, $empleado->identificador);
            $empleado->update(['Fotoqr' => $qrCodePath]);
        }

        $this->sendQRCodeByEmail($empleado);
        
        $oldData = $empleado->toArray();
        $empleado->update($validatedData);

        // Log the activity
        $this->logEmpleadosActivity('Update', $request);
        $this->logCentralizedActivity('empleados', 'Update', $oldData, $request->all());

        return redirect()->route('empleados.index')->with('flash_message', 'Registro de empleado actualizado exitosamente!');
    }

    public function destroy($identificador): RedirectResponse
    {
        $empleado = Empleado::where('identificador', $identificador)->firstOrFail();
        $oldData = $empleado->toArray();
        $empleado->delete();

        // Log the activity
        $this->logEmpleadosActivity('Delete', request());
        $this->logCentralizedActivity('empleados', 'Delete', $oldData, []);

        return redirect()->route('empleados.index')->with('flash_message', 'Registro de empleado eliminado exitosamente!');
    }

    // Show Entry Form
    public function showEntradaForm($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.entrada', compact('empleado'));
    }

    // Store Entry Data
    public function storeEntrada(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->entrada = now();
        $empleado->save();

        // Create log entry
        Log::create([
            'user_id' => $empleado->id,
            'user_type' => 'Empleado',
            'action' => 'Entrada',
            'timestamp' => now(),
        ]);

        return redirect()->route('InicioGuardia')->with('flash_message', 'Entrada registrada exitosamente!');
    }

    // Show Exit Form
    public function showSalidaForm($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.salida', compact('empleado'));
    }

    // Store Exit Data
    public function storeSalida(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->salida = now();
        $empleado->save();

        // Create log entry
        Log::create([
            'user_id' => $empleado->id,
            'user_type' => 'Empleado',
            'action' => 'Salida',
            'timestamp' => now(),
        ]);

        return redirect()->route('InicioGuardia')->with('flash_message', 'Salida registrada exitosamente!');
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
        $qrCodePath = 'ImagenesQREmpleados/' . time() . '_Empleado' . $identificador . '_qrcode.jpg';
        file_put_contents(public_path($qrCodePath), $imageData);

        return $qrCodePath;
    }

    private function sendQRCodeByEmail(Empleado $empleado)
    {
        $email = $empleado->email;
        $domain = substr(strrchr($email, "@"), 1);

        if ($domain === 'gmail.com' || $domain === 'googlemail.com') {
            Mail::mailer('smtp')->to($email)->send(new EmpleadoQR($empleado));
        } elseif (in_array($domain, ['outlook.com', 'hotmail.com', 'live.com'])) {
            Mail::mailer('smtp_outlook')->to($email)->send(new EmpleadoQR($empleado));
        } else {
            Mail::to($email)->send(new EmpleadoQR($empleado));
        }
    }

    public function log()
    {
        $logs = Log::where('table', 'empleados')->paginate(10); // Adjust as needed
        return view('empleados.logs', compact('logs'));
    }
}
