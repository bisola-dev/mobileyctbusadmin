<?php 
session_start();
require_once('envary.php');

if(isset($_SESSION['USERNAME']) && isset($_SESSION['ROLEZ'])) {
    $uzname = $_SESSION['USERNAME'];
    $rolez = $_SESSION['ROLEZ'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        
        #sidebar {
            width: 250px;
            height: 100%;
            background-color: #008000; /* Green */
            position: fixed;
            left: 0;
            top: 0;
            overflow-x: hidden;
            padding-top: 20px;
        }
        
        #sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        #sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        #sidebar ul li {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }
        
        #content {
            margin-left: 250px;
            padding: 20px;
        }
        
        
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="logo">
        <img src="glaze/yabayctlogo.png" alt="Yabayct Logo" style="width: 70px; height: auto; margin-top: 22px;">
        </div>
        <ul>
        <li><a href="busdashboard.php">Home</a></li> 
            <?php 
         if ($rolez == 1) { 
        echo '<li><a href="Addadmin.php">Add admin</a></li>';
        echo '<li><a href="Addstaff.php">Add Staff</a></li>';
        echo '<li><a href="Addroute.php">Add Route</a></li>';
              } ?>
            <li><a href="viewtransc.php">View all Wallet Transaction</a></li>
            <li><a href="viewbooking.php">View all Booking</a></li>
            <li><a href="viewroute.php">View all routes</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div id="content">
    <h3 style="text-align: center;">Welcome, Admin  <?php echo $uzname;?>, Do enjoy a seamless  check on the bus ticket system.</h3>
        <p style="text-align: center;">This is the YCT Bus Booking Dashboard.</p>


    </div>
</body>
</html>
