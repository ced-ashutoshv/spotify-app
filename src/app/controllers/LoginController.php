<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Loader;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Query;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

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
                $formdata['password'] = $formdata['pass'];
                unset( $formdata['pass'] );

                // Instantiate the Query 
                $query = new Query( 
                    "SELECT * FROM Users WHERE email = '" . $formdata['email'] . "' AND password = '" . $formdata['password'] . "'", 
                    $this->getDI() 
                );
                
                // Execute the query returning a result if any 
                $users = $query->execute(); 
                foreach ( $users as $key => $user) {
                    break;
                }

                if ( empty( $user ) ) {
                    $this->failResponse();
                } else {
                    $user_id = $user->id;
                    $session = new Manager();
                    $files = new Stream(
                        [
                            'savePath' => '/tmp',
                        ]
                    );
                    $session->setAdapter($files)->start();
                    $session->set( 'userId', $user_id );
                    $this->response->redirect('users/' . $user_id);
                }
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
        
                // // Store and check for errors.
                $success = $user->save();

                // Passing the result to the view.
                $this->view->success = $success;
        
                if ( $success ) {
                    $message = "Thanks for registering!";
                } else {
                    $message = "Sorry, the following problems were generated:<br>" . implode( '<br>', $user->getMessages() );
                }

                // passing a message to the view.
                $this->view->message = $message;
                $this->view->id      = $user->id;
            }
        } else {
            $this->failResponse();
        }
    }

    public function notifyAction() {
    }

    public function failResponse() {
        // Getting a response instance
       $this->response->redirect('login/notify');
    }
}