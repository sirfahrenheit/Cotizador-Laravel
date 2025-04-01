<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrderCheckin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkOrderCheckinController extends Controller
{
    /**
     * Muestra un mapa con las ubicaciones de los check-ins de un día dado.
     * Solo los administradores pueden acceder.
     * Si no se pasa una fecha, se usa el día actual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function mapByDate(Request $request)
    {
        // Restringe el acceso: solo los administradores pueden ver el mapa.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso no autorizado');
        }

        // Si no se proporciona una fecha, usamos el día actual
        $date = $request->input('date', Carbon::now()->toDateString());

        // Obtenemos los check-ins del día especificado con la relación "technician"
        $checkins = WorkOrderCheckin::with('technician')
            ->whereDate('checked_in_at', $date)
            ->get()
            ->map(function ($checkin) {
                return [
                    'latitude'        => $checkin->latitude,
                    'longitude'       => $checkin->longitude,
                    'checked_in_at'   => $checkin->checked_in_at,
                    'technician_name' => $checkin->technician->name ?? 'Desconocido',
                ];
            });

        return view('map-checkins', compact('checkins', 'date'));
    }
}
