<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class LoginController extends Controller {
    public function indexAction() {

        // Add some local CSS resources
        $this->assets->addCss( BASE_PATH . '/public/css/style.css' );

        // And some local JavaScript resources
        $this->assets->addJs( BASE_PATH . '/public/js/jquery.js' );
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
                echo '<pre>'; print_r( $formdata ); echo '</pre>';
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