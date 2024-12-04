<?php

namespace App\Models;

use PDO;

class User extends Model {
    protected string $table = 'Master.Users';
    protected string $primaryKey = 'user_id';
    protected string $tableStudentDetail = 'Master.StudentDetailUsers';
    protected string $tableLectureDetail = 'Master.LectureDetailUsers';
    protected string $tableRole = 'Master.Roles';

    public function getByUsername(string $username): array | null {
        $query = 'EXEC CRUD.SelectTableDataByColumnWithJoins
            @TableName = :tableName,
            @TableColumns = :tableColumn,
            @ColumnName = :columnName,
            @ColumnValue = :columnValue,
            @JoinConditions = :joinConditions
        ';
        $stmt = $this->getDbConnection()->prepare($query);

        $tableColumns = 'Master.Users.user_id, Master.Users.user_username, Master.Roles.role_name';
        $joinConditions = 'INNER JOIN Master.Roles ON Master.Users.role_id = Master.Roles.role_id';

        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindParam(':tableColumn', $tableColumns, PDO::PARAM_STR);
        $stmt->bindParam(':columnName', 'user_username', PDO::PARAM_STR);
        $stmt->bindParam(':columnValue', $username, PDO::PARAM_STR);
        $stmt->bindParam(':joinConditions', $joinConditions, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?? null;
    }


    public function createStudent(array $data): bool
    {
        try {
            $this->getDbConnection()->beginTransaction();

            $sqlStudentDetails = "INSERT INTO $this->tableStudentDetail
                (detail_id, spclass_id, detail_name, detail_nim, detail_date_of_birth, detail_phone_number, detail_email, detail_profile) 
                VALUES 
                (:detail_id, :spclass_id, :detail_name, :detail_nim, :detail_date_of_birth, :detail_phone_number, :detail_email, :detail_profile)";
            $stmtStudentDetails = $this->getDbConnection()->prepare($sqlStudentDetails);
            $stmtStudentDetails->execute([
                ':detail_id' => $data['detail_id'],
                ':spclass_id' => $data['spclass_id'],
                ':detail_name' => $data['detail_name'],
                ':detail_nim' => $data['detail_nim'],
                ':detail_date_of_birth' => $data['detail_date_of_birth'],
                ':detail_phone_number' => $data['detail_phone_number'],
                ':detail_email' => $data['detail_email'],
                ':detail_profile' => $data['detail_profile']
            ]);

            $sqlUser = "INSERT INTO $this->table 
                (user_id, detail_student_id, role_id, user_username, user_password) 
                VALUES 
                (:user_id, :detail_student_id, :role_id, :user_username, :user_password)";
            $stmtUser = $this->getDbConnection()->prepare($sqlUser);
            $stmtUser->execute([
                ':user_id' => $data['user_id'],
                ':detail_student_id' => $data['detail_id'],
                ':role_id' => $data['role_id'],
                ':user_username' => $data['user_username'],
                ':user_password' => $data['user_password']
            ]);

            $this->getDbConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->getDbConnection()->rollBack();
            error_log("Error creating student: " . $e->getMessage());
            return false;
        }
    }

    public function createLecture(array $data): bool
    {
        try {
            $this->getDbConnection()->beginTransaction();

            $sqlLectureDetails = "INSERT INTO $this->tableLectureDetail 
                (detail_id, department_id, detail_name, detail_nip, detail_phone_number, detail_email, detail_profile) 
                VALUES 
                (:detail_id, :department_id, :detail_name, :detail_nip, :detail_phone_number, :detail_email, :detail_profile)";
            $stmtLectureDetails = $this->getDbConnection()->prepare($sqlLectureDetails);
            $stmtLectureDetails->execute([
                ':detail_id' => $data['detail_id'],
                ':department_id' => $data['department_id'],
                ':detail_name' => $data['detail_name'],
                ':detail_nip' => $data['detail_nip'],
                ':detail_phone_number' => $data['detail_phone_number'],
                ':detail_email' => $data['detail_email'],
                ':detail_profile' => $data['detail_profile']
            ]);

            $sqlUser = "INSERT INTO $this->table
                (user_id, detail_lecture_id, role_id, user_username, user_password) 
                VALUES 
                (:user_id, :detail_lecture_id, :role_id, :user_username, :user_password)";
            $stmtUser = $this->getDbConnection()->prepare($sqlUser);
            $stmtUser->execute([
                ':user_id' => $data['user_id'],
                ':detail_lecture_id' => $data['detail_id'],
                ':role_id' => $data['role_id'],
                ':user_username' => $data['user_username'],
                ':user_password' => $data['user_password']
            ]);

            $this->getDbConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->getDbConnection()->rollBack();
            error_log("Error creating lecturer: " . $e->getMessage());
            return false;
        }
    }

    public function getUsers(): array
    {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUserById(string $userId): ?array
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateUser(array $data): bool
    {
        try {
            $sql = "UPDATE $this->table SET user_username = :user_username, user_password = :user_password WHERE user_id = :user_id";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute([
                ':user_username' => $data['user_username'],
                ':user_password' => password_hash($data['user_password'], PASSWORD_DEFAULT),
                ':user_id' => $data['user_id']
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser(string $userId): bool
    {
        try {
            $sql = "DELETE FROM $this->table WHERE user_id = :user_id";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return true;
        } catch (\Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }


    public function verifiedRegistration(array $data): bool
    {
        try {
            $sql = "UPDATE $this->table SET user_isverified = :user_isverified WHERE user_id = :user_id";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute([
                ':user_isverified' => $data['user_isverified'],
                ':user_id' => $data['user_id']
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Error user: " . $e->getMessage());
            return false;
        }
    }
}
