<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Helper;

class SupportTicket extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'admin_id',
        'ticket_reference',
        'title',
        'content',
        'ticket_status',
        'status',
    ];

    public function user() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function admin() {
        return $this->belongsTo( Administrator::class, 'admin_id' );
    }

    public function ticketResponses() {
        return $this->hasMany( TicketResponse::class, 'ticket_id', 'id' );
    }

    public function getDisplayStatusAttribute() {

        $status = [
            '1' => __( 'datatables.pending' ),
            '10' => __( 'datatables.active' ),
            '20' => __( 'datatables.inactive' ),
        ];

        return $status[ $this->attributes['status'] ];
    }

    public function getTicketStatusAttribute() {

        $status = [
            '1' => __( 'support_ticket.open' ),
            '10' => __( 'support_ticket.in_progress' ),
            '20' => __( 'support_ticket.closed' ),
        ];

        return $status[ $this->attributes['ticket_status'] ];
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
        'ticket_reference',
        'title',
        'content',
        'ticket_status',
        'status',
    ];

    protected static $logName = 'support_tickets';

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()->logFillable();
    }

    public function getDescriptionForEvent( string $eventName ): string {
        return "{$eventName} support ticket";
    }
}
