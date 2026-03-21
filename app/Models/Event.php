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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'new_button_enabled' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
