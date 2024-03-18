<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    PackageService,
};

class PackageController extends Controller
{
    public function getOptions( Request $request ) {

        return PackageService::getOptions( $request );
    }
}
