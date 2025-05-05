<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentMethod; // Ensure the correct namespace for PaymentMethod
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PaymentsModel;



class PickUpRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $table = 'pickup_requests';

    protected $fillable = [
        'reference_no',
        'user_id',
        'document_id',
        'request_date',
        'status',
        'payment_status',
        'pickup_date',
        'remarks'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function document()
{
    return $this->belongsTo(Document::class, 'document_id');
}



    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // Correctly define the relationship to payments
    public function payments()
{
    return $this->hasMany(PaymentsModel::class, 'pickup_request_id')->withTrashed();
}
    public function payment()
{
    return $this->hasOne(PaymentsModel::class, 'pickup_request_id')->withTrashed();
}


    // Automatically soft delete related payments when a pickup request is deleted
    protected static function booted()
    {
        static::deleting(function ($pickupRequest) {
            // Soft delete related payments
            $pickupRequest->payments->each->delete();
        });
    }
}

