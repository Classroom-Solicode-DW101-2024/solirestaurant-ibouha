<?php

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add item to cart
    if (isset($_POST['name'], $_POST['price'])) {
        $item = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'quantity' => 1
        ];
        $_SESSION['cart'][] = $item;
    }

    // Update item quantity
    if (isset($_POST['update_quantity'], $_POST['item_index'], $_POST['new_quantity'])) {
        $itemIndex = $_POST['item_index'];
        $newQuantity = $_POST['new_quantity'];

        if (isset($_SESSION['cart'][$itemIndex])) {
            $_SESSION['cart'][$itemIndex]['quantity'] = $newQuantity;
        }
    }

    // Clear cart
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = []; // Clear the cart
    }
}

// Calculate the total price
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>



<!-- Cart Icon -->
<button id="cart-icon" class="bg-rose-700 text-white flex justify-center items-center w-11 h-11 p-2 rounded-full shadow-lg hover:bg-rose-500">
    <ion-icon name="cart-outline" class="text-4xl"></ion-icon>
    <span class="text-xs"><?php echo count($_SESSION['cart']); ?></span>
</button>

<!-- Cart Sidebar -->
<div id="cart-sidebar" class="fixed top-0 right-0 w-80 h-full bg-white text-black shadow-lg transform translate-x-full transition-transform duration-300 z-50 flex flex-col">
    <div class="flex justify-between items-center p-5 border-b">
        <h2 class="text-2xl font-semibold">Your Cart</h2>
        <button id="close-cart" class="text-gray-600 hover:text-gray-800">
            <ion-icon name="close-outline" class="text-3xl"></ion-icon>
        </button>
    </div>
    <div id="cart-items" class="flex-1 p-5 overflow-y-auto">
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <form method="POST" class="flex justify-between items-center border-b py-3 text-black">
                <span><?= htmlspecialchars($item['name']) ?></span>
                <div class="flex items-center gap-2">
                    <!-- Decrease Quantity -->
                    <button type="submit" name="update_quantity" value="1" class="quantity-decrease px-2 bg-gray-200 rounded">
                        -
                    </button>
                    <input type="number" name="new_quantity" value="<?= $item['quantity'] ?>" class="quantity text-center w-12" min="1">
                    <!-- Increase Quantity -->
                    <button type="submit" name="update_quantity" value="1" class="quantity-increase px-2 bg-gray-200 rounded">
                        +
                    </button>
                </div>
                <span class="text-lg font-semibold">DH <?= htmlspecialchars($item['price']) ?></span>
                <input type="hidden" name="item_index" value="<?= $index ?>">
            </form>
        <?php endforeach; ?>
    </div>
    <div class="p-5 border-t flex justify-between items-center">
        <span class="text-xl font-semibold">Total: DH <?= number_format($totalPrice, 2) ?></span>
        <button class="bg-rose-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-rose-500">Checkout</button>
    </div>
    <form method="post" class="w-full flex justify-center items-center p-3">
        <button type="submit" name="clear_cart" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-400">
            Clear Cart
        </button>
    </form>
</div>


<!-- JavaScript -->
<script>
    document.getElementById("cart-icon").addEventListener("click", function () {
        document.getElementById("cart-sidebar").classList.remove("translate-x-full");
    });

    document.getElementById("close-cart").addEventListener("click", function () {
        document.getElementById("cart-sidebar").classList.add("translate-x-full");
    });
</script>
