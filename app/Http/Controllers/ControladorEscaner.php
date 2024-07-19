<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Empleado;
use App\Models\Visitante;
use Illuminate\Support\Facades\Log;

class ControladorEscaner extends Controller
{
    public function handleScan($qrCode)
    {
        try {
            // Try to find the QR code in the students table
            $estudiante = Estudiante::where('identificador', $qrCode)->first();
            if ($estudiante) {
                if (is_null($estudiante->entrada)) {
                    return redirect()->route('estudiantes.entrada', $estudiante->id);
                } elseif (is_null($estudiante->salida)) {
                    return redirect()->route('estudiantes.salida', $estudiante->id);
                } else {
                    return redirect()->route('estudiantes.log');
                }
            }

            // Try to find the QR code in the employees table
            $empleado = Empleado::where('identificador', $qrCode)->first();
            if ($empleado) {
                if (is_null($empleado->entrada)) {
                    return redirect()->route('empleados.entrada', $empleado->id);
                } elseif (is_null($empleado->salida)) {
                    return redirect()->route('empleados.salida', $empleado->id);
                } else {
                    return redirect()->route('empleados.log');
                }
            }

            // Try to find the QR code in the visitors table
            $visitante = Visitante::where('identificador', $qrCode)->first();
            if ($visitante) {
                if (is_null($visitante->entrada)) {
                    return redirect()->route('visitantes.entrada', $visitante->id);
                } elseif (is_null($visitante->salida)) {
                    return redirect()->route('visitantes.salida', $visitante->id);
                } else {
                    return redirect()->route('visitantes.log');
                }
            }

            // If QR code is not found in any table, redirect back with an error message
            return redirect()->back()->with('error', 'Código QR no encontrado.');
        } catch (\Exception $e) {
            Log::error('Error handling QR code scan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el código QR.');
        }
    }
}
