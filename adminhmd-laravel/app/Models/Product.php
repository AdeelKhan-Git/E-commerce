<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        '_description',
        'price',
        'attachments',
        'ishot',
        'isactive',
        'category_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'attachments' => 'array',
        'ishot'       => 'boolean',
        'isactive'    => 'boolean',
        'price'       => 'decimal:2',
    ];

    // ── Relationships ────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productattachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(Attachment::class)->where('is_primary', 1);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
}