<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'file_name',
        'file_type',
        'file_size',
        'file_url',
        'is_primary',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
    ];

    // ── Relationships ───────────
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}