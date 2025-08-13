<?php
// public/index.php - Main Application Router

// Start session
session_start();

// Error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader function
spl_autoload_register(function ($className) {
    // Define possible paths for class files
    $paths = [
        __DIR__ . '/../app/Core/' . $className . '.php',
        __DIR__ . '/../app/Modules/Subject/' . $className . '.php',
        __DIR__ . '/../app/Modules/Grade/' . $className . '.php',
        // Add more module paths as needed
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Get the request URI and method
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove query string from URI
$requestUri = parse_url($requestUri, PHP_URL_PATH);

// Remove leading slash
$requestUri = ltrim($requestUri, '/');

// Split URI into segments
$segments = explode('/', $requestUri);

// Simple Router Class
class Router 
{
    private $routes = [];
    
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }
    
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }
    
    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }
    
    public function dispatch($requestMethod, $requestUri) {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Convert route path to regex pattern
            $pattern = $this->pathToRegex($route['path']);
            
            if (preg_match($pattern, $requestUri, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                // Call the callback with parameters
                return call_user_func_array($route['callback'], $matches);
            }
        }
        
        // No route found - 404
        $this->handle404();
    }
    
    private function pathToRegex($path) {
        // Convert /path/{id} to regex pattern
        $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        return '/^' . $pattern . '$/';
    }
    
    private function handle404() {
        http_response_code(404);
        echo $this->render404();
    }
    
    private function render404() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page Not Found</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
                .container { max-width: 600px; margin: 0 auto; }
                h1 { color: #e74c3c; }
                p { color: #666; margin: 20px 0; }
                a { color: #3498db; text-decoration: none; }
                a:hover { text-decoration: underline; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>404 - Page Not Found</h1>
                <p>The page you are looking for could not be found.</p>
                <a href="/subjects">Go to Subjects</a> | 
                <a href="/">Home</a>
            </div>
        </body>
        </html>';
    }
}

// Initialize router
$router = new Router();

// ===== HOME ROUTES =====
$router->get('', function() {
    // Redirect to subjects for now
    header('Location: /subjects');
    exit;
});

$router->get('home', function() {
    header('Location: /subjects');
    exit;
});

// ===== SUBJECT ROUTES =====

// List all subjects
$router->get('subjects', function() {
    $controller = new SubjectController();
    $controller->index();
});

// Show create form
$router->get('subjects/create', function() {
    $controller = new SubjectController();
    $controller->create();
});

// Store new subject
$router->post('subjects/store', function() {
    $controller = new SubjectController();
    $controller->store();
});

// Show edit form
$router->get('subjects/{id}/edit', function($id) {
    $controller = new SubjectController();
    $controller->edit($id);
});

// Update subject
$router->post('subjects/{id}/update', function($id) {
    $controller = new SubjectController();
    $controller->update($id);
});

// Delete subject
$router->get('subjects/{id}/delete', function($id) {
    $controller = new SubjectController();
    $controller->delete($id);
});

// Alternative delete route (POST method)
$router->post('subjects/{id}/delete', function($id) {
    $controller = new SubjectController();
    $controller->delete($id);
});

// ===== API ROUTES (JSON responses) =====
$router->get('api/subjects', function() {
    try {
        $model = new SubjectModel();
        $subjects = $model->getAllActiveSubjects();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $subjects
        ]);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

$router->get('api/subjects/{id}', function($id) {
    try {
        $model = new SubjectModel();
        $subject = $model->findById($id);
        
        header('Content-Type: application/json');
        
        if ($subject) {
            echo json_encode([
                'success' => true,
                'data' => $subject
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Subject not found'
            ]);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// ===== TEST ROUTES =====
$router->get('test', function() {
    echo '<h1>Navigator App - Test Page</h1>';
    echo '<p>Application is working!</p>';
    echo '<a href="/subjects">Go to Subjects</a>';
});

$router->get('test/db', function() {
    try {
        $model = new SubjectModel();
        $count = $model->count();
        
        echo '<h1>Database Test</h1>';
        echo '<p>Database connection: <strong style="color: green;">Success</strong></p>';
        echo '<p>Total subjects in database: <strong>' . $count . '</strong></p>';
        echo '<a href="/subjects">View Subjects</a>';
        
    } catch (Exception $e) {
        echo '<h1>Database Test</h1>';
        echo '<p>Database connection: <strong style="color: red;">Failed</strong></p>';
        echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
});

// ===== ERROR HANDLING =====
try {
    // Dispatch the request
    $router->dispatch($requestMethod, $requestUri);
    
} catch (Exception $e) {
    // Handle application errors
    http_response_code(500);
    
    if (ini_get('display_errors')) {
        // Development mode - show detailed error
        echo '<h1>Application Error</h1>';
        echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        // Production mode - generic error message
        echo '<h1>Something went wrong</h1>';
        echo '<p>We apologize for the inconvenience. Please try again later.</p>';
    }
}

// ===== HELPER FUNCTIONS =====

/**
 * Generate URL for a route
 */
function url($path = '') {
    $baseUrl = 'http://localhost:8000'; // Adjust as needed
    return $baseUrl . '/' . ltrim($path, '/');
}

/**
 * Redirect helper
 */
function redirect($path, $statusCode = 302) {
    http_response_code($statusCode);
    header('Location: ' . url($path));
    exit;
}

/**
 * Asset helper
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}