<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_PENDING   = 'pending';
    public const STATUS_PAID      = 'paid';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_FAILED    = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    // Payment method label
    public const METHOD_ABA = 'ABA PayWay';

    protected $fillable = [
        'registration_id',
        'student_id',
        'course_id',
        'transaction_id',   // ABA's tran_id — filled after callback
        'amount',
        'currency',
        'payment_method',   // 'ABA PayWay'
        'status',           // pending | paid | failed | cancelled
        'payment_response', // raw ABA callback JSON
        'paid_at',
        // Legacy columns kept for backwards compatibility:
        'method',
        'gateway_response',
        'qr_string',
    ];

    protected function casts(): array
    {
        return [
            'payment_response' => 'array',
            'gateway_response' => 'array',
            'paid_at'          => 'datetime',
            'amount'           => 'decimal:2',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isGatewayPaid(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_CONFIRMED], true);
    }

    /**
     * Mark the payment as ABA-verified. The registrar confirms enrollment separately.
     * Safe to call more than once (idempotent).
     *
     * @param  string      $tranId   ABA's transaction ID
     * @param  array       $response Raw ABA callback/check-transaction response
     */
    public function markPaid(string $tranId, array $response = []): void
    {
        if (!$this->isPaid()) {
            $this->update([
                'status'           => self::STATUS_PAID,
                'transaction_id'   => $tranId,
                'payment_response' => $response,
                'paid_at'          => now(),
            ]);
        }

    }

    /**
     * Confirm payment after either ABA verification or an administrator's
     * reconciliation against the ABA Payment Link merchant portal.
     */
    public function confirm(): void
    {
        if (in_array($this->status, [self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_CONFIRMED], true)) {
            return;
        }

        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'paid_at' => $this->paid_at ?? now(),
            'payment_response' => array_merge($this->payment_response ?? [], [
                'confirmed_by_admin' => true,
                'confirmed_at' => now()->toDateTimeString(),
            ]),
        ]);

        if ($this->registration) {
            $this->registration->update([
                'status'        => Registration::STATUS_REGISTERED,
                'registered_at' => $this->paid_at ?? now(),
            ]);
        }
    }

    /**
     * Mark the payment as failed, leaving the registration in pending_payment
     * so the student can retry.
     */
    public function markFailed(array $response = []): void
    {
        $this->update([
            'status'           => self::STATUS_FAILED,
            'payment_response' => $response,
        ]);
        // Registration intentionally left as pending_payment — student can try again.
    }
}
