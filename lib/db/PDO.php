<?php

namespace Lib\Db;


class PDO extends \PDO {

    public function __construct($host, $db, $user, $pass, $port = 3306) {
        $dsn ="mysql:dbname={$db};host={$host};port={$port};";

        $options = array(
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
            PDO::ATTR_TIMEOUT => 3,
        );

        parent::__construct($dsn, $user, $pass, $options);
    }
} 