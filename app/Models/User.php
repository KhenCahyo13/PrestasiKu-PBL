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

    public function getTotalCount(): int {
        $query = 'EXEC Metadata.CountTableData @TableName = :tableName';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);

        $stmt->execute();
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['Total'];
    }

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

    public function getAll(int $limit = 10, int $offset = 0, string $search = ''): array {
        $query = 'EXEC CRUD.SelectSingleTableForDataTablesWithJoins 
                @TableName = :tableName,
                @TableColumns = :tableColumns,
                @SearchColumnName = :searchColumnName,
                @SearchValue = :searchValue,
                @Offset = :offset,
                @Limit = :limit,
                @JoinConditions = :joinConditions';
        $stmt = $this->getDbConnection()->prepare($query);

        $tableColumns = 'Master.Users.*,
                        Master.UserStudentDetails.detail_name AS student_name, Master.UserStudentDetails.detail_nim AS student_nim, 
                        Master.UserLecturerDetails.detail_name AS lecturer_name, Master.UserLecturerDetails.detail_nip AS lecturer_nip, 
                        Master.Roles.role_name';
        $joinConditions = 'LEFT JOIN Master.UserStudentDetails ON Master.UserStudentDetails.detail_id = Master.Users.details_student_id
                        LEFT JOIN Master.UserLecturerDetails ON Master.UserLecturerDetails.detail_id = Master.Users.details_lecturer_id
                        INNER JOIN Master.Roles ON Master.Roles.role_id = Master.Users.role_id';

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindValue(':tableColumns', $tableColumns, PDO::PARAM_STR);
        $stmt->bindValue(':searchColumnName', 'user_username', PDO::PARAM_STR);
        $stmt->bindValue(':searchValue', $search, PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':joinConditions', $joinConditions, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(string $userId): array | null {
        $sql = 'EXEC CRUD.SelectTableDataByColumn @TableName = :tableName, @ColumnName = :columnName, @Value = :user_id';
        $stmt = $this->getDbConnection()->prepare($sql);
        
        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindParam(':columnName', $this->primaryKey, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?? null;
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

    public function update(array $data): bool {
        try {
            $query = 'UPDATE $this->table SET user_username = :user_username, user_password = :user_password WHERE user_id = :user_id';
            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->bindParam(':user_username', $data['user_username'], PDO::PARAM_STR);
            $stmt->bindParam(':user_password', $data['user_password'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function delete(string $userId): bool {
        try {
            $query = 'EXEC CRUD.DeleteTableDataByColumn @TableName = :tableName, @ColumnName = :columnName, @Value = :value';
            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
            $stmt->bindParam(':columnName', $this->primaryKey, PDO::PARAM_STR);
            $stmt->bindParam(':value', $userId, PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }


    public function verifiedRegistration(array $data): bool {
        try {
            $query = 'UPDATE $this->table SET user_isverified = :user_isverified WHERE user_id = :user_id';
            $stmt = $this->getDbConnection()->prepare($query);

            $stmt->bindParam(':user_isverified', $data['user_isverified'], PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_STR);

            $stmt->execute();

            return true;
        } catch (Exception $e) {
            error_log("Error user: " . $e->getMessage());
            return false;
        }
    }
}
