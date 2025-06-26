<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class pruchase_items extends Model
{
    /** @use HasFactory<\Database\Factories\PruchaseItemsFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price_per_unit',
        'subtotal'
    ];

    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function purchase()
    {
        return $this->belongsTo(purchases::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
