<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

use function PHPSTORM_META\type;

class Inventory extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'type_id',
        'desc',
        'stock',
    ];

    public function category() {
        return $this->belongsTo( Category::class, 'category_id' );
    }

    public function type() {
        return $this->belongsTo( type::class, 'type_id' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'name',
        'price',
        'category',
        'type',
        'desc',
        'stock',
    ];

    protected static $logName = '';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} ";
    }
}
