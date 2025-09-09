<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'check_in',
        'check_out',
        'work_minutes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateWorkMinutes()
    {
        if (!$this->check_in || !$this->check_out) {
            return 0;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);
        
        $totalMinutes = $checkIn->diffInMinutes($checkOut);
        
        // Subtract break minutes
        $breakMinutes = $this->breaks->sum('minutes');
        
        return $totalMinutes - $breakMinutes;
    }

    public function calculateOvertime()
    {
        if (!$this->shift || !$this->shift->enable_ot) {
            return 0;
        }

        $workMinutes = $this->work_minutes ?: $this->calculateWorkMinutes();
        $scheduledMinutes = $this->shift->getTotalScheduledMinutes();
        
        return $this->shift->calculateOvertime($workMinutes, $scheduledMinutes);
    }

    public function updateStatus()
    {
        if (!$this->check_in) {
            $this->status = 'absent';
        } elseif ($this->shift && $this->shift->isLate($this->check_in)) {
            $this->status = 'late';
        } else {
            $this->status = 'present';
        }
        
        $this->save();
    }
}




