<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Carbon\Carbon;
use PDF; // Asegúrate de que el alias esté definido en config/app.php

class PublicQuoteController extends Controller
{
    /**
     * Muestra la cotización pública basada en un token.
     */
    public function view($token)
    {
        $quote = Cotizacion::where('cotizacion_token', $token)
            ->with(['client', 'items'])
            ->firstOrFail();

        // Formatear fechas
        $creationDateFormatted = Carbon::parse($quote->created_at)->format('d/m/Y');
        $expirationDateFormatted = $quote->expiration_date
            ? Carbon::parse($quote->expiration_date)->format('d/m/Y')
            : 'Sin vencimiento';

        // Calcular el porcentaje de descuento (si subtotal es mayor a 0)
        $discountPercentage = 0;
        if ($quote->subtotal > 0 && $quote->discount > 0) {
            $discountPercentage = round(($quote->discount / $quote->subtotal) * 100, 2);
        }

        // Ruta del logo de tu empresa
        $logoUrl = asset('images/mi-logo.jpg');

        return view('public_quotes.view', [
            'quote'                   => $quote,
            'creationDateFormatted'   => $creationDateFormatted,
            'expirationDateFormatted' => $expirationDateFormatted,
            'discountPercentage'      => $discountPercentage,
            'logoUrl'                 => $logoUrl,
        ]);
    }

    /**
     * Genera y descarga un PDF de la cotización.
     */
public function downloadPdf($token)
{
    $quote = Cotizacion::where('cotizacion_token', $token)
        ->with(['client', 'items'])
        ->firstOrFail();

    $creationDateFormatted = Carbon::parse($quote->created_at)->format('d/m/Y');
    $expirationDateFormatted = $quote->expiration_date
        ? Carbon::parse($quote->expiration_date)->format('d/m/Y')
        : 'Sin vencimiento';

    // Se asume que discount es el monto en moneda
    $subtotal = $quote->items->sum(function ($item) {
        return $item->quantity * $item->unit_price;
    });
    $descuentoTotal = $quote->discount;
    $total = $subtotal - $descuentoTotal;

    $logoUrl = asset('images/mi-logo.jpg');

    $pdf = PDF::loadView('public_quotes.pdf', [
        'quote'                   => $quote,
        'creationDateFormatted'   => $creationDateFormatted,
        'expirationDateFormatted' => $expirationDateFormatted,
        'logoUrl'                 => $logoUrl,
        'subtotal'                => $subtotal,
        'descuentoTotal'          => $descuentoTotal,
        'total'                   => $total,
    ]);

    $filename = 'Cotizacion_' . $quote->cotizacion_numero . '.pdf';
    return $pdf->download($filename);
}


    
}
