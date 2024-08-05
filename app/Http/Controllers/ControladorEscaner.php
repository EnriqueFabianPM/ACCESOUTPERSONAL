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
            // Search in all tables
            $models = [
                'estudiantes' => Estudiante::class,
                'empleados'   => Empleado::class,
                'visitantes'  => Visitante::class,
            ];

            foreach ($models as $table => $model) {
                $record = $model::where('identificador', $qrCode)->first();
                if ($record) {
                    return $this->handleRecord($record, $table);
                }
            }

            // If QR code is not found in any table
            return redirect()->back()->with('error', 'Código QR no encontrado.');
        } catch (\Exception $e) {
            Log::error('Error handling QR code scan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el código QR.');
        }
    }

    private function handleRecord($record, $table)
    {
        if (is_null($record->entrada)) {
            return redirect()->route("{$table}.entrada", $record->id);
        } elseif (is_null($record->salida)) {
            return redirect()->route("{$table}.salida", $record->id);
        } else {
            return redirect()->route("{$table}.log");
        }
    }
}
