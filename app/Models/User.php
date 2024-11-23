<?php
  namespace App\Models;

  class User extends Model {
    protected string $tableUser = 'Master.Users';
    protected string $tableStudentDetail = 'Master.StudentDetailUsers';
    protected string $tableLectureDetail = 'Master.LectureDetailUsers';
    protected string $tableRole = 'Master.Roles';

    public function getUserByUsername(string $username): ?array {
        $sql = "SELECT * FROM $this->tableUser WHERE user_username = :username";
        $stmt = $this->getDbConnection()->prepare($sql);
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function createStudent(array $data): bool {
        try {
          $this->getDbConnection()->beginTransaction();

          $sqlStudentDetails = "INSERT INTO $this->tableStudentDetail 
            (detail_id, spclass_id, detail_name, detail_nim, detail_date_of_birth, 
            detail_phone_number, detail_email, detail_profile) 
            VALUES 
            (:detail_id, :spclass_id, :detail_name, :detail_nim, :detail_date_of_birth, :detail_phone_number, :detail_email, :detail_profile)";
          $stmtStudent = $this->getDbConnection()->prepare($sqlStudentDetails);
          $stmtStudent->execute([
            ':detail_id' => $data['detail_student_id'],
            ':spclass_id' => $data['spclass_id'],
            ':detail_name' => $data['detail_name'],
            ':detail_nim' => $data['detail_nim'],
            ':detail_date_of_birth' => $data['detail_date_of_birth'],
            ':detail_phone_number' => $data['detail_phone_number'],
            ':detail_email' => $data['detail_email'],
            ':detail_profile' => $data['detail_profile'],
          ]);

          $sqlUser = "INSERT INTO $this->tableUser
            (user_id, detail_student_id, role_id, user_username, user_password)
            VALUES 
            (NEWID(), :detail_student_id, :role_id, :user_username, :user_password)";
          $stmtUser = $this->getDbConnection()->prepare($sqlUser);
          $stmtUser->execute([
            ':detail_student_id' => $data['detail_student_id'],
            ':role_id' => $data['role_id'],
            ':user_username' => $data['user_username'],
            ':user_password' => $data['user_password'],
          ]);

          $this->getDbConnection()->commit();
          return true; 
      } catch (\Exception $e) {
          $this->getDbConnection()->rollBack();
          error_log("Error in createStudent: " . $e->getMessage());
          return false;
      }
    }
  }
?>