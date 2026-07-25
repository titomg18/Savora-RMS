<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public const STATUSES = ['active', 'inactive'];
    public const STATIONS = ['grill', 'prep', 'other'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'status',
        'station',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * URL publik ke gambar produk (null kalau belum ada gambar).
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    protected static function booted(): void
    {
        // Jaga supaya categories.items_count selalu sinkron dengan jumlah menu item-nya,
        // tanpa perlu query count() setiap kali render halaman Categories.
        static::created(function (MenuItem $menuItem) {
            $menuItem->category()->increment('items_count');
        });

        static::deleted(function (MenuItem $menuItem) {
            $menuItem->category()->decrement('items_count');
        });

        static::updated(function (MenuItem $menuItem) {
            if ($menuItem->wasChanged('category_id')) {
                Category::whereKey($menuItem->getOriginal('category_id'))->decrement('items_count');
                Category::whereKey($menuItem->category_id)->increment('items_count');
            }
        });
    }
}