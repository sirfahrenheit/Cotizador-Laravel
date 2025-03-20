<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Obtener los técnicos disponibles
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

        WorkOrder::create($validated);

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
     * Muestra el listado de órdenes asignadas al técnico.
     */
    public function indexForTech()
    {
        $orders = WorkOrder::where('tecnico_id', Auth::id())
            ->orderBy('fecha', 'desc')
            ->get();

        return view('tech.work_orders.index', compact('orders'));
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
     * Permite que el técnico actualice la orden asignada a él.
     */
    public function updateForTech(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->tecnico_id != Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta orden.');
        }

        // Si la orden ya está finalizada, no se permiten cambios
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

        return redirect()->route('tech.work_orders.index')
            ->with('success', 'Orden de trabajo actualizada correctamente.');
    }
}
