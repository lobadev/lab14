<?php
require "../init.php"; // Ensure correct path to init.php

// Fetch customers and products
$customers = $stripe->customers->all();
$products = $stripe->products->all();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $customer_id = htmlspecialchars($_POST['customer']);
        $selected_products = $_POST['products'] ?? [];

        // Create a new invoice
        $invoice = $stripe->invoices->create([
            'customer' => $customer_id,
        ]);

        // Attach selected products as line items
        foreach ($selected_products as $product_id) {
            $product = $stripe->products->retrieve($product_id);
            $price = $stripe->prices->retrieve($product->default_price);

            // Check if the price type is 'one_time'
            if ($price->type === 'one_time') {
                // Create invoice items for only one-time prices
                $stripe->invoiceItems->create([
                    'customer' => $customer_id,
                    'price' => $price->id,
                    'invoice' => $invoice->id,
                ]);
            } else {
                // Skip products with recurring prices
                continue;
            }
        }

        // Finalize the invoice
        $stripe->invoices->finalizeInvoice($invoice->id);

        // Retrieve invoice details
        $invoice_details = $stripe->invoices->retrieve($invoice->id);
        $message = "Invoice created successfully!";

        // Generate Invoice PDF and Payment URL
        $invoice_pdf = $invoice_details->invoice_pdf;
        $hosted_invoice_url = $invoice_details->hosted_invoice_url;

    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="text-center mb-4">Generate Invoice</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info">
                <?= $message ?>
                <br>
                <?php if (isset($invoice_pdf)): ?>
                    <a href="<?= $invoice_pdf ?>" target="_blank" class="btn btn-primary mt-3">Download Invoice PDF</a>
                    <a href="<?= $hosted_invoice_url ?>" target="_blank" class="btn btn-success mt-3">Pay Invoice</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Invoice Form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="customer" class="form-label">Select Customer</label>
                <select id="customer" name="customer" class="form-control" required>
                    <?php foreach ($customers->data as $customer): ?>
                        <option value="<?= $customer->id ?>"><?= htmlspecialchars($customer->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Product List with Checkboxes -->
            <div class="mb-3">
                <label class="form-label">Select Products</label>
                <?php foreach ($products->data as $product): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="products[]" value="<?= $product->id ?>" id="product-<?= $product->id ?>">
                        <label class="form-check-label" for="product-<?= $product->id ?>">
                            <?= htmlspecialchars($product->name) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Generate Invoice</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
