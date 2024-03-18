<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

class Withdrawal extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'approved_by',
        'rejected_by',
        'reference',
        'bank_reference',
        'remark',
        'amount',
        'service_charge_rate',
        'service_charge_amount',
        'service_charge_type',
        'wallet_type',
        'payment_method',
        'status',
        'approved_at',
        'rejected_at',
    ];

    public function withdrawalMeta() {
        return $this->hasOne( WithdrawalMeta::class, 'withdrawal_id' );
    }

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function getDisplayAmountAttribute() {
        return Helper::numberFormat( $this->attributes['amount'], 2 );
    }

    public function getDisplayServiceChargeRateAttribute() {
        return Helper::numberFormat( $this->attributes['service_charge_rate'], 2 );
    }

    public function getDisplayServiceChargeAmountAttribute() {
        return Helper::numberFormat( $this->attributes['service_charge_amount'], 2 );
    }

    public function getDisplayActualAmountAttribute() {
        return Helper::numberFormat( $this->attributes['amount'] - $this->attributes['service_charge_amount'], 2 );
    }

    public function getDisplayPaymentMethodAttribute() {
        return $this->attributes['payment_method'] == 1 ? __( 'withdrawal.bank_transfer' ) : __( 'withdrawal.payment_gateway' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'user_id',
        'approved_by',
        'rejected_by',
        'reference',
        'bank_reference',
        'remark',
        'amount',
        'service_charge_rate',
        'service_charge_amount',
        'service_charge_type',
        'wallet_type',
        'payment_method',
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected static $logName = 'withdrawals';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} withdrawal";
    }
}
