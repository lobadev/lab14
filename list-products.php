<?php
require "../init.php"; // Ensure the correct path to init.php

// Fetch all products from Stripe
$products = $stripe->products->all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Your separate CSS file for custom styling -->
    
    <!-- Custom CSS for making product cards the same size -->
    <style>
        .product-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .product-image {
            max-height: 180px;
            object-fit: cover;
        }
        
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        
        .card-footer {
            display: flex;
            justify-content: center;
            padding-top: 10px;
        }
        
        /* Ensure all cards are the same height */
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">MyStripeApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Add Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="generate-invoice.php">Generate Invoice</a>
                    </li> <!-- New link for Generate Invoice -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Our Products</h1>
        
        <!-- Form to handle product selection -->
        <form method="POST" action="generate-payment-link.php">
            <div class="row">
                <?php foreach ($products->data as $product): ?>
                    <?php
                    // Fetch product price
                    $price = $stripe->prices->retrieve($product->default_price);
                    ?>
                    <div class="col-md-4 mb-4 d-flex">
                        <div class="card shadow-sm product-card">
                            <?php if (!empty($product->images)): ?>
                                <img src="<?= htmlspecialchars($product->images[0]) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product->name) ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/150" class="card-img-top product-image" alt="Product Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                                <p class="card-text"><?= strtoupper($price->currency) . ' ' . number_format($price->unit_amount / 100, 2) ?></p>

                                <!-- Checkbox to select product -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="products[]" value="<?= $price->id ?>" id="product-<?= $product->id ?>">
                                    <label class="form-check-label" for="product-<?= $product->id ?>">
                                        Select this product
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 mt-4">Generate Payment Link</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
