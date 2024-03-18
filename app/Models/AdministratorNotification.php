<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\HasTranslations;

class AdministratorNotification extends Model
{
    use HasFactory, LogsActivity, HasTranslations;

    public function admin() {
        return $this->belongsTo( Admin::class, 'administrator_id' )->withTrashed();
    }

    protected $fillable = [
        'administrator_id',
        'role_id',
        'title',
        'content',
        'system_title',
        'system_content',
        'meta_data',
        'image',
        'type',
        'status',
    ];
    
    public $translatable = [ 'title', 'content' ];

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'administrator_id',
        'role_id',
        'title',
        'content',
        'system_title',
        'system_content',
        'meta_data',
        'image',
        'type',
        'status',
    ];

    protected static $logName = 'administrator_notifications';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} admin notification";
    }
}
