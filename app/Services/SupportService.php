<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    User,
    SupportTicket,
    TicketResponse,
};

use Helper;

use Carbon\Carbon;

class SupportService {

    public static function oneSupportTicket( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $supportTicket = SupportTicket::with( [
            'user',
            'ticketResponses',
            'ticketResponses.user',
            'ticketResponses.admin'
        ] )->find( $request->id );

        if ( $supportTicket ) {
            $supportTicket->append( [
                'encrypted_id',
            ] );
        }

        return $supportTicket;
    }
    
    public static function userSupportTickets( $request ) {

        $userTickets = SupportTicket::with( [
            'user',
            'ticketResponses',
            'ticketResponses.admin'
        ] )
        ->when( !auth()->user()->hasrole('super_admin') && !auth()->user()->hasrole('admin') , function ( $query ) {
            $query->where( 'user_id', auth()->user()->id );
        })
        ->orderBy( 'created_at', 'DESC' ) 
        ->get();

        if ( $userTickets ) {
            $userTickets->append( [
                'encrypted_id',
            ] );
        }

        return $userTickets;
    }

    public static function createSupportTicketResponse( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'content' => [ 'required' ],
        ] );

        $attributeName = [
            'content' => __( 'support.content' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();    

        try {

            $supportTicket = SupportTicket::find( $request->id );

            if ( $supportTicket ) {

                $user = !auth()->user()->hasrole('user') ? $supportTicket->user_id : auth()->user()->id;
                $admin = !auth()->user()->hasrole('user') ? auth()->user()->id : null;
            
                $createNewTicketResponseAttributes = [
                    'user_id' => $user,
                    'admin_id' => $admin,
                    'ticket_id' => $supportTicket->id,
                    'title' => $supportTicket->title,
                    'content' => $request->content,
                ];

                $createTicketResponse = TicketResponse::create( $createNewTicketResponseAttributes );

            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.ticket_responses' ) ) ] ),
        ] );
    }

    public static function createSupportTicket( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'title' => [ 'required' ],
            'content' => [ 'required' ],
        ] );

        $attributeName = [
            'title' => __( 'support.title' ),
            'content' => __( 'support.content' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $user = auth()->user()->id;
        $admin = !auth()->user()->hasrole('user') ? auth()->user()->id : null;

        $createSupportTicketAttributes = [
            'user_id' => $user,
            'admin_id' => $admin,
            'title' => $request->title,
            'content' => $request->content,
            'ticket_reference' => 'JDG-' . Str::random( 8 ),
        ];

        $validator->setAttributeNames( $attributeName )->validate();    

        try {

            $createSupportTicket = SupportTicket::create( $createSupportTicketAttributes );
            
            if( $createSupportTicket ){
                
                $createTicketResponsesAttributes = [
                    'user_id' => $user,
                    'admin_id' => $admin,
                    'ticket_id' => $createSupportTicket->id,
                    'title' => $request->title,
                    'content' => $request->content,
                ];
                
            }

            $createTicketResponse = TicketResponse::create( $createTicketResponsesAttributes );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.support_tickets' ) ) ] ),
            'ticketId' => $createSupportTicket->encrypted_id
        ] );
    }
    
}
