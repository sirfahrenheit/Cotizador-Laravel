<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\User;
use App\Models\WorkOrderCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// Importamos eventos para broadcasting
use App\Events\TechnicianCheckedIn;
use App\Events\WorkOrderUpdated;
use App\Events\WorkOrderCreated;

// Importamos notificaciones para FCM
use App\Notifications\TechnicianCheckedInNotification;
use App\Notifications\WorkOrderCreatedNotification;
use App\Notifications\WorkOrderUpdatedNotification;

class WorkOrderController extends Controller
{
    // ============================
    // Métodos para Administradores
    // ============================

    /**
     * Muestra el listado de órdenes de trabajo para admin.
     */
    public function index()
    {
        $orders = WorkOrder::with('tecnico')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('work_orders.index', compact('orders'));
    }

    /**
     * Muestra el formulario para crear una nueva orden de trabajo (admin).
     */
    public function create()
    {
        $tecnicos = User::where('role', 'tecnico')->get();
        return view('work_orders.create', compact('tecnicos'));
    }

    /**
     * Almacena la nueva orden de trabajo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha'      => 'required|date',
            'tareas'     => 'required|string',
            'tecnico_id' => 'required|exists:users,id',
        ]);

        $order = WorkOrder::create($validated);

        // Despachamos el evento de creación (broadcasting)
        event(new WorkOrderCreated($order->id));

        // Notificar al técnico asignado
        $technician = User::find($validated['tecnico_id']);
        if ($technician) {
            $techTokens = $technician->routeNotificationForFcm();
            if (empty($techTokens)) {
                Log::warning('Técnico sin token FCM', ['tecnico_id' => $validated['tecnico_id']]);
            } else {
                Log::info('Notificando técnico', ['tecnico_id' => $validated['tecnico_id'], 'tokens' => $techTokens]);
                $technician->notify(new WorkOrderCreatedNotification($order));
            }
        } else {
            Log::warning('No se encontró el técnico asignado', ['tecnico_id' => $validated['tecnico_id']]);
        }

        // Notificar a todos los administradores
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $adminTokens = $admin->routeNotificationForFcm();
            if (empty($adminTokens)) {
                Log::warning('Administrador sin token FCM', ['admin_id' => $admin->id]);
            } else {
                Log::info('Notificando administrador', ['admin_id' => $admin->id, 'tokens' => $adminTokens]);
                $admin->notify(new WorkOrderCreatedNotification($order));
            }
        }

        return redirect()->route('work_orders.index')
            ->with('success', 'Orden de trabajo creada correctamente.');
    }

    /**
     * Muestra los detalles de una orden de trabajo (admin).
     */
    public function show(WorkOrder $workOrder)
    {
        return view('work_orders.show', compact('workOrder'));
    }

    /**
     * Muestra el formulario para editar una orden de trabajo (admin).
     */
    public function edit(WorkOrder $workOrder)
    {
        $tecnicos = User::where('role', 'tecnico')->get();
        return view('work_orders.edit', compact('workOrder', 'tecnicos'));
    }

