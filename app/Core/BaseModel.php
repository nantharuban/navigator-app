<?php
// app/Core/BaseModel.php

abstract class BaseModel 
{
    protected $pdo;
    protected $table;
    protected $primaryKey = 'ID';
    protected $fillable = [];
    
    public function __construct()
    {
        $this->pdo = $this->getDatabaseConnection();
    }
    
    private function getDatabaseConnection()
    {
        $config = require_once __DIR__ . '/../../config/config.php';
        
        $host = $config['db_host'];
        $db = $config['db_name'];
        $user = $config['db_user'];
        $pass = $config['db_pass'];
        $charset = 'utf8mb4';
        
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * Find record by primary key
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Get all records
     */
    public function all($conditions = [], $orderBy = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Create new record
     */
    public function create($data)
    {
        $filteredData = $this->filterFillable($data);
        
        $columns = array_keys($filteredData);
        $placeholders = array_map(function($col) { return ":{$col}"; }, $columns);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($filteredData);
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Update record
     */
    public function update($id, $data)
    {
        $filteredData = $this->filterFillable($data);
        
        $setParts = [];
        foreach ($filteredData as $column => $value) {
            $setParts[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " 
                WHERE {$this->primaryKey} = :id";
        
        $filteredData['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($filteredData);
    }
    
    /**
     * Delete record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Soft delete (set IsActive to 0)
     */
    public function softDelete($id)
    {
        return $this->update($id, ['IsActive' => 0]);
    }
    
    /**
     * Get active records only
     */
    public function getActive($orderBy = null)
    {
        return $this->all(['IsActive' => 1], $orderBy);
    }
    
    /**
     * Filter data based on fillable fields
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Execute custom query
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Check if record exists
     */
    public function exists($conditions)
    {
        $whereClause = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClause[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE " . implode(' AND ', $whereClause);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get count of records
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return (int)$result['count'];
    }
}