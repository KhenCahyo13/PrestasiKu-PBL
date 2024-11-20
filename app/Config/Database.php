<?php
    namespace App\Config;

    use PDO;
    use PDOException;

    class Database {
        private string $dbHost = "localhost"; 
        private string $dbName = "PrestasiKu";
        private string $dbUsername = "SA"; 
        private string $dbPassword = "Khencahyo@130402";
        private PDO $dbConnection;

        public function __construct() {
            try {
                $dsn = "sqlsrv:server=$this->dbHost;Database=$this->dbName";
                $this->dbConnection = new PDO($dsn, $this->dbUsername, $this->dbPassword);
                $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        public function getConnection(): PDO {
            return $this->dbConnection;
        }

        public function close(): void {
            $this->dbConnection = null;
        }
    }
?>