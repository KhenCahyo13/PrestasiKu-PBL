<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class Department extends Model
{
    protected string $table = "Master.Departments";
    protected string $primaryKey = "department_id";

    public function getAll(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM $this->table ORDER BY $this->primaryKey OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->bindValue(1, $offset, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(string $id): array
    {
        $sql = "SELECT * FROM $this->table WHERE department_id = :id";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return [];
        }

        return $result;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO $this->table (department_name) VALUES (:department_name)";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->bindParam(':department_name', $data['department_name']);

        return $stmt->execute();
    }

    public function update(array $data): bool
    {
        try {
            $sql = "UPDATE $this->table SET department_name = :department_name WHERE department_id = :department_id";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':department_name', $data['department_name']);
            $stmt->bindParam(':department_id', $data['department_id']);

            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("Failed to update department: " . $e->getMessage());
            return false;
        }
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM $this->table WHERE department_id = :department_id";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->bindParam(':department_id', $id);

        return $stmt->execute();
    }
}
