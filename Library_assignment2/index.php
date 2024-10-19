<?php
    session_start();
    if(!isset($_SESSION['success']))
    {
        header("Location: Login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portmarnock library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/Style.css">
</head>
<body>
<div class="main">
    <header>
        <nav>
            <img src="images/book3.png" class="logo">
            <h1>Portmarnock library</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="Register.php">Register</a></li>
                <li><a href="Reserve.php">My Reserved Books</a></li>
            </ul>
            <?php
            if (!isset($_SESSION["Username"])) { ?> 
                <a href="Login.php"><button class="btn"><img src="images/book2.png"> Log in</button></a>
            <?php } else { ?> 
                <a href="Logout.php"><button class="btn"> Logout</button></a>
            <?php } ?>
            
        </nav>
    </header>

    <div class="content">
        <h1>Wealth<br>of knowledge</h1>
        <p>Please search for any book out of our selection</p>
        <a href="Search.php"><button class="Search">Search</button></a>
    </div>
</div>

<footer class="footer">
    <div class="col-1">
        <h3>Contact</h3>
        <p>123, x road, D13<br> Portmarnock, Dublin, Ireland</p>
        <img class="Facebook" src="images/Facebook.png">
        <img class="insta" src="images/insta.png">
        <img class="Twitter" src="images/Twitter.png">
    </div>
    <div class="col-2">
        <h3>Subscribe to our Newsletter</h3>
        <form>
            <input type="email" placeholder="Your Email Address" required>
            <br>
            <button type="submit">SUBSCRIBE NOW</button>
        </form>
    </div>
    <div class="col-3">
        <h3>Learn about us</h3>
        <a href="#">Policies</a> <br>
        <a href="#">About the library</a><br>
        <a href="#">Library news</a><br>
        <a href="#">Library Board</a>
    </div>
</footer>
          
</body>
</html>