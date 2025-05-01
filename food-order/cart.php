<?php
include('config/constants.php');

// Start session if not already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Sample food items
$foods = [
    ['id' => 1, 'name' => 'Cheese Pizza', 'price' => 250],
    ['id' => 2, 'name' => 'Veg Burger', 'price' => 150],
    ['id' => 3, 'name' => 'White Sauce Pasta', 'price' => 200],
];

// Add to cart logic
if (isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $price = $_POST['price'];

    // Avoid duplicates
    $exists = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $food_id) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $_SESSION['cart'][] = [
            'id' => $food_id,
            'name' => $food_name,
            'price' => $price
        ];
    }
}

// Remove from cart logic
if (isset($_POST['remove_item'])) {
    $remove_id = $_POST['remove_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
        }
    }
    // Reindex the array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Place order logic
if (isset($_POST['order_now'])) {
    if (!empty($_SESSION['cart'])) {
        $_SESSION['order_success'] = "Order placed successfully!";
        $_SESSION['cart'] = [];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart | Bidzap</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial;
            background: #f9f9f9;
            padding: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .food-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .food-item form {
            margin: 0;
            display: inline;
        }
        .food-item button {
            background: #ff4757;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .food-item button:hover {
            background: #e84118;
        }
        h2, h3 {
            color: #2f3542;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 6px 0;
        }
        .order-button {
            background: #2ed573;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            margin-top: 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .order-button:hover {
            background: #27ae60;
        }
        .success {
            color: green;
            margin-top: 15px;
        }
        .remove-btn {
            background: #ff6b81;
            border: none;
            border-radius: 50%;
            padding: 5px 10px;
            margin-left: 10px;
            font-weight: bold;
            cursor: pointer;
        }
        .remove-btn:hover {
            background: #ff4757;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Available Foods</h2>
        <?php foreach ($foods as $food): ?>
            <div class="food-item">
                <span><strong><?php echo $food['name']; ?></strong> - ₹<?php echo $food['price']; ?></span>
                <form method="post">
                    <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                    <input type="hidden" name="food_name" value="<?php echo $food['name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $food['price']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>

        <h3>Your Cart</h3>
        <?php if (!empty($_SESSION['cart'])): ?>
            <ul>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $total += $item['price'];
                ?>
                    <li>
                        <?php echo $item['name']; ?> - ₹<?php echo $item['price']; ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="remove_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove_item" class="remove-btn">–</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Total:</strong> ₹<?php echo $total; ?></p>
            <form method="post">
                <button type="submit" class="order-button" name="order_now">Order Now</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>

        <?php
        if (!empty($_SESSION['order_success'])) {
            echo '<p class="success">' . $_SESSION['order_success'] . '</p>';
            unset($_SESSION['order_success']);
        }
        ?>
    </div>
</body>
</html>