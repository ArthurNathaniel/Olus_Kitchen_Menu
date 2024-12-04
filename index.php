<?php
// Include database connection
include('db.php');

// Fetch food items from the database
$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olu's Kitchen Menu</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./css/food.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Olu's Kitchen Menu</h1>
        <h2>Choose <br>Your Favorite <span>Food</span></h2>

        <div class="menu">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="menu-item" data-name="<?= $row['food_name']; ?>" data-price="<?= $row['food_price']; ?>">
                    <div class="food_image">
                        <img src="<?= $row['food_image']; ?>" alt="<?= $row['food_name']; ?>">
                    </div>
                    <div class="food_name">
                        <h3><?= $row['food_name']; ?> - GHS <?= $row['food_price']; ?></h3>
                    </div>
                    <div class="food_btns">
                        <button class="add-btn"><i class="fas fa-plus"></i></button>
                        <button class="remove-btn"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <button id="view-checkout-btn">
            <i class="fas fa-shopping-cart"></i>
            <span id="item-count" class="badge">0</span> View Checkout
        </button>
        
        <div id="checkout" class="hidden">
            <h2>Checkout</h2>
            <ul id="order-list"></ul>
            <h3>Total: GHS <span id="total">0</span></h3>
            <button id="customer_details">Proceed</button>
        </div>

 <!-- Customer Details Modal -->
 <div id="customer-details-modal" class="hidden">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        
        <h2 class="modal-header">Customer Details</h2>
        <form id="customer-details-form">
            <label for="customer-name">Full Name</label>
            <input type="text" id="customer-name" required>

            <label for="customer-phone">Phone Number</label>
            <input type="tel" id="customer-phone" required>

            <label for="customer-email">Email Address</label>
            <input type="email" id="customer-email" required>

            <label for="pickup-delivery">Pickup or Delivery</label>
            <select id="pickup-delivery" required>
                <option value="pickup">Pickup</option>
                <option value="delivery">Delivery</option>
            </select>

            <div id="delivery-address-field">
                <label for="delivery-address">Delivery Address</label>
                <textarea id="delivery-address" rows="3"></textarea>
            </div>

            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
</div>

</div>

</div>


    <script src="script.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
</body>
</html>
