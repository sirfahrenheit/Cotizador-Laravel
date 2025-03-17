<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower(trim($user->role));

        if ($role === 'técnico' || $role === 'tecnico') {
            // Para técnicos: cargar solo las órdenes asignadas a su ID
            $workOrders = WorkOrder::where('tecnico_id', $user->id)
                ->orderBy('fecha', 'desc')
                ->get();
            return view('dashboard', compact('workOrders'));
        } elseif ($role === 'admin') {
            // Para admin: calcular los totales y cargar todas las cotizaciones y work orders si se requiere
            $totalQuoted = Cotizacion::sum('total');
            $totalSold = Cotizacion::where('status', 'autorizada')->sum('total');
            $cotizaciones = Cotizacion::orderBy('created_at', 'desc')->get();
            $workOrders = WorkOrder::orderBy('fecha', 'desc')->get();
            return view('dashboard', compact('cotizaciones', 'workOrders', 'totalQuoted', 'totalSold'));
        } else {
            $cotizaciones = collect();
            $workOrders = collect();
            return view('dashboard', compact('cotizaciones', 'workOrders'));
        }
    }
}
