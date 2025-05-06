<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

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
        return $this->belongsTo(PickupRequest::class)->withTrashed();
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
        return $this->belongsTo(PaymentsModel::class)->withTrashed();
    }

    public function verifier()
    {
        return $this->belongsTo(StaffAccount::class, 'verified_by');
    }
}
