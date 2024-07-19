<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\VisitanteQR;
use Illuminate\Support\Facades\DB;
use App\Models\Log;

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

        try {
            DB::beginTransaction();

            $visitante = Visitante::create($validatedData);

            if ($request->filled('qrCodeData')) {
                $qrCodeData = $request->input('qrCodeData');
                $qrCodePath = $this->saveQRCode($qrCodeData);
                $visitante->update(['Fotoqr' => $qrCodePath]);
            }

            $this->sendQRCodeByEmail($visitante);

            DB::commit();
            return redirect()->route('visitantes.index')->with('flash_message', 'Visitante dado de alta exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating visitante: ' . $e->getMessage());
            return redirect()->route('visitantes.create')->withErrors(['error' => 'Error creating visitante.']);
        }
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

        try {
            DB::beginTransaction();

            $visitante->update($validatedData);

            if ($request->filled('qrCodeData')) {
                $qrCodeData = $request->input('qrCodeData');
                $qrCodePath = $this->saveQRCode($qrCodeData);
                $visitante->update(['Fotoqr' => $qrCodePath]);
            }

            $this->sendQRCodeByEmail($visitante);

            DB::commit();
            return redirect()->route('visitantes.index')->with('flash_message', 'Registro de visitante actualizado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating visitante: ' . $e->getMessage());
            return redirect()->route('visitantes.edit', ['visitante' => $visitante])->withErrors(['error' => 'Error updating visitante.']);
        }
    }

    public function destroy($identificador): RedirectResponse
    {
        try {
            $visitante = Visitante::where('identificador', $identificador)->firstOrFail();
            $visitante->delete();
            return redirect()->route('visitantes.index')->with('flash_message', 'Registro de visitante eliminado exitosamente!');
        } catch (\Exception $e) {
            Log::error('Error deleting visitante: ' . $e->getMessage());
            return redirect()->route('visitantes.index')->withErrors(['error' => 'Error deleting visitante.']);
        }
    }

    private function saveQRCode($qrCodeData)
    {
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $qrCodeData));
        $qrCodePath = 'ImagenesQRVisitantes/' . time() . '_qrcode.jpg';
        file_put_contents(public_path($qrCodePath), $imageData);

        return $qrCodePath;
    }

    private function sendQRCodeByEmail(Visitante $visitante)
    {
        $email = $visitante->email;
        $domain = substr(strrchr($email, "@"), 1);

        try {
            if ($domain === 'gmail.com' || $domain === 'googlemail.com') {
                Mail::mailer('smtp')->to($email)->send(new VisitanteQR($visitante->Fotoqr));
            } elseif (in_array($domain, ['outlook.com', 'hotmail.com', 'live.com'])) {
                Mail::mailer('smtp_outlook')->to($email)->send(new VisitanteQR($visitante->Fotoqr));
            } else {
                Mail::to($email)->send(new VisitanteQR($visitante->Fotoqr));
            }
        } catch (\Exception $e) {
            Log::error('Error sending email to ' . $email . ': ' . $e->getMessage());
        }
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
        $visitantes = Visitante::orderBy('updated_at', 'desc')->get();
        return view('visitantes.log', compact('visitantes'));
    }

}
