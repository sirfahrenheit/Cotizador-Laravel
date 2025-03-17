<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    protected $table = 'quote_items';
    protected $primaryKey = 'quote_item_id';

    protected $fillable = [
        'cotizacion_id',
        'line_order',
        'modelo',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }
}
