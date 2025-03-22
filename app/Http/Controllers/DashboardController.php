<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Models\Client;
use App\Models\Cotizacion;
use App\Models\QuoteItem;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Dispara un evento de prueba (si aún lo usas)
        event(new TestEvent('Mensaje de prueba'));

        $user = Auth::user();
        $role = strtolower(trim($user->role));

        // === Si es TÉCNICO ===
        if ($role === 'técnico' || $role === 'tecnico') {
            // Para técnicos: cargar solo las órdenes asignadas a su ID
            $workOrders = WorkOrder::where('tecnico_id', $user->id)
                ->orderBy('fecha', 'desc')
                ->get();

            return view('dashboard', compact('workOrders'));

        // === Si es ADMIN ===
        } elseif ($role === 'admin') {
            // 1) Cajas: Contar estados
            $autorizadasCount = Cotizacion::where('status', 'autorizada')->count();
            $pendientesCount  = Cotizacion::where('status', 'pendiente')->count();
            $rechazadasCount  = Cotizacion::where('status', 'rechazada')->count();

            // Suponiendo que el campo de vistas se llama 'view_count'
            $vistasTotales    = Cotizacion::sum('view_count'); 

            // 2) Gráfica "Aceptadas por Cliente"
            //    (status 'autorizada' => "aceptada")
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

            // 3) Gráfica "Rechazadas por Cliente"
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

            // 4) (Opcional) Otras consultas, e.g. totales, topProducts, etc.
            // Ejemplo de totales en dinero
            $totalQuoted = Cotizacion::sum('total'); // Monto total cotizado
            $totalSold   = Cotizacion::where('status', 'autorizada')->sum('total'); // Monto total autorizado

            // (Opcional) topProducts
            $topProductsData = QuoteItem::select('modelo', DB::raw('sum(quantity) as total_quantity'))
                ->groupBy('modelo')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->pluck('total_quantity', 'modelo')
                ->toArray();

            // Retornamos la vista con todos los datos
            return view('dashboard', [
                'autorizadasCount'     => $autorizadasCount,
                'pendientesCount'      => $pendientesCount,
                'rechazadasCount'      => $rechazadasCount,
                'vistasTotales'        => $vistasTotales,

                'clientesAceptadas'    => $clientesAceptadas,
                'datosAceptadas'       => $datosAceptadas,
                'clientesRechazadas'   => $clientesRechazadas,
                'datosRechazadas'      => $datosRechazadas,

                'totalQuoted'          => $totalQuoted,
                'totalSold'            => $totalSold,
                'topProductsData'      => $topProductsData,
            ]);

        // === Si no es admin ni técnico ===
        } else {
            // Vista vacía o genérica
            $cotizaciones = collect();
            $workOrders   = collect();
            return view('dashboard', compact('cotizaciones', 'workOrders'));
        }
    }
}
