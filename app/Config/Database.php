<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private string $dbHost;
    private string $dbName;
    private string $dbUsername;
    private string $dbPassword;
    private PDO $dbConnection;

        public function __construct() {
            $this->dbHost = 'localhost';
            $this->dbName = 'PrestasiKu';
            $this->dbUsername = 'SA';
            $this->dbPassword = 'Khencahyo@130402';

            try {
                $dsn = "sqlsrv:server=$this->dbHost;Database=$this->dbName";
                $this->dbConnection = new PDO($dsn, $this->dbUsername, $this->dbPassword);
                $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                 echo "Connection failed: " . $e->getMessage();
            }
        }

    public function getConnection(): PDO
    {
        return $this->dbConnection;
    }

    public function close(): void
    {
        $this->dbConnection = null;
    }
}
