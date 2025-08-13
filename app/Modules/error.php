<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .error-header {
            background: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .error-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .error-header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .error-content {
            padding: 30px;
        }
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .error-message h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .error-details {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            word-break: break-word;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .suggestions {
            margin-top: 20px;
            padding: 20px;
            background: #e7f3ff;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        
        .suggestions h4 {
            margin-bottom: 10px;
            color: #0056b3;
        }
        
        .suggestions ul {
            margin-left: 20px;
        }
        
        .suggestions li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-header">
            <h1>🚨 Application Error</h1>
            <p>Something went wrong while processing your request</p>
        </div>
        
        <div class="error-content">
            <div class="error-message">
                <h3>Error Details:</h3>
                <div class="error-details">
                    <?= htmlspecialchars($error ?? 'An unexpected error occurred. Please try again later.') ?>
                </div>
            </div>
            
            <?php if (isset($file) && isset($line)): ?>
            <div class="error-message">
                <h3>Location:</h3>
                <div class="error-details">
                    <strong>File:</strong> <?= htmlspecialchars($file) ?><br>
                    <strong>Line:</strong> <?= htmlspecialchars($line) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="actions">
                <a href="/" class="btn btn-primary">🏠 Go Home</a>
                <a href="javascript:history.back()" class="btn btn-secondary">← Go Back</a>
            </div>
            
            <div class="suggestions">
                <h4>💡 What you can do:</h4>
                <ul>
                    <li>Check if the URL is correct</li>
                    <li>Try refreshing the page</li>
                    <li>Go back to the previous page and try again</li>
                    <li>Contact support if the problem persists</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>