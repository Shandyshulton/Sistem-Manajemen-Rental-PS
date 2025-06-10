<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Console extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_console';
    protected $fillable = [
        'typeConsole',
        'availability',
        'consoleRoom',
        'price'
    ];

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'Ready');
    }

    public function isAvailable()
    {
        return $this->availability === 'Ready';
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'console_id');
    }
}
