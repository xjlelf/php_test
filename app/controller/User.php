<?php

namespace App\Controller;

use App\Model\UserModel;
use Lib\Controller\Controller;

class User extends Controller {

    private $data;

    public function run() {
        $this->_init();
        $users = new UserModel();
        $user_info = $users->getUserById($this->data['id']);
        $this->echoView($user_info);
    }

    public function _init() {
        $this->data['id'] = $this->request['REQUEST']['id'];
        return true;
    }
}