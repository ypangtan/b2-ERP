<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

class UserKyc extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'nationality_id',
        'approved_by',
        'rejected_by',
        'fullname',
        'identification_number',
        'date_of_birth',
        'address',
        'remarks',
        'status',
        'approved_at',
        'rejected_at',
    ];

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function nationality() {
        return $this->belongsTo( Country::class, 'nationality_id' );
    }

    public function userBank() {
        return $this->hasOne( UserBank::class, 'user_kyc_id' );
    }

    public function userKycDocuments() {
        return $this->hasMany( UserKycDocument::class, 'user_kyc_id' );
    }

    public function userBeneficiary() {
        return $this->hasOne( UserBeneficiary::class, 'user_kyc_id' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'user_id',
        'nationality_id',
        'approved_by',
        'rejected_by',
        'fullname',
        'identification_number',
        'date_of_birth',
        'address',
        'remarks',
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected static $logName = 'user_kycs';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} user kyc";
    }
}
