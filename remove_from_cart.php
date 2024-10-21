<?php
session_save_path('C:/xampp/tmp'); // Ensure the path is correct and writable
session_start();

if (isset($_POST['item_id'])) {
    $item_id = (int) $_POST['item_id']; // Get the item index from the POST request

    if (isset($_SESSION['cart'][$item_id])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$item_id]);
        
        // Reindex the cart to maintain proper indices after removal
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
// Redirect back to the checkout page
header('Location: checkout.php');
exit();
?>
