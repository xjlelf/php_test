<?php

namespace App\Controller;

use App\Model\UserModel;
use Lib\Controller\Controller;

class User extends Controller {

    public function run() {
        $users = new UserModel();
        $user_info = $users->getUserById(1);
        pr($user_info);die();
    }
}