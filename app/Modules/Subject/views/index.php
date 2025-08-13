<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Navigator App</title>
    
    <!-- Your existing CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <style>
        /* Custom DataTables integration with your design */
        .dataTables_wrapper {
            margin-top: 20px;
        }
        
        .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
        }
        
        .dataTables_filter input:focus {
            border-color: #3182ce;
            outline: none;
        }
        
        .dataTables_length select {
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            padding: 5px 10px;
        }
        
        .page-link {
            color: #3182ce;
        }
        
        .page-item.active .page-link {
            background-color: #3182ce;
            border-color: #3182ce;
        }
        
        table.dataTable thead th {
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            color: #4a5568;
        }
        
        table.dataTable tbody td {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .dt-buttons {
            margin-bottom: 15px;
        }
        
        .dt-button {
            background: #3182ce !important;
            color: white !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 8px 16px !important;
            margin-right: 10px !important;
            font-size: 14px !important;
        }
        
        .dt-button:hover {
            background: #2c5aa0 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
            <p class="page-subtitle">Manage subjects for the education system</p>
            <div style="margin-top: 15px;">
                <a href="/subjects/create" class="btn btn-success">
                    + Add New Subject
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($message) && $message): ?>
            <div class="alert alert-<?= $message['type'] === 'error' ? 'danger' : 'success' ?>">
                <?= htmlspecialchars($message['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Professional DataTable -->
        <div class="table-container">
            <?php if (!empty($subjects)): ?>
                <table id="subjectsTable" class="table table-striped table-hover">
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
                                    <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 12px;">
                                        <?= htmlspecialchars($subject['SubjectCode']) ?>
                                    </code>
                                </td>
                                <td>
                                    <?php if ($subject['IsActive']): ?>
                                        <span class="badge badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="/subjects/<?= $subject['SubjectID'] ?>/edit" 
                                           class="btn btn-primary btn-sm">Edit</a>
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
                <div class="text-center" style="padding: 40px 20px; color: #666;">
                    <h3 style="margin-bottom: 10px; color: #999;">No subjects found</h3>
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

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            $('#subjectsTable').DataTable({
                // Basic configuration
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                
                // Enable features
                searching: true,
                ordering: true,
                paging: true,
                info: true,
                responsive: true,
                
                // Column configuration
                columnDefs: [
                    {
                        targets: [0], // ID column
                        width: "80px"
                    },
                    {
                        targets: [4], // Status column  
                        width: "100px",
                        orderable: true
                    },
                    {
                        targets: [5], // Actions column
                        orderable: false,
                        searchable: false,
                        width: "150px"
                    }
                ],
                
                // Buttons for export
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copy Data'
                    },
                    {
                        extend: 'csv',
                        text: 'Export CSV'
                    },
                    {
                        extend: 'excel',
                        text: 'Export Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF',
                        orientation: 'landscape'
                    },
                    {
                        extend: 'print',
                        text: 'Print Table'
                    }
                ],
                
                // Order by ID descending by default
                order: [[0, 'desc']],
                
                // Custom language/text
                language: {
                    search: "Search subjects:",
                    lengthMenu: "Show _MENU_ subjects per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ subjects",
                    infoEmpty: "No subjects available",
                    infoFiltered: "(filtered from _MAX_ total subjects)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
</body>
</html>