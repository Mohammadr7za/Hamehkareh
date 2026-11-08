<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Pishran\Zarinpal\Error;

class ZarinpalPaymentService
{
    private const API_URL = 'https://api.zarinpal.com/pg/v4/payment';
    private const GATEWAY_URL = 'https://www.zarinpal.com/pg/StartPay/';

    public function request(Payment $payment, $mobile = null, $email = null)
    {
        $metadata = array_filter(['mobile' => $mobile, 'email' => $email]);
        $response = Http::asJson()->acceptJson()->timeout(15)->retry(2, 300)
            ->post(self::API_URL . '/request.json', [
                'merchant_id' => config('zarinpal.merchant_id'),
                'amount' => $payment->amount,
                'description' => 'پرداخت رزرو شماره ' . $payment->booking_id,
                'callback_url' => route('payment.verification', ['payment' => $payment->uuid]),
                'metadata' => (object) $metadata,
            ]);

        return $this->normalize($response->json(), false);
    }

    public function verify(Payment $payment, $authority)
    {
        $response = Http::asJson()->acceptJson()->timeout(15)->retry(2, 300)
            ->post(self::API_URL . '/verify.json', [
                'merchant_id' => config('zarinpal.merchant_id'),
                'amount' => $payment->amount,
                'authority' => $authority,
            ]);

        return $this->normalize($response->json(), true);
    }

    private function normalize(array $response, $verification)
    {
        $data = isset($response['data']) && is_array($response['data']) ? $response['data'] : [];
        $errors = isset($response['errors']) && is_array($response['errors']) ? $response['errors'] : [];
        $code = (int) ($data['code'] ?? $errors['code'] ?? 0);
        $success = $verification ? in_array($code, [100, 101], true) : $code === 100;

        return [
            'success' => $success,
            'code' => $code,
            'message' => $success ? null : (new Error($code))->message(),
            'authority' => $data['authority'] ?? null,
            'gateway_url' => isset($data['authority']) ? self::GATEWAY_URL . $data['authority'] : null,
            'reference_id' => isset($data['ref_id']) ? (string) $data['ref_id'] : null,
            'card_pan' => $data['card_pan'] ?? null,
            'card_hash' => $data['card_hash'] ?? null,
            'already_verified' => $code === 101,
            'raw' => $response,
        ];
    }
}
