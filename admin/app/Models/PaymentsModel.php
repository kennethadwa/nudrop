<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentsModel extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'pickup_request_id',
        'request_type',
        'amount',
        'payment_method_id',
        'paid_at',
        'proof_of_payment', 
        'is_verified', 
        'verification_remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pickupRequest()
    {
        return $this->belongsTo(PickupRequest::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Add a method to get the payment method name
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function payment()
{
    return $this->hasOne(Payment::class, 'pickup_request_id');
}

}
