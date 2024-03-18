<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    MissionHistoryService,
};

class MissionHistoryController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.mission_histories' );
        $this->data['content'] = 'admin.mission_history.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.mission_histories' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.mission_histories' ),
        ];

        return view( 'admin.main' )->with( $this->data );
    }

    public function allMissionHistories( Request $request ) {

        return MissionHistoryService::allMissionHistories( $request );
    }
}
