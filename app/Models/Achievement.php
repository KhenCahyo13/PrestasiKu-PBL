<?php

namespace App\Models;

use PDO;
use App\Models\Model;
use Ramsey\Uuid\Uuid;

class Achievement extends Model
{
    protected string $tableAchievement = "Achievement.Achievements";
    protected string $tableApprover = "Achievement.AchievementApprovers";

    protected string $primaryKey = "achievement_id";


    public function create(array $data): bool
    {
        $uuid = Uuid::uuid4();
        $achievementId = $uuid->toString();
        try {
            $this->getDbConnection()->beginTransaction();

            $sql = "INSERT INTO Achievement.Achievements (
                    achievement_id, 
                    user_id, 
                    achievement_title, 
                    achievement_description, 
                    achievement_type, 
                    achievement_event_location, 
                    achievement_event_city, 
                    achievement_event_start, 
                    achievement_event_end, 
                    achievement_scope
                )
                VALUES (
                    :achievement_id, 
                    :user_id, 
                    :achievement_title, 
                    :achievement_description, 
                    :achievement_type, 
                    :achievement_event_location, 
                    :achievement_event_city, 
                    :achievement_event_start, 
                    :achievement_event_end,  
                    :achievement_scope
                )";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':achievement_id', $achievementId);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':achievement_title', $data['achievement_title']);
            $stmt->bindParam(':achievement_description', $data['achievement_description']);
            $stmt->bindParam(':achievement_type', $data['achievement_type']);
            $stmt->bindParam(':achievement_event_location', $data['achievement_event_location']);
            $stmt->bindParam(':achievement_event_city', $data['achievement_event_city']);
            $stmt->bindParam(':achievement_event_start', $data['achievement_event_start']);
            $stmt->bindParam(':achievement_event_end', $data['achievement_event_end']);
            $stmt->bindParam(':achievement_scope', $data['achievement_scope']);
            $stmt->execute();

            if (!empty($data['approvers'])) {
                $approverSql = "INSERT INTO Achievement.AchievementApprovers (achievement_id, user_id) 
                            VALUES (:achievement_id, :user_id)";
                foreach ($data['approvers'] as $approver) {
                    $stmtApprover = $this->getDbConnection()->prepare($approverSql);
                    $stmtApprover->bindParam(':achievement_id', $achievementId);
                    $stmtApprover->bindParam(':user_id', $approver['user_id']);
                    $stmtApprover->execute();
                }
            }

            if (!empty($data['files'])) {
                $fileSql = "INSERT INTO Achievement.AchievementFiles (achievement_id, file_title, file_description, file_path) 
                        VALUES (:achievement_id, :file_title, :file_description, :file_path)";
                foreach ($data['files'] as $file) {
                    $stmtFile = $this->getDbConnection()->prepare($fileSql);
                    $stmtFile->bindParam(':achievement_id', $achievementId);
                    $stmtFile->bindParam(':file_title', $file['file_title']);
                    $stmtFile->bindParam(':file_description', $file['file_description']);
                    $stmtFile->bindParam(':file_path', $file['file_path']);
                    $stmtFile->execute();
                }
            }


            if (!empty($data['category_id'])) {
                $categorySql = "INSERT INTO Achievement.AchievementCategoryDetails (achievement_id, category_id) 
                            VALUES (:achievement_id, :category_id)";
                $stmtCategory = $this->getDbConnection()->prepare($categorySql);
                $stmtCategory->bindParam(':achievement_id', $achievementId);
                $stmtCategory->bindParam(':category_id', $data['category_id']);
                $stmtCategory->execute();
            }

            if (!empty($data['verification_code'])) {
                $verificationSql = "INSERT INTO Achievement.AchievementVerifications (
                                    achievement_id, verification_code, verification_status, verification_notes
                                ) 
                                VALUES (
                                    :achievement_id, :verification_code, :verification_status, :verification_notes
                                )";
                $stmtVerification = $this->getDbConnection()->prepare($verificationSql);
                $stmtVerification->bindParam(':achievement_id', $achievementId);
                $stmtVerification->bindParam(':verification_code', $data['verification_code']);
                $stmtVerification->bindParam(':verification_status', $data['verification_status']);
                $stmtVerification->bindParam(':verification_notes', $data['verification_notes']);
                $stmtVerification->execute();
            }

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

            $isApproved = $data['action'] === 'approved' ? 1 : 0;

            $sqlUser = "SELECT role_id, user_username FROM Master.Users WHERE user_id = :user_id";
            $stmtUser = $this->getDbConnection()->prepare($sqlUser);
            $stmtUser->bindParam(':user_id', $data['user_id']);
            $stmtUser->execute();
            $userResult = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if (!$userResult) {
                throw new \Exception("User not found with ID: " . $data['user_id']);
            }

            $roleId = $userResult['role_id'];
            $approverName = $roleId === '6EC386D9-7313-4659-8C7D-B11148750B7A'
            ? 'Admin'
            : ($roleId === 'FBA2D7AA-4F83-4C48-9C9A-4EB7F8A253F8' ? $userResult['user_username'] : null);

            if (!$approverName) {
                throw new \Exception("User role is not valid for this operation.");
            }

            $sqlUpdateApprover = "UPDATE Achievement.AchievementApprovers
                              SET approver_is_done = 1, approver_updatedat = GETDATE(), approver_status = :approver_status
                              WHERE achievement_id = :achievement_id AND user_id = :user_id";
            $stmtApprover = $this->getDbConnection()->prepare($sqlUpdateApprover);
            $stmtApprover->bindParam(':approver_status', $data['action']);
            $stmtApprover->bindParam(':achievement_id', $data['achievement_id']);
            $stmtApprover->bindParam(':user_id', $data['user_id']);
            $stmtApprover->execute();

            $logMessage = "{$approverName} " . ($isApproved ? "menyetujui" : "menolak") . " pencapaian.";
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
                                       verification_notes = :verification_notes,
                                       verification_is_done = :verification_is_done,
                                       verification_updatedat = GETDATE()
                                   WHERE achievement_id = :achievement_id";
            $stmtVerification = $this->getDbConnection()->prepare($sqlUpdateVerification);
            $stmtVerification->bindParam(':verification_code', $verificationStatus['code']);
            $stmtVerification->bindParam(':verification_status', $verificationStatus['status']);
            $stmtVerification->bindParam(':verification_notes', $data['notes']);
            $stmtVerification->bindParam(':verification_is_done', $verificationStatus['isDone']);
            $stmtVerification->bindParam(':achievement_id', $data['achievement_id']);
            $stmtVerification->execute();

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

    private function determineVerificationStatus(string $achievementId): array
    {
        $sql = "SELECT 
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

}
