<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        '_description',
        'category_image',
        'created_by',
        'updated_by',
    ];

    // ── Relationships ───────────────
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}