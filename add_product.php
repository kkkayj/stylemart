<?php
require_once 'auth.php';
ensure_admin();
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = strtolower(trim($_POST['category'])); // force lowercase
    $image = '';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        }
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $price, $category, $image])) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Add Product</h1>
    <?php if ($message) echo "<div class='alert alert-info'>$message</div>"; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price (RM)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <select name="category" class="form-select" required>
                <option value="man">Men</option>
                <option value="woman">Women</option>
                <option value="kids">Kids</option>
                <option value="index">Home</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Add Product</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
