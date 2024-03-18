<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\HasTranslations;

use Helper;

class Package extends Model
{
    use HasFactory, LogsActivity, HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'price',
        'sort',
        'status',
    ];

    public $translatable = [ 'name', 'description' ];

    public function packageBonusRebate() {
        return $this->hasOne( PackageBonus::class, 'package_id' )
            ->where( 'type', 1 )
            ->where( 'status', 10 );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'name',
        'description',
        'price',
        'sort',
        'status',
    ];

    protected static $logName = 'packages';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} package";
    }
}
