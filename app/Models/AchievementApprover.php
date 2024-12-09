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
}