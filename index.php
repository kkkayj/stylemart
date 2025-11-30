<?php
// home.php
require_once 'config.php'; // Make sure this includes session_start() and $pdo

// Fetch all products
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Now $products is always an array
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Stylemart Online Shopping" />
  <meta name="author" content="Stylemart" />
  <title>Stylemart | Home</title>
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container px-4 px-lg-5">
    <a class="navbar-brand" href="home.php">Stylemart</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="man.php">Man</a></li>
        <li class="nav-item"><a class="nav-link" href="woman.php">Woman</a></li>
        <li class="nav-item"><a class="nav-link" href="kids.php">Kids</a></li>
      </ul>

      <!-- Dynamic Login / User -->
      <ul class="navbar-nav ms-auto">
      <?php if (!isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.html">Sign Up</a></li>
      <?php else: ?>
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                  Hi, <?= htmlspecialchars($_SESSION['username']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                  <?php if ($_SESSION['role'] === 'admin'): ?>
                      <li><a class="dropdown-item" href="dashboard.php">Admin Dashboard</a></li>
                  <?php else: ?>
                      <li><a class="dropdown-item" href="home.php">My Account</a></li>
                  <?php endif; ?>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
          </li>
      <?php endif; ?>
      </ul>

      <form class="d-flex ms-lg-3" role="search">
        <input class="form-control me-2" id="searchInput" type="search" placeholder="Search products..." aria-label="Search">
        <button class="btn btn-outline-dark" type="button" id="cart-button">
          <i class="bi-cart-fill me-1"></i>
          Cart
          <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
        </button>
      </form>
    </div>
  </div>
</nav>

<!-- Header -->
<header class="bg-dark py-5">
  <div class="container px-4 px-lg-5 my-5">
    <div class="text-center text-white">
      <h1 class="display-4 fw-bolder">Welcome to Stylemart</h1>
      <p class="lead fw-normal text-white-50 mb-0">Shop the latest fashion for Men, Women, and Kids</p>
    </div>
  </div>
</header>

<!-- Products Section -->
<section class="py-5">
  <div class="container px-4 px-lg-5 mt-5">
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

      <?php foreach ($products as $product): ?>
      <div class="col mb-5">
        <div class="card h-100" data-name="<?= htmlspecialchars($product['name']); ?>" data-price="<?= $product['price']; ?>">
          <img class="card-img-top" src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" style="height:300px; object-fit:cover;" />
          <div class="card-body p-4 text-center">
            <h5 class="fw-bolder"><?= htmlspecialchars($product['name']); ?></h5>
            RM<?= $product['price']; ?>
          </div>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
            <a class="btn btn-outline-dark mt-auto add-to-cart" href="#">Add to cart</a>
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


// ⭐⭐⭐ ADD TO CART (FIXED) ⭐⭐⭐
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


// ⭐⭐⭐ SHOW CART (FIXED TOTAL) ⭐⭐⭐
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


// ⭐⭐⭐ CHECKOUT (WORKS WITH YOUR checkout.php) ⭐⭐⭐
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