<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'status', 'first_check_in', 'last_check_out', 'work_hours', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'first_check_in' => 'datetime',
        'last_check_out' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
