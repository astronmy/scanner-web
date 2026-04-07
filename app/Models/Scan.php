<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
   use HasFactory;

    public const ORIGIN_AUTOMATIC = 'AUTOMATIC';
    public const ORIGIN_MANUAL = 'MANUAL';

    protected $table = 'scans';

    protected $fillable = [
        'user_id',
        'value',
        'id_list',
        'qr_list',
        'observations',
        'scanned_at',
        'event_id',
        'origin',
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
