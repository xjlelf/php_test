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

    public function save($data) {
        $fields_arr = array_keys($data);
        $fields_str = implode(',', $fields_arr);

        foreach ($fields_arr as &$v) {
            $v = ':' . $v;
        }
        $fields_value_arr = implode(',', $fields_arr);

        $sql = "INSERT INTO {$this->table} ({$fields_str}) VALUES ({$fields_value_arr})";
        $rs = $this->db->write($sql, $data);
        return $rs;
    }

    public function update($data, $where) {
        $fields_arr = array_keys($data);
        foreach ($fields_arr as &$v) {
            $v = '`' . $v . '` = :' . $v;
        }
        $fields_str = implode(',', $fields_arr);

        $fields_where_str = $this->getCondition($where);

        $sql = "UPDATE {$this->table} SET {$fields_str} WHERE {$fields_where_str}";

        $exec_data = array_merge($data, $where);
        return $this->db->write($sql, $exec_data);
    }
} 