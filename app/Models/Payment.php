<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    public const METHOD_ABA = 'aba_payway';
    public const METHOD_BAKONG = 'bakong';

    protected $fillable = [
        'student_id',
        'course_id',
        'method',
        'amount',
        'currency',
        'transaction_id',
        'status',
        'qr_string',
        'gateway_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Mark this payment as paid and create the actual course Registration.
     * Safe to call more than once (idempotent) — the unique(student_id, course_id)
     * index on `registrations` prevents duplicates.
     */
    public function markPaid(array $gatewayResponse = []): Registration
    {
        if (!$this->isPaid()) {
            $this->update([
                'status' => self::STATUS_PAID,
                'gateway_response' => $gatewayResponse ?: $this->gateway_response,
                'paid_at' => now(),
            ]);
        }

        $existing = Registration::where('student_id', $this->student_id)
            ->where('course_id', $this->course_id)
            ->first();

        if ($existing) {
            if ($existing->status !== 'registered') {
                $existing->update(['status' => 'registered', 'registered_at' => now()]);
            }
            return $existing;
        }

        return Registration::create([
            'student_id' => $this->student_id,
            'course_id' => $this->course_id,
            'registered_at' => now(),
            'status' => 'registered',
        ]);
    }
}