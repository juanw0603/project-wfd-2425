<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchases extends Model
{
    /** @use HasFactory<\Database\Factories\PurchasesFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'supplier_id',
        'purchase_date',
        'total_price'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(suppliers::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(pruchase_items::class);
    }
}
