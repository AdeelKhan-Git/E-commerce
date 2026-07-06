<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    // protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relationships ──────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}