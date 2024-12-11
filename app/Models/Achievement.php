<?php

namespace App\Models;

use PDO;
use App\Models\Model;

class Achievement extends Model
{
    protected string $table = "Achievement.Achievements";
    protected string $primaryKey = "achievement_id";

    public function create(array $data): bool
    {
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

    public function getPendingAchievementsByApprover(string $userId): array
    {
        try {
            $sql = "SELECT 
                    a.achievement_id,
                    a.achievement_title,
                    a.achievement_description,
                    a.achievement_type,
                    v.verification_status,
                    v.verification_notes,
                    v.verification_updatedat
                FROM Achievement.AchievementApprovers aa
                JOIN Achievement.Achievements a ON aa.achievement_id = a.achievement_id
                JOIN Achievement.AchievementVerifications v ON a.achievement_id = v.achievement_id
                WHERE aa.user_id = :user_id 
                  AND v.verification_status = 'Pending'";

            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function getApprovedAchievementsByApprover(string $userId): array
    {
        try {
            $sql = "SELECT 
                    a.achievement_id,
                    a.achievement_title,
                    a.achievement_description,
                    a.achievement_type,
                    aa.approver_status,
                    v.verification_status,
                    v.verification_notes,
                    v.verification_updatedat
                FROM Achievement.AchievementApprovers aa
                JOIN Achievement.Achievements a ON aa.achievement_id = a.achievement_id
                JOIN Achievement.AchievementVerifications v ON a.achievement_id = v.achievement_id
                WHERE aa.user_id = :user_id 
                  AND aa.approver_is_done = 1";

            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }


    public function processApproveAchievement(array $data): bool
    {
        try {
            $this->getDbConnection()->beginTransaction();

            $sqlUser = "SELECT role_id FROM Master.Users WHERE user_id = :user_id";
            $stmtUser = $this->getDbConnection()->prepare($sqlUser);
            $stmtUser->bindParam(':user_id', $data['user_id']);
            $stmtUser->execute();
            $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new \Exception("User not found.");
            }

            $roleId = $user['role_id'];

            if ($roleId === '6EC386D9-7313-4659-8C7D-B11148750B7A') {
                $sqlCheck = " SELECT COUNT(*) AS pending_lecturers 
                    FROM Achievement.AchievementApprovers 
                    WHERE achievement_id = :achievement_id 
                    AND approver_is_done = 0 
                    AND user_id IN (SELECT user_id FROM Master.Users WHERE role_id = 'FBA2D7AA-4F83-4C48-9C9A-4EB7F8A253F8')";
                $stmtCheck = $this->getDbConnection()->prepare($sqlCheck);
                $stmtCheck->bindParam(':achievement_id', $data['achievement_id']);
                $stmtCheck->execute();
                $checkResult = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($checkResult['pending_lecturers'] > 0) {
                    throw new \Exception("Admin cannot approve before all lecturers finish approval.");
                }
            }

            $sqlRejection = " SELECT COUNT(*) AS rejected_count 
                FROM Achievement.AchievementApprovers 
                WHERE achievement_id = :achievement_id 
                AND approver_status = 'rejected'";
            $stmtRejection = $this->getDbConnection()->prepare($sqlRejection);
            $stmtRejection->bindParam(':achievement_id', $data['achievement_id']);
            $stmtRejection->execute();
            $rejectionResult = $stmtRejection->fetch(PDO::FETCH_ASSOC);

            if ($rejectionResult['rejected_count'] > 0) {
                throw new \Exception("Approval cannot proceed as previous approver has rejected.");
            }

            $sqlUpdate = " UPDATE Achievement.AchievementApprovers 
                SET approver_is_done = 1, approver_updatedat = GETDATE(), approver_status = :status 
                WHERE achievement_id = :achievement_id AND user_id = :user_id";
            $stmtUpdate = $this->getDbConnection()->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':status', $data['action']);
            $stmtUpdate->bindParam(':achievement_id', $data['achievement_id']);
            $stmtUpdate->bindParam(':user_id', $data['user_id']);
            $stmtUpdate->execute();

            $logMessage = $data['action'] === 'approved'
                ? "User {$data['user_id']} approved achievement {$data['achievement_id']}."
                : "User {$data['user_id']} rejected achievement {$data['achievement_id']}.";
            $sqlLog = "INSERT INTO Achievement.AchievementLogs (achievement_id, log_message) 
               VALUES (:achievement_id, :log_message)";
            $stmtLog = $this->getDbConnection()->prepare($sqlLog);
            $stmtLog->bindParam(':achievement_id', $data['achievement_id']);
            $stmtLog->bindParam(':log_message', $logMessage);
            $stmtLog->execute();

            $verificationStatus = $this->determineVerificationStatus($data['achievement_id']);

            $sqlUpdateVerification = "UPDATE Achievement.AchievementVerifications
                               SET verification_code = :verification_code,
                                   verification_status = :verification_status,
                                   verification_is_done = :verification_is_done,
                                   verification_updatedat = GETDATE()
                               WHERE achievement_id = :achievement_id";
            $stmtVerification = $this->getDbConnection()->prepare($sqlUpdateVerification);
            $stmtVerification->bindParam(':verification_code', $verificationStatus['code']);
            $stmtVerification->bindParam(':verification_status', $verificationStatus['status']);
            $stmtVerification->bindParam(':verification_is_done', $verificationStatus['isDone']);
            $stmtVerification->bindParam(':achievement_id', $data['achievement_id']);
            $stmtVerification->execute();

            $this->getDbConnection()->commit();
            return true;
        } catch (\PDOException $e) {
            $this->getDbConnection()->rollBack();
            throw new \Exception("Database error: " . $e->getMessage());
        } catch (\Exception $e) {
            $this->getDbConnection()->rollBack();
            throw $e;
        }
    }

    private function determineVerificationStatus(string $achievementId): array
    {
        $sql = " SELECT 
            COUNT(*) AS total_approvers,
            SUM(CASE WHEN approver_status = 'approved' THEN 1 ELSE 0 END) AS approved_count,
            SUM(CASE WHEN approver_status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count,
            COUNT(*) - SUM(CASE WHEN approver_is_done = 1 THEN 1 ELSE 0 END) AS pending_count
            FROM Achievement.AchievementApprovers
            WHERE achievement_id = :achievement_id";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->bindParam(':achievement_id', $achievementId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalApprovers = $result['total_approvers'];
        $approvedCount = $result['approved_count'];
        $rejectedCount = $result['rejected_count'];
        $pendingCount = $result['pending_count'];

        if ($rejectedCount > 0) {
            return [
                'code' => 'DT',
                'status' => 'Achievement ditolak',
                'isDone' => $pendingCount === 0 ? 1 : 0
            ];
        }

        if ($pendingCount > 0) {
            return [
                'code' => 'DS' . $approvedCount . 'BS' . $pendingCount,
                'status' => "Disetujui $approvedCount approver, menunggu persetujuan $pendingCount approver",
                'isDone' => 0
            ];
        }

        return [
            'code' => 'DS' . $approvedCount . str_repeat(' ', $totalApprovers - $approvedCount),
            'status' => 'Achievement disetujui oleh semua approver',
            'isDone' => 1
        ];
    }



    public function getNotificationsByUserId(string $userId): array
    {
        try {
            $sql = "SELECT 
                    al.log_message,
                    av.verification_status,
                    av.verification_code,
                    av.verification_updatedat
                FROM Achievement.AchievementLogs al
                JOIN Achievement.AchievementVerifications av 
                    ON al.achievement_id = av.achievement_id
                WHERE al.achievement_id IN (
                    SELECT achievement_id 
                    FROM Achievement.Achievements 
                    WHERE user_id = :user_id
                )
                ORDER BY av.verification_updatedat DESC";

            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function rankingAchievementStudent(): array
    {
        try {
            $sql = "SELECT TOP 10
                ds.detail_name AS student_name,
                COUNT(a.achievement_id) AS total_achievements
            FROM Achievement.AchievementApprovers aa
            JOIN Achievement.Achievements a ON aa.achievement_id = a.achievement_id
            JOIN Achievement.AchievementVerifications v ON a.achievement_id = v.achievement_id
            JOIN Master.Users u ON aa.user_id = u.user_id
            JOIN Master.StudentDetailUsers ds ON u.detail_student_id = ds.detail_id
            WHERE v.verification_isdone = 1
            GROUP BY ds.detail_name
            ORDER BY total_achievements DESC";

            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function deleteAchievement(string $achievementId): bool
    {
        try {
            $this->getDbConnection()->beginTransaction();

            $logSql = "DELETE FROM Achievement.AchievementLogs WHERE achievement_id = :achievement_id";
            $stmtLog = $this->getDbConnection()->prepare($logSql);
            $stmtLog->bindParam(':achievement_id', $achievementId);
            $stmtLog->execute();

            $fileSql = "DELETE FROM Achievement.AchievementFiles WHERE achievement_id = :achievement_id";
            $stmtFile = $this->getDbConnection()->prepare($fileSql);
            $stmtFile->bindParam(':achievement_id', $achievementId);
            $stmtFile->execute();

            $categorySql = "DELETE FROM Achievement.AchievementCategoryDetails WHERE achievement_id = :achievement_id";
            $stmtCategory = $this->getDbConnection()->prepare($categorySql);
            $stmtCategory->bindParam(':achievement_id', $achievementId);
            $stmtCategory->execute();

            $approverSql = "DELETE FROM Achievement.AchievementApprovers WHERE achievement_id = :achievement_id";
            $stmtApprover = $this->getDbConnection()->prepare($approverSql);
            $stmtApprover->bindParam(':achievement_id', $achievementId);
            $stmtApprover->execute();
            $verificationSql = "DELETE FROM Achievement.AchievementVerifications WHERE achievement_id = :achievement_id";
            $stmtVerification = $this->getDbConnection()->prepare($verificationSql);
            $stmtVerification->bindParam(':achievement_id', $achievementId);
            $stmtVerification->execute();

            $achievementSql = "DELETE FROM Achievement.Achievements WHERE achievement_id = :achievement_id";
            $stmtAchievement = $this->getDbConnection()->prepare($achievementSql);
            $stmtAchievement->bindParam(':achievement_id', $achievementId);
            $stmtAchievement->execute();

            $this->getDbConnection()->commit();
            return true;
        } catch (\PDOException $e) {
            $this->getDbConnection()->rollBack();
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Database error: " . $e->getMessage());
        } catch (\Exception $e) {
            $this->getDbConnection()->rollBack();
            error_log("Error: " . $e->getMessage());
            throw new \Exception("Error: " . $e->getMessage());
        }
    }

    public function getAchievementScopeCounts(): array
    {
        try {
            $sql = "SELECT achievement_scope, COUNT(*) AS count
                FROM Achievement.Achievements
                GROUP BY achievement_scope";

            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }
}
