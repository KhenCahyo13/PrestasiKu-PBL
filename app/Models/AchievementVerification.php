<?php

namespace App\Models;

use PDO;
use App\Models\Model;
use PDOException;

class AchievementVerification extends Model {
    protected string $table = "Achievement.AchievementVerifications";
    protected string $primaryKey = "verification_id";

    public function create(array $data): bool {
        $query = 'EXEC CRUD.InsertTableData @TableName = :tableName, @Columns = :columns, @Values = :values';
        $stmt = $this->getDbConnection()->prepare($query);
        
        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        
        $columns = implode(',', array_keys($data));
        $stmt->bindParam(':columns', $columns, PDO::PARAM_STR);
    
        $values = array_map(function($value) {
            return is_string($value) ? "'" . addslashes($value) . "'" : $value;
        }, array_values($data));
        $values = implode(',', $values);
        $stmt->bindParam(':values', $values, PDO::PARAM_STR);
    
        return $stmt->execute();
    }

    public function update(array $data): bool {
        try {
            $sql = "UPDATE $this->table SET verification_code = :verification_code, verification_status = :verification_status, verification_isdone = :verification_isdone, verification_notes = :verification_notes WHERE verification_id = :verification_id";
            $stmt = $this->getDbConnection()->prepare($sql);

            $stmt->bindParam(':verification_code', $data['verification_code'], PDO::PARAM_STR);
            $stmt->bindParam(':verification_status', $data['verification_status'], PDO::PARAM_STR);
            $stmt->bindParam(':verification_isdone', $data['verification_isdone'], PDO::PARAM_INT);
            $stmt->bindParam(':verification_notes', $data['verification_notes'], PDO::PARAM_STR);
            $stmt->bindParam(':verification_id', $data['verification_id'], PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update achievement verification: " . $e->getMessage());
            return false;
        }
    }
}