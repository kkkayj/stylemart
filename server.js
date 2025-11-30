const express = require('express');
const app = express();
const PORT = 3000;

// Serve static files (your HTML, CSS, JS)
app.use(express.static(__dirname));

// Parse JSON (for checkout data)
app.use(express.json());

// Example checkout endpoint
app.post('/checkout', (req, res) => {
    const cart = req.body.cart;
    const total = cart.reduce((sum, item) => sum + item.price, 0);
    res.json({ message: 'Checkout successful!', total });
});

app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});
