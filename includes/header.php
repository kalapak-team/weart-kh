<?php
// Include database connection
require_once __DIR__ . '/../config/db.php';

// Get page content from database
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
    // Handle error silently for frontend
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeArt - Khmer Traditional Art Showcase</title>

    <!-- SEO & Social Sharing -->
    <meta name="description" content="WeArt – Khmer Traditional Art Showcase. Explore Khmer artists, galleries, and cultural heritage.">
    <meta name="keywords" content="WeArt, Khmer Art, Cambodia, Traditional Art, Artists, Gallery">

    <!-- Open Graph (Facebook, Telegram, LinkedIn) -->
    <meta property="og:title" content="WeArt - Khmer Traditional Art Showcase">
    <meta property="og:description" content="Discover traditional Khmer art, artists, and galleries. A digital home for Cambodian culture.">
    <meta property="og:image" content="https://yourdomain.com/assets/images/logo.png">
    <meta property="og:url" content="https://yourdomain.com/">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="WeArt - Khmer Traditional Art Showcase">
    <meta name="twitter:description" content="Discover traditional Khmer art, artists, and galleries.">
    <meta name="twitter:image" content="https://yourdomain.com/assets/images/logo.png">

    <!-- Bootstrap / Fonts / CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-palette me-2"></i>WeArt
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artists.php">Artists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-admin" href="admin/index.php">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
