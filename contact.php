<?php
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real application, you would process the form (send email, save to database, etc.)
        $success = 'Thank you for your message! We will get back to you soon.';
        
        // Clear form fields
        $name = $email = $message = '';
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title">Contact Us</h1>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-info">
                        <?php
                        if (!empty($contact_info)) {
                            echo $contact_info;
                        } else {
                            echo '
                            <h3>Get In Touch</h3>
                            <p><i class="fas fa-map-marker-alt me-2"></i> Phnom Penh, Cambodia</p>
                            <p><i class="fas fa-phone me-2"></i> +855 123 456 789</p>
                            <p><i class="fas fa-envelope me-2"></i> info@weart.com</p>
                            ';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="contact-form">
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Your Name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>