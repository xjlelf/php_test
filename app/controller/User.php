<?php

namespace App\Controller;

use Lib\Controller\Controller;
use App\Model\Users;

class User extends Controller {

    public function run() {
        $users = new Users();
        pr($users->getInfo());
    }
}