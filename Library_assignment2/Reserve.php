<?php
    session_start();
    if(!isset($_SESSION['success']))
    {
        header("Location: Login.php");
        exit();
    }
    require_once "database.php";

    $bt = isset($_SESSION['BookTitle']) ? $_SESSION['BookTitle'] : '';
    $a = isset($_SESSION['Author']) ? $_SESSION['Author'] : '';
    $e = isset($_SESSION['Edition']) ? $_SESSION['Edition'] : '';
    $y = isset($_SESSION['Year']) ? $_SESSION['Year'] : '';
    $cid = isset($_SESSION['CategoryID']) ? $_SESSION['CategoryID'] : '';
    $r = isset($_SESSION['Reserved']) ? $_SESSION['Reserved'] : '';
    $cat = isset($_SESSION['CategoryDescription']) ? $_SESSION['CategoryDescription'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Portmarnock library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/Style.css">
</head>
<body>
<div class="smain">
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

    

    <?php
    if (isset($_SESSION["Username"])) {
        $username = $_SESSION["Username"];
    
        // Use a prepared statement to protect against SQL injection
        $reservedSql = "SELECT R.*, B.BookTitle FROM Reservations R JOIN books B using (ISBN) WHERE Username = :username";
        $stmt = $pdo->prepare($reservedSql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $reservedResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if (count($reservedResult) > 0) {
            echo "<h2>Your Reserved Books</h2>";
            echo "<table border='1'>";
            echo "<tr><th>BookTitle</th><th>Reserved Date</th><th>Action</th></tr>";

            foreach ($reservedResult as $reservedRow) {
                $isbn = htmlentities($reservedRow["ISBN"]);
                $reservedDate = htmlentities($reservedRow["ReservedDate"]);

                echo "<tr>";
                echo "<td>" . htmlentities($reservedRow["BookTitle"]) . "</td>";
                echo "<td>" . htmlentities($reservedRow["ReservedDate"]) . "</td>";

                // Add Return button for each reserved book
                echo "<td>
                        <form method='post'>
                            <input type='hidden' name='isbn' value='$isbn'>
                            <input type='hidden' name='reservedDate' value='$reservedDate'>
                            <button type='submit' name='return'>Return</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "You have no reserved books";
        }
    }

    // Handle book return when the Return button is clicked
    if (isset($_POST['return'])) {
        $isbn = $_POST['isbn'];
        $reservedDate = $_POST['reservedDate'];

        // Use prepared statements to prevent SQL injection
        $deleteSql = "DELETE FROM Reservations WHERE ISBN = :isbn AND ReservedDate = :reservedDate";
        $stmt = $pdo->prepare($deleteSql);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':reservedDate', $reservedDate);
        $stmt->execute();

        // Update the books table to mark the book as not reserved
        $updateSql = "UPDATE books SET Reserved = 'N' WHERE ISBN = :isbn";
        $stmt = $pdo->prepare($updateSql);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->execute();

        // Redirect after completing the update
        header("Location: Reserve.php");
        exit();
    }

    $pdo = null;  // Close the database connection
    ?>

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