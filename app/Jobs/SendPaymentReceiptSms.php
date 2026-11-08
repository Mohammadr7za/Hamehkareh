<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendPaymentReceiptSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 60;
    private $paymentId;

    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function handle()
    {
        $payment = Payment::with(['customer', 'booking.handymanAdded.handyman'])
            ->find($this->paymentId);

        if (!$payment || $payment->payment_status !== 'paid') {
            return;
        }

        $amount = number_format($payment->amount) . ' ' . ($payment->currency === 'IRR' ? 'ریال' : 'تومان');
        $reference = $payment->reference_id;

        if (!$payment->customer_sms_sent_at && $payment->customer) {
            $message = "پرداخت رزرو شماره {$payment->booking_id} با موفقیت انجام شد.\nمبلغ: {$amount}\nکد پیگیری: {$reference}";
            if (!sendSmsToUser($payment->customer->contact_number, $message)) {
                throw new \RuntimeException('Customer payment SMS could not be sent.');
            }
            $payment->forceFill(['customer_sms_sent_at' => now()])->save();
        }

        if (!$payment->handyman_sms_sent_at && $payment->booking) {
            $numbers = $payment->booking->handymanAdded
                ->pluck('handyman.contact_number')->filter()->unique()->values()->all();
            if (!empty($numbers)) {
                $message = "هزینه رزرو شماره {$payment->booking_id} توسط مشتری پرداخت شد.\nمبلغ: {$amount}\nکد پیگیری: {$reference}";
                if (!sendSmsToUser($numbers, $message)) {
                    throw new \RuntimeException('Handyman payment SMS could not be sent.');
                }
            }
            $payment->forceFill(['handyman_sms_sent_at' => now()])->save();
        }

        if (!$payment->notifications_sent_at) {
            $recipients = collect([$payment->customer_id])
                ->merge($payment->booking ? $payment->booking->handymanAdded->pluck('handyman_id') : [])
                ->filter()->unique();
            foreach ($recipients as $userId) {
                Notification::create([
                    'id' => Str::random(32),
                    'type' => 'payment',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $userId,
                    'data' => json_encode([
                        'id' => $payment->booking_id,
                        'payment_id' => $payment->uuid,
                        'type' => 'payment_paid',
                        'subject' => 'پرداخت موفق رزرو',
                        'message' => 'پرداخت رزرو شماره ' . $payment->booking_id . ' با کد پیگیری ' . $payment->reference_id . ' تأیید شد.',
                        'notification-type' => 'booking',
                    ]),
                ]);
            }
            $payment->forceFill(['notifications_sent_at' => now()])->save();
        }
    }
}
