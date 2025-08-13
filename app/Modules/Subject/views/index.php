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
            max-width: 1200px;
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
            margin-bottom: 10px;
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
        
        .btn-success {
            background: #27ae60;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-danger {
            background: #e74c3c;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .table tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><?= htmlspecialchars($title) ?></h1>
            <p>Manage subjects for the education system</p>
            <div style="margin-top: 15px;">
                <a href="/subjects/create" class="btn btn-success">
                    + Add New Subject
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($message) && $message): ?>
            <div class="alert alert-<?= $message['type'] === 'error' ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Subjects Table -->
        <div class="card">
            <?php if (!empty($subjects)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject Name</th>
                            <th>Short Name</th>
                            <th>Subject Code</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= htmlspecialchars($subject['SubjectID']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($subject['SubjectName']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($subject['SubjectShortName'] ?? '-') ?></td>
                                <td>
                                    <code><?= htmlspecialchars($subject['SubjectCode']) ?></code>
                                </td>
                                <td>
                                    <?php if ($subject['IsActive']): ?>
                                        <span class="badge badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="/subjects/<?= $subject['SubjectID'] ?>/edit" 
                                           class="btn btn-sm">Edit</a>
                                        <a href="/subjects/<?= $subject['SubjectID'] ?>/delete" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this subject?')">
                                           Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No subjects found</h3>
                    <p>Get started by creating your first subject.</p>
                    <div style="margin-top: 20px;">
                        <a href="/subjects/create" class="btn btn-success">
                            + Add New Subject
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>