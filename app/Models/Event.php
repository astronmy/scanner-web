<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'label',
        'end_date',
        'new_button_enabled',
        'message_not_found',
        'scan_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'new_button_enabled' => 'boolean',
        'scan_type' => 'integer',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
