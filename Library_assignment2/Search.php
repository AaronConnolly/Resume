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
        <nav class="navbar">
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

    <div class="SearchMain">
        <form method="post">
            <p>Please Search by Author</p>
            <input type="text" name="Author" ><br>
            <button type="submit" class="btn2"><img src="images/magnifyingGlass.png">Search</button>
            <p>Please Search by Book Title</p>
            <input type="text" name="BookTitle" ><br>
            <button type="submit" class="btn2"><img src="images/magnifyingGlass.png">Search</button>
        </form>
        <?php
            $categorySql = "SELECT * FROM category";
            $categoryResult = $pdo->query($categorySql);

            echo "<div class='dropdown'>";
            echo "<button class='catbtn'>Category</button><ul>";
    
            while ($row = $categoryResult->fetch(PDO::FETCH_ASSOC)) {
                $categoryId = htmlentities($row["CategoryID"]);
                $categoryDescription = htmlentities($row["CategoryDescription"]);
                echo "<li class='dropdown_item'><form method='post'>
                        <input type='hidden' name='CategoryID' value='$categoryId'>
                        <button class='dropbtn' type='submit' name='category'>$categoryDescription</button>
                      </form></li>";
            }
    
            echo "</ul></div>";
        ?>
    </div>

    <?php    // Handle the search and filter logic
    if (isset($_POST["Author"]) || isset($_POST["BookTitle"]) || isset($_POST['category'])) {
        $authorInput = isset($_POST['Author']) ? $_POST['Author'] : '';
        $bookTitleInput = isset($_POST['BookTitle']) ? $_POST['BookTitle'] : '';
        $categoryIDInput = isset($_POST['CategoryID']) ? $_POST['CategoryID'] : '';

        // Prepare the base query with optional filters
        $sql = "SELECT B.*, C.CategoryDescription FROM books B JOIN category C using (CategoryID) WHERE 1=1";

        $params = [];
        if (!empty($authorInput)) {
            $sql .= " AND B.Author LIKE :author";
            $params[':author'] = "%$authorInput%";
        }
        if (!empty($bookTitleInput)) {
            $sql .= " AND B.BookTitle LIKE :bookTitle";
            $params[':bookTitle'] = "%$bookTitleInput%";
        }
        if (!empty($categoryIDInput)) {
            $sql .= " AND B.CategoryID = :categoryID";
            $params[':categoryID'] = $categoryIDInput;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>BookTitle</th><th>Author</th><th>Edition</th><th>Year</th><th>Category</th><th>Reserved</th></tr>";

            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . (isset($row["BookTitle"]) ? htmlentities($row["BookTitle"]) : 'N/A') . "</td>";
                echo "<td>" . (isset($row["Author"]) ? htmlentities($row["Author"]) : 'N/A') . "</td>";
                echo "<td>" . (isset($row["Edition"]) ? htmlentities($row["Edition"]) : 'N/A') . "</td>";
                echo "<td>" . (isset($row["Year"]) ? htmlentities($row["Year"]) : 'N/A') . "</td>";
                echo "<td>" . (isset($row["CategoryDescription"]) ? htmlentities($row["CategoryDescription"]) : 'N/A') . "</td>";

                // Add Reserve button if the book is not reserved
                if (isset($row["Reserved"]) && $row["Reserved"] == 'N') {
                    $isbn = htmlentities($row["ISBN"]);
                    echo "<td><form method='post'>
                            <input type='hidden' name='isbn' value='$isbn'>
                            <button type='submit' name='reserve'>Reserve</button>
                        </form></td>";
                } else {
                    echo "<td>Already Reserved</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No books found.";
        }
    }


    // Handle the reservation when Reserve button is clicked
    if (isset($_POST['reserve'])) {
        $isbn = $_POST['isbn'];
        $username = $_SESSION['Username'];
        $reservedDate = date("Y-m-d");

        // Use prepared statement for updating book reservation status
        $updateSql = "UPDATE books SET Reserved = 'Y' WHERE ISBN = :isbn";
        $stmt = $pdo->prepare($updateSql);
        $stmt->execute([':isbn' => $isbn]);

        // Insert reservation information into the reservations table
        $insertSql = "INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES (:isbn, :username, :reservedDate)";
        $stmt = $pdo->prepare($insertSql);
        $stmt->execute([':isbn' => $isbn, ':username' => $username, ':reservedDate' => $reservedDate]);
    }

// Close the connection
$pdo = null;
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