<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public const WEEKDAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    protected $fillable = [
        'restaurant_name',
        'legal_name',
        'email',
        'phone',
        'logo',
        'address',
        'city',
        'state',
        'zip',
        'business_hours',
        'tax_rate',
        'currency',
        'receipt_printer',
        'kitchen_printer',
        'auto_print_kitchen',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'tax_rate' => 'decimal:2',
        'auto_print_kitchen' => 'boolean',
    ];

    /**
     * Settings cuma 1 baris untuk seluruh aplikasi (singleton).
     * Dipanggil dari mana pun butuh setting, mis. OrderController buat ambil tax_rate.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1], [
            'business_hours' => collect(self::WEEKDAYS)->mapWithKeys(fn ($day) => [
                $day => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            ])->toArray(),
        ]);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}