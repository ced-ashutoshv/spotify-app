<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

class UsersController extends Controller {
    public function indexAction() {
        $request = new Request();
        $user_id = str_replace( '/users/', '', $request->get('_url') );
        $user_id = str_replace( '/users', '', $request->get('_url') );
        $user_id = $this->session->get( 'userId' ) ? $this->session->get( 'userId' ) : $user_id;
        if( empty( $user_id ) ) {
            $this->response->redirect( 'login/' );
            return;
        }

        $this->view->user_id = $this->session->get( 'userId' ) ?? $user_id;
    }

    public function logoutAction() {
        $this->session->remove( 'userId' );
        $this->response->redirect( 'login/' );
    }
}