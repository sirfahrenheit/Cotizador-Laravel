<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\WorkOrder;
use App\Models\QuoteItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            // Para administradores:
            // Totales generales
            $totalQuoted = Cotizacion::sum('total');
            $totalSold = Cotizacion::where('status', 'autorizada')->sum('total');

            // Todas las cotizaciones y órdenes de trabajo
            $cotizaciones = Cotizacion::orderBy('created_at', 'desc')->get();
            $workOrders = WorkOrder::orderBy('fecha', 'desc')->get();

            // Datos para la gráfica de estatus de cotizaciones:
            // Ejemplo: ['pendiente' => 5, 'autorizada' => 3, 'rechazada' => 2]
            $quoteStatusData = Cotizacion::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Datos para la gráfica de top productos vendidos (usando el campo "modelo")
            $topProductsData = QuoteItem::select('modelo', DB::raw('sum(quantity) as total_quantity'))
                ->groupBy('modelo')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->pluck('total_quantity', 'modelo')
                ->toArray();

            return view('dashboard', compact(
                'cotizaciones',
                'workOrders',
                'totalQuoted',
                'totalSold',
                'quoteStatusData',
                'topProductsData'
            ));
        } else {
            $cotizaciones = collect();
            $workOrders = collect();
            return view('dashboard', compact('cotizaciones', 'workOrders'));
        }
    }
}

