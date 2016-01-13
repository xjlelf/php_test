<?php

namespace Lib\Db;


class Database {

    protected $database;
    protected $config;
    protected $connection;
    protected $sql;
    protected $params;

    public static function getConn($database) {
        static $singletons = array();
        !isset($singletons[$database]) && $singletons[$database] = new self($database);
        return $singletons[$database];
    }

    protected function __CONSTRUCT($database) {
        $this->database = $database;
        $this->config = \Lib\Config::load('Mysql')->$database;
    }

    public function read($sql, $params = array()) {
        $this->sql = $sql;
        $this->params = $params;
        $sth = $this->prepare($sql, $params);

        $result = array();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        $sth->closeCursor();
        return $result;
    }

    protected function prepare($sql, $params) {
        $connection = $this->getConnection();

        $sth = $connection->prepare($sql, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => FALSE));
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (strpos($key, '_') === 0) {
                    $sth->bindValue(":{$key}", $value, PDO::PARAM_INT);
                } else {
                    $sth->bindValue(":{$key}", $value, PDO::PARAM_STR);
                }
            }
        }
        $sth->execute();
        return $sth;
    }

    public function getConnection() {
        if (!empty($this->connection)) {
            return $this->connection;
        }

        $conf = $this->config;

        try {
            $this->connection = new PDO($conf['host'], $conf['db'], $conf['user'], $conf['pass'], $conf['port']);
            return $this->connection;
        } catch (\PDOException $e) {
            die('db connect failed');
        }
    }
} 