<?php
namespace App\Models;

use PDO;

class AchievementCategory extends Model {
    protected string $table = 'Achievement.AchievementCategories';
    protected string $primaryKey = 'category_id';

    public function getTotalCount(): int {
        $query = 'EXEC Metadata.CountTableData @TableName = :tableName';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);

        $stmt->execute();
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['Total'];
    }

    public function getAll(int $limit = 10, int $offset = 0, string $search = ''): array {
        $query = 'EXEC CRUD.SelectSingleTableForDataTables 
                @TableName = :tableName,
                @Columns = :columns,
                @SearchColumnName = :searchColumnName,
                @SearchValue = :searchValue,
                @Offset = :offset,
                @Limit = :limit';
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindValue(':tableName', $this->table, PDO::PARAM_STR);
        $stmt->bindValue(':columns', '*', PDO::PARAM_STR);
        $stmt->bindValue(':searchColumnName', 'category_name', PDO::PARAM_STR);
        $stmt->bindValue(':searchValue', $search, PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}