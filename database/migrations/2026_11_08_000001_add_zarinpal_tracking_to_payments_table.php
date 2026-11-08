<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddZarinpalTrackingToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->unsignedBigInteger('provider_id')->nullable()->after('customer_id');
            $table->unsignedBigInteger('handyman_id')->nullable()->after('provider_id');
            $table->unsignedBigInteger('amount')->nullable()->after('total_amount');
            $table->string('currency', 3)->default('IRT')->after('amount');
            $table->string('gateway', 30)->default('zarinpal')->after('payment_type');
            $table->string('authority', 64)->nullable()->unique()->after('gateway');
            $table->string('reference_id', 100)->nullable()->unique()->after('authority');
            $table->text('gateway_url')->nullable()->after('reference_id');
            $table->string('card_pan', 32)->nullable()->after('gateway_url');
            $table->string('card_hash', 128)->nullable()->after('card_pan');
            $table->string('failure_code', 50)->nullable()->after('card_hash');
            $table->text('failure_message')->nullable()->after('failure_code');
            $table->json('gateway_response')->nullable()->after('failure_message');
            $table->timestamp('requested_at')->nullable()->after('gateway_response');
            $table->timestamp('verified_at')->nullable()->after('requested_at');
            $table->timestamp('failed_at')->nullable()->after('verified_at');
            $table->timestamp('customer_sms_sent_at')->nullable()->after('failed_at');
            $table->timestamp('handyman_sms_sent_at')->nullable()->after('customer_sms_sent_at');
            $table->timestamp('notifications_sent_at')->nullable()->after('handyman_sms_sent_at');

            $table->index(['booking_id', 'payment_status']);
            $table->index(['provider_id', 'created_at']);
            $table->index(['handyman_id', 'created_at']);
        });

        DB::table('payments')->select('id')->orderBy('id')->chunkById(200, function ($payments) {
            foreach ($payments as $payment) {
                DB::table('payments')->where('id', $payment->id)->update([
                    'uuid' => (string) Str::uuid(),
                ]);
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['booking_id', 'payment_status']);
            $table->dropIndex(['provider_id', 'created_at']);
            $table->dropIndex(['handyman_id', 'created_at']);
            $table->dropColumn([
                'uuid', 'provider_id', 'handyman_id', 'amount', 'currency', 'gateway',
                'authority', 'reference_id', 'gateway_url', 'card_pan', 'card_hash',
                'failure_code', 'failure_message', 'gateway_response', 'requested_at',
                'verified_at', 'failed_at', 'customer_sms_sent_at', 'handyman_sms_sent_at',
                'notifications_sent_at',
            ]);
        });
    }
}
