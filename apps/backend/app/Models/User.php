<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email', 'password', 'role', 'status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function approvedOvertimeRequests()
    {
        return $this->hasMany(OvertimeRequest::class, 'approved_by');
    }

    public function createdDocuments()
    {
        return $this->hasMany(EmployeeDocument::class, 'created_by');
    }

    public function createdPayments()
    {
        return $this->hasMany(Payment::class, 'created_by');
    }
}