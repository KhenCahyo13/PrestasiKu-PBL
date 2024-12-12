<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class AchievementLog extends Model {
    protected string $table = "Achievement.AchievementLogs";
    protected string $primaryKey = "log_id";

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

    public function getByAchievementId(string $achievementId): array {
        $query = 'EXEC CRUD.SelectTableDataByColumn @TableName = :tableName, @ColumnName = :columnName, @Value = :value';
        $stmt = $this->getDbConnection()->prepare($query);

        $columnName = 'achievement_id';

        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindParam(':columnName', $columnName, PDO::PARAM_STR);
        $stmt->bindParam(':value', $achievementId, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result === false) {
            return array();
        }

        return $result;
    }
}