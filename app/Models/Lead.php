<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

use Carbon\Carbon;

class Lead extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'inventory_id',
        'user_id',
        'status',
    ];

    public function customers(){
        return $this->belongsTo( Customer::class, 'customer_id' );
    }

    public function inventories(){
        return $this->belongsTo( Inventory::class, 'inventory_id' );
    }

    public function users(){
        return $this->belongsTo( Administrator::class, 'user_id' );
    }

    public function enquiries(){
        return $this->hasMany( Enquiry::class, 'lead_id' );
    }

    public function call_backs(){
        return $this->hasMany( Callback::class, 'lead_id' );
    }

    public function sales(){
        return $this->hasMany( Sale::class, 'lead_id' );
    }

    public function complaint(){
        return $this->hasMany( Comment::class, 'lead_id' );
    }

    public function services(){
        return $this->hasMany( Service::class, 'lead_id' );
    }

    public function other(){
        return $this->hasMany( Other::class, 'lead_id' );
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'customer_id',
        'inventory_id',
        'user_id',
        'status',
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
