<?php

namespace App\Model;

use Lib\Model\Model;

class UserModel extends Model {

    protected $table = 'users';

    public function getUserById($id) {
        $query = array(
            'id' => $id,
        );
        return $this->getAllByCondition($query);
    }
} 