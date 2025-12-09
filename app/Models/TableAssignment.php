<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableAssignment extends Model
{
     use HasFactory;

    protected $table = 'table_assignments';

    protected $fillable = [
        'table_number',
        'guest_name',
    ];
}
