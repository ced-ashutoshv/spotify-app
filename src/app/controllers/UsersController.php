<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

class UsersController extends Controller {
    public function indexAction() {
        $request = new Request();
        $user_id = str_replace( '/users/', '', $request->get('_url') );
        
        $this->view->user_id = $user_id;
    }
}