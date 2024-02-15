<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Framework\Enums\PaymentStatusEnum;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Traits\HasPrice;

class Payment extends Model
{
    use HasFactory, HasPrice, SoftDeletes;

    protected $fillable = [
        'order_id',
        'user_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'payment_date',
        'payment_details',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'status' => PaymentStatusEnum::class,
    ];

    protected $appends = [
        'formatted_amount',
    ];

    public function getFormattedAmountAttribute(): string|null
    {
        if ($this->amount) {

            return $this->formattedPrice((int)$this->amount);
        }

        return null;
    }

    public function getAmountAttribute($value)
    {
        return number_format($value / 100, 2);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
