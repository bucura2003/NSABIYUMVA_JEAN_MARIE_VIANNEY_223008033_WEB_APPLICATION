<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Hotel Paradise</title>
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
                <li><a href="gallery.php" class="active">Gallery</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <h1 class="section-title">Our Gallery</h1>
        <p style="text-align: center; margin-bottom: 30px;">Click on any image to order!</p>
        
        <div class="gallery-grid">
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400" alt="Grilled Salmon">
                <div class="caption">Grilled Salmon with Vegetables</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400" alt="Pizza">
                <div class="caption">Margherita Pizza</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400" alt="Pancakes">
                <div class="caption">Breakfast Pancakes</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400" alt="Fresh Juice">
                <div class="caption">Fresh Fruit Juices</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400" alt="Dessert">
                <div class="caption">Chocolate Dessert</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?w=400" alt="Seafood">
                <div class="caption">Seafood Platter</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=400" alt="Salad">
                <div class="caption">Fresh Garden Salad</div>
            </div>
            
            <div class="gallery-item" onclick="location.href='order.php'">
                <img src="https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=400" alt="Coffee">
                <div class="caption">Hot Coffee</div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Hotel Paradise. All rights reserved.</p>
    </div>
    
    <script src="script.js"></script>
</body>
</html>