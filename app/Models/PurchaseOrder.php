<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_PARTIALLY_RECEIVED = 'partially_received';
    public const STATUS_RECEIVED = 'received';

    protected $fillable = [
        'reference',
        'supplier_name',
        'supplier_email',
        'expected_at',
        'status',
        'notes',
        'admin_id',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'expected_at' => 'date',
            'received_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
