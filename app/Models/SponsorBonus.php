<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Helper;

use Carbon\Carbon;

class SponsorBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'from_type_id',
        'from_type',
        'is_free',
        'type',
        'status',
        'date',
        'original_amount',
        'interest_rate',
        'interest_amount',
        'release_amount',
        'release_date',
        'remark',
    ];

    public function getEncryptedIdAttribute() {
        return Helper::encode( $this->attributes['id'] );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->timezone( 'Asia/Kuala_Lumpur' )->format( 'Y-m-d H:i:s' );
    }
}
