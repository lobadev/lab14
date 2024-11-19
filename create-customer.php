<?php
require "../init.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $line1 = htmlspecialchars($_POST["line1"]);
        $line2 = htmlspecialchars($_POST["line2"]);
        $city = htmlspecialchars($_POST["city"]);
        $state = htmlspecialchars($_POST["state"]);
        $country = htmlspecialchars($_POST["country"]);
        $postal_code = htmlspecialchars($_POST["postal_code"]);

        $customer = $stripe->customers->create([
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "address" => [
                "line1" => $line1,
                "line2" => $line2,
                "city" => $city,
                "state" => $state,
                "country" => $country,
                "postal_code" => $postal_code
            ]
        ]);

        $message = "Customer created successfully! Customer ID: " . $customer->id;

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
    <title>Register Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
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
        <h1 class="text-center mb-4">Register Customer</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="line1" class="form-label">Address Line 1</label>
                <input type="text" class="form-control" id="line1" name="line1" required>
            </div>
            <div class="mb-3">
                <label for="line2" class="form-label">Address Line 2</label>
                <input type="text" class="form-control" id="line2" name="line2">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state">
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" required>
            </div>
            <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
