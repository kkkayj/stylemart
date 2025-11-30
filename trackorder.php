<?php
include 'db.php'; 


$order_id = isset($_GET["order_id"]) ? intval($_GET["order_id"]) : 0;


if ($order_id == 0) {
    die("<h2 class='text-danger'>No order ID provided.</h2>");
}


$sql = "SELECT product_name, product_price, quantity FROM order_items WHERE order_id = $order_id";
$result = $conn->query($sql);


if (!$result) {
    die("<div class='alert alert-danger'>SQL Error: " . $conn->error . "</div>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Track Order #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .table-hover tbody tr:hover {
            background-color: #f0f8ff;
        }
        .badge-total {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
        }
        body {
            background: #f9f9f9;
        }
        .order-header {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <div class="order-header mb-4">
        <h2>Your Order <span class="badge bg-warning text-dark">#<?php echo $order_id; ?></span></h2>
    </div>

    <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Price (RM)</th>
                    <th>Quantity</th>
                    <th>Subtotal (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                while($row = $result->fetch_assoc()): 
                    $subtotal = $row["product_price"] * $row["quantity"];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><strong><?php echo $row["product_name"]; ?></strong></td>
                    <td>RM <?php echo number_format($row["product_price"], 2); ?></td>
                    <td><?php echo $row["quantity"]; ?></td>
                    <td>RM <?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-3">
        <h3>Total: <span class="badge bg-success badge-total">RM <?php echo number_format($total, 2); ?></span></h3>
    </div>

    <div class="mt-4">
        <a href="index.php" class="btn btn-primary">Back to Home</a>
        <a href="print_order.php?order_id=<?php echo $order_id; ?>" class="btn btn-outline-success">Print Invoice</a>
    </div>

    <?php else: ?>
        <div class="alert alert-warning">No items found for this order.</div>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
