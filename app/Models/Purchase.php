<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'supplier_id',
        'inventory_id',
        'quantity',
        'remark',
        'price',
    ];

    public function inventories() {
        return $this->belongsTo( Inventory::class, 'inventory_id' );
    }

    public function suppliers() {
        return $this->belongsTo( Supplier::class, 'supplier_id' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'supplier_id',
        'inventory_id',
        'quantity',
        'remark',
        'price',
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
