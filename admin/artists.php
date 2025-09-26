<?php
session_start();
require_once '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_artist'])) {
        // Add new artist
        $name = trim($_POST['name']);
        $bio = trim($_POST['bio']);
        
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                $image = $file_name;
            } else {
                $error = 'Failed to upload image.';
            }
        }
        
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO artists (name, bio, image) VALUES (?, ?, ?)");
                $stmt->execute([$name, $bio, $image]);
                $message = 'Artist added successfully!';
                $action = 'list'; // Switch back to list view
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    } elseif (isset($_POST['edit_artist'])) {
        // Edit artist
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $bio = trim($_POST['bio']);
        
        // Handle image upload if a new image is provided
        $image = $_POST['current_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                // Delete old image
                if (!empty($image) && file_exists($upload_dir . $image)) {
                    unlink($upload_dir . $image);
                }
                $image = $file_name;
            } else {
                $error = 'Failed to upload image.';
            }
        }
        
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE artists SET name = ?, bio = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $bio, $image, $id]);
                $message = 'Artist updated successfully!';
                $action = 'list'; // Switch back to list view
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    try {
        // Get image filename to delete
        $stmt = $pdo->prepare("SELECT image FROM artists WHERE id = ?");
        $stmt->execute([$id]);
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($artist && !empty($artist['image']) && file_exists('../uploads/' . $artist['image'])) {
            unlink('../uploads/' . $artist['image']);
        }
        
        // Delete artist from database
        $stmt = $pdo->prepare("DELETE FROM artists WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Artist deleted successfully!';
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Get artist for editing
$edit_artist = null;
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM artists WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $edit_artist = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$edit_artist) {
            $error = 'Artist not found.';
            $action = 'list';
        }
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
        $action = 'list';
    }
}

// Get all artists for listing
$artists = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM artists ORDER BY name");
        $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = 'Failed to load artists: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Artists - WeArt Admin</title>
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
                        <a class="nav-link active" href="artists.php">Artists</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title">Manage Artists</h1>
            <a href="artists.php?action=add" class="btn btn-primary">Add New Artist</a>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($action === 'list'): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Bio</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($artists)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No artists found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($artists as $artist): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($artist['image'])): ?>
                                            <img src="../uploads/<?php echo htmlspecialchars($artist['image']); ?>" alt="<?php echo htmlspecialchars($artist['name']); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                        <?php else: ?>
                                            <div style="width: 80px; height: 80px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($artist['name']); ?></td>
                                    <td><?php echo strlen($artist['bio']) > 100 ? substr(htmlspecialchars($artist['bio']), 0, 100) . '...' : htmlspecialchars($artist['bio']); ?></td>
                                    <td>
                                        <a href="artists.php?action=edit&id=<?php echo $artist['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="artists.php?delete=<?php echo $artist['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this artist?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo $action === 'add' ? 'Add New Artist' : 'Edit Artist'; ?></h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <?php if ($action === 'edit'): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_artist['id']; ?>">
                                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($edit_artist['image']); ?>">
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $action === 'edit' ? htmlspecialchars($edit_artist['name']) : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" required><?php echo $action === 'edit' ? htmlspecialchars($edit_artist['bio']) : ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" <?php echo $action === 'add' ? 'required' : ''; ?>>
                                    
                                    <?php if ($action === 'edit' && !empty($edit_artist['image'])): ?>
                                        <div class="mt-2">
                                            <img src="../uploads/<?php echo htmlspecialchars($edit_artist['image']); ?>" alt="Current image" style="max-width: 200px; border-radius: 50%;">
                                            <p class="text-muted">Current image</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" name="<?php echo $action === 'add' ? 'add_artist' : 'edit_artist'; ?>" class="btn btn-primary">Save Artist</button>
                                <a href="artists.php" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>