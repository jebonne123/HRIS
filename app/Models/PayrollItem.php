<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'basic_salary',
        'allowances_json',
        'deductions_json',
        'net_pay',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances_json' => 'array',
        'deductions_json' => 'array',
        'net_pay' => 'decimal:2',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}




