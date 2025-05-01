<?php 
include('partials-front/menu.php'); 

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    // Add item to cart session
    $_SESSION['cart'][] = [
        'id' => $food_id,
        'name' => $food_name,
        'price' => $price,
        'qty' => $qty
    ];

    // Set success message
    $_SESSION['add_to_cart_success'] = "Item added to cart successfully!";
}
?>

<!-- fOOD SEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for Food.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>
<!-- fOOD SEARCH Section Ends Here -->

<?php 
// Display success message if set
if (isset($_SESSION['add_to_cart_success'])) {
    echo "<div class='success text-center'>" . $_SESSION['add_to_cart_success'] . "</div>";
    unset($_SESSION['add_to_cart_success']);
}
?>

<!-- fOOD Menu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
        // Display Foods that are Active
        $sql = "SELECT * FROM tbl_food WHERE active='Yes'";

        // Execute the Query
        $res = mysqli_query($conn, $sql);

        // Count Rows
        $count = mysqli_num_rows($res);

        // Check whether the foods are available or not
        if ($count > 0) {
            // Foods Available
            while ($row = mysqli_fetch_assoc($res)) {
                // Get the Values
                $id = $row['id'];
                $title = $row['title'];
                $description = $row['description'];
                $price = $row['price'];
                $image_name = $row['image_name'];
                ?>
                
                <div class="food-menu-box">
                    <div class="food-menu-img">
                        <?php 
                        // Check whether image is available or not
                        if ($image_name == "") {
                            // Image not Available
                            echo "<div class='error'>Image not Available.</div>";
                        } else {
                            // Image Available
                            ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Food Image" class="img-responsive img-curve">
                            <?php
                        }
                        ?>
                    </div>

                    <div class="food-menu-desc">
                        <h4><?php echo $title; ?></h4>
                        <p class="food-price">₹<?php echo $price; ?></p>
                        <p class="food-detail">
                            <?php echo $description; ?>
                        </p>
                        <br>

                        <!-- Order Now Button -->
                        <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>

                        <!-- Add to Cart Button -->
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="food_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="food_name" value="<?php echo $title; ?>">
                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                            <input type="hidden" name="qty" value="1"> <!-- Default quantity -->
                            <button type="submit" name="add_to_cart" class="btn btn-secondary">Add to Cart</button>
                        </form>
                    </div>
                </div>

                <?php
            }
        } else {
            // Food not Available
            echo "<div class='error'>Food not found.</div>";
        }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- fOOD Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>