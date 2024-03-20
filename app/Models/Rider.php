<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

class Rider extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'username',
        'fullname',
        'ic',
        'email',
        'phone_number',
        'password',
        'status',
    ];

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static $logAttributes = [
        'username',
        'fullname',
        'ic',
        'email',
        'phone_number',
        'password',
        'status',
    ];

    protected static $logName = 'riders';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} ";
    }
}
