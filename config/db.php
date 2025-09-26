<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'weart_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    createTables($pdo);
} catch(PDOException $e) {
    // If database doesn't exist, create it
    if ($e->getCode() == 1049) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE " . DB_NAME);
            
            // Create tables
            createTables($pdo);
            
            // Insert sample data
            insertSampleData($pdo);
            
            // Reconnect to the new database
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("ERROR: Could not create database. " . $e->getMessage());
        }
    } else {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
}

// Function to create tables
function createTables($pdo) {
    // Admin table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // Artists table
    $sql = "CREATE TABLE IF NOT EXISTS artists (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        bio TEXT,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // Gallery table
    $sql = "CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        image VARCHAR(255),
        artist_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    
    // Pages table
    $sql = "CREATE TABLE IF NOT EXISTS pages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_name VARCHAR(50) NOT NULL UNIQUE,
        content TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
}

// Function to insert sample data
function insertSampleData($pdo) {
    // Insert default admin (password: admin123)
    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($checkAdmin == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO admins (username, password) VALUES ('admin', '$hashedPassword')";
        $pdo->exec($sql);
    }
    
    // Insert default pages if not exists
    $pages = ['about', 'contact'];
    foreach ($pages as $page) {
        $checkPage = $pdo->query("SELECT COUNT(*) FROM pages WHERE page_name = '$page'")->fetchColumn();
        if ($checkPage == 0) {
            $content = $page == 'about' 
                ? '<h2>About WeArt</h2><p>WeArt is dedicated to preserving and promoting Khmer traditional arts. Our mission is to showcase the beauty and cultural significance of Cambodian artistic heritage.</p><h3>Our History</h3><p>Founded in 2023, WeArt emerged from a passion for Cambodian culture and a desire to ensure that traditional art forms continue to thrive in the modern world.</p><h3>Cultural Context</h3><p>Khmer art has a rich history dating back to the Angkor period. It encompasses various forms including sculpture, weaving, ceramics, and performance arts.</p>' 
                : '<h2>Contact Information</h2><p><strong>Address:</strong> Phnom Penh, Cambodia</p><p><strong>Email:</strong> info@weart.com</p><p><strong>Phone:</strong> +855 123 456 789</p><h3>Visit Our Gallery</h3><p>We welcome visitors to our gallery to experience the beauty of Khmer traditional art firsthand.</p>';
            $sql = "INSERT INTO pages (page_name, content) VALUES ('$page', '$content')";
            $pdo->exec($sql);
        }
    }
    
    // Insert sample artists if not exists
    $checkArtists = $pdo->query("SELECT COUNT(*) FROM artists")->fetchColumn();
    if ($checkArtists == 0) {
        $artists = [
            ['Sokha Chen', 'Master of traditional Khmer painting techniques with over 20 years of experience. Specializes in depicting scenes from Cambodian mythology and daily life.', 'artist1.jpg'],
            ['Bopha Srey', 'Renowned ceramic artist who revives ancient Khmer pottery techniques. Her work has been exhibited internationally.', 'artist2.jpg'],
            ['Rithy Sam', 'Expert in traditional Khmer textile arts, particularly ikat weaving and natural dyeing processes.', 'artist3.jpg']
        ];
        
        foreach ($artists as $artist) {
            $sql = "INSERT INTO artists (name, bio, image) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($artist);
        }
    }
    
    // Insert sample artworks if not exists
    $checkArtworks = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
    if ($checkArtworks == 0) {
        $artworks = [
            ['Apsara Dancer', 'Traditional depiction of a celestial dancer from Khmer mythology, inspired by the bas-reliefs of Angkor Wat.', 'artwork1.jpg', 1],
            ['Ancient Pottery', 'Recreation of 12th century Khmer pottery using traditional techniques and materials.', 'artwork2.jpg', 2],
            ['Ikat Weaving', 'Intricately patterned textile created using the traditional ikat dyeing technique.', 'artwork3.jpg', 3],
            ['Floating Village', 'Scene depicting life on Tonlé Sap lake, showcasing the unique aquatic culture of Cambodia.', 'artwork4.jpg', 1],
            ['Angkor Sunrise', 'Silhouette of Angkor Wat at sunrise, capturing the mystical atmosphere of the ancient temple complex.', 'artwork5.jpg', 1]
        ];
        
        foreach ($artworks as $artwork) {
            $sql = "INSERT INTO gallery (title, description, image, artist_id) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($artwork);
        }
    }
}

// Function to get page content
function getPageContent($pdo, $page_name) {
    try {
        $stmt = $pdo->prepare("SELECT content FROM pages WHERE page_name = ?");
        $stmt->execute([$page_name]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
        return $page ? $page['content'] : '';
    } catch(PDOException $e) {
        return '';
    }
}

// Get page content for frontend
$about_content = getPageContent($pdo, 'about');
$contact_info = getPageContent($pdo, 'contact');
?>