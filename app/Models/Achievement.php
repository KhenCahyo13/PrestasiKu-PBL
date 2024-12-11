<?php

namespace App\Models;

use PDO;
use App\Models\Model;
use Exception;
use PDOException;

class Achievement extends Model
{
    protected string $table = "Achievement.Achievements";
    protected string $primaryKey = "achievement_id";

    public function getTotalCount(string $search = ''): int {
        $role = $_SESSION['user']['role'];
        $userId = $_SESSION['user']['id'];
        $query = '';

        if ($role == 'Student') {
            $query = "SELECT COUNT(Achievement.Achievements.user_id) AS Total
                    FROM Achievement.Achievements
                    WHERE Achievement.Achievements.user_id = :userId AND 
                    Achievement.Achievements.achievement_title LIKE :search
            ";
        } else if ($role == 'Admin' || $role == 'Lecturer') {
            $query = "SELECT COUNT(Achievement.AchievementApprovers.user_id) AS Total
                    FROM Achievement.AchievementApprovers
                    INNER JOIN Achievement.Achievements 
                    ON Achievement.AchievementApprovers.achievement_id = Achievement.Achievements.achievement_id
                    WHERE Achievement.AchievementApprovers.user_id = :userId AND 
                    Achievement.Achievements.achievement_title LIKE :search
            ";
        }

        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);

        $stmt->execute();
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['Total'];
    }

    public function getAll(int $limit = 10, int $offset = 0, string $search = '', ): array {
        $role = $_SESSION['user']['role'];
        $userId = $_SESSION['user']['id'];

        if ($role == 'Student') {
            $query = "SELECT Achievement.Achievements.*, Achievement.AchievementVerifications.*
                    FROM Achievement.Achievements 
                    INNER JOIN Achievement.AchievementVerifications ON Achievement.Achievements.achievement_id = Achievement.AchievementVerifications.achievement_id 
                    WHERE Achievement.Achievements.user_id = :userId AND 
                    Achievement.Achievements.achievement_title LIKE :search
                    ORDER BY Achievement.Achievements.achievement_id DESC 
                    OFFSET :offset ROWS 
                    FETCH NEXT :limit ROWS ONLY
            ";    
        } else if ($role == 'Admin' || $role == 'Lecturer') {
            $query = "SELECT Achievement.Achievements.*, Achievement.AchievementApprovers.*, Achievement.AchievementVerifications.*
                    FROM Achievement.Achievements 
                    INNER JOIN Achievement.AchievementApprovers ON Achievement.Achievements.achievement_id = Achievement.AchievementApprovers.achievement_id 
                    INNER JOIN Achievement.AchievementVerifications ON Achievement.Achievements.achievement_id = Achievement.AchievementVerifications.achievement_id 
                    WHERE Achievement.AchievementApprovers.user_id = :userId AND 
                    Achievement.Achievements.achievement_title LIKE :search
                    ORDER BY Achievement.Achievements.achievement_id DESC 
                    OFFSET :offset ROWS 
                    FETCH NEXT :limit ROWS ONLY
            ";
        }

        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(string $achievementId): array | null {
        $query = 'EXEC CRUD.SelectTableDataByColumnWithJoins
            @TableName = :tableName,
            @TableColumns = :tableColumns,
            @ColumnName = :columnName,
            @ColumnValue = :columnValue,
            @JoinConditions = :joinConditions
        ';
        $stmt = $this->getDbConnection()->prepare($query);

        $tableColumns = '
            Achievement.Achievements.*, 
            Achievement.AchievementVerifications.*, 
            Achievement.AchievementApprovers.*, 
            Achievement.AchievementFiles.*, 
            Achievement.AchievementCategoryDetails.*, 
            Achievement.AchievementCategories.*,
            StudentUsers.user_id AS student_user_id, 
            Master.UserStudentDetails.detail_name AS student_name, Master.UserStudentDetails.detail_nim AS student_nim, Master.UserStudentDetails.detail_email AS student_email, Master.UserStudentDetails.detail_phonenumber AS student_phonenumber, 
            ApproverUsers.user_id AS approver_user_id, ApproverUsers.user_username AS approver_username,
            Master.UserLecturerDetails.detail_name AS lecturer_name, Master.UserLecturerDetails.detail_nip AS lecturer_nip, Master.UserLecturerDetails.detail_email AS lecturer_email, Master.UserLecturerDetails.detail_phonenumber AS lecturer_phonenumber
        ';    
    
        $joinConditions = 'INNER JOIN Achievement.AchievementVerifications ON Achievement.Achievements.achievement_id = Achievement.AchievementVerifications.achievement_id
                        INNER JOIN Achievement.AchievementApprovers ON Achievement.AchievementApprovers.achievement_id = Achievement.Achievements.achievement_id
                        INNER JOIN Achievement.AchievementFiles ON Achievement.AchievementFiles.achievement_id = Achievement.Achievements.achievement_id
                        INNER JOIN Achievement.AchievementCategoryDetails ON Achievement.AchievementCategoryDetails.achievement_id = Achievement.Achievements.achievement_id
                        INNER JOIN Achievement.AchievementCategories ON Achievement.AchievementCategories.category_id = Achievement.AchievementCategoryDetails.category_id
                        INNER JOIN Master.Users AS StudentUsers ON StudentUsers.user_id = Achievement.Achievements.user_id
                        LEFT JOIN Master.Users AS ApproverUsers ON ApproverUsers.user_id = Achievement.AchievementApprovers.user_id
                        LEFT JOIN Master.UserStudentDetails ON Master.UserStudentDetails.detail_id = StudentUsers.details_student_id
                        LEFT JOIN Master.UserLecturerDetails ON Master.UserLecturerDetails.detail_id = ApproverUsers.details_lecturer_id
        ';    

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindValue(':tableColumns', $tableColumns, PDO::PARAM_STR);
        $stmt->bindValue(':columnName', 'Achievement.Achievements.achievement_id', PDO::PARAM_STR);
        $stmt->bindValue(':columnValue', $achievementId, PDO::PARAM_STR);
        $stmt->bindValue(':joinConditions', $joinConditions, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results ?: null;
    }

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

    public function getTotalBasedOnScope(): array {
        try {
            $query = 'SELECT * FROM Metadata.TotalAchievementsBasedOnScope';

            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getTotalPerMonthInOneYear($year): array {
        try {
            $query = 'EXEC Metadata.GetTotalAchievementsPerMonthInOneYear @Year = :year';
            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->bindParam(':year', $year, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getTotalBasedOnVerificationStatus(): array {
        try {
            $role = $_SESSION['user']['role'];
            $userId = $_SESSION['user']['id'];

            $query = '';

            if ($role == 'Student') {
                $query = 'EXEC Metadata.GetTotalAchievementsStudentBasedOnVerificationStatus @UserId = :userId';
            } else if ($role == 'Admin' || $role == 'Lecturer') {
                $query = 'EXEC Metadata.GetTotalAchievementsAdminLecturerBasedOnVerificationStatus @UserId = :userId';
            }
            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getTop10ByStudent(): array {
        try {
            $query = 'SELECT * FROM Metadata.Top10TotalAchievementsRankingByStudent';

            $stmt = $this->getDbConnection()->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
