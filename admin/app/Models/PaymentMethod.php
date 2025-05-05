<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'payment_image',
        'payment_number',
        'is_active',
    ];


    protected $casts = [
        'payment_number' => 'string',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
