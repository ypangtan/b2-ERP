<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    AnnouncementService,
};

class AnnouncementController extends MController
{
    public function index( Request $request )
    {
        $this->data['header']['title'] = __( 'member.announcements' );
        $this->data['header']['active'] = 'announcements';
        $this->data['content'] = 'client.announcement';

        $this->data['data']['announcements'] = AnnouncementService::getAnnouncements( $request );

        return view( 'client.templates.postlogin-main' )->with( $this->data );
    }

    public function detail( Request $request )
    {
        $this->data['header']['title'] = __( 'member.announcement_detail' );
        $this->data['header']['active'] = 'announcements';
        $this->data['content'] = 'client.announcement_detail';

        $this->data['data']['announcement'] = AnnouncementService::getAnnouncement( $request );

        return view( 'client.templates.postlogin-main' )->with( $this->data );
    }
}
