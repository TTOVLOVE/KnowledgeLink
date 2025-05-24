<?php
class Database {
    private static $instance = null;
    private $connection;

    private $dbhost = 'localhost';
    private $dbuser = 'moon';
    private $dbpass = 'root123';
    private $dbname = 'knowledge_sharing_platform';

    private function __construct() {
        $this->connection = new mysqli(
            $this->dbhost,
            $this->dbuser,
            $this->dbpass,
            $this->dbname
        );
        if ($this->connection->connect_error) {
            die("连接失败: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
