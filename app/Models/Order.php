<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['held', 'submitted', 'completed', 'cancelled'];
    public const KITCHEN_STATUSES = ['waiting', 'cooking', 'ready', 'served'];

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
     * Generate nomor order berikutnya, mis. #ORD-4920.
     */
    public static function generateOrderNumber(): string
    {
        $last = static::query()->latest('id')->first();
        $next = $last ? ((int) str_replace('#ORD-', '', $last->order_number)) + 1 : 4001;

        return '#ORD-' . $next;
    }
}