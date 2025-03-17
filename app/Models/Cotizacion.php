<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';
    protected $primaryKey = 'cotizacion_id';

    protected $fillable = [
        'user_id',
        'cliente_id',
        'cotizacion_numero',
        'cotizacion_token',
        'expiration_date',
        'payment_conditions',
        'additional_notes',
        'status',
        'subtotal',
        'discount',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
public function isExpired()
{
    return !empty($this->expiration_date) && now()->greaterThan($this->expiration_date);
}


    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class, 'cotizacion_id');
    }
}