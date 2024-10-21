<?php
session_save_path('C:/xampp/tmp');


session_start();

// Check if the cart session exists, if not, create an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve the product details from the POST request
if (isset($_POST['product_name']) && isset($_POST['price'])) {
    $productName = $_POST['product_name'];
    $price = floatval($_POST['price']);

    // Add the product to the cart
    $_SESSION['cart'][] = [
        'product_name' => $productName,
        'price' => $price
    ];

    // Return a response (optional)
    echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully']);
} else {
    // Return an error response if product details are missing
    echo json_encode(['status' => 'error', 'message' => 'Invalid product data']);
}
?>
