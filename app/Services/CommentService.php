<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Hash,
    Validator,
};

use Illuminate\Validation\Rules\Password;

use App\Models\{
    Comment,
    Role as RoleModel
};

use App\Rules\CheckASCIICharacter;

use PragmaRX\Google2FAQRCode\Google2FA;

use Helper;

use Carbon\Carbon;

class CommentService {

    public static function createComment( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
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

            $createAdmin = Comment::create( [
                'customer_id' => $request->customer_id ,
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

}