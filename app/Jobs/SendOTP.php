<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOTP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $to, $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $to, $body )
    {
        $this->to = $to;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sid = config( 'services.twilio.sid' );
        $token = config( 'services.twilio.token' );
        $from = config( 'services.twilio.from' );

        $client = new \Twilio\Rest\Client( $sid, $token );

        $client->messages->create(
            $this->to,
            [
                'from' => $from,
                'body' => $this->body,
            ],
        );
    }
}
