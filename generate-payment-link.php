<?php
require "init.php"; // Ensure correct path to init.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected products (prices)
    $selected_prices = $_POST['products'] ?? [];

    if (empty($selected_prices)) {
        die("No products selected.");
    }

    try {
        $line_items = [];

        // Loop through the selected prices and add them as line items
        foreach ($selected_prices as $price_id) {
            $line_items[] = [
                'price' => $price_id,
                'quantity' => 1, // Default to 1 quantity per item
            ];
        }

        // Create a payment link
        $payment_link = $stripe->paymentLinks->create([
            'line_items' => $line_items
        ]);

        // Redirect to the generated payment link
        header('Location: ' . $payment_link->url);
        exit();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    die("Invalid request.");
}
?>
