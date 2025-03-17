<?php include('partials-front/menu.php'); ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

    <?php 
        if(isset($_SESSION['order']))
        {
            echo $_SESSION['order'];
            unset($_SESSION['order']);
        }
    ?>

    <!-- CAtegories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <?php 
                //Create SQL Query to Display CAtegories from Database
                $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3";
                //Execute the Query
                $res = mysqli_query($conn, $sql);
                //Count rows to check whether the category is available or not
                $count = mysqli_num_rows($res);

                if($count>0)
                {
                    //CAtegories Available
                    while($row=mysqli_fetch_assoc($res))
                    {
                        //Get the Values like id, title, image_name
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        ?>
                        
                        <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                            <div class="box-3 float-container">
                                <?php 
                                    //Check whether Image is available or not
                                    if($image_name=="")
                                    {
                                        //Display MEssage
                                        echo "<div class='error'>Image not Available</div>";
                                    }
                                    else
                                    {
                                        //Image Available
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" alt="Pizza" class="img-responsive img-curve">
                                        <?php
                                    }
                                ?>
                                

                                <h3 class="float-text text-white"><?php echo $title; ?></h3>
                            </div>
                        </a>

                        <?php
                    }
                }
                else
                {
                    //Categories not Available
                    echo "<div class='error'>Category not Added.</div>";
                }
            ?>


            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Featured Foods</h2>

            <?php 
            
            //Getting Foods from Database that are active and featured
            //SQL Query
            $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' LIMIT 6";

            //Execute the Query
            $res2 = mysqli_query($conn, $sql2);

            //Count Rows
            $count2 = mysqli_num_rows($res2);

            //CHeck whether food available or not
            if($count2>0)
            {
                //Food Available
                while($row=mysqli_fetch_assoc($res2))
                {
                    //Get all the values
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $description = $row['description'];
                    $image_name = $row['image_name'];
                    ?>

                    <div class="food-menu-box">
                        <div class="food-menu-img">
                            <?php 
                                //Check whether image available or not
                                if($image_name=="")
                                {
                                    //Image not Available
                                    echo "<div class='error'>Image not available.</div>";
                                }
                                else
                                {
                                    //Image Available
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
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

                            <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>

                    <?php
                }
            }
            else
            {
                //Food Not Available 
                echo "<div class='error'>Food not available.</div>";
            }
            
            ?>

            

 

            <div class="clearfix"></div>

            

        </div>

        <p class="text-center">
            <a href="http://localhost/food-order/foods.php">See All Foods</a>
        </p>
    </section>
    <!-- fOOD Menu Section Ends Here -->

    
    <?php include('partials-front/footer.php'); ?>

    <!-- Chatbot Section Starts Here -->
    <div id="chat-container" style="display: none;">
        <div id="chat-header" style="background: linear-gradient(to right, #ff7e5f, #feb47b); color: white;">
            <h2>ZapBot</h2>
        </div>
        <div id="chat-box" style="background: #f9f9f9;"></div>
    </div>
    <!-- Chatbot Section Ends Here -->

    <style>
        #chat-container {
            width: 300px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ffffff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        #chat-box {
            height: 200px;
            overflow-y: auto;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .message {
            margin: 5px 0;
        }
        .message.bot {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 5px;
            border-radius: 10px;
            cursor: pointer;
        }
        .message.answer {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            color: white;
            padding: 5px;
            border-radius: 10px;
            margin-left: 20px;
        }
    </style>

    <script>
        function toggleChatbot() {
            const chatContainer = document.getElementById('chat-container');
            if (chatContainer.style.display === 'none') {
                chatContainer.style.display = 'block';
                loadFAQ();
                displayWelcomeMessage();
            } else {
                chatContainer.style.display = 'none';
            }
        }

        function loadFAQ() {
            fetch('config/faq.json')
                .then(response => response.json())
                .then(data => {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML = ''; // Clear previous messages
                    data.forEach(faq => {
                        const question = document.createElement('div');
                        question.className = 'message bot';
                        question.textContent = faq.question;
                        question.onclick = () => displayAnswer(question, faq.answer);
                        chatBox.appendChild(question);
                    });
                });
        }

        function displayWelcomeMessage() {
            const chatBox = document.getElementById('chat-box');
            const welcomeMessage = document.createElement('div');
            welcomeMessage.className = 'message bot';
            welcomeMessage.textContent = 'Hi, I am here to assist you!';
            chatBox.appendChild(welcomeMessage);
        }

        function displayAnswer(questionElement, answer) {
            const answerMessage = document.createElement('div');
            answerMessage.className = 'message answer';
            answerMessage.textContent = answer;
            questionElement.insertAdjacentElement('afterend', answerMessage);
            const chatBox = document.getElementById('chat-box');
            // chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
        }

        document.getElementById('bot').addEventListener('click', toggleChatbot);
    </script>
</body>
</html>