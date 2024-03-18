<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\HasTranslations;

use Helper;

use Carbon\Carbon;

class Mission extends Model
{
    use HasFactory, LogsActivity, HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'link',
        'icon',
        'color',
        'type',
        'status',
    ];

    public $translatable = [ 'title', 'description' ];

    public function currentMonthCompleted() {
        return $this->hasOne( MissionHistory::class, 'mission_id' )
            ->where( 'user_id', auth()->user()->id )
            ->where( 'status', 10 )
            ->where( 'created_at', '>=', Carbon::now( 'Asia/Kuala_Lumpur' )->startOfMonth()->timezone( 'UTC' ) )
            ->where( 'created_at', '<=', Carbon::now( 'Asia/Kuala_Lumpur' )->endOfMonth()->timezone( 'UTC' ) );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'title',
        'description',
        'link',
        'icon',
        'color',
        'type',
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
