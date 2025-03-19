<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Muestra el formulario de configuración.
     */
    public function index()
    {
        // Obtenemos algunos settings actuales. En este ejemplo, se lee desde la sesión.
        $settings = [
            'app_name' => config('app.name'),
            'dark_mode' => session('dark_mode', false),
            // Agrega más settings si lo requieres...
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Actualiza la configuración.
     */
    public function update(Request $request)
    {
        // Validar la entrada, incluyendo el campo dark_mode
        $data = $request->validate([
            'app_name'  => 'required|string|max:255',
            'dark_mode' => 'required|boolean', // Nuevo campo para el modo oscuro
            // Agrega otros campos de configuración si lo requieres...
        ]);

        // Guardamos el modo oscuro en la sesión (podrías guardarlo en la BD)
        session(['dark_mode' => $data['dark_mode']]);

        // (Opcional) Si deseas actualizar el nombre de la app en el config, puedes hacerlo aquí,
        // pero recuerda que actualizar el archivo .env o config en tiempo de ejecución requiere pasos adicionales.

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
