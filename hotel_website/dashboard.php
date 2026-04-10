<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}


$orders_result = $conn->query("SELECT * FROM orders ORDER BY id DESC");


$contacts_result = $conn->query("SELECT * FROM contacts ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Hotel Paradise</title>
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
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="logout.php" class="logout-link">Logout</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <h1 class="section-title">Admin Dashboard</h1>
        <p style="text-align: center; margin-bottom: 30px;">Welcome, <?php echo $_SESSION['admin_username']; ?>!</p>
        
        <h2 style="color: #2c1810; margin-bottom: 20px;">📋 Customer Orders</h2>
        <div class="dashboard-table">
            <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th>
                            <th>Menu Item</th><th>Address</th><th>Order Date</th><th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['email']); ?></td>
                                <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                <td><?php echo htmlspecialchars($order['menu_item']); ?></td>
                                <td><?php echo htmlspecialchars(substr($order['address'], 0, 50)); ?>...</td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders yet.</p>
            <?php endif; ?>
        </div>
        
        <h2 style="color: #2c1810; margin: 40px 0 20px;">📧 Contact Messages</h2>
        <div class="dashboard-table">
            <?php if ($contacts_result && $contacts_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th>
                            <th>Location</th><th>Message</th><th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($contact = $contacts_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo htmlspecialchars($contact['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                <td><?php echo htmlspecialchars($contact['location']); ?></td>
                                <td><?php echo htmlspecialchars(substr($contact['message'], 0, 50)); ?>...</td>
                                <td><?php echo $contact['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No messages yet.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Hotel Paradise. All rights reserved.</p>
    </div>
</body>
</html>
<?php $conn->close(); ?>