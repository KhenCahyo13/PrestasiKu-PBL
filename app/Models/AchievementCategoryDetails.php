<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class AchievementCategoryDetails extends Model {
    protected string $table = "Achievement.AchievementCategoryDetails";
    protected string $primaryKey = "detail_id";

    public function create(array $data): bool {
        $query = "INSERT INTO $this->table (
            achievement_id,
            category_id
        ) VALUES (
                :achievement_id, 
                :category_id
        )";
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':achievement_id', $data['achievement_id'], PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_STR);

        return $stmt->execute();
    }
}