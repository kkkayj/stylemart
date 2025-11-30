<?php
include 'db.php';

if ($conn->connect_error) {
    echo json_encode(["message" => "Database connection failed"]);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["message" => "Cart is empty"]);
        exit;
    }

    $total = 0;
    foreach($data as $item){
        $total += $item['price'] * $item['quantity'];
    }

    // Create order
    $conn->query("INSERT INTO orders (total) VALUES ('$total')");
    $order_id = $conn->insert_id;

    foreach($data as $item){
        $name = $conn->real_escape_string($item["name"]);
        $price = $conn->real_escape_string($item["price"]);
        $qty = $conn->real_escape_string($item["quantity"]);
        $image = $conn->real_escape_string($item["image"]);

        $conn->query("INSERT INTO order_items (order_id, product_name, product_price, quantity, product_image)
                      VALUES ('$order_id', '$name', '$price', '$qty', '$image')");
    }

    echo json_encode(["order_id" => $order_id]);
    exit;
}


?>
