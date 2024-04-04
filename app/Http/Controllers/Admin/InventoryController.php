<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    InventoryService,
};

class InventoryController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.inventories' );
        $this->data['content'] = 'admin.inventory.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.inventories' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.inventories' ),
        ];

        $categories = InventoryService::categories();
        foreach ( $categories as $category ) {
            $this->data['data']['categories'][] = [ 'key' => $category->id, 'value' => $category->id, 'title' => $category->name ];
        }

        $types = InventoryService::categories();
        foreach ( $types as $type ) {
            $this->data['data']['types'][] = [ 'key' => $type->id, 'value' => $type->id, 'title' => $type->name ];
        }
        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.inventories' );
        $this->data['content'] = 'admin.inventory.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.inventories' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.inventories' ),
        ];

        $categories = InventoryService::categories();
        foreach ( $categories as $category ) {
            $this->data['data']['categories'][] = [ 'key' => $category->id, 'value' => $category->id, 'title' => $category->name ];
        }

        $types = InventoryService::categories();
        foreach ( $types as $type ) {
            $this->data['data']['types'][] = [ 'key' => $type->id, 'value' => $type->id, 'title' => $type->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.inventories' );
        $this->data['content'] = 'admin.inventory.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.inventories' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.inventories' ),
        ];

        $categories = InventoryService::categories();
        foreach ( $categories as $category ) {
            $this->data['data']['categories'][] = [ 'key' => $category->id, 'value' => $category->id, 'title' => $category->name ];
        }

        $types = InventoryService::categories();
        foreach ( $types as $type ) {
            $this->data['data']['types'][] = [ 'key' => $type->id, 'value' => $type->id, 'title' => $type->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function allInventories( Request $request ) {

        return InventoryService::allInventories( $request );
    }

    public function oneInventory( Request $request ) {

        return InventoryService::oneInventory( $request );
    }

    public function createInventory( Request $request ) {

        return InventoryService::createInventory( $request );
    }

    public function updateInventory( Request $request ) {

        return InventoryService::updateInventory( $request );
    }

    public function deleteInventory( Request $request ) {

        return InventoryService::deleteInventory( $request );
    }
}
