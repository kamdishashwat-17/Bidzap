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
        // Loop through the cart items and insert them into the database
        foreach ($_SESSION['cart'] as $item) {
            $food = $item['name'];
            $price = $item['price'];
            $qty = 1; // Default quantity
            $total = $price * $qty;
            $order_date = date("Y-m-d H:i:s");
            $status = "Ordered"; // Default status
            $u_id = $_SESSION['u_id']; // Assuming user ID is stored in session

            // Insert the order into the database
            $sql = "INSERT INTO tbl_order (food, price, qty, total, order_date, status, u_id) 
                    VALUES ('$food', $price, $qty, $total, '$order_date', '$status', $u_id)";
            mysqli_query($conn, $sql);
        }

        // Clear the cart after placing the order
        $_SESSION['cart'] = [];
        $_SESSION['order_success'] = "Order placed successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart | Food Order</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://t4.ftcdn.net/jpg/02/94/21/87/360_F_294218701_se4mQtVmQoPnG4UX7J8PjvTzn8yeWyqF.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #333333;
            text-align: center;
            margin-bottom: 20px;
        }
        .food-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .food-item:last-child {
            border-bottom: none;
        }
        .food-item span {
            font-size: 16px;
            color: #555555;
        }
        .food-item form {
            margin: 0;
            display: inline;
        }
        .food-item button {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .food-item button:hover {
            background: #e84118;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        ul li {
            padding: 10px 0;
            font-size: 16px;
            color: #555555;
        }
        .order-button {
            display: block;
            width: 100%;
            background: #2ed573;
            padding: 12px 0;
            font-size: 18px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
            transition: background 0.3s ease;
        }
        .order-button:hover {
            background: #27ae60;
        }
        .success {
            color: green;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
        .remove-btn {
            background: #ff6b81;
            border: none;
            border-radius: 50%;
            padding: 5px 10px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .remove-btn:hover {
            background: #ff4757;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #333333;
            text-align: right;
            margin-top: 20px;
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
                            <button type="submit" name="remove_item" class="remove-btn"> - </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="total"><strong>Total:</strong> ₹<?php echo $total; ?></p>
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
