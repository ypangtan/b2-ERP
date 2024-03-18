<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

class WithdrawalMeta extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'withdrawal_id',
        'bank_id',
        'account_holder_name',
        'account_number',
    ];

    public function bank() {
        return $this->belongsTo( Bank::class, 'bank_id' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'withdrawal_id',
        'bank_id',
        'account_holder_name',
        'account_number',
    ];

    protected static $logName = 'withdrawal_metas';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} withdrawal meta";
    }
}
