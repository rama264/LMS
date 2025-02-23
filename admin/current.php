<?php
require('dbconn.php');
?>

<?php 
if ($_SESSION['RollNo'] == 'admin') {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <i class="icon-reorder shaded"></i></a><a class="brand" href="index.php">LMS </a>
                <div class="nav-collapse collapse navbar-inverse-collapse">
                    <ul class="nav pull-right">
                        <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="images/user.png" class="nav-avatar" />
                            <b class="caret"></b></a>
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

    <!-- Sidebar -->
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
                    <form class="form-horizontal row-fluid" action="current.php" method="post">
                        <div class="control-group">
                            <label class="control-label" for="Search"><b>Search:</b></label>
                            <div class="controls">
                                <input type="text" id="title" name="title" placeholder="Enter ID No of Student/Book Name/Book Id." class="span8" required>
                                <button type="submit" name="submit" class="btn">Search</button>
                            </div>
                        </div>
                    </form>
                    
                    <br>
                    <?php
                    if (isset($_POST['submit'])) {
                        $s = $_POST['title'];
                        $sql = "SELECT record.BookId, id, RollNo, Textbook, Due_Date, Date_of_Issue, Date_of_Return, 
                                DATEDIFF(CURDATE(), Due_Date) AS x 
                                FROM LMS.record, LMS.book 
                                WHERE Date_of_Issue IS NOT NULL AND Date_of_Return IS NULL 
                                  AND book.BookId = record.BookId 
                                  AND (RollNo='$s' OR record.BookId='$s' OR Textbook LIKE '%$s%')";
                    } else {
                        $sql = "SELECT record.BookId, id, RollNo, Textbook, Due_Date, Date_of_Issue, Date_of_Return, 
                                DATEDIFF(CURDATE(), Due_Date) AS x 
                                FROM LMS.record, LMS.book 
                                WHERE Date_of_Issue IS NOT NULL AND Date_of_Return IS NULL 
                                  AND book.BookId = record.BookId";
                    }

                    // Execute the query
                    $result = $conn->query($sql);

                    // Check if query was successful and there are results
                    if ($result && mysqli_num_rows($result) > 0) {
                    ?>
                    <form action="dellall.php" method="post">
                        <button onclick="return myFunction2()" name="delete" type="submit" class="btn btn-primary">Delete All</button>
                        <script>
                        function myFunction2() {
                            return confirm('Are you sure you want to delete all currently issued books even if not returned?');
                        }
                        </script>
                    </form>

                    <table class="table" id="tables">
                        <thead>
                            <tr>
                                <th>Borrower's ID</th> 
                                <th>Book id</th>
                                <th>Book name</th>
                                <th>Issue Date</th>
                                <th>Due date</th>
                                <th>Return Date</th>
                                <th>Dues</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                $id = $row['id'];
                                $rollno = $row['RollNo'];
                                $bookid = $row['BookId'];
                                $name = $row['Textbook'];
                                $issuedate = $row['Date_of_Issue'];
                                $return = $row['Date_of_Return'];
                                $duedate = $row['Due_Date'];
                                $dues = $row['x'];
                            ?>
                            <tr>
                                <td><?php echo strtoupper($rollno) ?></td>
                                <td><?php echo $bookid ?></td>
                                <td><?php echo $name ?></td>
                                <td><?php echo $issuedate ?></td>
                                <td><?php echo $duedate ?></td>
                                <td><?php echo $return ?></td>
                                <td><?php echo $dues > 0 ? "<font color='red'>$dues</font>" : "<font color='green'>0</font>"; ?></td>
                                <td>
                                    <form action="delcu.php" method="post">
                                        <input type="hidden" name="bookId" value="<?php echo $bookid; ?>">
                                        <button onclick="return myFunction2()" name="delete" type="submit" class="btn btn-primary">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } else {
                        echo "<br><center><h2><b><i>No Results</i></b></h2></center>";
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <b class="copyright"><p> &copy; 2025 Library Management System. </p></b>All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
    <script src="scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
    <script src="scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="scripts/common.js" type="text/javascript"></script>
</body>

</html>

<?php 
} else {
    echo "<script type='text/javascript'>alert('Access Denied!!!')</script>";
}
?>
