<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    PackageOrderService,
};

class PackageOrderController extends Controller
{
    public function index() {

        $this->data['header']['title'] = __( 'template.package_orders' );
        $this->data['content'] = 'admin.package_order.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.package_orders' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.package_orders' ),
        ];
        $this->data['data']['packages'] = PackageOrderService::packages();

        return view( 'admin.main' )->with( $this->data );
    }

    public function allPackageOrders( Request $request ) {

        return PackageOrderService::allPackageOrders( $request );
    }
}
