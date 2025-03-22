<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\QuoteItem;
use App\Models\Client;
use App\Models\Product;
use App\Mail\CotizacionNotificacion;
use App\Mail\CotizacionAutorizadaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CotizacionController extends Controller
{
    /**
     * Muestra el listado de cotizaciones y permite buscar por número.
     */
    public function index(Request $request)
    {
        // Inicia la consulta base con la relación 'client' y ordena por fecha descendente.
        $query = Cotizacion::with('client')->orderBy('created_at', 'desc');

        // Si se envía un término de búsqueda, filtra por 'cotizacion_numero'
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('cotizacion_numero', 'LIKE', '%' . $search . '%');
        }

        // Obtiene las cotizaciones (filtradas o todas)
        $cotizaciones = $query->get();

        return view('cotizaciones.index', compact('cotizaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva cotización.
     */
    public function create()
    {
        $clientes = Client::all();
        $products = Product::all();
        return view('cotizaciones.create', compact('clientes', 'products'));
    }

    /**
     * Guarda la nueva cotización y envía el correo de notificación.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id'          => 'required|exists:clientes,cliente_id',
            'expiration_date'     => 'required|date',
            'payment_conditions'  => 'nullable|string',
            'additional_notes'    => 'nullable|string',
            'products_data'       => 'required|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100'
        ]);

        $products = json_decode($validatedData['products_data'], true);
        if (!is_array($products) || count($products) < 1) {
            return redirect()->back()
                ->withErrors(['products_data' => 'Debe agregar al menos un producto válido.'])
                ->withInput();
        }

        $user = Auth::user();
        $token = Str::random(32);
        $quoteNumber = date("YmdHis");

        // Crear la cotización
        $cotizacion = Cotizacion::create([
            'user_id'            => $user->id,
            'cliente_id'         => $validatedData['cliente_id'],
            'cotizacion_numero'  => $quoteNumber,
            'cotizacion_token'   => $token,
            'expiration_date'    => $validatedData['expiration_date'],
            'payment_conditions' => $validatedData['payment_conditions'] ?? null,
            'additional_notes'   => $validatedData['additional_notes'] ?? null,
            'status'             => 'pendiente',
        ]);

        // Calcular subtotal y crear QuoteItems
        $subtotal = 0;
        foreach ($products as $index => $itemData) {
            $quantity  = floatval($itemData['quantity'] ?? 0);
            $unitPrice = floatval($itemData['unit_price'] ?? 0);
            $lineTotal = $quantity * $unitPrice;
            $subtotal += $lineTotal;

            QuoteItem::create([
                'cotizacion_id' => $cotizacion->cotizacion_id,
                'line_order'    => $index + 1,
                'modelo'        => $itemData['model'] ?? '',  // se guarda el "model" que llega del front
                'description'   => $itemData['description'] ?? '',
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'total_price'   => $lineTotal,
            ]);
        }

        // Calcular descuento y total
        $discountPercentage = floatval($validatedData['discount_percentage'] ?? 0);
        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = $subtotal - $discountAmount;

        // Guardar los montos finales
        $cotizacion->update([
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'total'    => $total,
        ]);

        // Enviar correo si el cliente tiene email
        $publicLink = route('quotes.public_view', ['token' => $token]);
        $cliente = $cotizacion->client;
        if ($cliente && !empty($cliente->correo)) {
            Mail::to($cliente->correo)->send(new CotizacionNotificacion($cotizacion, $publicLink));
        }

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización creada con éxito. Número: ' . $quoteNumber);
    }

    /**
     * Muestra una cotización específica.
     */
    public function show($id)
    {
        $cotizacion = Cotizacion::with(['client', 'items', 'user'])->findOrFail($id);

        if (!$cotizacion->client) {
            return back()->with('error', 'Esta cotización no tiene un cliente asociado.');
        }
        if (!$cotizacion->user) {
            return back()->with('error', 'No se encontró el usuario que creó esta cotización.');
        }

        $subtotal = $cotizacion->subtotal;
        $descuentoTotal = $cotizacion->discount;
        $total = $cotizacion->total;

        return view('cotizaciones.show', compact('cotizacion', 'subtotal', 'descuentoTotal', 'total'));
    }

    /**
     * Muestra el formulario para editar una cotización.
     */
    public function edit(Cotizacion $cotizacion)
    {
        $clientes = Client::all();
        $products = Product::all();

        // Convertir los QuoteItems a un arreglo con las claves que tu JS espera
        // (product_id, model, description, quantity, unit_price).
        // Si no almacenas 'product_id' en la DB, hacemos un mejor esfuerzo
        // buscando un producto con el mismo "modelo" para asignar su ID.
        $allProducts = Product::all();

        $itemsJson = $cotizacion->items->map(function ($item) use ($allProducts) {
            // Buscar en la lista de productos aquel que tenga el mismo "modelo"
            $foundProduct = $allProducts->firstWhere('modelo', $item->modelo);
            $productId = $foundProduct ? $foundProduct->producto_id : 0;

            return [
                'product_id'  => $productId,              // si no hay match, será 0
                'model'       => $item->modelo,           // la etiqueta que mostrará el front
                'description' => $item->description,
                'quantity'    => $item->quantity,
                'unit_price'  => $item->unit_price,
            ];
        })->toJson();

        return view('cotizaciones.edit', [
            'cotizacion' => $cotizacion,
            'clientes'   => $clientes,
            'products'   => $products,
            'itemsJson'  => $itemsJson
        ]);
    }

    /**
     * Actualiza una cotización.
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        $validatedData = $request->validate([
            'cliente_id'           => 'required|exists:clientes,cliente_id',
            'expiration_date'      => 'required|date',
            'payment_conditions'   => 'nullable|string',
            'additional_notes'     => 'nullable|string',
            'products_data'        => 'required|string',
            'discount_percentage'  => 'nullable|numeric|min:0|max:100'
        ]);

        // Actualizar datos principales de la cotización
        $cotizacion->update([
            'cliente_id'         => $validatedData['cliente_id'],
            'expiration_date'    => $validatedData['expiration_date'],
            'payment_conditions' => $validatedData['payment_conditions'] ?? null,
            'additional_notes'   => $validatedData['additional_notes'] ?? null,
        ]);

        // Decodificar los productos
        $products = json_decode($validatedData['products_data'], true);
        if (!is_array($products) || count($products) < 1) {
            return redirect()->back()
                ->withErrors(['products_data' => 'Debe haber al menos un producto válido.'])
                ->withInput();
        }

        // Eliminar los items previos
        $cotizacion->items()->delete();

        // Calcular nuevo subtotal y crear QuoteItems
        $subtotal = 0;
        foreach ($products as $index => $itemData) {
            $quantity  = floatval($itemData['quantity'] ?? 0);
            $unitPrice = floatval($itemData['unit_price'] ?? 0);
            $lineTotal = $quantity * $unitPrice;
            $subtotal += $lineTotal;

            QuoteItem::create([
                'cotizacion_id' => $cotizacion->cotizacion_id,
                'line_order'    => $index + 1,
                'modelo'        => $itemData['model'] ?? '',
                'description'   => $itemData['description'] ?? '',
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'total_price'   => $lineTotal,
            ]);
        }

        // Calcular descuento y total
        $discountPercentage = floatval($validatedData['discount_percentage'] ?? 0);
        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = $subtotal - $discountAmount;

        $cotizacion->update([
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'total'    => $total,
        ]);

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización actualizada correctamente.');
    }

    /**
     * Autoriza la cotización y envía el correo de aviso.
     */
    public function authorizeQuote($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        if ($cotizacion->status !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta cotización no puede ser autorizada.');
        }

        $cotizacion->update([
            'status' => 'autorizada'
        ]);

        $cliente = $cotizacion->client;
        if ($cliente && !empty($cliente->correo)) {
            Mail::to($cliente->correo)->send(new CotizacionAutorizadaMail($cotizacion));
        }

        return redirect()->route('cotizaciones.show', $cotizacion->cotizacion_id)
            ->with('success', 'Cotización autorizada exitosamente.');
    }

    /**
     * Rechaza la cotización.
     */
    public function rejectQuote($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        if ($cotizacion->status !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta cotización no puede ser rechazada.');
        }

        $cotizacion->update([
            'status' => 'rechazada'
        ]);
    }

    /**
     * Elimina una cotización.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        $cotizacion->delete();
        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada exitosamente.');
    }
}
