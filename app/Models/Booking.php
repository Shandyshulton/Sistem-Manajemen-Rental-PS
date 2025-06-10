<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_booking';
    protected $fillable = [
        'customer_name',
        'phone_number',
        'booking_date',
        'start_time',
        'end_time',
        'estimated_hours',
        'console_id',
        'selected_games',
        'total_price',
        'payment_type',
        'status',
        'playing_status'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'selected_games' => 'array'
    ];

    public function console()
    {
        return $this->belongsTo(Console::class, 'console_id');
    }

    public static function calculateEndTime($startTime, $hours)
    {
        try {
            $hours = (int)$hours;
            $start = Carbon::createFromFormat('H:i', $startTime);
            return $start->addHours($hours)->format('H:i');
        } catch (\Exception $e) {
            return '23:59';
        }
    }
}
