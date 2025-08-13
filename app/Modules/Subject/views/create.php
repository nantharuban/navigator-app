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
                <a href="/subjects">Subjects</a> / Add New
            </div>
            <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
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

        <!-- Create Form -->
        <div class="card-form">
            <form method="POST" action="/subjects/store">
                <div class="form-group">
                    <label for="SubjectName" class="form-label required">Subject Name</label>
                    <input 
                        type="text" 
                        id="SubjectName" 
                        name="SubjectName" 
                        class="form-control <?= !empty($errors) && in_array('Subject Name already exists', $errors) ? 'error' : '' ?>"
                        value="<?= htmlspecialchars($old['SubjectName'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($old['SubjectShortName'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($old['SubjectCode'] ?? '') ?>"
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
                            <?= (!isset($old['IsActive']) || $old['IsActive']) ? 'checked' : '' ?>
                        >
                        <label for="IsActive" class="form-label">Active</label>
                    </div>
                    <div class="form-text">Check to make this subject active and available for use.</div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        Create Subject
                    </button>
                    <a href="/subjects" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-generate subject code from subject name
        document.getElementById('SubjectName').addEventListener('input', function() {
            const subjectName = this.value;
            const codeField = document.getElementById('SubjectCode');
            
            // Only auto-generate if code field is empty
            if (!codeField.value) {
                // Take first 3 letters and add 01
                const code = subjectName.replace(/[^a-zA-Z]/g, '').substring(0, 3).toUpperCase() + '01';
                codeField.value = code;
            }
        });

        // Auto-generate short name from subject name
        document.getElementById('SubjectName').addEventListener('input', function() {
            const subjectName = this.value;
            const shortNameField = document.getElementById('SubjectShortName');
            
            // Only auto-generate if short name field is empty
            if (!shortNameField.value) {
                // Take first word or first 4 characters
                const words = subjectName.split(' ');
                const shortName = words[0] ? words[0].substring(0, 4) : subjectName.substring(0, 4);
                shortNameField.value = shortName;
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
        });
    </script>
</body>
</html>