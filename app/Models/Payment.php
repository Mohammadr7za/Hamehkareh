<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'payments';
    protected $fillable = [
        'uuid', 'customer_id', 'provider_id', 'handyman_id', 'booking_id', 'datetime',
        'discount', 'total_amount', 'amount', 'currency', 'payment_type', 'gateway',
        'authority', 'reference_id', 'gateway_url', 'txn_id', 'payment_status',
        'card_pan', 'card_hash', 'failure_code', 'failure_message', 'gateway_response',
        'requested_at', 'verified_at', 'failed_at', 'customer_sms_sent_at',
        'handyman_sms_sent_at', 'other_transaction_detail',
        'notifications_sent_at',
    ];

    protected $casts = [
        'booking_id'    => 'integer',
        'customer_id'   => 'integer',
        'discount'      => 'double',
        'total_amount'  => 'double',
        'amount' => 'integer',
        'gateway_response' => 'array',
        'requested_at' => 'datetime',
        'verified_at' => 'datetime',
        'failed_at' => 'datetime',
        'customer_sms_sent_at' => 'datetime',
        'handyman_sms_sent_at' => 'datetime',
        'notifications_sent_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function customer(){
        return $this->belongsTo(User::class,'customer_id', 'id')->withTrashed();
    }
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id', 'id')->withTrashed();
    }
    public function provider(){
        return $this->belongsTo(User::class, 'provider_id', 'id')->withTrashed();
    }
    public function handyman(){
        return $this->belongsTo(User::class, 'handyman_id', 'id')->withTrashed();
    }
    public function scopeMyPayment($query)
    {
        $user = auth()->user();
        if($user->hasAnyRole(['admin', 'manager'])){
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->whereHas('booking', function($q) use($user) {
                $q->where('provider_id', '=', $user->id);
            });
        }

        if($user->hasRole('user')) {
            return $query->where('customer_id', $user->id);
        }

        if($user->hasRole('handyman')) {
            return $query->whereHas('booking',function ($q) use($user) {
                $q->whereHas('handymanAdded',function($handyman) use($user){
                    $handyman->where('handyman_id',$user->id);
                });
            });
        }

        return $query;
    }
    public function paymentHistory(){
        return $this->hasMany(PaymentHistory::class, 'payment_id','id');
    }
}
