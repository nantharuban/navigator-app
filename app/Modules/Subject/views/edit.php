<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Navigator App</title>
    
    <!-- Link to our reusable CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/forms.css">
</head>
<body>
    <div class="container form-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb">
                <a href="/subjects">Subjects</a> / Edit
            </div>
            <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
            <p class="page-subtitle">Update subject information</p>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (isset($message) && $message): ?>
            <div class="alert alert-<?= $message['type'] === 'error' ? 'danger' : 'success' ?>">
                <?= htmlspecialchars($message['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Edit Form -->
        <div class="card-form">
            <form method="POST" action="/subjects/<?= $subject['SubjectID'] ?>/update">
                <input type="hidden" name="_method" value="PUT">
                
                <div class="form-group">
                    <label for="SubjectName" class="form-label required">Subject Name</label>
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
                    <label for="SubjectShortName" class="form-label">Subject Short Name</label>
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
                    <label for="SubjectCode" class="form-label required">Subject Code</label>
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
                            class="form-checkbox"
                            <?= ($old['IsActive'] ?? $subject['IsActive']) ? 'checked' : '' ?>
                        >
                        <label for="IsActive" class="form-label">Active</label>
                    </div>
                    <div class="form-text">Check to make this subject active and available for use.</div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        Update Subject
                    </button>
                    <a href="/subjects" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
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
        });

        // Optional: Warn user about changes when leaving page
        let formChanged = false;
        const formInputs = document.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                formChanged = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Reset formChanged when form is submitted
        document.querySelector('form').addEventListener('submit', function() {
            formChanged = false;
        });
    </script>
</body>
</html>