    /**
     * Actualiza la orden de trabajo (admin).
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'tareas'      => 'required|string',
            'avances'     => 'nullable|string',
            'solicitudes' => 'nullable|string',
            'estado'      => 'required|in:pendiente,finalizado',
            'tecnico_id'  => 'required|exists:users,id',
        ]);

        $workOrder->update($validated);

        // Despachamos el evento de actualización
        event(new WorkOrderUpdated($workOrder->id));

        return redirect()->route('work_orders.index')
            ->with('success', 'Orden de trabajo actualizada correctamente.');
    }

    /**
     * Elimina una orden de trabajo.
     */
    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('work_orders.index')
            ->with('success', 'Orden de trabajo eliminada correctamente.');
    }

    // ============================
    // Métodos para Técnicos
    // ============================

    /**
     * Muestra el listado de órdenes asignadas al técnico y determina si ya hizo el check-in.
     */
    public function indexForTech()
    {
        $currentDate = Carbon::now('America/Guatemala')->toDateString();

        $checkin = WorkOrderCheckin::where('tecnico_id', Auth::id())
                    ->whereDate('checked_in_at', $currentDate)
                    ->first();

        $isCheckedIn = $checkin ? true : false;

        if ($isCheckedIn) {
            $orders = WorkOrder::where('tecnico_id', Auth::id())
                        ->orderBy('fecha', 'desc')
                        ->get();
        } else {
            $orders = collect(); // Colección vacía
        }

        return view('tech.work_orders.index', [
            'orders' => $orders,
            'isCheckedIn' => $isCheckedIn,
        ]);
    }

    /**
     * Registra el check-in del técnico.
     */
    public function checkinTech(Request $request)
    {
        Log::info("checkinTech llamado", ['user_id' => Auth::id()]);

        $validatedData = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'timestamp' => 'required',
        ]);

        Log::info("Datos validados", $validatedData);

        $checkin = new WorkOrderCheckin();
        $checkin->tecnico_id = Auth::id();
        $checkin->latitude = $validatedData['latitude'];
        $checkin->longitude = $validatedData['longitude'];
        $checkin->checked_in_at = Carbon::now('America/Guatemala');
        $checkin->save();

        Log::info("Check-in guardado", ['checkin_id' => $checkin->id]);

        // Despachamos el evento de check-in para broadcasting
        event(new TechnicianCheckedIn(
            Auth::id(),
            $validatedData['timestamp'],
            $validatedData['latitude'],
            $validatedData['longitude']
        ));

        // Notificar a administradores sobre el check-in del técnico
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $adminTokens = $admin->routeNotificationForFcm();
            if (empty($adminTokens)) {
                Log::warning('Administrador sin token FCM', ['admin_id' => $admin->id]);
            } else {
                Log::info('Notificando administrador por check-in', ['admin_id' => $admin->id, 'tokens' => $adminTokens]);
                $admin->notify(new TechnicianCheckedInNotification(
                    Auth::id(),
                    $validatedData['timestamp'],
                    $validatedData['latitude'],
                    $validatedData['longitude']
                ));
            }
        }

        return response('Check-in registrado correctamente.', 200)
               ->header('Content-Type', 'text/plain');
    }

    /**
     * Muestra los detalles de la orden asignada al técnico.
     */
    public function showForTech(WorkOrder $workOrder)
    {
        if ($workOrder->tecnico_id != Auth::id()) {
            abort(403, 'No tienes permiso para ver esta orden.');
        }
        return view('tech.work_orders.show', compact('workOrder'));
    }

    /**
     * Muestra el formulario para que el técnico actualice sus avances y solicitudes.
     */
    public function editForTech(WorkOrder $workOrder)
    {
        if ($workOrder->tecnico_id != Auth::id()) {
            abort(403, 'No tienes permiso para editar esta orden.');
        }
        return view('tech.work_orders.edit', compact('workOrder'));
    }

    /**
     * Permite que el técnico actualice la orden asignada a él y notifica a administradores.
     */
    public function updateForTech(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->tecnico_id != Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta orden.');
        }

        if ($workOrder->estado === 'finalizado') {
            return redirect()->route('tech.work_orders.index')
                ->with('error', 'Esta orden de trabajo está finalizada y no se pueden realizar cambios.');
        }

        $validated = $request->validate([
            'avances'     => 'required|string',
            'solicitudes' => 'required|string',
            'estado'      => 'required|in:pendiente,finalizado',
        ]);

        $workOrder->update($validated);

        // Despachamos el evento de actualización de work order
        event(new WorkOrderUpdated($workOrder->id));

        // Notificar a los administradores sobre la actualización realizada por el técnico
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $adminTokens = $admin->routeNotificationForFcm();
            if (empty($adminTokens)) {
                Log::warning('Administrador sin token FCM', ['admin_id' => $admin->id]);
            } else {
                Log::info('Notificando administrador por actualización de orden de trabajo', ['admin_id' => $admin->id, 'tokens' => $adminTokens]);
                $admin->notify(new WorkOrderUpdatedNotification($workOrder));
            }
        }

        // Redirigimos al detalle de la orden; pasamos el modelo completo para que se resuelva el binding
        return redirect()->route('tech.work_orders.show', $workOrder)
            ->with('success', 'Orden de trabajo actualizada correctamente.');
    }
}
