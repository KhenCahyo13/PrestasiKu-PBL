<?php
    namespace App\Models;

    use PDO;
    use App\Models\Model;

    class Department extends Model {
        protected $table = "Master.Departments";
        protected $primaryKey = "department_id";
        public function getAll(): array {
            $sql = "SELECT * FROM $this->table";
            $stmt = $this->getDbConnection()->query($sql);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function create(array $data): bool {
            $sql = "INSERT INTO $this->table (department_name) VALUES (:department_name)";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':department_name', $data['departement_name']);
    
            return $stmt->execute();
        }

        public function update(array $data): bool {
            $sql = "UPDATE $this->table SET department_name = :department_name WHERE department_id = :department_id";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':department_name', $data['department_name']);
            $stmt->bindParam(':department_id', $data['department_id']);
    
            return $stmt->execute();
        }

        public function delete(string $departmentId): bool {
            $sql = "DELETE FROM $this->table WHERE department_id = :department_id";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':department_id', $departmentId);
    
            return $stmt->execute();
        }
    }
?>