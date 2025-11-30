<?php
require_once 'auth.php';
ensure_admin();
require_once 'config.php';


$filter = $_GET['category'] ?? '';


$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalUsers    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(); // Make sure orders table exists


if ($filter) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
    $stmt->execute([$filter]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Stylemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Stylemart Admin</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><span class="nav-link">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4">Dashboard</h1>

   
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text"><?php echo $totalProducts; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <p class="card-text"><?php echo $totalOrders; ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mb-3">
        <strong>Filter by Category:</strong>
        <a href="dashboard.php" class="btn btn-sm btn-outline-secondary <?php echo $filter==''?'active':'' ?>">All</a>
        <a href="dashboard.php?category=index" class="btn btn-sm btn-outline-secondary <?php echo $filter=='index'?'active':'' ?>">Home</a>
        <a href="dashboard.php?category=man" class="btn btn-sm btn-outline-secondary <?php echo $filter=='man'?'active':'' ?>">Men</a>
        <a href="dashboard.php?category=woman" class="btn btn-sm btn-outline-secondary <?php echo $filter=='woman'?'active':'' ?>">Women</a>
        <a href="dashboard.php?category=kids" class="btn btn-sm btn-outline-secondary <?php echo $filter=='kids'?'active':'' ?>">Kids</a>
    </div>

    
    <div class="card">
        <div class="card-header">
            Product Management
            <a href="add_product.php" class="btn btn-sm btn-primary float-end">Add Product</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price (RM)</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($products): ?>
                    <?php foreach($products as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td><?php echo $p['price']; ?></td>
                            <td>
                              <?php if($p['image']): ?>
                                <img src="<?php echo htmlspecialchars($p['image']); ?>" width="50">
                              <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No products found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
