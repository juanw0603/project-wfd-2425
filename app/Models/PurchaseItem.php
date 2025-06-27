<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_items'; // ini eksplisit, opsional tapi aman

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price_per_unit',
        'subtotal'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function purchase()
    {
        return $this->belongsTo(purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}