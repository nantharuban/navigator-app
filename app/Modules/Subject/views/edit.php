<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Navigator App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .breadcrumb {
            color: #666;
            font-size: 14px;
        }
        
        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-group label.required::after {
            content: " *";
            color: #e74c3c;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-control.error {
            border-color: #e74c3c;
        }
        
        .form-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn-warning {
            background: #f39c12;
        }
        
        .btn-warning:hover {
            background: #e67e22;
        }
        
        .btn-secondary {
            background: #95a5a6;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .alert ul li {
            margin-bottom: 5px;
        }
        
        .info-box {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-box h4 {
            color: #0c5460;
            margin-bottom: 5px;
        }
        
        .info-box p {
            color: #0c5460;
            font-size: 14px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="breadcrumb">
                <a href="/subjects">Subjects</a> / Edit
            </div>
            <h1><?= htmlspecialchars($title) ?></h1>
        </div>

        <!-- Subject Info -->
        <div class="info-box">
            <h4>Editing Subject ID: <?= htmlspecialchars($subject['SubjectID']) ?></h4>
            <p>Make changes to the subject information below.</p>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Edit Form -->
        <div class="card">
            <form method="POST" action="/subjects/<?= $subject['SubjectID'] ?>/update">
                <div class="form-group">
                    <label for="SubjectName" class="required">Subject Name</label>
                    <input 
                        type="text" 
                        id="SubjectName" 
                        name="SubjectName" 
                        class="form-control <?= !empty($errors) && in_array('Subject Name already exists', $errors) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($old['SubjectName'] ?? $subject['SubjectName']) ?>"
                        placeholder="Enter subject name (e.g., Mathematics, Science)"
                        maxlength="100"
                        required
                    >
                    <div class="form-text">Maximum 100 characters. Must be unique.</div>
                </div>

                <div class="form-group">
                    <label for="SubjectShortName">Subject Short Name</label>
                    <input 
                        type="text" 
                        id="SubjectShortName" 
                        name="SubjectShortName" 
                        class="form-control"
                        value="<?= htmlspecialchars($old['SubjectShortName'] ?? $subject['SubjectShortName']) ?>"
                        placeholder="Enter short name (e.g., Math, Sci)"
                        maxlength="20"
                    >
                    <div class="form-text">Optional. Maximum 20 characters.</div>
                </div>

                <div class="form-group">
                    <label for="SubjectCode" class="required">Subject Code</label>
                    <input 
                        type="text" 
                        id="SubjectCode" 
                        name="SubjectCode" 
                        class="form-control <?= !empty($errors) && in_array('Subject Code already exists', $errors) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($old['SubjectCode'] ?? $subject['SubjectCode']) ?>"
                        placeholder="Enter subject code (e.g., MATH01, SCI01)"
                        maxlength="20"
                        required
                    >
                    <div class="form-text">Maximum 20 characters. Must be unique.</div>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="IsActive" 
                            name="IsActive" 
                            value="1"
                            <?php 
                            $isActive = isset($old['IsActive']) ? $old['IsActive'] : $subject['IsActive'];
                            echo $isActive ? 'checked' : '';
                            ?>
                        >
                        <label for="IsActive">Active</label>
                    </div>
                    <div class="form-text">Check to make this subject active and available for use.</div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-warning">
                        Update Subject
                    </button>
                    <a href="/subjects" class="btn btn-secondary">
                        Cancel
                    </a>
                    <a href="/subjects/<?= $subject['SubjectID'] ?>/delete" 
                       class="btn"
                       style="background: #e74c3c;"
                       onclick="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                        Delete Subject
                    </a>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; font-size: 12px; color: #666;">
            <strong>Subject Information:</strong><br>
            Created: <?= isset($subject['CreatedAt']) ? date('M d, Y g:i A', strtotime($subject['CreatedAt'])) : 'N/A' ?><br>
            Last Updated: <?= isset($subject['UpdatedAt']) ? date('M d, Y g:i A', strtotime($subject['UpdatedAt'])) : 'N/A' ?><br>
            Status: <?= $subject['IsActive'] ? '<span style="color: #27ae60;">Active</span>' : '<span style="color: #e74c3c;">Inactive</span>' ?>
        </div>
    </div>

    <script>
        // Track original values to detect changes
        const originalValues = {
            SubjectName: <?= json_encode($subject['SubjectName']) ?>,
            SubjectShortName: <?= json_encode($subject['SubjectShortName']) ?>,
            SubjectCode: <?= json_encode($subject['SubjectCode']) ?>,
            IsActive: <?= json_encode((bool)$subject['IsActive']) ?>
        };

        // Check for unsaved changes
        function hasUnsavedChanges() {
            const currentValues = {
                SubjectName: document.getElementById('SubjectName').value,
                SubjectShortName: document.getElementById('SubjectShortName').value,
                SubjectCode: document.getElementById('SubjectCode').value,
                IsActive: document.getElementById('IsActive').checked
            };

            return JSON.stringify(originalValues) !== JSON.stringify(currentValues);
        }

        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const subjectName = document.getElementById('SubjectName').value.trim();
            const subjectCode = document.getElementById('SubjectCode').value.trim();
            
            if (!subjectName) {
                alert('Subject Name is required');
                e.preventDefault();
                return;
            }
            
            if (!subjectCode) {
                alert('Subject Code is required');
                e.preventDefault();
                return;
            }

            // Remove beforeunload listener when submitting
            window.removeEventListener('beforeunload', arguments.callee);
        });

        // Cancel button warning
        document.querySelector('a[href="/subjects"]').addEventListener('click', function(e) {
            if (hasUnsavedChanges()) {
                if (!confirm('You have unsaved changes. Are you sure you want to leave?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>