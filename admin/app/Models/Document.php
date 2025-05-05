<?php

// app/Models/Document.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_name', // Add other fields you want to fill
    ];

    // Add any relationships or methods as needed

    public function pickupRequests()
{
    return $this->hasMany(PickupRequest::class);
}
    
}

