@extends('layouts.app')

@section('title', 'Pay with Bakong - SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.courses.show', $payment->course->course_code) }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Scan to Pay
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 col-lg-5 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white text-center">
            <h5 class="fw-bold mb-1">Scan with your Bakong app</h5>
            <p class="text-muted small mb-4">{{ $payment->course->course_code }} — ${{ number_format($payment->amount, 2) }}</p>

            <div id="qrContainer" class="d-flex justify-content-center mb-4">
                <canvas id="qrCanvas" width="240" height="240"></canvas>
            </div>

            <div id="statusBadge" class="badge bg-warning-subtle text-warning fw-semibold px-3 py-2 mb-3">
                <i class="bi bi-hourglass-split me-1"></i> Waiting for payment…
            </div>

            <p class="text-muted extra-small" style="font-size:0.75rem;">
                This page checks automatically every few seconds. Do not close it until payment is confirmed.
            </p>
        </div>
    </div>
</div>

<!-- Lightweight QR renderer (client-side only, no external image service) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById('qrContainer'), {
        text: @json($payment->qr_string),
        width: 240,
        height: 240,
    });
    document.getElementById('qrCanvas')?.remove(); // qrcodejs injects its own canvas/img

    const pollUrl = @json(route('student.payment.bakong.poll', $payment->id));
    const successUrl = @json(route('student.courses.my-courses'));

    const poll = setInterval(async () => {
        try {
            const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (data.status === 'paid') {
                clearInterval(poll);
                document.getElementById('statusBadge').outerHTML =
                    '<div class="badge bg-success text-white fw-semibold px-3 py-2 mb-3"><i class="bi bi-check-circle me-1"></i> Payment confirmed!</div>';
                setTimeout(() => window.location.href = successUrl, 1200);
            }
        } catch (e) {
            console.error('Poll failed', e);
        }
    }, 3000);
</script>
@endsection