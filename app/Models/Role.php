<?php
namespace App\Models;

use PDO;

class Role extends Model {
    protected string $table = 'Master.Roles';
    protected string $primaryKey = 'role_id';

    public function getById (string $id): array | null {
        $query = 'EXEC CRUD.SelectTableDataByColumn @TableName = :tableName, @ColumnName = :columnName, @Value = :value';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindParam(':columnName', $this->primaryKey, PDO::PARAM_STR);
        $stmt->bindParam(':value', $id, PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $results ?? null;
    }
}