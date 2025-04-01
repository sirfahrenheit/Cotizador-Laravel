<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Models\Client;
use App\Models\Cotizacion;
use App\Models\QuoteItem;
use App\Models\WorkOrder;
use App\Models\WorkOrderCheckin;
use App\Models\Actividad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower(trim($user->role));

        if ($role === 'técnico' || $role === 'tecnico') {
            // Lógica para técnicos
            $currentDate = Carbon::now('America/Guatemala')->toDateString();
            $checkin = WorkOrderCheckin::where('tecnico_id', $user->id)
                ->whereDate('checked_in_at', $currentDate)
                ->first();

            $isCheckedIn = $checkin ? true : false;
            // Filtrar solo órdenes asignadas que NO estén finalizadas
            $workOrders = $isCheckedIn
                ? WorkOrder::where('tecnico_id', $user->id)
                    ->where('estado', '<>', 'finalizado')
                    ->orderBy('fecha', 'desc')
                    ->get()
                : collect();

            return view('dashboard', [
                'role'             => $role,
                'isCheckedIn'      => $isCheckedIn,
                'workOrders'       => $workOrders,
                // Estadísticas admin no aplican para técnicos
                'autorizadasCount' => null,
                'pendientesCount'  => null,
                'rechazadasCount'  => null,
                'vistasTotales'    => null,
                'clientesAceptadas'=> [],
                'datosAceptadas'   => [],
                'clientesRechazadas'=> [],
                'datosRechazadas'  => [],
                'totalQuoted'      => null,
                'totalSold'        => null,
                'topProductsData'  => [],
            ]);
        } elseif ($role === 'admin') {
            // Lógica para administradores: estadísticas solo para el mes actual
            $now = Carbon::now('America/Guatemala');
            $month = $now->month;
            $year  = $now->year;

            $autorizadasCount = Cotizacion::where('status', 'autorizada')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $pendientesCount = Cotizacion::where('status', 'pendiente')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $rechazadasCount = Cotizacion::where('status', 'rechazada')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $vistasTotales = Cotizacion::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('view_count');

            $totalQuoted = Cotizacion::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('total');

            $totalSold = Cotizacion::where('status', 'autorizada')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('total');

            // Gráfica: Aceptadas por Cliente
            $aceptadasPorCliente = DB::table('cotizaciones')
                ->select('cliente_id', DB::raw('count(*) as total'))
                ->where('status', 'autorizada')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->groupBy('cliente_id')
                ->get();

            $clientesAceptadas = [];
            $datosAceptadas    = [];
            foreach ($aceptadasPorCliente as $fila) {
                $cliente = Client::find($fila->cliente_id);
                $nombreCliente = $cliente ? $cliente->nombre : 'Cliente ' . $fila->cliente_id;
                $clientesAceptadas[] = $nombreCliente;
                $datosAceptadas[]    = $fila->total;
            }

            // Gráfica: Rechazadas por Cliente
            $rechazadasPorCliente = DB::table('cotizaciones')
                ->select('cliente_id', DB::raw('count(*) as total'))
                ->where('status', 'rechazada')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->groupBy('cliente_id')
                ->get();

            $clientesRechazadas = [];
            $datosRechazadas    = [];
            foreach ($rechazadasPorCliente as $fila) {
                $cliente = Client::find($fila->cliente_id);
                $nombreCliente = $cliente ? $cliente->nombre : 'Cliente ' . $fila->cliente_id;
                $clientesRechazadas[] = $nombreCliente;
                $datosRechazadas[]    = $fila->total;
            }

            // Top products (opcional)
            $topProductsData = [];
            $topProducts = DB::table('quote_items')
                ->select('modelo', DB::raw('sum(quantity) as total_quantity'))
                ->groupBy('modelo')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->get();
            foreach ($topProducts as $item) {
                $topProductsData[$item->modelo] = $item->total_quantity;
            }

            // Recordatorio de actividades del día (para hoy)
            $today = Carbon::today('America/Guatemala');
            $tomorrow = Carbon::tomorrow('America/Guatemala');
            $actividadesHoy = Actividad::whereBetween('fecha', [$today, $tomorrow])->get();

            return view('dashboard', [
                'role'              => $role,
                'isCheckedIn'       => false,
                'workOrders'        => collect(),
                'autorizadasCount'  => $autorizadasCount,
                'pendientesCount'   => $pendientesCount,
                'rechazadasCount'   => $rechazadasCount,
                'vistasTotales'     => $vistasTotales,
                'clientesAceptadas' => $clientesAceptadas,
                'datosAceptadas'    => $datosAceptadas,
                'clientesRechazadas'=> $clientesRechazadas,
                'datosRechazadas'   => $datosRechazadas,
                'totalQuoted'       => $totalQuoted,
                'totalSold'         => $totalSold,
                'topProductsData'   => $topProductsData,
                'actividadesHoy'    => $actividadesHoy,
            ]);
        } else {
            return view('dashboard', [
                'role'              => $role,
                'isCheckedIn'       => false,
                'workOrders'        => collect(),
                'autorizadasCount'  => null,
                'pendientesCount'   => null,
                'rechazadasCount'   => null,
                'vistasTotales'     => null,
                'clientesAceptadas' => [],
                'datosAceptadas'    => [],
                'clientesRechazadas'=> [],
                'datosRechazadas'   => [],
                'totalQuoted'       => null,
                'totalSold'         => null,
                'topProductsData'   => [],
            ]);
        }
    }
}
