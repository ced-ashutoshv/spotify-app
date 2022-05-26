<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Loader;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Query;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Adapter\Files;

class LoginController extends Controller {

    private $escaper;

    public function indexAction() {
    }
    
    public function validateAction() {

        // Register some classes.
        // require_once APP_PATH . '/components/myescaper.php';

        $request = new Request();
        if ( true === $request->isPost() ) {
            
            $formdata = $request->get('formdata');
            $escaper = new My_Escaper();

            foreach ( $formdata as $key => $value ) {
                $formdata[$key] = $escaper->sanitize( $value );
            }

            if ( empty( $formdata ) ) {
                $this->failResponse();
            } else {
                $formdata['password'] = $formdata['pass'];
                unset( $formdata['pass'] );

                // Instantiate the Query.
                $query = new Query( 
                    "SELECT * FROM Users WHERE email = '" . $formdata['email'] . "' AND password = '" . $formdata['password'] . "'", 
                    $this->getDI() 
                );
                
                // Execute the query returning a result if any.
                $users = $query->execute(); 
                foreach ( $users as $key => $user) {
                    break;
                }

                if ( empty( $user ) ) {
                    $this->failResponse();
                } else {
                    $user_id = $user->id;
                    $this->session->set( 'userId', $user_id );
                    setcookie('remember_me', $user_id, time() + (86400 * 30)); // 86400 = 1 days
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

                $escaper = new My_Escaper();

                foreach ( $formdata as $key => $value ) {
                    $formdata[$key] = $escaper->sanitize( $value );
                }
   
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
                $this->session->set( 'userId', $user_id );

            }

        } else {
            $this->failResponse();
        }
    }

    public function failResponse() {
        // Getting a response instance
        $response = new Response();

        $content = sprintf( 'Sorry, the user doesn\'t exist. Please check your credentials again. <a href="/login">Go Back to Login</a>' );
        $response->setStatusCode(403, 'Authorization failed');
        $response->setContent($content);
        $response->send();
    }
}