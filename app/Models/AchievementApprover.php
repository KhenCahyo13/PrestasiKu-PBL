<?php

namespace App\Models;

use PDO;
use App\Models\Model;
use PDOException;

class AchievementApprover extends Model {
    protected string $table = "Achievement.AchievementApprovers";
    protected string $primaryKey = "approver_id";

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
        $query = 'EXEC CRUD.SelectTableDataByColumnWithJoins
            @TableName = :tableName,
            @TableColumns = :tableColumns,
            @ColumnName = :columnName,
            @ColumnValue = :columnValue,
            @JoinConditions = :joinConditions
        ';
        $stmt = $this->getDbConnection()->prepare($query);

        $tableColumns = 'Achievement.AchievementApprovers.*, 
                        Master.Users.user_id, Master.Users.user_username, 
                        Master.UserLecturerDetails.detail_name AS lecturer_name, Master.UserLecturerDetails.detail_nip AS lecturer_nip'
        ;
        $joinConditions = 'INNER JOIN Master.Users ON Master.Users.user_id = Achievement.AchievementApprovers.user_id
                        LEFT JOIN Master.UserLecturerDetails ON Master.UserLecturerDetails.detail_id = Master.Users.details_lecturer_id'
        ;

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindValue(':tableColumns', $tableColumns, PDO::PARAM_STR);
        $stmt->bindValue(':columnName', 'Achievement.AchievementApprovers.achievement_id', PDO::PARAM_STR);
        $stmt->bindValue(':columnValue', $achievementId, PDO::PARAM_STR);
        $stmt->bindValue(':joinConditions', $joinConditions, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results ?: null;
    }

    public function update(array $data): bool {
        try {
            $sql = "UPDATE $this->table SET approver_isdone = :approver_isdone WHERE approver_id = :approver_id";
            $stmt = $this->getDbConnection()->prepare($sql);

            $stmt->bindParam(':approver_isdone', $data['approver_isdone'], PDO::PARAM_INT);
            $stmt->bindParam(':approver_id', $data['approver_id'], PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Failed to update achievement approver: " . $e->getMessage());
            return false;
        }
    }
}