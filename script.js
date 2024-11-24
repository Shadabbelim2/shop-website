function addToCart(name, price, image) {
    fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "add", product_name: name, product_price: price, product_image: image, user_id: 1 }),
    }).then(() => alert(`${name} added to cart!`));
}

function displayCartItems() {
    fetch("cart.php")
        .then(res => res.json())
        .then(cartItems => {
            const cartItemsContainer = document.getElementById("cart-items");
            const cartTotal = document.getElementById("cart-total");
            cartItemsContainer.innerHTML = "";
            let total = 0;

            cartItems.forEach(item => {
                const div = document.createElement("div");
                div.innerHTML = `<p>${item.product_name} - $${item.product_price}</p>`;
                total += parseFloat(item.product_price);
                cartItemsContainer.appendChild(div);
            });

            cartTotal.textContent = total.toFixed(2);
        });
}

displayCartItems();
