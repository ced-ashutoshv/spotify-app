<?php

use Phalcon\Mvc\Controller;

class ConfigController extends Controller{

    public function indexAction(){
    }

    public function showAction(){
        $config                   = $this->di->get('config');
        $this->view->appName      = $config->get('app')->get('name');
        $this->view->dbKeys       = $config->get('db');
    }
}