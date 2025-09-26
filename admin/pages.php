<?php
session_start();
require_once '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about_content = trim($_POST['about_content']);
    $contact_info = trim($_POST['contact_info']);
    
    try {
        // Update about page
        $stmt = $pdo->prepare("UPDATE pages SET content = ? WHERE page_name = 'about'");
        $stmt->execute([$about_content]);
        
        // Update contact page
        $stmt = $pdo->prepare("UPDATE pages SET content = ? WHERE page_name = 'contact'");
        $stmt->execute([$contact_info]);
        
        $message = 'Pages updated successfully!';
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Get current page content
$about_content = '';
$contact_info = '';

try {
    $stmt = $pdo->query("SELECT * FROM pages WHERE page_name = 'about'");
    $about_page = $stmt->fetch(PDO::FETCH_ASSOC);
    $about_content = $about_page ? $about_page['content'] : '';
    
    $stmt = $pdo->query("SELECT * FROM pages WHERE page_name = 'contact'");
    $contact_page = $stmt->fetch(PDO::FETCH_ASSOC);
    $contact_info = $contact_page ? $contact_page['content'] : '';
} catch(PDOException $e) {
    $error = 'Failed to load page content: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pages - WeArt Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.png">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-palette me-2"></i>WeArt Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Artworks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artists.php">Artists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="pages.php">Pages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php" target="_blank">View Site</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="section-title">Manage Pages</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>About Page</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="about_content" class="form-label">About Content</label>
                                <textarea class="form-control" id="about_content" name="about_content" rows="10" required><?php echo htmlspecialchars($about_content); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Contact Page</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="contact_info" class="form-label">Contact Information</label>
                                <textarea class="form-control" id="contact_info" name="contact_info" rows="10" required><?php echo htmlspecialchars($contact_info); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>