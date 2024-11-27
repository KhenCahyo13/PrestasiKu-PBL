<?php

namespace App\Config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
    private string $dbHost;
    private string $dbName;
    private string $dbUsername;
    private string $dbPassword;
    private PDO $dbConnection;


    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->dbHost = $_ENV['DB_HOST'];
        $this->dbName = $_ENV['DB_NAME'];
        $this->dbUsername = $_ENV['DB_USERNAME'];
        $this->dbPassword = $_ENV['DB_PASSWORD'];

        try {
            $dsn = "sqlsrv:server=$this->dbHost;Database=$this->dbName";
            $this->dbConnection = new PDO($dsn, $this->dbUsername, $this->dbPassword);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
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
