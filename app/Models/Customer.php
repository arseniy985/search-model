<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

//    protected $primaryKey = 'user_id';

    protected $fillable = ['first_name', 'last_name', 'phone', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 📨 Жеткізу адресі (Shipping Address)
    public function shippingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('type', AddressType::Shipping->value);
    }

    // 💳 Төлем адресі (Billing Address)
    public function billingAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('type', AddressType::Billing->value);
    }
}
