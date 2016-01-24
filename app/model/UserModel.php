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

    public function userLogin($id) {
        $user_info = $this->getUserById($id);

        $query = array(
            'id' => $id,
        );

        $login_times = $user_info[0]['login_times'] - 1;
        if ($login_times < 0) {
            return array('msg' => 'fail');
        }

        $save_data = array(
            'login_times' => $user_info[0]['login_times'] - 1
        );
        $this->update($save_data, $query);

        $login = new LoginModel();
        $data = array(
            'admin' => $user_info[0]['name'],
            'time' => date('Y-m-d H:i:s')
        );
        $login->save($data);
        return array('msg' => 'success');
    }
} 