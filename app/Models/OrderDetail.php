<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'products',
        'total_price',
        'status',
        'cash',
        'credit',
        'received',
        'pending',
        'discount_status',
        'discount_stage',
        'quotation_id',
        'discount_price',
        'customer_id',
        'quotation_status',
        'delivery_date'
    ];

    protected $casts = [
        'products' => 'array',
        'total_price' => 'decimal:2',
        'status' => 'integer',
        'delivery_date' => 'date',
    ];

    /**
     * Get the architect (customer) that this order belongs to.
     */
    public function customer()
    {
        return $this->belongsTo(Architect::class, 'customer_id');
    }
}
