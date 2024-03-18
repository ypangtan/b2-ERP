<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\MController;
use Illuminate\Http\Request;

use App\Services\{
    BankService,
};

class BankController extends MController
{

    public function getActiveBank( Request $request ) {

        return BankService::getActiveBank( $request );
    }
}
