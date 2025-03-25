<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Models\Client;
use App\Models\Cotizacion;
use App\Models\QuoteItem;
use App\Models\WorkOrder;
use App\Models\WorkOrderCheckin; // Asegúrate de tener este modelo
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Dispara un evento de prueba (si aún lo usas)
        event(new TestEvent('Mensaje de prueba'));

        $user = Auth::user();
        $role = strtolower(trim($user->role));

        // Pasar el rol a la vista para que se pueda condicionar
        if ($role === 'técnico' || $role === 'tecnico') {
            // ================================
            // Lógica para técnicos
            // ================================
            // Obtener la fecha actual en la zona horaria de Guatemala
            $currentDate = Carbon::now('America/Guatemala')->toDateString();

            // Verificar si el técnico ya hizo check in hoy
            $checkin = WorkOrderCheckin::where('tecnico_id', $user->id)
                ->whereDate('checked_in_at', $currentDate)
                ->first();

            $isCheckedIn = $checkin ? true : false;

            // Si ya hizo check in, obtener sus órdenes asignadas; de lo contrario, colección vacía
            if ($isCheckedIn) {
                $workOrders = WorkOrder::where('tecnico_id', $user->id)
                    ->orderBy('fecha', 'desc')
                    ->get();
            } else {
                $workOrders = collect();
            }

            return view('dashboard', [
                'role'             => $role,
                'isCheckedIn'      => $isCheckedIn,
                'workOrders'       => $workOrders,
                // Para técnicos, las estadísticas admin se pasan como null o valores vacíos
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
            // ================================
            // Lógica para admin
            // ================================
            // Estadísticas generales
            $autorizadasCount = Cotizacion::where('status', 'autorizada')->count();
            $pendientesCount  = Cotizacion::where('status', 'pendiente')->count();
            $rechazadasCount  = Cotizacion::where('status', 'rechazada')->count();
            $vistasTotales    = Cotizacion::sum('view_count');

            // Gráfica: Aceptadas por Cliente
            $aceptadasPorCliente = DB::table('cotizaciones')
                ->select('cliente_id', DB::raw('count(*) as total'))
                ->where('status', 'autorizada')
                ->groupBy('cliente_id')
                ->get();

            $clientesAceptadas = [];
            $datosAceptadas    = [];
            foreach ($aceptadasPorCliente as $fila) {
                $cliente = Client::find($fila->cliente_id);
                $nombreCliente = $cliente ? $cliente->nombre : 'Cliente '.$fila->cliente_id;
                $clientesAceptadas[] = $nombreCliente;
                $datosAceptadas[]    = $fila->total;
            }

            // Gráfica: Rechazadas por Cliente
            $rechazadasPorCliente = DB::table('cotizaciones')
                ->select('cliente_id', DB::raw('count(*) as total'))
                ->where('status', 'rechazada')
                ->groupBy('cliente_id')
                ->get();

            $clientesRechazadas = [];
            $datosRechazadas    = [];
            foreach ($rechazadasPorCliente as $fila) {
                $cliente = Client::find($fila->cliente_id);
                $nombreCliente = $cliente ? $cliente->nombre : 'Cliente '.$fila->cliente_id;
                $clientesRechazadas[] = $nombreCliente;
                $datosRechazadas[]    = $fila->total;
            }

            // Totales en dinero
            $totalQuoted = Cotizacion::sum('total');  
            $totalSold   = Cotizacion::where('status', 'autorizada')->sum('total');

            // Top products (opcional)
            $topProductsData = QuoteItem::select('modelo', DB::raw('sum(quantity) as total_quantity'))
                ->groupBy('modelo')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->pluck('total_quantity', 'modelo')
                ->toArray();

            // Para admin, no se utiliza el check in ni las órdenes asignadas
            return view('dashboard', [
                'role'             => $role,
                'isCheckedIn'      => false,
                'workOrders'       => collect(),
                'autorizadasCount' => $autorizadasCount,
                'pendientesCount'  => $pendientesCount,
                'rechazadasCount'  => $rechazadasCount,
                'vistasTotales'    => $vistasTotales,
                'clientesAceptadas'=> $clientesAceptadas,
                'datosAceptadas'   => $datosAceptadas,
                'clientesRechazadas'=> $clientesRechazadas,
                'datosRechazadas'  => $datosRechazadas,
                'totalQuoted'      => $totalQuoted,
                'totalSold'        => $totalSold,
                'topProductsData'  => $topProductsData,
            ]);
        } else {
            // Para otros roles, se retornan valores por defecto
            return view('dashboard', [
                'role'             => $role,
                'isCheckedIn'      => false,
                'workOrders'       => collect(),
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
        }
    }
}

