<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Empleado;
use App\Models\Visitante;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ControladorGuardia extends Controller
{
    // Show the main page for the security guard
    public function index(): View
    {
        return view('InicioGuardia');
    }

    // Show the QR scanner page
    public function scanner(): View
    {
        return view('scanner');
    }

    // Register entry for students, employees, and visitors by ID
    public function registerEntrada(Request $request, $type): RedirectResponse
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:' . $this->getTableByType($type) . ',id',
        ]);

        $id = $request->input('id');
        $model = $this->getModelByType($type);

        $record = $model::findOrFail($id);
        $record->entrada = now();
        $record->save();

        return redirect()->route('inicio.guardias')->with('success', 'Entrada registrada exitosamente.');
    }

    // Register exit for students, employees, and visitors by ID
    public function registerSalida(Request $request, $type): RedirectResponse
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:' . $this->getTableByType($type) . ',id',
        ]);

        $id = $request->input('id');
        $model = $this->getModelByType($type);

        $record = $model::findOrFail($id);
        $record->salida = now();
        $record->save();

        return redirect()->route('inicio.guardias')->with('success', 'Salida registrada exitosamente.');
    }

    // Helper method to get the model based on type
    private function getModelByType($type)
    {
        switch ($type) {
            case 'estudiante':
                return Estudiante::class;
            case 'empleado':
                return Empleado::class;
            case 'visitante':
                return Visitante::class;
            default:
                abort(404, 'Tipo no válido');
        }
    }

    // Helper method to get the table name based on type
    private function getTableByType($type)
    {
        switch ($type) {
            case 'estudiante':
                return 'estudiantes';
            case 'empleado':
                return 'empleados';
            case 'visitante':
                return 'visitantes';
            default:
                abort(404, 'Tipo no válido');
        }
    }
}
