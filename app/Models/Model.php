<?php
    namespace App\Models;

    use App\Config\Database;
    use PDO;

    abstract class Model {
        protected string $table;
        protected string $primaryKey;
        private PDO $db;
    
        public function __construct() {
            $database = new Database();
            $this->db = $database->getConnection();
        }
    
        public function getDbConnection(): PDO {
            return $this->db;
        }
    }
?>