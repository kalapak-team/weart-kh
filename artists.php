<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <h1 class="section-title">Our Artists</h1>
    
    <div class="row">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM artists ORDER BY name");
            $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($artists) {
                foreach ($artists as $artist) {
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="card artist-card text-center">
                            <img src="uploads/' . htmlspecialchars($artist['image']) . '" class="card-img-top" alt="' . htmlspecialchars($artist['name']) . '">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($artist['name']) . '</h5>
                                <p class="card-text">' . htmlspecialchars($artist['bio']) . '</p>
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
</div>

<?php include 'includes/footer.php'; ?>