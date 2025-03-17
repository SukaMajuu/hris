<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_cycle_id', 'amount', 'payment_method', 'payment_reference', 'payment_date', 'status', 'created_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'created_at' => 'datetime',
    ];

    public function billingCycle()
    {
        return $this->belongsTo(BillingCycle::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
