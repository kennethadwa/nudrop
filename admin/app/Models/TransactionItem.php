<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'document_id',
        'amount',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

