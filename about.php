<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title">About WeArt</h1>
            <div class="about-content">
                <?php
                if (!empty($about_content)) {
                    echo $about_content;
                } else {
                    echo '
                    <h2>Our Mission</h2>
                    <p>WeArt is dedicated to preserving and promoting Khmer traditional arts. Our mission is to showcase the beauty and cultural significance of Cambodian artistic heritage.</p>
                    
                    <h2>History</h2>
                    <p>Founded in 2023, WeArt emerged from a passion for Cambodian culture and a desire to ensure that traditional art forms continue to thrive in the modern world.</p>
                    
                    <h2>Cultural Context</h2>
                    <p>Khmer art has a rich history dating back to the Angkor period. It encompasses various forms including sculpture, weaving, ceramics, and performance arts.</p>
                    ';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>