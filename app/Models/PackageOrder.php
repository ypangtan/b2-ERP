<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

class PackageOrder extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'package_id',
        'user_id',
        'approved_by',
        'rejected_by',
        'reference',
        'amount',
        'monthly_buy_back',
        'monthly_buy_back_rate',
        'total_released',
        'buy_back_limit',
        'mission_complete',
        'mission_incomplete',
        'type',
        'status',
        'approved_at',
        'rejected_at',
    ];

    public function package() {
        return $this->belongsTo( Package::class, 'package_id' );
    }

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function getDisplayAmountAttribute() {
        return Helper::numberFormat( $this->attributes['amount'], 2, true );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'package_id',
        'user_id',
        'approved_by',
        'rejected_by',
        'reference',
        'amount',
        'monthly_buy_back',
        'monthly_buy_back_rate',
        'total_released',
        'buy_back_limit',
        'mission_complete',
        'mission_incomplete',
        'type',
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected static $logName = 'package_orders';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} package order";
    }
}
