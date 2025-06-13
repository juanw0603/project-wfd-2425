<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class suppliers extends Model
{
    /** @use HasFactory<\Database\Factories\SuppliersFactory> */
    use HasFactory, Notifiable;

        /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'minimal_stock',
    ];


    public function purchases()
    {
        return $this->hasMany(purchases::class);
    }
}
