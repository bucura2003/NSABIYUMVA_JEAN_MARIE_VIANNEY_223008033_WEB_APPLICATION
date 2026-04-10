<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Hotel Paradise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="nav">
            <div class="logo">Hotel <span>Paradise</span></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="menu.php" class="active">Menu</a></li>
                <li><a href="order.php">Order</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <h1 class="section-title">Our Menu</h1>
        
        <div class="menu-table">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Price (RWF)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fish Section -->
                    <tr style="background:#f0e6d2;">
                        <td rowspan="4"><strong>🐟 FISH</strong></td>
                        <td>Grilled Salmon</td>
                        <td>Fresh salmon with lemon butter sauce</td>
                        <td class="price">15,000</td>
                    </tr>
                    <tr>
                        <td>Fish & Chips</td>
                        <td>Crispy battered fish served with fries</td>
                        <td class="price">12,000</td>
                    </tr>
                    <tr>
                        <td>Spicy Fish Curry</td>
                        <td>Traditional fish curry with spices</td>
                        <td class="price">10,000</td>
                    </tr>
                    <tr>
                        <td>Fish Tikka</td>
                        <td>Marinated fish grilled to perfection</td>
                        <td class="price">13,000</td>
                    </tr>
                    
                    <!-- Drink Section -->
                    <tr style="background:#e6f0fa;">
                        <td rowspan="4"><strong>🥤 DRINKS</strong></td>
                        <td>Fresh Lemonade</td>
                        <td>Homemade lemonade with mint</td>
                        <td class="price">3,000</td>
                    </tr>
                    <tr>
                        <td>Iced Coffee</td>
                        <td>Cold brew with vanilla</td>
                        <td class="price">4,000</td>
                    </tr>
                    <tr>
                        <td>Soft Drinks</td>
                        <td>Coke, Fanta, Sprite</td>
                        <td class="price">2,000</td>
                    </tr>
                    <tr>
                        <td>Milkshake</td>
                        <td>Chocolate, Strawberry, Vanilla</td>
                        <td class="price">5,000</td>
                    </tr>
                    
                    <!-- Fresh Juice Section -->
                    <tr style="background:#e8f5e9;">
                        <td rowspan="4"><strong>🍹 FRESH JUICES</strong></td>
                        <td>Orange Juice</td>
                        <td>Freshly squeezed oranges</td>
                        <td class="price">4,000</td>
                    </tr>
                    <tr>
                        <td>Mango Juice</td>
                        <td>Seasonal fresh mangoes</td>
                        <td class="price">5,000</td>
                    </tr>
                    <tr>
                        <td>Mixed Fruit Juice</td>
                        <td>Combination of seasonal fruits</td>
                        <td class="price">6,000</td>
                    </tr>
                    <tr>
                        <td>Watermelon Juice</td>
                        <td>Refreshing watermelon blend</td>
                        <td class="price">4,000</td>
                    </tr>
                    
                    <!-- Appetizers -->
                    <tr style="background:#fff3e0;">
                        <td rowspan="3"><strong>🍕 APPETIZERS</strong></td>
                        <td>Garlic Bread</td>
                        <td>Toasted bread with garlic butter</td>
                        <td class="price">3,500</td>
                    </tr>
                    <tr>
                        <td>Spring Rolls</td>
                        <td>Crispy vegetable spring rolls</td>
                        <td class="price">5,000</td>
                    </tr>
                    <tr>
                        <td>Chicken Wings</td>
                        <td>Spicy BBQ chicken wings</td>
                        <td class="price">8,000</td>
                    </tr>
                    
                    <!-- Main Course -->
                    <tr style="background:#fce4ec;">
                        <td rowspan="3"><strong>🍛 MAIN COURSE</strong></td>
                        <td>Chicken Biryani</td>
                        <td>Fragrant rice with chicken</td>
                        <td class="price">12,000</td>
                    </tr>
                    <tr>
                        <td>Butter Chicken</td>
                        <td>Creamy tomato curry with naan</td>
                        <td class="price">14,000</td>
                    </tr>
                    <tr>
                        <td>Grilled Steak</td>
                        <td>Beef steak with mashed potatoes</td>
                        <td class="price">18,000</td>
                    </tr>
                    
                    <!-- Desserts -->
                    <tr style="background:#f3e5f5;">
                        <td rowspan="3"><strong>🍰 DESSERTS</strong></td>
                        <td>Chocolate Cake</td>
                        <td>Rich chocolate layered cake</td>
                        <td class="price">6,000</td>
                    </tr>
                    <tr>
                        <td>Ice Cream</td>
                        <td>Vanilla, Chocolate, Strawberry</td>
                        <td class="price">3,000</td>
                    </tr>
                    <tr>
                        <td>Fruit Salad</td>
                        <td>Fresh mixed fruits</td>
                        <td class="price">5,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Hotel Paradise. All rights reserved.</p>
    </div>
</body>
</html>