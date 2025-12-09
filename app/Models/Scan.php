<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
   use HasFactory;

    protected $table = 'scans';

    protected $fillable = [
        'user_id',
        'value',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    // Relación con el usuario que escaneó
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
