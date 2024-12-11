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
        $query = "INSERT INTO $this->table (
                achievement_id, 
                user_id, 
                achievement_title, 
                achievement_description, 
                achievement_type, 
                achievement_scope, 
                achievement_eventlocation, 
                achievement_eventcity, 
                achievement_eventstart, 
                achievement_eventend
        ) VALUES (
                :achievement_id, 
                :user_id, 
                :achievement_title, 
                :achievement_description, 
                :achievement_type, 
                :achievement_scope, 
                :achievement_eventlocation, 
                :achievement_eventcity, 
                :achievement_eventstart, 
                :achievement_eventend
        )";
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':achievement_id', $data['achievement_id'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_title', $data['achievement_title'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_description', $data['achievement_description'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_type', $data['achievement_type'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_scope', $data['achievement_scope'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_eventlocation', $data['achievement_eventlocation'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_eventcity', $data['achievement_eventcity'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_eventstart', $data['achievement_eventstart'], PDO::PARAM_STR);
        $stmt->bindParam(':achievement_eventend', $data['achievement_eventend'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getTotalBasedOnScope(): array {
        try {
            $query = "WITH PossibleScopes AS (
                        SELECT 'International' AS scope
                        UNION ALL
                        SELECT 'National'
                        UNION ALL
                        SELECT 'Regional'
                    )
                    SELECT  PossibleScopes.scope, COUNT(Achievement.Achievements.achievement_id) AS total FROM PossibleScopes 
                    LEFT JOIN Achievement.Achievements ON PossibleScopes.scope = Achievement.Achievements.achievement_scope
                    GROUP BY PossibleScopes.scope;
            ";

            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getTotalPerMonthInOneYear($year): array {
        try {
            $query = "WITH Calendar AS (
                        SELECT 1 AS month_number, 'January' AS month_name
                        UNION ALL SELECT 2, 'February'
                        UNION ALL SELECT 3, 'March'
                        UNION ALL SELECT 4, 'April'
                        UNION ALL SELECT 5, 'May'
                        UNION ALL SELECT 6, 'June'
                        UNION ALL SELECT 7, 'July'
                        UNION ALL SELECT 8, 'August'
                        UNION ALL SELECT 9, 'September'
                        UNION ALL SELECT 10, 'October'
                        UNION ALL SELECT 11, 'November'
                        UNION ALL SELECT 12, 'December'
                    )
                    SELECT 
                        Calendar.month_name AS month,
                        :yearColumn AS year,
                        COUNT(Achievements.achievement_id) AS total
                    FROM Calendar
                    LEFT JOIN Achievement.Achievements 
                        ON MONTH(Achievements.achievement_createdat) = Calendar.month_number
                        AND YEAR(Achievements.achievement_createdat) = :yearValue
                    GROUP BY Calendar.month_name, Calendar.month_number
                    ORDER BY Calendar.month_number;
            ";
            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->bindParam(':yearColumn', $year, PDO::PARAM_STR);
            $stmt->bindParam(':yearValue', $year, PDO::PARAM_STR);

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
                $query = "SELECT 
                            COUNT(Achievement.Achievements.achievement_id) AS total, 
                            VerificationStatus.status,
                            Achievement.Achievements.user_id
                        FROM 
                            (SELECT 'Menunggu Persetujuan' AS status
                            UNION ALL
                            SELECT 'Disetujui'
                            UNION ALL
                            SELECT 'Ditolak') AS VerificationStatus
                        LEFT JOIN Achievement.AchievementVerifications ON Achievement.AchievementVerifications.verification_status = VerificationStatus.status
                        LEFT JOIN Achievement.Achievements ON Achievement.Achievements.achievement_id = Achievement.AchievementVerifications.achievement_id AND Achievement.Achievements.user_id = :userId
                        GROUP BY VerificationStatus.status, Achievement.Achievements.user_id;
                ";
            } else if ($role == 'Admin' || $role == 'Lecturer') {
                $query = "SELECT 
                            COUNT(Achievement.Achievements.achievement_id) AS total, 
                            VerificationStatus.status,
                            Achievement.AchievementApprovers.user_id
                        FROM 
                            (SELECT 'Menunggu Persetujuan' AS status
                            UNION ALL
                            SELECT 'Disetujui'
                            UNION ALL
                            SELECT 'Ditolak') AS VerificationStatus
                        LEFT JOIN Achievement.AchievementVerifications ON Achievement.AchievementVerifications.verification_status = VerificationStatus.status
                        LEFT JOIN Achievement.Achievements ON Achievement.Achievements.achievement_id = Achievement.AchievementVerifications.achievement_id
                        LEFT JOIN Achievement.AchievementApprovers ON Achievement.AchievementApprovers.achievement_id = Achievement.Achievements.achievement_id AND Achievement.AchievementApprovers.user_id = :userId
                        GROUP BY VerificationStatus.status, Achievement.AchievementApprovers.user_id
                ";
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
            $query = "SELECT TOP 10 
                        Achievement.Achievements.user_id, 
                        COUNT(Achievement.Achievements.achievement_id) AS total,
                        Master.UserStudentDetails.detail_name,
                        Master.StudyPrograms.studyprogram_name,
                        Master.SPClass.spclass_name
                    FROM Achievement.Achievements
                    INNER JOIN Master.Users ON Master.Users.user_id = Achievement.Achievements.user_id
                    INNER JOIN Master.UserStudentDetails ON Master.UserStudentDetails.detail_id = Master.Users.details_student_id
                    INNER JOIN Master.SPClass ON Master.SPClass.spclass_id = Master.UserStudentDetails.spclass_id
                    INNER JOIN Master.StudyPrograms ON Master.StudyPrograms.studyprogram_id = Master.SPClass.studyprogram_id
                    GROUP BY 
                        Achievement.Achievements.user_id,
                        Master.UserStudentDetails.detail_name,
                        Master.StudyPrograms.studyprogram_name,
                        Master.SPClass.spclass_name
                    ORDER BY total DESC
            ";

            $stmt = $this->getDbConnection()->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
