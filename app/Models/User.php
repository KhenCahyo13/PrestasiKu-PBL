<?php

namespace App\Models;

use Exception;
use PDO;

class User extends Model {
    protected string $table = 'Master.Users';
    protected string $primaryKey = 'user_id';
    // Additional Attributes
    protected string $studentDetailsTable = 'Master.UserStudentDetails';
    protected string $lecturerDetailsTable = 'Master.UserLecturerDetails';
    protected string $rolesTable = 'Master.Roles';

    public function getByUsername(string $username): array | null {
        $query = 'EXEC CRUD.SelectTableDataByColumnWithJoins
            @TableName = :tableName,
            @TableColumns = :tableColumns,
            @ColumnName = :columnName,
            @ColumnValue = :columnValue,
            @JoinConditions = :joinConditions
        ';
        $stmt = $this->getDbConnection()->prepare($query);

        $tableColumns = 'Master.Users.user_id, Master.Users.user_username, Master.Users.user_password, Master.Users.user_isverified, Master.Roles.role_name';
        $joinConditions = 'INNER JOIN Master.Roles ON Master.Users.role_id = Master.Roles.role_id';

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindValue(':tableColumns', $tableColumns, PDO::PARAM_STR);
        $stmt->bindValue(':columnName', 'Master.Users.user_username', PDO::PARAM_STR);
        $stmt->bindValue(':columnValue', $username, PDO::PARAM_STR);
        $stmt->bindValue(':joinConditions', $joinConditions, PDO::PARAM_STR);

        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results ?: null;
    }


    public function createStudent(array $userData, array $studentDetailsData): bool {
        try {
            $this->getDbConnection()->beginTransaction();

            // STMT Student Details
            $queryStudentDetails = 'EXEC CRUD.InsertTableData @TableName = :tableName, @Columns = :columns, @Values = :values';
            $stmtStudentDetails = $this->getDbConnection()->prepare($queryStudentDetails);

            $studentDetailsColumns = implode(',', array_keys($studentDetailsData));
            $studentDetailsValues = array_map(function($value) {
                return is_string($value) ? "'" . addslashes($value) . "'" : $value;
            }, array_values($studentDetailsData));
            $studentDetailsValues = implode(',', $studentDetailsValues);

            $stmtStudentDetails->bindParam(':tableName', $this->studentDetailsTable, PDO::PARAM_STR);
            $stmtStudentDetails->bindParam(':columns', $studentDetailsColumns, PDO::PARAM_STR);
            $stmtStudentDetails->bindParam(':values', $studentDetailsValues, PDO::PARAM_STR);
            $stmtStudentDetails->execute();

            // STMT User
            $queryUser = 'EXEC CRUD.InsertTableData @TableName = :tableName, @Columns = :columns, @Values = :values';
            $stmtUser = $this->getDbConnection()->prepare($queryUser);

            $userColumns = implode(',', array_keys($userData));
            $userValues = array_map(function($value) {
                return is_string($value) ? "'" . addslashes($value) . "'" : $value;
            }, array_values($userData));
            $userValues = implode(',', $userValues);

            $stmtUser->bindParam(':tableName', $this->table, PDO::PARAM_STR);
            $stmtUser->bindParam(':columns', $userColumns, PDO::PARAM_STR);
            $stmtUser->bindParam(':values', $userValues, PDO::PARAM_STR);
            $stmtUser->execute();

            $this->getDbConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->getDbConnection()->rollBack();
            error_log("Error when creating student data: " . $e->getMessage());
            return false;
        }
    }

    public function createLecture(array $userData, array $lecturerDetailsData): bool
    {
        try {
            $this->getDbConnection()->beginTransaction();

            // STMT Lecturer Details
            $queryLecturerDetails = 'EXEC CRUD.InsertTableData @TableName = :tableName, @Columns = :columns, @Values = :values';
            $stmtLecturerDetails = $this->getDbConnection()->prepare($queryLecturerDetails);

            $lecturerDetailsColumns = implode(',', array_keys($lecturerDetailsData));
            $lecturerDetailsValues = array_map(function($value) {
                return is_string($value) ? "'" . addslashes($value) . "'" : $value;
            }, array_values($lecturerDetailsData));
            $lecturerDetailsValues = implode(',', $lecturerDetailsValues);

            $stmtLecturerDetails->bindParam(':tableName', $this->lecturerDetailsTable, PDO::PARAM_STR);
            $stmtLecturerDetails->bindParam(':columns', $lecturerDetailsColumns, PDO::PARAM_STR);
            $stmtLecturerDetails->bindParam(':values', $lecturerDetailsValues, PDO::PARAM_STR);
            $stmtLecturerDetails->execute();

            // STMT User
            $queryUser = 'EXEC CRUD.InsertTableData @TableName = :tableName, @Columns = :columns, @Values = :values';
            $stmtUser = $this->getDbConnection()->prepare($queryUser);

            $userColumns = implode(',', array_keys($userData));
            $userValues = array_map(function($value) {
                return is_string($value) ? "'" . addslashes($value) . "'" : $value;
            }, array_values($userData));
            $userValues = implode(',', $userValues);

            $stmtUser->bindParam(':tableName', $this->table, PDO::PARAM_STR);
            $stmtUser->bindParam(':columns', $userColumns, PDO::PARAM_STR);
            $stmtUser->bindParam(':values', $userValues, PDO::PARAM_STR);
            $stmtUser->execute();

            $this->getDbConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->getDbConnection()->rollBack();
            error_log("Error when creating lecturer data: " . $e->getMessage());
            return false;
        }
    }

    public function getUsers(): array {
        $sql = 'SELECT * FROM $this->table';
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById(string $userId): array | null {
        $sql = 'SELECT * FROM $this->table WHERE user_id = :user_id';
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?? null;
    }

    public function updateUser(array $data): bool {
        try {
            $sql = 'UPDATE $this->table SET user_username = :user_username, user_password = :user_password WHERE user_id = :user_id';
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute([
                ':user_username' => $data['user_username'],
                ':user_password' => password_hash($data['user_password'], PASSWORD_DEFAULT),
                ':user_id' => $data['user_id']
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser(string $userId): bool {
        try {
            $sql = 'DELETE FROM $this->table WHERE user_id = :user_id';
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return true;
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }


    public function verifiedRegistration(array $data): bool {
        try {
            $sql = 'UPDATE $this->table SET user_isverified = :user_isverified WHERE user_id = :user_id';
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->execute([
                ':user_isverified' => $data['user_isverified'],
                ':user_id' => $data['user_id']
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error user: " . $e->getMessage());
            return false;
        }
    }
}
