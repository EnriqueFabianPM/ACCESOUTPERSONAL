<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Mail\EmpleadoQR;
use App\Models\Log;

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

        if ($request->hasFile('Foto')) {
            $imagen = $request->file('Foto');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->move(public_path('FotosEmpleados'), $nombreImagen);
            $validatedData['Foto'] = 'FotosEmpleados/' . $nombreImagen;
        }

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData);
            $validatedData['Fotoqr'] = $qrCodePath;
        }

        $empleado = Empleado::create($validatedData);
        $this->sendQRCodeByEmail($empleado);

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
            'Fotoqr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->move(public_path('FotosEmpleados'), $nombreImagen);
            $validatedData['Foto'] = 'FotosEmpleados/' . $nombreImagen;
        }

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData);
            $validatedData['Fotoqr'] = $qrCodePath;
        }

        $empleado->update($validatedData);
        $this->sendQRCodeByEmail($empleado);

        return redirect()->route('empleados.index')->with('flash_message', 'Registro de empleado actualizado exitósamente!');
    }

    public function destroy($identificador): RedirectResponse
    {
        $empleado = Empleado::where('identificador', $identificador)->firstOrFail();
        $empleado->delete();

        return redirect()->route('empleados.index')->with('flash_message', 'Registro de empleado eliminado exitósamente!');
    }

    private function saveQRCode($qrCodeData)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrCodeData));
        $qrCodePath = 'ImagenesQREmpleados/' . time() . '_qrcode.jpg';
        file_put_contents(public_path($qrCodePath), $imageData);

        return $qrCodePath;
    }

    private function sendQRCodeByEmail(Empleado $empleado)
    {
        $email = $empleado->email;
        $domain = substr(strrchr($email, "@"), 1);

        if ($domain === 'gmail.com' || $domain === 'googlemail.com') {
            Mail::mailer('smtp')->to($email)->send(new EmpleadoQR($empleado->Fotoqr));
        } elseif (in_array($domain, ['outlook.com', 'hotmail.com', 'live.com'])) {
            Mail::mailer('smtp_outlook')->to($email)->send(new EmpleadoQR($empleado->Fotoqr));
        } else {
            Mail::to($email)->send(new EmpleadoQR($empleado->Fotoqr));
        }
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
    
    public function log(): View
    {
        $empleados = Empleado::orderBy('updated_at', 'desc')->get();
        return view('empleados.log', compact('empleados'));
    }
}
