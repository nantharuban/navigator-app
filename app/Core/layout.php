<?php
// app/Core/layout.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Navigator App') ?> - Navigator App</title>
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <!-- Additional CSS files -->
    <?php if (isset($cssFiles) && is_array($cssFiles)): ?>
        <?php foreach ($cssFiles as $css): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation (optional for future) -->
    <?php if (isset($showNav) && $showNav): ?>
        <nav class="main-nav">
            <div class="nav-container">
                <div class="nav-brand">
                    <a href="/">Navigator App</a>
                </div>
                <ul class="nav-menu">
                    <li><a href="/subjects" class="nav-link">Subjects</a></li>
                    <li><a href="/grades" class="nav-link">Grades</a></li>
                    <li><a href="/competencies" class="nav-link">Competencies</a></li>
                </ul>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="<?= $containerClass ?? 'container' ?>">
        <?php
        // Include the specific view content
        if (isset($viewFile) && file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo $content ?? '';
        }
        ?>
    </div>

    <!-- Footer -->
    <?php if (isset($showFooter) && $showFooter): ?>
        <footer class="main-footer">
            <div class="container">
                <p>&copy; <?= date('Y') ?> Navigator App. All rights reserved.</p>
            </div>
        </footer>
    <?php endif; ?>

    <!-- JavaScript -->
    <?php if (isset($jsFiles) && is_array($jsFiles)): ?>
        <?php foreach ($jsFiles as $js): ?>
            <script src="<?= htmlspecialchars($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Inline JavaScript -->
    <?php if (isset($inlineJs)): ?>
        <script><?= $inlineJs ?></script>
    <?php endif; ?>
</body>
</html>