<?php

namespace App\Models;

use PDO;
use App\Models\Model;
use PDOException;

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