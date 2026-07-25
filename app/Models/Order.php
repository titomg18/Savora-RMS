<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['held', 'submitted', 'completed', 'cancelled'];
    public const KITCHEN_STATUSES = ['waiting', 'cooking', 'ready', 'served'];
    public const PAYMENT_STATUSES = ['unpaid', 'paid'];
    public const PAYMENT_METHODS = ['cash', 'card', 'qris', 'ewallet'];

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
        'kitchen_status',
        'subtotal',
        'tax',
        'total',
        'payment_status',
        'payment_method',
        'discount',
        'promo_code',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
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
     * Status berikutnya di alur dapur: waiting -> cooking -> ready -> served.
     */
    public function nextKitchenStatus(): ?string
    {
        $index = array_search($this->kitchen_status, self::KITCHEN_STATUSES, true);

        return self::KITCHEN_STATUSES[$index + 1] ?? null;
    }

    /**
     * Total yang harus dibayar setelah dikurangi diskon.
     */
    public function getAmountDueAttribute(): float
    {
        return round(($this->subtotal + $this->tax) - $this->discount, 2);
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