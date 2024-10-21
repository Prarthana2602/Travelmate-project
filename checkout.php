<?php
session_save_path('C:/xampp/tmp'); // Ensure the path is correct and writable
session_start();

// Assuming cart is stored in session and structured as an array of items
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TravelMate Checkout - Review your cart items before confirming your rental.">
    <title>Checkout - TravelMate</title>
    <link rel="stylesheet" href="checkout.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Open+Sans">
    <style>
        /* Your CSS styling */
        .remove-btn {
            background-color: #ff6666;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
    <script>
        // JavaScript to handle removing items and updating the total
        function removeItem(itemIndex, itemPrice) {
            // Send AJAX request to remove the item from the session
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "remove_from_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Remove the item from the list
                    var item = document.getElementById("cart-item-" + itemIndex);
                    item.remove();

                    // Update the total price
                    var totalElement = document.getElementById("total");
                    var currentTotal = parseFloat(totalElement.innerText.replace('$', ''));
                    var newTotal = currentTotal - itemPrice;
                    totalElement.innerText = "$" + newTotal.toFixed(2);

                    // If the cart is empty, display an empty message
                    var itemList = document.getElementById("item-list");
                    if (itemList.children.length === 0) {
                        itemList.innerHTML = "<p>Your cart is empty. Please add some items before checking out.</p>";
                    }
                }
            };
            xhr.send("item_id=" + itemIndex);
        }
    </script>
</head>
<body>

<header>
    <h1>TravelMate Checkout</h1>
</header>

<div class="container">
    <h2>Your Cart Items</h2>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. Please add some items before checking out.</p>
        <a href="rental.html" class="back-link">Go back to Rent Gear page</a>
    <?php else: ?>
        <ul id="item-list">
            <?php
            $total = 0;
            foreach ($cart_items as $index => $item):
                $item_price = $item['price'];
                $total += $item_price;
            ?>
                <li id="cart-item-<?php echo $index; ?>">
                    <?php
                    if (isset($item['product_name']) && isset($item['price'])) {
                        echo htmlspecialchars($item['product_name']) . ' - $' . number_format($item['price'], 2);
                    } else {
                        echo 'Item - Undefined';
                    }
                    ?>

                    <!-- Remove Button -->
                    <button class="remove-btn" onclick="removeItem(<?php echo $index; ?>, <?php echo $item_price; ?>)">Remove</button>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="total">Total: <span id="total">$<?php echo number_format($total, 2); ?></span></p>
        <p class="success-message">Thank you for your order! Your items have been checked out successfully.</p>
        <a href="rental.html" class="back-link">Confirm order</a>
    <?php endif; ?>
</div>

</body>
</html>
