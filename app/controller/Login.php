<?php

namespace App\Controller;


use App\Model\UserModel;
use Lib\Controller\Controller;

class Login extends Controller {

    private $data;

    public function run() {
        $this->_init();

        $users = new UserModel();
        $info = $users->userLogin($this->data['id']);

        $this->echoView($info);
    }

    public function _init() {
        $this->data['id'] = $this->request['REQUEST']['id'];
        return true;
    }
}