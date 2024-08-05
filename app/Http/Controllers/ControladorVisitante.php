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

    public function showEntradaForm($id): View
    {
        $visitante = Visitante::findOrFail($id);
        return view('visitantes.entrada', compact('visitante'));
    }

    public function storeEntrada(Request $request, $id): RedirectResponse
    {
        $visitante = Visitante::findOrFail($id);
        $visitante->entrada = now();
        $visitante->save();

        return redirect()->route('visitantes.log')->with('flash_message', 'Entrada registrada exitósamente!');
    }

    public function showSalidaForm($id): View
    {
        $visitante = Visitante::findOrFail($id);
        return view('visitantes.salida', compact('visitante'));
    }

    public function storeSalida(Request $request, $id): RedirectResponse
    {
        $visitante = Visitante::findOrFail($id);
        $visitante->salida = now();
        $visitante->save();

        return redirect()->route('visitantes.log')->with('flash_message', 'Salida registrada exitósamente!');
    }

    protected function logVisitantesActivity($action, $request)
    {
        VisitantesLog::create([
            'user_id'    => Auth::id(),
            'user_email' => Auth::user()->email,
            'action'     => $action,
            'visitante_id' => $request->input('identificador'), // Ensure this is set
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
        $qrCodePath = 'ImagenesQRVisitantes/' . time() . '_Visitante' . $identificador . '_qrcode.jpg';
        file_put_contents(public_path($qrCodePath), $imageData);

        return $qrCodePath;
    }

    private function sendQRCodeByEmail(Visitante $visitante)
    {
        $email = $visitante->email;
        $domain = substr(strrchr($email, "@"), 1);

        if ($domain === 'gmail.com' || $domain === 'googlemail.com') {
            Mail::mailer('smtp')->to($email)->send(new VisitanteQR($visitante));
        } elseif (in_array($domain, ['outlook.com', 'hotmail.com', 'live.com'])) {
            Mail::mailer('smtp_outlook')->to($email)->send(new VisitanteQR($visitante));
        } else {
            Mail::to($email)->send(new VisitanteQR($visitante));
        }
    }
    public function log()
    {
        $logs = Log::where('table', 'visitantes')->paginate(10); // Adjust as needed
        return view('visitantes.logs', compact('logs'));
    }
}
