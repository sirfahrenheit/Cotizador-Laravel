<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Muestra la lista de clientes
    public function index(Request $request)
{
    $query = Client::query();

    if ($request->filled('search')) {
        $search = trim($request->input('search'));
        $query->where('nombre', 'LIKE', "%{$search}%");
    }

    $clients = $query->orderBy('nombre')->get();

    return view('clients.index', compact('clients'));
}
    // Muestra el formulario para crear un cliente
    public function create()
    {
        return view('clients.create');
    }

    // Almacena un cliente nuevo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:50',
            'correo'     => 'nullable|email|max:100',
        ]);

        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'Cliente creado correctamente.');
    }

    // Muestra los detalles de un cliente
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    // Muestra el formulario para editar un cliente
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // Actualiza un cliente existente
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:50',
            'correo'     => 'nullable|email|max:100',
        ]);

        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Elimina un cliente
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
