<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'fp_id',
        'card_id',
        'pwd',
        'grade',
        'department_id',
        'designation_id',
        'primary_shift_id',
        'hire_date',
        'photo_path',
        'status',
        'employment_status_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    protected $appends = [
        'current_shift',
        'code',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'employee_shifts')
            ->withPivot('effective_from', 'effective_until')
            ->withTimestamps();
    }

    public function primaryShift()
    {
        return $this->belongsTo(Shift::class, 'primary_shift_id');
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function payrollItems()
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCurrentShift()
    {
        return $this->shifts()
            ->where('effective_from', '<=', now())
            ->where(function ($query) {
                $query->where('effective_until', '>=', now())
                      ->orWhereNull('effective_until');
            })
            ->first();
    }

    public function getCurrentShiftAttribute()
    {
        return $this->getCurrentShift();
    }

    /**
     * Virtual employee code like EMP-<id>. Ensures a stable, readable identifier
     * even if no explicit code column exists.
     */
    public function getCodeAttribute(): string
    {
        $id = $this->getAttribute('id');
        if (!$id) {
            return 'EMP-NEW';
        }
        return 'EMP-' . $id;
    }
}



