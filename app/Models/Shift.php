<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time_1',
        'end_time_1',
        'start_time_2',
        'end_time_2',
        'bell_enabled',
        'allow_late_minutes',
        'allow_early_minutes',
        'rest_days',
        'enable_ot',
        'ot_start_minutes',
        'ot_valid_minutes',
    ];

    protected $casts = [
        'bell_enabled' => 'boolean',
        'enable_ot' => 'boolean',
        'start_time_1' => 'datetime:H:i',
        'end_time_1' => 'datetime:H:i',
        'start_time_2' => 'datetime:H:i',
        'end_time_2' => 'datetime:H:i',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_shifts')
            ->withPivot('effective_from', 'effective_until')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function isLate($checkInTime)
    {
        if (!$this->start_time_1) return false;
        
        $checkIn = Carbon::parse($checkInTime);
        $shiftStart = Carbon::parse($this->start_time_1);
        $allowedLate = $shiftStart->copy()->addMinutes($this->allow_late_minutes);
        
        return $checkIn->gt($allowedLate);
    }

    public function isEarlyLeave($checkOutTime)
    {
        if (!$this->end_time_2) return false;
        
        $checkOut = Carbon::parse($checkOutTime);
        $shiftEnd = Carbon::parse($this->end_time_2);
        $allowedEarly = $shiftEnd->copy()->subMinutes($this->allow_early_minutes);
        
        return $checkOut->lt($allowedEarly);
    }

    public function calculateOvertime($workMinutes, $scheduledMinutes)
    {
        if (!$this->enable_ot) return 0;
        
        $extraMinutes = $workMinutes - $scheduledMinutes;
        
        if ($extraMinutes < $this->ot_start_minutes) return 0;
        
        // Calculate OT in blocks of ot_valid_minutes
        $otMinutes = floor($extraMinutes / $this->ot_valid_minutes) * $this->ot_valid_minutes;
        
        return $otMinutes;
    }

    public function getTotalScheduledMinutes()
    {
        $minutes = 0;
        
        if ($this->start_time_1 && $this->end_time_1) {
            $start1 = Carbon::parse($this->start_time_1);
            $end1 = Carbon::parse($this->end_time_1);
            $minutes += $start1->diffInMinutes($end1);
        }
        
        if ($this->start_time_2 && $this->end_time_2) {
            $start2 = Carbon::parse($this->start_time_2);
            $end2 = Carbon::parse($this->end_time_2);
            $minutes += $start2->diffInMinutes($end2);
        }
        
        return $minutes;
    }
}




