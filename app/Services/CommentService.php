<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use Illuminate\Validation\Rules\Password;

use App\Models\{
    Customer,
    Inventory,
    Comment,
    Lead,
};

use Helper;

use Carbon\Carbon;

class CommentService {

    public static function Customers() {

        $customers = Customer::where( 'status', 10 )
            ->get();
        $customers->append( [
            'encrypted_id',
        ] );
        return $customers;
    }

    public static function Inventories() {

        $inventories = Inventory::all();

        $inventories->append( [
            'encrypted_id',
        ] );

        return $inventories;
    }

    public static function allComments( $request ) {

        $comment = Comment::with(
            'leads.inventories',
            'leads.customers',
        )->select( 'comments.*' );

        $filterObject = self::filter( $request, $comment );
        $comment = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $comment->orderBy( 'created_at', $dir );
                    break;
                case 5:
                    $comment->orderBy( 'rating', $dir );
                    break;
            }
        }

        $commentCount = $comment->count();

        $limit = $request->length;
        $offset = $request->start;

        $comments = $comment->skip( $offset )->take( $limit )->get();

        $comments->append( [
            'encrypted_id',
        ] );

        $comment = comment::select(
            DB::raw( 'COUNT(comments.id) as total'
        ) );

        $filterObject = self::filter( $request, $comment );
        $comment = $filterObject['model'];
        $filter = $filterObject['filter'];

        $comment = $comment->first();

        $data = [
            'comments' => $comments,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $commentCount : $comment->total,
            'recordsTotal' => $filter ? comment::count() : $commentCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;

        if ( !empty( $request->created_at ) ) {
            if ( str_contains( $request->created_at, 'to' ) ) {
                $dates = explode( ' to ', $request->created_at );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'comments.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->created_at );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'comments.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->customer ) ) {
            $customer = Helper::decode( $request->customer );
            $model->whereHas('leads', function( $query ) use ( $customer ){
                $query->where( 'leads.customer_id', $customer );
            });
            $filter = true;
        }

        if ( !empty( $request->inventory ) ) {
            $inventory = Helper::decode( $request->inventory );
            $model->whereHas('leads', function( $query ) use ( $inventory ){
                $query->where( 'leads.inventory_id', $inventory );
            });
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneComment( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $comment = comment::with(
            'leads.inventories',
            'leads.customers',
        )->find( $request->id );

        if ( $comment ) {
            $comment->append( [
                'encrypted_id',
            ] );
            $comment->leads->inventories->append( [
                'encrypted_id',
            ] );
            $comment->leads->customers->append( [
                'encrypted_id',
            ] );
        }

        return $comment;
    }

    public static function createComment( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'inventory_id' => Helper::decode( $request->inventory_id ),
            'customer_id' => Helper::decode( $request->customer_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'customer_id' => [ 'required',  'exists:customers,id', function( $attribute, $value, $fail ) use ( $request ){
                $leadOther = Lead::where( 'customer_id', $request->customer_id )
                    ->where( 'user_id', '!=', auth()->user()->id )
                    ->where( function( $query ){
                        $query->orWhere( 'status', 20)
                            ->orWhere( 'status', 30);
                    } )
                    ->first();

                if( $leadOther ){
                    $fail( __( 'sale.invalid_lead' ) );
                }
                
            } ],
            'inventory_id' => [ 'required', 'exists:inventories,id', function ( $attribute, $value, $fail ) use ( $request ){
                
                $leadOther = Lead::where( 'customer_id', $request->customer_id )
                    ->where( 'user_id', auth()->user()->id )
                    ->where( 'inventory_id', '!=', $request->inventory_id )
                    ->where( function ( $query ) {
                        $query->orWhere( 'status', 20)
                            ->orWhere( 'status', 30);
                    } )
                    ->first();

                if( $leadOther ){
                    $fail( __( 'sale.invalid_lead_inventory' ) );
                }
            } ],
            'comment' => [ 'required' ],
            'rating' => [ 'required' ],
        ] );

        $attributeName = [
            'comment' => __( 'comment.comment' ),
            'rating' => __( 'comment.rating' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {

            $lead = Lead::where( 'customer_id', $request->customer_id )
                ->where( 'inventory_id', $request->inventory_id)
                ->where( 'user_id', auth()->user()->id )
                ->orderBy('created_at', 'desc')
                ->first(); 

            if( !$lead ){
                $lead = Lead::create( [
                    'customer_id' => $request->customer_id,
                    'inventory_id' => $request->inventory_id,
                    'user_id' => auth()->user()->id,
                    'status' => '30'
                ] ); 
                
                $customer = Customer::lockForUpdate()
                    ->find( $request->customer_id );
                $customer->status = 20;
                $customer->save(); 
            }

            $createComment = Comment::create( [
                'lead_id' => $lead->id ,
                'comment' => $request->comment ,
                'rating' => $request->rating ,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.comments' ) ) ] ),
        ] );
    }

    public static function updateComment( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
            'inventory_id' => Helper::decode( $request->inventory_id ),
            'customer_id' => Helper::decode( $request->customer_id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'inventory_id' => [ 'required', 'exists:inventories,id' ],
            'customer_id' => [ 'required', 'exists:customers,id' ],
            'comment' => [ 'required' ],
            'rating' => [ 'required' ],
        ] );

        $attributeName = [
            'comment' => __( 'comment.comment' ),
            'rating' => __( 'comment.rating' ),
        ];


        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
        
        try {
            $updatecomment = comment::lockForUpdate()
                ->find( $request->id );
            $lead = Lead::lockForUpdate()
                ->find( $updatecomment->lead_id );
            $lead->inventory_id = $request->inventory_id;
            $updatecomment->comment = $request->comment;
            $updatecomment->rating = $request->rating;
            $lead->save();
            $updatecomment->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.comments' ) ) ] ),
        ] );
    }

    public static function deleteComment( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $deletecomment = comment::find( $request->id )
            ->delete();

        return response()->json( [
            'message' => __( 'template.x_deleted', [ 'title' => Str::singular( __( 'template.comments' ) ) ] ),
        ] );
    }

}