<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiningTable extends Model
{
    use HasFactory;

    public const AREAS = ['main', 'patio', 'bar'];
    public const STATUSES = ['available', 'occupied', 'reserved'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'number',
        'seats',
        'area',
        'status',
        'label',
        'subtitle',
        'sort_order',
    ];

    /**
     * Nomor meja diformat 2 digit, mis. 1 -> "01".
     */
    public function getFormattedNumberAttribute(): string
    {
        return str_pad((string) $this->number, 2, '0', STR_PAD_LEFT);
    }
}