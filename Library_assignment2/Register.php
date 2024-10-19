<?php
require_once "database.php";
session_start();

if( isset($_POST['Username']) && isset($_POST['Password']) && isset($_POST['confirmpas']) 
    && isset($_POST['FirstName']) && isset($_POST['Surname']) && isset($_POST['AddressLine1'])
    && isset($_POST['AddressLine2']) && isset($_POST['City']) && isset($_POST['Telephone']) 
    && isset($_POST['Mobile'])) {

    $u = $_POST['Username'];
    $fn = $_POST['FirstName'];
    $sn = $_POST['Surname'];
    $a1 = $_POST['AddressLine1'];
    $a2 = $_POST['AddressLine2'];
    $c = $_POST['City'];
    $t = $_POST['Telephone'];
    $m = $_POST['Mobile'];

    // Check if passwords match
    $p = $_POST['Password'];
    $pc = $_POST['confirmpas'];

    if ($p != $pc) {
        echo "Error: Passwords do not match";
        exit; // Stop execution if passwords do not match
    }

    // Hash the password for security
    $Password = $p;

    // Insert data into the "users" table using PDO
    try {
        $sql = "INSERT INTO users (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) 
                VALUES (:Username, :Password, :FirstName, :Surname, :AddressLine1, :AddressLine2, :City, :Telephone, :Mobile)";

        // Prepare statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':Username', $u);
        $stmt->bindParam(':Password', $p); 
        $stmt->bindParam(':FirstName', $fn);
        $stmt->bindParam(':Surname', $sn);
        $stmt->bindParam(':AddressLine1', $a1);
        $stmt->bindParam(':AddressLine2', $a2);
        $stmt->bindParam(':City', $c);
        $stmt->bindParam(':Telephone', $t);
        $stmt->bindParam(':Mobile', $m);

        // Execute statement
        $stmt->execute();
        echo "New record created successfully";

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
<div class="main3">
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

    <div class="reg">
        <h1>Register</h1>
        <form method="post">
            <label>Username *</label><input type="text" name="Username" required><br>
            <label>First Name *</label><input type="text" name="FirstName" required><br>
            <label>Surname *</label><input type="text" name="Surname" required><br>
            <label>Address line 1 *</label><input type="text" name="AddressLine1" required><br>
            <label>Address line 2 (optional)</label><input type="text" name="AddressLine2"><br>
            <label>City *</label><input type="text" name="City" required><br>
            <label>Phone number *</label><input type="text" name="Mobile" maxlength="10" minlength="10" required><br>
            <label>Telephone number *</label><input type="text" name="Telephone" maxlength="10" minlength="10" required><br>
            <label>Password *</label><input type="password" name="Password" maxlength="12" minlength="6" required><br>
            <label>Confirm Password *</label><input type="password" name="confirmpas" maxlength="12" minlength="6" required><br>
            <button type="submit" value="add new">Confirm</button>
        </form>
        <p>Already have an account?</p>
        <a href="Login.php">Log in</a>
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