<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class AchievementVerification extends Model {
    protected string $table = "Achievement.AchievementVerifications";
    protected string $primaryKey = "verification_id";

    public function create(array $data): bool {
        $query = "INSERT INTO $this->table (
            achievement_id,
            verification_code,
            verification_status
        ) VALUES (
                :achievement_id, 
                :verification_code, 
                :verification_status
        )";
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':achievement_id', $data['achievement_id'], PDO::PARAM_STR);
        $stmt->bindParam(':verification_code', $data['verification_code'], PDO::PARAM_STR);
        $stmt->bindParam(':verification_status', $data['verification_status'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}