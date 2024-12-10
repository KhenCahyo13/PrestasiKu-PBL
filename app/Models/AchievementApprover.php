<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class AchievementApprover extends Model {
    protected string $table = "Achievement.AchievementApprovers";
    protected string $primaryKey = "approver_id";

    public function create(array $data): bool {
        $query = "INSERT INTO $this->table (
            achievement_id,
            user_id
        ) VALUES (
                :achievement_id, 
                :user_id
        )";
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':achievement_id', $data['achievement_id'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getApproversByAchievementId(string $achievementId): array {
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
}