<x-master-layout>
    <div class="container-fluid"><div class="card"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="font-weight-bold mb-0">{{ $pageTitle }}</h5>
            <div>
                @if($payment->payment_status !== 'paid' && $payment->authority)
                    <form method="POST" action="{{ route('payment.recheck', $payment) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm">استعلام مجدد از زرین‌پال</button>
                    </form>
                @endif
                <a href="{{ route('booking.show', $payment->booking_id) }}" class="btn btn-primary btn-sm">مشاهده رزرو #{{ $payment->booking_id }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3"><strong>مشتری:</strong> {{ optional($payment->customer)->display_name ?? '-' }}</div>
            <div class="col-md-4 mb-3"><strong>نمایندگی:</strong> {{ optional($payment->provider)->display_name ?: optional(optional($payment->booking)->provider)->display_name ?: '-' }}</div>
            <div class="col-md-4 mb-3"><strong>متخصص:</strong> {{ optional($payment->handyman)->display_name ?? optional($payment->booking)->handymanAdded->pluck('handyman.display_name')->filter()->implode('، ') }}</div>
            <div class="col-md-4 mb-3"><strong>خدمت:</strong> {{ optional(optional($payment->booking)->service)->name ?? '-' }}</div>
            <div class="col-md-4 mb-3"><strong>مبلغ:</strong> {{ number_format($payment->amount ?: $payment->total_amount) }} {{ $payment->currency === 'IRR' ? 'ریال' : 'تومان' }}</div>
            <div class="col-md-4 mb-3"><strong>وضعیت:</strong> {{ $payment->payment_status }}</div>
            <div class="col-md-6 mb-3"><strong>Authority:</strong> <span dir="ltr">{{ $payment->authority ?? '-' }}</span></div>
            <div class="col-md-6 mb-3"><strong>کد پیگیری:</strong> {{ $payment->reference_id ?? '-' }}</div>
            <div class="col-md-4 mb-3"><strong>کارت:</strong> <span dir="ltr">{{ $payment->card_pan ?? '-' }}</span></div>
            <div class="col-md-4 mb-3"><strong>زمان ایجاد:</strong> {{ $payment->created_at ? jdate($payment->created_at) : '-' }}</div>
            <div class="col-md-4 mb-3"><strong>زمان تأیید:</strong> {{ $payment->verified_at ? jdate($payment->verified_at) : '-' }}</div>
            <div class="col-md-6 mb-3"><strong>پیامک مشتری:</strong> {{ $payment->customer_sms_sent_at ? jdate($payment->customer_sms_sent_at) : 'ارسال نشده' }}</div>
            <div class="col-md-6 mb-3"><strong>پیامک متخصص:</strong> {{ $payment->handyman_sms_sent_at ? jdate($payment->handyman_sms_sent_at) : 'ارسال نشده' }}</div>
            <div class="col-md-6 mb-3"><strong>اعلان داخل برنامه:</strong> {{ $payment->notifications_sent_at ? jdate($payment->notifications_sent_at) : 'ثبت نشده' }}</div>
            @if($payment->failure_message)<div class="col-12"><div class="alert alert-danger mb-0">{{ $payment->failure_message }}</div></div>@endif
        </div>
    </div></div></div>
</x-master-layout>
