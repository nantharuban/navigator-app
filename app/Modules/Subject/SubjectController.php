<?php
// app/Modules/Subject/SubjectController.php

require_once __DIR__ . '/../../Core/BaseController.php';
require_once __DIR__ . '/SubjectModel.php';

class SubjectController extends BaseController
{
    private $subjectModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->subjectModel = new SubjectModel();
    }
    
    /**
     * Display list of all subjects
     */
    public function index()
    {
        try {
            $subjects = $this->subjectModel->getAllActiveSubjects();
            
            $data = [
                'title' => 'Subjects',
                'subjects' => $subjects,
                'message' => $this->getSessionMessage()
            ];
            
            // FIXED: Use the correct format for your view system
            echo $this->view('Subject.index', $data);
            
        } catch (Exception $e) {
            $this->handleError('Error loading subjects: ' . $e->getMessage());
        }
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Subject',
            'errors' => $this->getSessionData('errors', []),
            'old' => $this->getSessionData('old', [])
        ];
        
        $this->clearSessionData(['errors', 'old']);
        
        // FIXED: Use the correct format for your view system
        echo $this->view('Subject.create', $data);
    }
    
    /**
     * Store new subject
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('/subjects/create');
            return;
        }
        
        try {
            $postData = $this->getPostData();
            
            // Prepare data
            $subjectData = [
                'SubjectName' => trim($postData['SubjectName'] ?? ''),
                'SubjectShortName' => trim($postData['SubjectShortName'] ?? ''),
                'SubjectCode' => trim($postData['SubjectCode'] ?? ''),
                'IsActive' => isset($postData['IsActive']) ? 1 : 0
            ];
            
            // Validate
            $errors = $this->subjectModel->validate($subjectData);
            
            if (!empty($errors)) {
                $this->setSessionData('errors', $errors);
                $this->setSessionData('old', $postData);
                $this->redirect('/subjects/create');
                return;
            }
            
            // Create subject
            $subjectId = $this->subjectModel->create($subjectData);
            
            if ($subjectId) {
                $this->setSessionMessage('Subject created successfully!', 'success');
                $this->redirect('/subjects');
            } else {
                throw new Exception('Failed to create subject');
            }
            
        } catch (Exception $e) {
            $this->setSessionData('errors', ['Error: ' . $e->getMessage()]);
            $this->setSessionData('old', $postData ?? []);
            $this->redirect('/subjects/create');
        }
    }
    
    /**
     * Show edit form
     */
    public function edit($subjectId)
    {
        try {
            $subject = $this->subjectModel->findById($subjectId);
            
            if (!$subject) {
                $this->setSessionMessage('Subject not found', 'error');
                $this->redirect('/subjects');
                return;
            }
            
            $data = [
                'title' => 'Edit Subject',
                'subject' => $subject,
                'errors' => $this->getSessionData('errors', []),
                'old' => $this->getSessionData('old', $subject)
            ];
            
            $this->clearSessionData(['errors', 'old']);
            
            // FIXED: Use the correct format for your view system
            echo $this->view('Subject.edit', $data);
            
        } catch (Exception $e) {
            $this->handleError('Error loading subject: ' . $e->getMessage());
        }
    }
    
    /**
     * Update subject
     */
    public function update($subjectId)
    {
        if (!$this->isPost()) {
            $this->redirect('/subjects/' . $subjectId . '/edit');
            return;
        }
        
        try {
            $subject = $this->subjectModel->findById($subjectId);
            
            if (!$subject) {
                $this->setSessionMessage('Subject not found', 'error');
                $this->redirect('/subjects');
                return;
            }
            
            $postData = $this->getPostData();
            
            // Prepare data
            $subjectData = [
                'SubjectName' => trim($postData['SubjectName'] ?? ''),
                'SubjectShortName' => trim($postData['SubjectShortName'] ?? ''),
                'SubjectCode' => trim($postData['SubjectCode'] ?? ''),
                'IsActive' => isset($postData['IsActive']) ? 1 : 0
            ];
            
            // Validate (exclude current record)
            $errors = $this->subjectModel->validate($subjectData, $subjectId);
            
            if (!empty($errors)) {
                $this->setSessionData('errors', $errors);
                $this->setSessionData('old', $postData);
                $this->redirect('/subjects/' . $subjectId . '/edit');
                return;
            }
            
            // Update subject
            $updated = $this->subjectModel->updateById($subjectId, $subjectData);
            
            if ($updated) {
                $this->setSessionMessage('Subject updated successfully!', 'success');
                $this->redirect('/subjects');
            } else {
                throw new Exception('Failed to update subject');
            }
            
        } catch (Exception $e) {
            $this->setSessionData('errors', ['Error: ' . $e->getMessage()]);
            $this->setSessionData('old', $postData ?? []);
            $this->redirect('/subjects/' . $subjectId . '/edit');
        }
    }
    
    /**
     * Delete subject
     */
    public function delete($subjectId)
    {
        try {
            $subject = $this->subjectModel->findById($subjectId);
            
            if (!$subject) {
                $this->setSessionMessage('Subject not found', 'error');
                $this->redirect('/subjects');
                return;
            }
            
            // Soft delete
            $deleted = $this->subjectModel->softDeleteById($subjectId);
            
            if ($deleted) {
                $this->setSessionMessage('Subject deleted successfully!', 'success');
            } else {
                $this->setSessionMessage('Failed to delete subject', 'error');
            }
            
        } catch (Exception $e) {
            $this->setSessionMessage('Error deleting subject: ' . $e->getMessage(), 'error');
        }
        
        $this->redirect('/subjects');
    }
    
    /**
     * Handle errors - FIXED to pass more error details
     */
    private function handleError($message)
    {
        $data = [
            'title' => 'Error',
            'error' => $message,  // Changed from 'message' to 'error' to match error.php
            'file' => '',         // Could add more debug info here
            'line' => ''          // Could add more debug info here
        ];
        
        echo $this->view('error', $data);
    }
    
    /**
     * Session helper methods
     */
    private function setSessionMessage($message, $type = 'info')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    
    private function getSessionMessage()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $message = $_SESSION['flash_message'] ?? null;
        $type = $_SESSION['flash_type'] ?? 'info';
        
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        
        return $message ? ['message' => $message, 'type' => $type] : null;
    }
    
    private function setSessionData($key, $data)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$key] = $data;
    }
    
    private function getSessionData($key, $default = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION[$key] ?? $default;
    }
    
    private function clearSessionData($keys)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        foreach ((array)$keys as $key) {
            unset($_SESSION[$key]);
        }
    }
}