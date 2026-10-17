<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_variant_id',
        'admin_id',
        'type',
        'quantity_change',
        'reference',
        'notes',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity_change' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }



    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
