<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchase';


    protected $fillable = [
        'product_id',
        'qty',
        'price',
        'discount',
        'customer_id',
        'user_id',
        'reflector_color',
        'location',
        'custome_name'
    ];

    // Relationship to Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
