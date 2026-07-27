<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    // Daftar kategori bawaan buat dropdown form/filter. Kolom 'category' tetap string bebas,
    // jadi kalau user isi kategori baru di luar daftar ini, tetap kesimpen & muncul di filter.
    public const CATEGORIES = ['Produce', 'Dry Goods', 'Meat & Poultry', 'Dairy', 'Beverages', 'Frozen', 'Other'];
    public const UNITS = ['kg', 'g', 'L', 'ml', 'pcs', 'box', 'pack'];

    protected $fillable = [
        'name',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
    ];

    public function deliveries()
    {
        return $this->hasMany(InventoryDelivery::class);
    }

    /**
     * 'out_of_stock' | 'low_stock' | 'in_stock' — dihitung dari current_stock vs minimum_stock.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->current_stock < $this->minimum_stock) {
            return 'low_stock';
        }

        return 'in_stock';
    }
}