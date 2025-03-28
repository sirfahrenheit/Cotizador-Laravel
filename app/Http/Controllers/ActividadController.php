<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\ActividadNotificacion;
use App\Notifications\QuoteTrackingNotification;
use Carbon\Carbon;
use App\Models\User;
use Log;

class ActividadController extends Controller
{
    /**
     * Muestra una lista de todas las actividades.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtenemos todas las actividades
        $actividades = Actividad::orderBy('fecha', 'desc')->get();

        // Transformamos cada actividad en un evento para FullCalendar
        $events = $actividades->map(function ($actividad) {
            return [
                'title' => $actividad->tipo,     // Texto que se mostrará en el calendario
                'start' => $actividad->fecha,    // Fecha/hora de inicio
                'description' => $actividad->descripcion,  // Podemos usarlo para tooltips o modales
                'id' => $actividad->id,          // ID para identificar la actividad
            ];
        });

        // Retornamos la vista con la colección de eventos en formato JSON
        return view('actividades.index', [
            'events' => $events,
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva actividad.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Obtener todos los clientes (ajusta el orden o filtros si es necesario)
        $clientes = \App\Models\Client::orderBy('nombre')->get();

        // Pasar la fecha opcional de la query string y la lista de clientes a la vista
        return view('actividades.create', [
            'clientes' => $clientes,
            'fechaPredefinida' => $request->get('fecha')
        ]);
    }

    /**
     * Almacena una nueva actividad en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   public function store(Request $request)
{
    // Validación de datos recibidos
    $data = $request->validate([
        'cliente_id'  => 'required|exists:clientes,cliente_id',
        'tipo'        => 'required|string',
        'fecha'       => 'required|date',
        'descripcion' => 'nullable|string',
    ]);

    // Crear la actividad
    $actividad = Actividad::create($data);

    // Usar la zona horaria configurada en la app (por ejemplo, America/Guatemala)
    $tz = config('app.timezone'); // Asegúrate de que APP_TIMEZONE=America/Guatemala en .env
    $sendAt = Carbon::parse($actividad->fecha, $tz);  // Interpretar la fecha en la zona local
    $now    = Carbon::now($tz);

    // Calcular el delay: la diferencia en segundos desde ahora hasta la fecha programada
    $delay  = $sendAt->greaterThan($now) ? $now->diffInSeconds($sendAt) : 0;

    \Log::info('Despachando QuoteTrackingNotification para actividad ' . $actividad->id . ' con delay de ' . $delay . ' segundos.');

    // Obtener los administradores (suponiendo que el modelo User tiene el campo 'role' y 'admin' es el valor)
    $admins = \App\Models\User::where('role', 'admin')->get();

    // Enviar notificación FCM con delay
    \Illuminate\Support\Facades\Notification::send(
        $admins,
        (new \App\Notifications\QuoteTrackingNotification($actividad))->delay($delay)
    );

    // Enviar notificación por correo (opcional)
    \Illuminate\Support\Facades\Mail::to(['hugobeteta@distribuidorajadi.com', 'diegobeteta@distribuidorajadi.com'])
        ->send(new \App\Mail\ActividadNotificacion($actividad));

    return redirect()->route('actividades.index')
                     ->with('success', 'Actividad creada y notificaciones encoladas.');
}


    /**
     * Muestra los detalles de una actividad específica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $actividad = Actividad::findOrFail($id);
        return view('actividades.show', compact('actividad'));
    }

    /**
     * Muestra el formulario para editar una actividad existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $actividad = Actividad::findOrFail($id);
        return view('actividades.edit', compact('actividad'));
    }

    /**
     * Actualiza una actividad en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);

        // Validación de datos
        $data = $request->validate([
            'cliente_id'  => 'required|exists:clientes,cliente_id',
            'tipo'        => 'required|string',
            'fecha'       => 'required|date',
            'descripcion' => 'nullable|string',
        ]);

        $actividad->update($data);

        return redirect()->route('actividades.index')
                         ->with('success', 'Actividad actualizada.');
    }

    /**
     * Elimina una actividad de la base de datos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividades.index')
                         ->with('success', 'Actividad eliminada.');
    }
}
