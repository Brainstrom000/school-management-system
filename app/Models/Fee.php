<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'amount',
        'due_date',
        'status',
        'payment_method',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'datetime',
        'amount'   => 'decimal:2',
    ];

    /**
     * Student Relationship
     */
    public function student()
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    /**
     * Scope: Unpaid fees
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope: Paid fees
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
