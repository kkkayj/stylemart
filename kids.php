<?php
session_start();
require 'config.php';

// Fetch only kids category products
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
$stmt->execute(['kids']);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Kids' Fashion | Stylemart</title>
<link rel="icon" href="assets/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<style>
.card-img-top { height: 300px; width: 100%; object-fit: cover; }
</style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container px-4 px-lg-5">
    <a class="navbar-brand" href="index.php">Stylemart</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="man.php">Man</a></li>
        <li class="nav-item"><a class="nav-link" href="woman.php">Woman</a></li>
        <li class="nav-item"><a class="nav-link active" href="kids.php">Kids</a></li>
      </ul>

      <!-- CART BUTTON -->
      <form class="d-flex ms-lg-3" role="search">
        <button class="btn btn-outline-dark" type="button" id="cart-button">
          <i class="bi-cart-fill me-1"></i>
          Cart
          <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
        </button>
      </form>
    </div>
  </div>
</nav>

<!-- HEADER -->
<header class="bg-dark py-5">
  <div class="container text-center text-white">
    <h1 class="display-4 fw-bolder">Kids' Collection</h1>
    <p class="lead fw-normal text-white-50 mb-0">Cute & Comfy Styles</p>
  </div>
</header>

<!-- PRODUCTS -->
<section class="py-5">
  <div class="container px-4 px-lg-5 mt-5">
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

      <?php foreach ($products as $product): ?>
      <div class="col mb-5">
        <div class="card h-100" 
            data-name="<?= htmlspecialchars($product['name']) ?>" 
            data-price="<?= htmlspecialchars($product['price']) ?>">

           <img class="card-img-top" src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" style="height:300px; object-fit:cover;" />
          <div class="card-body text-center">
            <h5 class="fw-bolder"><?= htmlspecialchars($product['name']) ?></h5>
            RM<?= htmlspecialchars($product['price']) ?>
          </div>

          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center">
              <a class="btn btn-outline-dark mt-auto add-to-cart" href="#">Add to cart</a>
            </div>
          </div>

        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group" id="cart-items"></ul>
        <hr>
        <h5>Total: <span id="cart-total">RM 0</span></h5>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" onclick="checkout()">Checkout</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="py-5 bg-dark">
  <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Stylemart 2025</p></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Update cart badge
function updateCartCount() {
  document.querySelector(".badge").textContent = cart.length;
}
updateCartCount();


document.querySelectorAll(".add-to-cart").forEach(btn => {
  btn.addEventListener("click", function(e) {
    e.preventDefault();

    let card = this.closest(".card");

    // FIX: extract ONLY the filename (important for trackorder)
    let fullImg = card.querySelector("img").src;
    let filename = fullImg.substring(fullImg.lastIndexOf("/") + 1);

    let item = {
      name: card.getAttribute("data-name"),
      price: Number(card.getAttribute("data-price")),  // FIX: convert to number
      image: filename, // FIX: only filename
      quantity: 1
    };

    cart.push(item);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    alert(item.name + " added to cart!");
  });
});


// Open cart modal
document.getElementById("cart-button").addEventListener("click", function(e) {
  e.preventDefault();
  showCart();
  let modal = new bootstrap.Modal(document.getElementById("cartModal"));
  modal.show();
});



function showCart() {
  let cartList = document.getElementById("cart-items");
  let totalPrice = 0;
  cartList.innerHTML = "";

  cart.forEach((item, index) => {
    totalPrice += Number(item.price) * item.quantity;

    cartList.innerHTML += `
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>${item.name}</strong><br>
          RM${item.price}
        </div>
        <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">X</button>
      </li>
    `;
  });

  document.getElementById("cart-total").textContent = "RM " + totalPrice;
}


// Remove item
function removeItem(index) {
  cart.splice(index, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
  showCart();
}


function checkout() {
    if(cart.length === 0){
        alert("Your cart is empty!");
        return;
    }

    fetch("checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(cart)
    })
    .then(res => res.json())
    .then(data => {
        if(data.order_id){
            // clear cart
            cart = [];
            localStorage.setItem("cart", JSON.stringify(cart));
            updateCartCount();

            // redirect to payment FIRST
            window.location.href = "payment.html?order_id=" + data.order_id;
        } else {
            alert("Checkout failed. Try again.");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error during checkout.");
    });
}


// Search filter
const searchInput = document.getElementById("searchInput");
if (searchInput) {
    searchInput.addEventListener("keyup", function () {
        let filter = searchInput.value.toLowerCase();
        let products = document.querySelectorAll(".card");

        products.forEach(card => {
            let name = card.getAttribute("data-name").toLowerCase();
            card.parentElement.style.display = name.includes(filter) ? "block" : "none";
        });
    });
}
</script>
</body>
</html>
