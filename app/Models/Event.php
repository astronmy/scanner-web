<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cover_image',
        'start_date',
        'label',
        'end_date',
        'new_button_enabled',
        'message_not_found',
        'scan_type',
        'autostart',
        'separator',
        'check_duplicity',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'new_button_enabled' => 'boolean',
        'scan_type' => 'integer',
        'autostart' => 'boolean',
        'check_duplicity' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : null;
    }
}
