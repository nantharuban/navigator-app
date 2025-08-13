<?php
// app/Core/BaseController.php

abstract class BaseController 
{
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Render a view
     */
    protected function view($viewPath, $data = [])
    {
        // Extract variables for the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Build the view file path - adjust based on module structure
        $viewFile = __DIR__ . '/../Modules/' . str_replace('.', '/views/', $viewPath) . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View file not found: {$viewFile}");
        }
        
        // Get the content and clean the buffer
        $content = ob_get_clean();
        
        return $content;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Get request method
     */
    protected function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $this->getRequestMethod() === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    protected function isGet()
    {
        return $this->getRequestMethod() === 'GET';
    }
    
    /**
     * Get POST data
     */
    protected function getPostData($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    /**
     * Get GET data
     */
    protected function getGetData($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    /**
     * Get request data (POST or GET)
     */
    protected function getRequestData($key = null, $default = null)
    {
        $data = array_merge($_GET, $_POST);
        
        if ($key === null) {
            return $data;
        }
        
        return isset($data[$key]) ? $data[$key] : $default;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken($token = null)
    {
        if ($token === null) {
            $token = $this->getPostData('csrf_token');
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate CSRF token
     */
    protected function generateCsrfToken()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate required fields
     */
    protected function validateRequired($fields, $data)
    {
        $errors = [];
        
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        
        return $errors;
    }
    
    /**
     * Validate email
     */
    protected function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get client IP address
     */
    protected function getClientIp()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
    
    /**
     * Get current URL
     */
    protected function getCurrentUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        
        return $protocol . '://' . $host . $uri;
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get flash message
     */
    protected function getFlash($key = null)
    {
        if ($key === null) {
            $flash = $_SESSION['flash'] ?? [];
            unset($_SESSION['flash']);
            return $flash;
        }
        
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        
        return $message;
    }
    
    /**
     * Check if user has flash message
     */
    protected function hasFlash($key)
    {
        return isset($_SESSION['flash'][$key]);
    }
    
    /**
     * Upload file
     */
    protected function uploadFile($file, $uploadDir, $allowedTypes = [])
    {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error');
        }
        
        if (!empty($allowedTypes)) {
            $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($fileType), $allowedTypes)) {
                throw new Exception('File type not allowed');
            }
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadPath = rtrim($uploadDir, '/') . '/' . $fileName;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $fileName;
        }
        
        throw new Exception('Failed to upload file');
    }
    
    /**
     * Paginate results
     */
    protected function paginate($totalRecords, $perPage = 10, $currentPage = 1)
    {
        $totalPages = ceil($totalRecords / $perPage);
        $currentPage = max(1, min($totalPages, $currentPage));
        $offset = ($currentPage - 1) * $perPage;
        
        return [
            'total_records' => $totalRecords,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'offset' => $offset,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages
        ];
    }
    
    /**
     * Log message
     */
    protected function log($message, $level = 'info')
    {
        $logFile = __DIR__ . '/../../storage/logs/app.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}