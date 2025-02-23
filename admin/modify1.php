<?php
session_start();
ob_start();
require('dbconn.php');

if (!isset($_SESSION['RollNo'])) {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
</head>

<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="index.php">LMS</a>
                <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav pull-right">
                        <li class="nav-user dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="images/user.png" class="nav-avatar" />
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php">Your Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="span3">
                    <div class="sidebar">
                        <ul class="widget widget-menu unstyled">
                            <li class="active"><a href="index.php"><i class="menu-icon icon-home"></i>Home</a></li>
                            <li><a href="student.php"><i class="menu-icon icon-user"></i>Manage Students</a></li>
                            <li><a href="book.php"><i class="menu-icon icon-book"></i>All Books</a></li>
                            <li><a href="addbook.php"><i class="menu-icon icon-edit"></i>Add Books</a></li>
                            <li><a href="current.php"><i class="menu-icon icon-list"></i>Currently Issued Books</a></li>
                        </ul>
                        <ul class="widget widget-menu unstyled">
                            <li><a href="logout.php"><i class="menu-icon icon-signout"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>

                <div class="span9">
                    <div class="module">
                        <div class="module-head">
                            <h3>Update Book Details</h3>
                        </div>
                        <div class="module-body">

                            <?php
                            $bookid = $_GET['id'];
                            $sql = "SELECT * FROM LMS.book WHERE Bookid=?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $bookid);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $stmt->close();
                            ?>

                            <br>
                            <form class="form-horizontal row-fluid" action="" method="post">

                                <div class="control-group">
                                    <label class="control-label"><b>Book Section:</b></label>
                                    <div class="controls">
                                        <select name="Section" class="span8" required>
                                            <option value="General Reference" <?= ($row['Section'] == "General Reference") ? 'selected' : ''; ?>>General Reference</option>
                                            <option value="Reference" <?= ($row['Section'] == "Reference") ? 'selected' : ''; ?>>Reference</option>
                                            <option value="Filipiniana" <?= ($row['Section'] == "Filipiniana") ? 'selected' : ''; ?>>Filipiniana</option>
                                            <option value="Periodical" <?= ($row['Section'] == "Periodical") ? 'selected' : ''; ?>>Periodical</option>
                                            <option value="Reserved Books" <?= ($row['Section'] == "Reserved Books") ? 'selected' : ''; ?>>Reserved Books</option>
                                            <option value="Graduate Studies" <?= ($row['Section'] == "Graduate Studies") ? 'selected' : ''; ?>>Graduate Studies</option>
                                            <option value="Special Collections" <?= ($row['Section'] == "Special Collections") ? 'selected' : ''; ?>>Special Collections</option>
                                            <option value="Rare Book" <?= ($row['Section'] == "Rare Book") ? 'selected' : ''; ?>>Rare Book</option>
                                            <option value="Computer Internet Area" <?= ($row['Section'] == "Computer Internet Area") ? 'selected' : ''; ?>>Computer Internet Area</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><b>Subject</b></label>
                                    <div class="controls">
                                        <input type="text" name="Subject" class="span8" required value="<?= $row['Subject'] ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><b>Stock Left</b></label>
                                    <div class="controls">
                                        <input type="number" name="stock" class="span8" required>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <div class="controls">
                                        <button type="submit" name="submit" class="btn btn-primary">Update Details</button>
                                    </div>
                                </div>

                            </form>

                            <?php
                            if (isset($_POST['submit'])) {
                                $Section = $_POST['Section'];
                                $Subject = $_POST['Subject'];
                                $stock = $_POST['stock'];

                                // Update book details
                                $sql1 = "UPDATE LMS.book SET Section=?, Subject=? WHERE BookId=?";
                                $stmt1 = $conn->prepare($sql1);
                                $stmt1->bind_param("sss", $Section, $Subject, $bookid);

                                // Update stock details
                                $sql2 = "UPDATE stock SET stock_left=? WHERE book_id=?";
                                $stmt2 = $conn->prepare($sql2);
                                $stmt2->bind_param("is", $stock, $bookid);

                                if ($stmt1->execute() && $stmt2->execute()) {
                                    echo "<script>alert('Book details updated successfully!');</script>";
                                } else {
                                    echo "<script>alert('Error updating book details.');</script>";
                                }

                                $stmt1->close();
                                $stmt2->close();
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts/jquery-1.9.1.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>

</body>

</html>
