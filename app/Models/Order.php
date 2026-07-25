<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['held', 'submitted', 'completed', 'cancelled'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'dining_table_id',
        'user_id',
        'guests',
        'status',
        'subtotal',
        'tax',
        'total',
    ];

    public function table()
    {
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate nomor order berikutnya, mis. #ORD-4920.
     */
    public static function generateOrderNumber(): string
    {
        $last = static::query()->latest('id')->first();
        $next = $last ? ((int) str_replace('#ORD-', '', $last->order_number)) + 1 : 4001;

        return '#ORD-' . $next;
    }
}