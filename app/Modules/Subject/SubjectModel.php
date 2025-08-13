<?php
// app/Modules/Subject/SubjectModel.php

require_once __DIR__ . '/../../Core/BaseModel.php';

class SubjectModel extends BaseModel
{
    protected $table = 'subject';
    protected $primaryKey = 'SubjectID';
    protected $fillable = [
        'SubjectName',
        'SubjectShortName', 
        'SubjectCode',
        'IsActive'
    ];
    
    /**
     * Get all active subjects ordered by name
     */
    public function getAllActiveSubjects()
    {
        return $this->getActive('SubjectName ASC');
    }
    
    /**
     * Check if subject name already exists (for validation)
     */
    public function isSubjectNameExists($subjectName, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE SubjectName = :subjectName";
        $params = ['subjectName' => $subjectName];
        
        if ($excludeId) {
            $sql .= " AND SubjectID != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Check if subject code already exists (for validation)
     */
    public function isSubjectCodeExists($subjectCode, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE SubjectCode = :subjectCode";
        $params = ['subjectCode' => $subjectCode];
        
        if ($excludeId) {
            $sql .= " AND SubjectID != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Validate subject data
     */
    public function validate($data, $excludeId = null)
    {
        $errors = [];
        
        // Required field validation
        if (empty($data['SubjectName'])) {
            $errors[] = "Subject Name is required";
        }
        
        if (empty($data['SubjectCode'])) {
            $errors[] = "Subject Code is required";
        }
        
        // Length validation
        if (!empty($data['SubjectName']) && strlen($data['SubjectName']) > 100) {
            $errors[] = "Subject Name must not exceed 100 characters";
        }
        
        if (!empty($data['SubjectShortName']) && strlen($data['SubjectShortName']) > 20) {
            $errors[] = "Subject Short Name must not exceed 20 characters";
        }
        
        if (!empty($data['SubjectCode']) && strlen($data['SubjectCode']) > 20) {
            $errors[] = "Subject Code must not exceed 20 characters";
        }
        
        // Unique validation
        if (!empty($data['SubjectName']) && $this->isSubjectNameExists($data['SubjectName'], $excludeId)) {
            $errors[] = "Subject Name already exists";
        }
        
        if (!empty($data['SubjectCode']) && $this->isSubjectCodeExists($data['SubjectCode'], $excludeId)) {
            $errors[] = "Subject Code already exists";
        }
        
        return $errors;
    }
    
    /**
     * Find subject by SubjectID (override to use correct primary key)
     */
    public function findById($subjectId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE SubjectID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $subjectId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Update subject by SubjectID
     */
    public function updateById($subjectId, $data)
    {
        $filteredData = $this->filterFillable($data);
        
        $setParts = [];
        foreach ($filteredData as $column => $value) {
            $setParts[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " 
                WHERE SubjectID = :id";
        
        $filteredData['id'] = $subjectId;
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($filteredData);
    }
    
    /**
     * Delete subject by SubjectID
     */
    public function deleteById($subjectId)
    {
        $sql = "DELETE FROM {$this->table} WHERE SubjectID = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $subjectId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Soft delete subject by SubjectID
     */
    public function softDeleteById($subjectId)
    {
        return $this->updateById($subjectId, ['IsActive' => 0]);
    }
}