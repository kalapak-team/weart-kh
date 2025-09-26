<?php
session_start();
require_once '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// Get counts for dashboard
try {
    $artworks_count = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
    $artists_count = $pdo->query("SELECT COUNT(*) FROM artists")->fetchColumn();
} catch(PDOException $e) {
    $artworks_count = 0;
    $artists_count = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WeArt</title>
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
                        <a class="nav-link" href="pages.php">Pages</a>
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
        <h1 class="section-title">Admin Dashboard</h1>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <i class="fas fa-paint-brush"></i>
                    <h3><?php echo $artworks_count; ?></h3>
                    <p>Artworks</p>
                    <a href="gallery.php" class="btn btn-primary">Manage Artworks</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card">
                    <i class="fas fa-user"></i>
                    <h3><?php echo $artists_count; ?></h3>
                    <p>Artists</p>
                    <a href="artists.php" class="btn btn-primary">Manage Artists</a>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <h3>Quick Actions</h3>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <a href="gallery.php?action=add" class="btn btn-primary">Add New Artwork</a>
                        <a href="artists.php?action=add" class="btn btn-primary">Add New Artist</a>
                        <a href="pages.php" class="btn btn-primary">Edit Pages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>