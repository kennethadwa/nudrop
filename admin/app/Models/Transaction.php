<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Add the fillable property
    protected $fillable = [
    'transaction_no',
    'pickup_request_id',
    'payment_id',
    'amount',
    'verified_by',
    'verified_at',
];

    public function pickupRequest()
    {
        return $this->belongsTo(PickupRequest::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentsModel::class);
    }

    public function verifier()
{
    return $this->belongsTo(StaffAccount::class, 'verified_by');
}

}
