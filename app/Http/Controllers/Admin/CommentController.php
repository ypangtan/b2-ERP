<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\{
    CommentService,
};

class CommentController extends Controller
{
    public function index() {
        $this->data['header']['title'] = __( 'template.comments' );
        $this->data['content'] = 'admin.comment.index';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.comments' ),
            'title' => __( 'template.list' ),
            'mobile_title' => __( 'template.comments' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = CommentService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = CommentService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function add() {
        $this->data['header']['title'] = __( 'template.comments' );
        $this->data['content'] = 'admin.comment.add';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.comments' ),
            'title' => __( 'template.create' ),
            'mobile_title' => __( 'template.comments' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = CommentService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = CommentService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function edit() {
        $this->data['header']['title'] = __( 'template.comments' );
        $this->data['content'] = 'admin.comment.edit';
        $this->data['breadcrumbs'] = [
            'enabled' => true,
            'main_title' => __( 'template.comments' ),
            'title' => __( 'template.edit' ),
            'mobile_title' => __( 'template.comments' ),
        ];

        $this->data['data']['inventories'] = [];
        $this->data['data']['customers'] = [];
        $customers = CommentService::Customers();
        foreach ( $customers as $customer ) {
            $this->data['data']['customers'][] = [ 'key' => $customer->encrypted_id, 'value' => $customer->encrypted_id, 'title' => $customer->name ];
        }

        $inventories = CommentService::Inventories();
        foreach ( $inventories as $inventory ) {
            $this->data['data']['inventories'][] = [ 'key' => $inventory->encrypted_id, 'value' => $inventory->encrypted_id, 'title' => $inventory->name ];
        }

        return view( 'admin.main' )->with( $this->data );
    }

    public function allComments( Request $request ) {

        return CommentService::allComments( $request );
    }

    public function oneComment( Request $request ) {

        return CommentService::oneComment( $request );
    }

    public function createComment( Request $request ) {

        return CommentService::createComment( $request );
    }

    public function updateComment( Request $request ) {

        return CommentService::updateComment( $request );
    }

    public function deleteComment( Request $request ) {

        return CommentService::deleteComment( $request );
    }
}
