<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">WeArt</h1>
            <p class="hero-subtitle">Preserving the Beauty of Khmer Traditional Arts</p>
            <a href="gallery.php" class="btn btn-primary">Explore Gallery</a>
        </div>
    </div>
</section>

<!-- Featured Artworks -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Featured Artworks</h2>
        <div class="row">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC LIMIT 3");
                $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($artworks) {
                    foreach ($artworks as $artwork) {
                        echo '
                        <div class="col-md-4">
                            <div class="card">
                                <img src="uploads/' . htmlspecialchars($artwork['image']) . '" class="card-img-top" alt="' . htmlspecialchars($artwork['title']) . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($artwork['title']) . '</h5>
                                    <p class="card-text">' . substr(htmlspecialchars($artwork['description']), 0, 100) . '...</p>
                                    <a href="gallery.php" class="btn btn-sm btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">No artworks found.</p></div>';
                }
            } catch(PDOException $e) {
                echo '<div class="col-12"><p class="text-center">Unable to load artworks.</p></div>';
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="gallery.php" class="btn btn-primary">View All Artworks</a>
        </div>
    </div>
</section>

<!-- Featured Artists -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Featured Artists</h2>
        <div class="row">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM artists ORDER BY created_at DESC LIMIT 3");
                $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($artists) {
                    foreach ($artists as $artist) {
                        echo '
                        <div class="col-md-4">
                            <div class="card artist-card text-center">
                                <img src="uploads/' . htmlspecialchars($artist['image']) . '" class="card-img-top" alt="' . htmlspecialchars($artist['name']) . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($artist['name']) . '</h5>
                                    <p class="card-text">' . substr(htmlspecialchars($artist['bio']), 0, 100) . '...</p>
                                    <a href="artists.php" class="btn btn-sm btn-primary">View Profile</a>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12"><p class="text-center">No artists found.</p></div>';
                }
            } catch(PDOException $e) {
                echo '<div class="col-12"><p class="text-center">Unable to load artists.</p></div>';
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="artists.php" class="btn btn-primary">View All Artists</a>
        </div>
    </div>
</section>

<!-- About Preview -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="section-title">About WeArt</h2>
                <?php
                if (!empty($about_content)) {
                    echo $about_content;
                } else {
                    echo '<p>WeArt is dedicated to preserving and promoting Khmer traditional arts. Our mission is to showcase the beauty and cultural significance of Cambodian artistic heritage.</p>';
                }
                ?>
                <a href="about.php" class="btn btn-primary">Learn More</a>
            </div>
            <div class="col-md-6">
                <img src="assets/images/about-preview.jpg" alt="About WeArt" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>