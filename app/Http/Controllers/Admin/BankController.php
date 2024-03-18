<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    BankService,
};

class BankController extends Controller
{
    public function index() {

    }

    public function add() {

    }

    public function edit() {

    }

    public function allBanks( Request $request ) {

        return BankService::allBanks( $request );
    }

    public function oneBank( Request $request ) {

        return BankService::oneBank( $request );
    }

    public function createBank( Request $request ) {

        return BankService::createBank( $request );
    }

    public function updateBank( Request $request ) {

        return BankService::updateBank( $request );
    }

    public function updateBankStatus( Request $request ) {

        return BankService::updateBankStatus( $request );
    }
}
