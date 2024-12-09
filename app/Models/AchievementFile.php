<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class AchievementFile extends Model {
    protected string $table = "Achievement.AchievementFiles";
    protected string $primaryKey = "file_id";

    public function create(array $data): bool {
        $query = "INSERT INTO $this->table (
            achievement_id,
            file_title,
            file_path
        ) VALUES (
                :achievement_id, 
                :file_title, 
                :file_path
        )";
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':achievement_id', $data['achievement_id'], PDO::PARAM_STR);
        $stmt->bindParam(':file_title', $data['file_title'], PDO::PARAM_STR);
        $stmt->bindParam(':file_path', $data['file_path'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}