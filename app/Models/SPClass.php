<?php
namespace App\Models;

use PDO;

class SPClass extends Model {
    protected string $table = 'Master.SPClass';
    protected string $primaryKey = 'spclass_id';

    public function getTotalCount(): int {
        $query = 'EXEC Metadata.CountTableData @TableName = :tableName';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);

        $stmt->execute();
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['Total'];
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

        $tableColumns = 'Master.SPClass.*, Master.StudyPrograms.studyprogram_id, Master.StudyPrograms.studyprogram_name';
        $joinConditions = 'INNER JOIN Master.StudyPrograms ON Master.SPClass.studyprogram_id = Master.StudyPrograms.studyprogram_id';

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindValue(':tableColumns', $tableColumns, PDO::PARAM_STR);
        $stmt->bindValue(':searchColumnName', 'spclass_name', PDO::PARAM_STR);
        $stmt->bindValue(':searchValue', $search, PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':joinConditions', $joinConditions, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(string $id): array {
        $query = 'EXEC CRUD.SelectTableDataByColumn @TableName = :tableName, @ColumnName = :columnName, @Value = :value';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindParam(':columnName', $this->primaryKey, PDO::PARAM_STR);
        $stmt->bindParam(':value', $id, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return array();
        }

        return $result;
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

    public function update(array $data): bool {
        $sql = "UPDATE $this->table SET studyprogram_id = :studyprogram_id, spclass_name = :spclass_name WHERE $this->primaryKey = :spclass_id";
        $stmt = $this->getDbConnection()->prepare($sql);

        $stmt->bindParam(':studyprogram_id', $data['studyprogram_id'], PDO::PARAM_INT);
        $stmt->bindParam(':spclass_name', $data['spclass_name'], PDO::PARAM_STR);
        $stmt->bindParam(':spclass_id', $data['spclass_id'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete(string $id): bool {
        $query = 'EXEC CRUD.DeleteTableDataByColumn @TableName = :tableName, @ColumnName = :columnName, @Value = :value';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindParam(':columnName', $this->primaryKey, PDO::PARAM_STR);
        $stmt->bindParam(':value', $id, PDO::PARAM_STR);

        return $stmt->execute();
    }
}