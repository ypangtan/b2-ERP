<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

class MissionHistory extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'mission_id',
        'user_id',
        'status',
    ];

    public function mission() {
        return $this->belongsTo( Mission::class, 'mission_id' );
    }

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'mission_id',
        'user_id',
        'status',
    ];

    protected static $logName = 'missions';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} mission";
    }
}
