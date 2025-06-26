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
        'name',
        'contact',
        'address'
    ];
    public $timestamps = false;


    public function purchases()
    {
        return $this->hasMany(purchases::class);
    }
}
