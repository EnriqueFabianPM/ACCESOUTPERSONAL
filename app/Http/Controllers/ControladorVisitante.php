<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\VisitantesLog;
use App\Models\Log; // Import the centralized log model
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Mail\VisitanteQR;
use Illuminate\Support\Facades\DB;

class ControladorVisitante extends Controller
{
    public function index(): View
    {
        $visitantes = Visitante::paginate(10);
        return view('visitantes.index', compact('visitantes'));
    }

    public function create(): View
    {
        return view('visitantes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'motivo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:visitantes',
        ]);

        $visitante = Visitante::create($validatedData);

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData, $visitante->identificador);
            $visitante->update(['Fotoqr' => $qrCodePath]);
        }

        $this->sendQRCodeByEmail($visitante);

        $visitante->save();
        
        // Log the activity
        $this->logVisitantesActivity('Create', $request);
        $this->logCentralizedActivity('visitantes', 'Create', [], $request->all());

        return redirect()->route('visitantes.index')->with('flash_message', 'Visitante dado de alta exitósamente!');
    }

    public function show($identificador): View
    {
        $visitante = Visitante::where('identificador', $identificador)->firstOrFail();
        return view('visitantes.show', compact('visitante'));
    }

    public function edit($identificador): View
    {
        $visitante = Visitante::where('identificador', $identificador)->firstOrFail();
        return view('visitantes.edit', compact('visitante'));
    }

    public function update(Request $request, Visitante $visitante): RedirectResponse
    {
        $validatedData = $request->validate([
            'identificador' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'motivo' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:visitantes,email,' . $visitante->id,
        ]);

        if ($request->filled('qrCodeData')) {
            $qrCodeData = $request->input('qrCodeData');
            $qrCodePath = $this->saveQRCode($qrCodeData, $visitante->identificador);
            $visitante->update(['Fotoqr' => $qrCodePath]);
        }

        $oldData = $visitante->toArray();
        $visitante->update($validatedData);

        $this->sendQRCodeByEmail($visitante);
        
        // Log the activity
        $this->logVisitantesActivity('Update', $request);
        $this->logCentralizedActivity('visitantes', 'Update', $oldData, $request->all());

        return redirect()->route('visitantes.index')->with('flash_message', 'Registro de visitante actualizado exitosamente!');
    }

    public function destroy($identificador): RedirectResponse
    {
        $visitante = Visitante::where('identificador', $identificador)->firstOrFail();
        $oldData = $visitante->toArray();
        $visitante->delete();

        // Log the activity
        $this->logVisitantesActivity('Delete', request());
        $this->logCentralizedActivity('visitantes', 'Delete', $oldData, []);

        return redirect()->route('visitantes.index')->with('flash_message', 'Registro de visitante eliminado exitosamente!');
    }

    // Show Entry Form for Visitor
    public function showEntradaForm($id)
    {
        $visitante = Visitante::findOrFail($id);
        return view('visitantes.entrada', compact('visitante'));
    }

    // Store Entry Data for Visitor
    public function storeEntrada(Request $request, $id)
    {
        $visitante = Visitante::findOrFail($id);
        $visitante->entrada = now();
        $visitante->save();

        // Create log entry
        Log::create([
            'user_id' => $visitante->id,
            'user_type' => 'Visitante',
            'action' => 'Entrada',
            'timestamp' => now(),
        ]);

        return redirect()->route('InicioGuardia')->with('flash_message', 'Entrada registrada exitosamente!');
    }

    // Show Exit Form for Visitor
    public function showSalidaForm($id)
    {
        $visitante = Visitante::findOrFail($id);
        return view('visitantes.salida', compact('visitante'));
    }

    // Store Exit Data for Visitor
    public function storeSalida(Request $request, $id)
    {
        $visitante = Visitante::findOrFail($id);
        $visitante->salida = now();
        $visitante->save();

        // Create log entry
        Log::create([
            'user_id' => $visitante->id,
            'user_type' => 'Visitante',
            'action' => 'Salida',
            'timestamp' => now(),
        ]);

        return redirect()->route('InicioGuardia')->with('flash_message', 'Salida registrada exitosamente!');
    }
    
    public function log(): View
    {
        $logs = Log::where('table', 'visitantes')->paginate(10); // Adjust as needed
        return view('visitantes.logs', compact('logs'));
    }

}
