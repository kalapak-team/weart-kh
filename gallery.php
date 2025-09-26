<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <h1 class="section-title">Art Gallery</h1>
    
    <div class="row">
        <?php
        try {
            $stmt = $pdo->query("SELECT gallery.*, artists.name as artist_name 
                                FROM gallery 
                                LEFT JOIN artists ON gallery.artist_id = artists.id 
                                ORDER BY created_at DESC");
            $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($artworks) {
                foreach ($artworks as $artwork) {
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="gallery-item">
                            <img src="uploads/' . htmlspecialchars($artwork['image']) . '" alt="' . htmlspecialchars($artwork['title']) . '">
                            <div class="gallery-overlay">
                                <div class="overlay-content text-center">
                                    <h5>' . htmlspecialchars($artwork['title']) . '</h5>';
                    
                    if (!empty($artwork['artist_name'])) {
                        echo '<p>By: ' . htmlspecialchars($artwork['artist_name']) . '</p>';
                    }
                    
                    echo '<a href="uploads/' . htmlspecialchars($artwork['image']) . '" data-lightbox="gallery" data-title="' . htmlspecialchars($artwork['title']) . '" class="btn btn-sm btn-primary">View Larger</a>
                                </div>
                            </div>
                        </div>
                        <div class="artwork-info mt-2">
                            <h5>' . htmlspecialchars($artwork['title']) . '</h5>';
                    
                    if (!empty($artwork['artist_name'])) {
                        echo '<p class="text-muted">By: ' . htmlspecialchars($artwork['artist_name']) . '</p>';
                    }
                    
                    echo '<p>' . htmlspecialchars($artwork['description']) . '</p>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-center">No artworks found in the gallery.</p></div>';
            }
        } catch(PDOException $e) {
            echo '<div class="col-12"><p class="text-center">Unable to load gallery.</p></div>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>