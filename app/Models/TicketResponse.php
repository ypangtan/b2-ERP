<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

class TicketResponse extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'admin_id',
        'ticket_id',
        'title',
        'content',
        'image',
        'status',
    ];

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function admin() {
        return $this->belongsTo( Administrator::class, 'admin_id' );
    }

    public function ticket() {
        return $this->belongsTo( SupportTicket::class, 'ticket_id' );
    }

    public function getPathAttribute() {
        return $this->attributes['image'] ? asset( 'storage/' . $this->attributes['image'] ) : null;
    }

    public function getDisplayStatusAttribute() {

        $status = [
            '1' => __( 'datatables.pending' ),
            '10' => __( 'datatables.active' ),
            '20' => __( 'datatables.inactive' ),
        ];

        return $status[ $this->attributes['status'] ];
    }

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate(DateTimeInterface $date) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }

    protected static $logAttributes = [
        'user_id',
        'admin_id',
        'ticket_id',
        'title',
        'content',
        'image',
        'status',
    ];

    protected static $logName = 'ticket_responses';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} ticket response";
    }
}
