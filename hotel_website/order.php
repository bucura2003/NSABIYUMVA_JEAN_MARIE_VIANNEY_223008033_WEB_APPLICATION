<?php include 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $menu_item = trim($_POST['menu_item'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $order_date = trim($_POST['order_date'] ?? '');
    
    $errors = [];
    if (empty($full_name)) $errors[] = "Full name required";
    if (empty($email)) $errors[] = "Email required";
    if (empty($phone)) $errors[] = "Phone required";
    if (empty($menu_item)) $errors[] = "Please select a menu item";
    if (empty($address)) $errors[] = "Address required";
    if (empty($order_date)) $errors[] = "Order date required";
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO orders (full_name, email, phone, menu_item, address, order_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $full_name, $email, $phone, $menu_item, $address, $order_date);
        
        if ($stmt->execute()) {
            $message = "✅ Order placed successfully! We will contact you soon.";
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


$menu_items = [
    'Grilled Salmon', 'Fish & Chips', 'Spicy Fish Curry', 'Fish Tikka',
    'Fresh Lemonade', 'Iced Coffee', 'Soft Drinks', 'Milkshake',
    'Orange Juice', 'Mango Juice', 'Mixed Fruit Juice', 'Watermelon Juice',
    'Garlic Bread', 'Spring Rolls', 'Chicken Wings',
    'Chicken Biryani', 'Butter Chicken', 'Grilled Steak',
    'Chocolate Cake', 'Ice Cream', 'Fruit Salad'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Online | Hotel Paradise</title>
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
                <li><a href="order.php" class="active">Order</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <h1 class="section-title">Place Your Order</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="" id="orderForm">
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
                    <label>Select Menu Item *</label>
                    <select name="menu_item" required>
                        <option value="">-- Select an item --</option>
                        <?php foreach ($menu_items as $item): ?>
                            <option value="<?php echo $item; ?>" <?php echo (isset($_POST['menu_item']) && $_POST['menu_item'] == $item) ? 'selected' : ''; ?>>
                                <?php echo $item; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Delivery Address *</label>
                    <textarea name="address" rows="3" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Order Date *</label>
                    <input type="date" name="order_date" value="<?php echo htmlspecialchars($_POST['order_date'] ?? date('Y-m-d')); ?>" required>
                </div>
                
                <button type="submit" name="submit_order" class="btn">Place Order</button>
            </form>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Hotel Paradise. All rights reserved.</p>
    </div>
    
    <script src="script.js"></script>
</body>
</html>