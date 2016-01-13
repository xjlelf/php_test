<?php

namespace Lib\Model;

use Lib\Db\Database;

class Model {

    protected $table = NULL;
    protected $database = 'default';

    public $db;

    public function __CONSTRUCT() {
        $this->db = Database::getConn('default');
    }

    public function getAllByCondition($data, $select = '*', $limit = 0, $offset = 2000) {
        $sql = "SELECT {$select} FROM {$this->table} WHERE ";

        $patern = $this->getCondition($data);

        $data['_limit'] = (int)$limit;
        $data['_offset'] = (int)$offset;

        $rs = $this->db->read($sql . $patern . " LIMIT :_limit,:_offset", $data);
        return $rs;
    }

    protected function getCondition($data) {
        $patern = '1=1';
        foreach ($data as $k => $v) {
            $patern .= " AND `{$k}` = :{$k}";
        }
        return $patern;
    }
} 