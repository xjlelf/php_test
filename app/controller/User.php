<?php

namespace App\Controller;

use App\Model\UserModel;
use Lib\Controller\Controller;
use Lib\Db\Memcache;

class User extends Controller {

    private $data;

    public function run() {
        $this->_init();

        $mem = Memcache::getinstance();
        $user_info = $mem->get('user_' . $this->data['id']);
        if (empty($user_info)) {
            $users = new UserModel();
            $user_info = $users->getUserById($this->data['id']);
            $mem->set('user_' . $this->data['id'], $user_info, 0, 60);
        }

        $this->echoView($user_info);
    }

    public function _init() {
        $this->data['id'] = $this->request['REQUEST']['id'];
        return true;
    }
}