<?php
require_once 'auth.php';
ensure_admin();
require_once 'config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product) { exit('Product not found'); }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $product['image'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $target_dir = "images/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, image=? WHERE id=?");
    $stmt->execute([$name, $price, $image, $id]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1>Edit Product</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Price (RM)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
            <?php if($product['image']): ?>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button class="btn btn-success">Update Product</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
