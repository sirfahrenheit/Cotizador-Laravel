@extends('adminlte::page')

@section('title', 'Listado de Órdenes de Trabajo')

@section('content_header')
    <h1>Órdenes de Trabajo</h1>
@stop

@section('content')
<div class="container">
    @if(Auth::user()->role == 'admin')
        <a href="{{ route('work_orders.create') }}" class="btn btn-primary mb-3">Crear Orden de Trabajo</a>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Tareas</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->orden_id }}</td>
                    <td>{{ $order->fecha }}</td>
                    <td>{{ Str::limit($order->tareas, 30) }}</td>
                    <td>{{ ucfirst($order->estado) }}</td>
                    <td>{{ $order->tecnico ? $order->tecnico->name : 'No asignado' }}</td>
                    <td>
                        <a href="{{ route('work_orders.show', $order->orden_id) }}" class="btn btn-info btn-sm">Ver</a>
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('work_orders.edit', $order->orden_id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('work_orders.destroy', $order->orden_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta orden?')">Eliminar</button>
                            </form>
                        @elseif(Auth::user()->role == 'técnico')
                            <a href="{{ route('work_orders.edit', $order->orden_id) }}" class="btn btn-warning btn-sm">Actualizar</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron órdenes de trabajo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<style>
    /* Agrega estilos responsivos si es necesario */
</style>
@stop

@section('js')
<script>
    console.log('Órdenes de trabajo cargadas.');
</script>
@stop
