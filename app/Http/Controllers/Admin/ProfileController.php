<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    ProfileService,
};

class ProfileController extends Controller {

    public function index() {

        $this->data['header']['title'] = __( 'template.profile' );
        $this->data['content'] = 'admin.profile.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.profile' ),
            'title' => __( 'template.profile' ),
            'mobile_title' => __( 'template.profile' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function update( Request $request ){
        return ProfileService::update( $request );
    } 

}