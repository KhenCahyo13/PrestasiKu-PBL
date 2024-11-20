<?php
    namespace App\Config;

    use PDO;
    use PDOException;

    class Database {
        private $host = "KHOIRUL"; 
        private $dbName = "prestasiku_db";
        private $username = ""; 
        private $password = "";
        private $conn;

        public function __construct() {
            try {
                $dsn = "sqlsrv:server=$this->host;Database=$this->dbName";
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        public function getConnection() {
            return $this->conn;
        }

        public function close() {
            $this->conn = null;
        }
    }
?>