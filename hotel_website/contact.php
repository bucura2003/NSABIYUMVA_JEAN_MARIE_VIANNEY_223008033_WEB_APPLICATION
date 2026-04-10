<?php include 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    
    $errors = [];
    if (empty($full_name)) $errors[] = "Full name required";
    if (empty($email)) $errors[] = "Email required";
    if (empty($phone)) $errors[] = "Phone required";
    if (empty($message_text)) $errors[] = "Message required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO contacts (full_name, email, phone, location, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $phone, $location, $message_text);
        
        if ($stmt->execute()) {
            $message = "✅ Message sent successfully! We will get back to you soon.";
            $message_type = "success";
            $_POST = array();
        } else {
            $message = "❌ Error: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    } else {
        $message = "❌ " . implode(", ", $errors);
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Hotel Paradise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="nav">
            <div class="logo">Hotel <span>Paradise</span></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="order.php">Order</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php" class="active">Contact Us</a></li>
                <li><a href="login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <h1 class="section-title">Contact Us</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Phone *</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" placeholder="City/Area">
                </div>
                
                <div class="form-group">
                    <label>Message *</label>
                    <textarea name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" name="submit_contact" class="btn">Send Message</button>
            </form>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Hotel Paradise. All rights reserved.</p>
    </div>
    
    <script src="script.js"></script>
</body>
</html>