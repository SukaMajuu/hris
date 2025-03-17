<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'overtime_type_id', 'date', 'start_time', 'end_time',
        'hours', 'estimated_compensation', 'reason', 'status', 'approved_by'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function overtimeType()
    {
        return $this->belongsTo(OvertimeType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
