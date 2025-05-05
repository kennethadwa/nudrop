<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrarModel extends Model
{
    // Manually define the table name this model is connected to
    protected $table = 'documents';

    // Allow mass assignment for these fields (useful for create/update)
    protected $fillable = [
        'document_name',
        'fee',
    ];

}
