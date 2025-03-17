@extends('layouts.tech')

@section('title', 'Mis Órdenes de Trabajo')

@section('content')
<div class="container">
    <h2>Órdenes de Trabajo Asignadas</h2>
    
    @if($orders->isEmpty())
        <p>No tienes órdenes de trabajo asignadas.</p>
    @else
        <div class="list-group">
            @foreach($orders as $order)
                <a href="{{ route('tech.work_orders.show', $order->orden_id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Orden #{{ $order->orden_id }}</h5>
                        <small>{{ \Carbon\Carbon::parse($order->fecha)->format('d/m/Y') }}</small>
                    </div>
                    <p class="mb-1">{{ Str::limit($order->tareas, 50) }}</p>
                    <small>Estado: {{ ucfirst($order->estado) }}</small>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
