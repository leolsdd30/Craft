<?php
/**
 * Base Model - PDO Database Wrapper
 * All models extend this class for safe database queries
 */
class Model {
    protected PDO $db;
    protected string $table = '';

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Find a record by ID
     */
    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get all records with optional ordering
     */
    public function all(string $orderBy = 'id', string $direction = 'DESC'): array {
        $allowed = ['ASC', 'DESC'];
        $direction = in_array(strtoupper($direction), $allowed) ? strtoupper($direction) : 'DESC';
        
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}");
        return $stmt->fetchAll();
    }

    /**
     * Find records by a specific column value
     */
    public function where(string $column, $value): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetchAll();
    }

    /**
     * Find a single record by column value
     */
    public function findBy(string $column, $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1");
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Insert a new record
     * @return int The inserted ID
     */
    public function create(array $data): int {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a record by ID
     */
    public function update(int $id, array $data): bool {
        $setClause = '';
        foreach (array_keys($data) as $key) {
            $setClause .= "{$key} = :{$key}, ";
        }
        $setClause = rtrim($setClause, ', ');

        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Delete a record by ID
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count records with optional condition
     */
    public function count(string $condition = '', array $params = []): int {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($condition) {
            $sql .= " WHERE {$condition}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch()['total'];
    }

    /**
     * Execute a custom query with prepared statements
     */
    public function query(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a custom statement (INSERT/UPDATE/DELETE)
     */
    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Get paginated results
     */
    public function paginate(int $page = 1, int $perPage = 12, string $condition = '', array $params = [], string $orderBy = 'created_at DESC'): array {
        $offset = ($page - 1) * $perPage;

        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $dataSql = "SELECT * FROM {$this->table}";

        if ($condition) {
            $countSql .= " WHERE {$condition}";
            $dataSql .= " WHERE {$condition}";
        }

        $dataSql .= " ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";

        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $dataStmt = $this->db->prepare($dataSql);
        $dataStmt->execute($params);
        $data = $dataStmt->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }
}
