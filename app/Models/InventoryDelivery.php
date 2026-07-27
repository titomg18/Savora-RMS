<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDelivery extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'received'];

    protected $fillable = [
        'inventory_item_id',
        'quantity',
        'status',
        'expected_date',
    ];

    protected $casts = [
        'expected_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}