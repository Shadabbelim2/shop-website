// Function to add item to the cart
function addToCart(name, price, image) {
    fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            action: "add",
            product_name: name,
            product_price: price,
            product_image: image,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            displayCartItems(); // Refresh cart display
        } else {
            alert("Failed to add item to cart.");
        }
    });
}

// Function to remove an item from the cart
function removeFromCart(itemId) {
    fetch("cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            action: "remove",
            id: itemId,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            displayCartItems(); // Refresh cart display
        } else {
            alert("Failed to remove item from cart.");
        }
    });
}

// Function to display cart items
function displayCartItems() {
    fetch("cart.php")
        .then(response => response.json())
        .then(cartItems => {
            const cartItemsContainer = document.getElementById("cart-items");
            const cartTotal = document.getElementById("cart-total");
            cartItemsContainer.innerHTML = "";

            if (cartItems.length === 0) {
                cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
                cartTotal.textContent = "0.00";
                return;
            }

            let total = 0;
            cartItems.forEach(item => {
                const cartItem = document.createElement("div");
                cartItem.classList.add("cart-item");
                cartItem.innerHTML = `
                    <img src="${item.product_image}" alt="${item.product_name}">
                    <div class="cart-item-details">
                        <h4>${item.product_name}</h4>
                        <p>$${item.product_price.toFixed(2)}</p>
                    </div>
                    <button onclick="removeFromCart(${item.id})">Remove</button>
                `;
                cartItemsContainer.appendChild(cartItem);
                total += parseFloat(item.product_price);
            });

            cartTotal.textContent = total.toFixed(2);
        });
}

// Initial display of cart items
displayCartItems();
