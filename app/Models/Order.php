<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['restaurant_id', 'waiter_id', 'table_number', 'customer_phone', 'customer_name', 'whatsapp_jid', 'status', 'payment_reference', 'total_amount', 'notes', 'is_vip', 'bill_image_pushed_at'];

    protected function casts(): array
    {
        return [
            'bill_image_pushed_at' => 'datetime',
            'is_vip' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\RestaurantScope);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    public function tip()
    {
        return $this->hasOne(Tip::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function isBillStage(): bool
    {
        return in_array($this->status, ['served'], true);
    }

    public function billImageSignature(): string
    {
        $seed = implode('|', [
            $this->id,
            $this->restaurant_id,
            (string) $this->updated_at,
        ]);

        return hash_hmac('sha256', $seed, (string) config('app.key'));
    }

    public function billImageUrl(): string
    {
        return route('bill.image', [
            'orderId' => $this->id,
            'signature' => $this->billImageSignature(),
        ]);
    }

    public function shouldPushBillImage(): bool
    {
        return $this->isBillStage()
            && ! empty($this->whatsapp_jid)
            && is_null($this->bill_image_pushed_at);
    }

    public function markBillImagePushed(): void
    {
        $this->forceFill(['bill_image_pushed_at' => now()])->saveQuietly();
    }
}
