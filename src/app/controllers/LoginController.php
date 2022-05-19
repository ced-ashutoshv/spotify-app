<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Loader;
use Phalcon\Http\Request;
use Phalcon\Http\Response;


class LoginController extends Controller {
    public function indexAction() {
    }

    public function validateAction() {
        $request = new Request();
        if ( true === $request->isPost() ) {
            $formdata = $request->get('formdata');
            if ( empty( $formdata ) ) {
                $this->failResponse();
            } else {
                echo '<pre>'; print_r( $formdata ); echo '</pre>';
            }
        } else {
            $this->failResponse();
        }
    }

    public function registerAction() {
        $request = new Request();
        if ( true === $request->isPost() ) {
            $formdata = $request->get('formdata');
            if ( empty( $formdata ) ) {
                $this->failResponse();
            } else {
   
                $user = new Users();

                $formdata['password'] = $formdata['pass'];
                unset( $formdata['pass'] );
                unset( $formdata['cpass'] );

                //assign value from the form to $user.
                $user->assign(
                    $formdata,
                    [
                        'name',
                        'email',
                        'password'
                    ]
                );
        
                // // Store and check for errors
                $success = $user->save();

                // Passing the result to the view.
                $this->view->success = $success;
        
                if ( $success ) {
                    $message = "Thanks for registering!";
                } else {
                    $message = "Sorry, the following problems were generated:<br>" . implode( '<br>', $user->getMessages() );
                }

                // passing a message to the view
                $this->view->message = $message;
                $this->view->id = $user->id;
            }
        } else {
            $this->failResponse();
        }
    }

    public function failResponse() {
        // Getting a response instance
        $response = new Response();
        $contents = file_get_contents( APP_PATH . '/views/login/notify.phtml');
        $response->setContent($contents);
        $response->send();
    }
}