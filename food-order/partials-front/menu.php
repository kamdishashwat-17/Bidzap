<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidzap</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="http://localhost/food-order/" title="Logo">
                    <img src="images/bidzap-logo.png" alt="Bidzap-Logo" class="img-responsive">
                </a>
            </div>
            <br>
            <div class="menu text-right">
                <ul>
                    <li>
                        <a href="#" id="bot">
                            <img src="images/bot.png" alt="Chatbot" width="30" height="30" style="vertical-align: middle;">
                            ChatBot
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>categories.php">Categories</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>foods.php">Foods</a>
                    </li>
                    <li>            
                        <?php
                        if(empty($_SESSION["u_id"])) {
                            echo '<a href="login.php" class="nav-link active">Login</a>';
                        } else {
                            echo '<a href="myorders.php" class="nav-link active">Myorders</a>';
                            echo '<a href="logout.php" class="nav-link active">Logout</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->
</body>
</html>