<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    PackageOrderService,
};

class PackageOrderController extends Controller
{
    public function purchaseMembership( Request $request ) {
        return PackageOrderService::purchaseMembership( $request );
    }
}
