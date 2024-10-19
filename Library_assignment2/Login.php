<?php
session_start();
require_once "database.php";

if ( isset($_POST["Username"]) && isset($_POST["Password"]) ) { 
    $un = $_POST['Username'];
    $pw = $_POST['Password'];

    // Prepare the SQL query using placeholders
    $sql = "SELECT Username, Password FROM users WHERE Username = '$un' AND Password = '$pw'";
    
    try {
        $stmt = $pdo->query($sql);

        // Fetch the user data
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists
        if ($row) {
            $_SESSION["Username"] = $un;
            $_SESSION["success"] = "Login Successful";
            header('Location: index.php');
            return;
        } else {
            $_SESSION["error"] = "Incorrect username or password. Please try again";
            header('Location: Login.php');
            return;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="CSS/Style.css">
</head>
<body>
<div class="main2">
    <header>
        <nav>
            <img src="images/book3.png" class="logo">
            <h1>Portmarnock library</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="Register.php">Register</a></li>
            </ul>
            <a href="Login.php"><button class="btn"><img src="images/book2.png"> Log_in</button></a>
        </nav>
    </header>

    <div class="log">
        <h1>Log in</h1>
        <?php
            if ( isset($_SESSION["error"]) ) {
            echo('<p style="color:red">Error:'.
            $_SESSION["error"]."</p>\n");
            unset($_SESSION["error"]);
            }
        ?>
        <form method="post">
            <label>Username</label>
            <input type="text" name="Username" required><br>
            <label>Password</label>
            <input type="password" name="Password" required><br>
            <button type="submit" value="Login">Confirm</button>
        </form>
        <p>Don't have an account?</p>
        <a href="Register.php">Register</a>
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