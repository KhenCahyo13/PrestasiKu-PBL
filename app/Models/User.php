<?php
    namespace App\Models;

    class User extends Model {
        protected string $table = 'Master.Users';
        protected string $primaryKey = 'user_id';

        public function create(array $data): bool {
            $sql = "INSERT INTO $this->table (user_username, user_password) VALUES (:user_username, :user_password)";
            $stmt = $this->getDbConnection()->prepare($sql);
            $stmt->bindParam(':user_username', $data['user_username']);
            $stmt->bindParam(':user_password', $data['user_password']);

            return $stmt->execute();
        }
    }
?>