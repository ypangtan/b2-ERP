<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    FileManagerService,
};

class FileManagerController extends MController
{
    public function upload( Request $request ) {

        return FileManagerService::upload( $request );
    }
}
