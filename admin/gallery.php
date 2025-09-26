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
    if (isset($_POST['add_artwork'])) {
        // Add new artwork
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $artist_id = !empty($_POST['artist_id']) ? $_POST['artist_id'] : null;
        
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
                $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image, artist_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $description, $image, $artist_id]);
                $message = 'Artwork added successfully!';
                $action = 'list'; // Switch back to list view
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    } elseif (isset($_POST['edit_artwork'])) {
        // Edit artwork
        $id = $_POST['id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $artist_id = !empty($_POST['artist_id']) ? $_POST['artist_id'] : null;
        
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
                $stmt = $pdo->prepare("UPDATE gallery SET title = ?, description = ?, image = ?, artist_id = ? WHERE id = ?");
                $stmt->execute([$title, $description, $image, $artist_id, $id]);
                $message = 'Artwork updated successfully!';
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
        $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $artwork = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($artwork && !empty($artwork['image']) && file_exists('../uploads/' . $artwork['image'])) {
            unlink('../uploads/' . $artwork['image']);
        }
        
        // Delete artwork from database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Artwork deleted successfully!';
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Get artists for dropdown
$artists = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM artists ORDER BY name");
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = 'Failed to load artists: ' . $e->getMessage();
}

// Get artwork for editing
$edit_artwork = null;
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $edit_artwork = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$edit_artwork) {
            $error = 'Artwork not found.';
            $action = 'list';
        }
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
        $action = 'list';
    }
}

// Get all artworks for listing
$artworks = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT gallery.*, artists.name as artist_name 
                            FROM gallery 
                            LEFT JOIN artists ON gallery.artist_id = artists.id 
                            ORDER BY created_at DESC");
        $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = 'Failed to load artworks: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - WeArt Admin</title>
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
                        <a class="nav-link active" href="gallery.php">Artworks</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title">Manage Artworks</h1>
            <a href="gallery.php?action=add" class="btn btn-primary">Add New Artwork</a>
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
                            <th>Title</th>
                            <th>Artist</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($artworks)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No artworks found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($artworks as $artwork): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($artwork['image'])): ?>
                                            <img src="../uploads/<?php echo htmlspecialchars($artwork['image']); ?>" alt="<?php echo htmlspecialchars($artwork['title']); ?>" style="width: 80px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 80px; height: 60px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($artwork['title']); ?></td>
                                    <td><?php echo !empty($artwork['artist_name']) ? htmlspecialchars($artwork['artist_name']) : 'Unknown'; ?></td>
                                    <td>
                                        <a href="gallery.php?action=edit&id=<?php echo $artwork['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="gallery.php?delete=<?php echo $artwork['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this artwork?')">Delete</a>
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
                            <h3><?php echo $action === 'add' ? 'Add New Artwork' : 'Edit Artwork'; ?></h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <?php if ($action === 'edit'): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_artwork['id']; ?>">
                                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($edit_artwork['image']); ?>">
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $action === 'edit' ? htmlspecialchars($edit_artwork['title']) : ''; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $action === 'edit' ? htmlspecialchars($edit_artwork['description']) : ''; ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="artist_id" class="form-label">Artist (optional)</label>
                                    <select class="form-control" id="artist_id" name="artist_id">
                                        <option value="">Select an artist</option>
                                        <?php foreach ($artists as $artist): ?>
                                            <option value="<?php echo $artist['id']; ?>" <?php echo ($action === 'edit' && $edit_artwork['artist_id'] == $artist['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($artist['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" <?php echo $action === 'add' ? 'required' : ''; ?>>
                                    
                                    <?php if ($action === 'edit' && !empty($edit_artwork['image'])): ?>
                                        <div class="mt-2">
                                            <img src="../uploads/<?php echo htmlspecialchars($edit_artwork['image']); ?>" alt="Current image" style="max-width: 200px;">
                                            <p class="text-muted">Current image</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" name="<?php echo $action === 'add' ? 'add_artwork' : 'edit_artwork'; ?>" class="btn btn-primary">Save Artwork</button>
                                <a href="gallery.php" class="btn btn-secondary">Cancel</a>
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