<?php

namespace App\Queue;

use App\Model\UserModel;
use Lib\Queue\Queue;

class Login extends Queue {

    public function run() {
        $users = new UserModel();
        $info = $users->userLogin($this->data['id']);

        return $info;
    }
}