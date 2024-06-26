<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Common styles for sidebar and content */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        /* Sidebar styles */
        #sidebar {
            width: 250px;
            height: 100%;
            background-color: #008000; /* Green */
            position: fixed;
            left: 0;
            top: 0;
            overflow-x: hidden;
            padding-top: 20px;
            transition: width 0.5s; /* Add transition for smooth animation */
            z-index: 1000; /* Ensure sidebar appears above other content */
            display: none; /* Initially hide sidebar */
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

        /* Content styles */
        #content {
            padding: 20px;
            margin-left: 250px; /* Adjust according to sidebar width */
            transition: margin-left 0.5s;
        }

        @media screen and (max-width: 768px) {
            #sidebar {
                width: 150px; /* Adjust width for smaller screens */
            }
            #content {
                margin-left: 0; /* Ensure no margin when sidebar is hidden */
            }
        }

        /* Toggle button styles */
        .toggle-sidebar {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100; /* Ensure the button is above the sidebar and content */
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="logo">
            <img src="glaze/yabayctlogo.png" alt="Yabayct Logo" style="width: 80px; height: auto; margin-top: 22px;">
        </div>
        <ul>
            <li><a href="busdashboard.php">Home</a></li>
            <?php if ($rolez == 1) { ?>
                <li><a href="Addadmin.php">Add admin</a></li>
                <li><a href="Addstaff.php">Add Staff</a></li>
                <li><a href="Addroute.php">Add Route</a></li>
            <?php } ?>
            <li><a href="viewtransc.php">View all Wallet Transaction</a></li>
            <li><a href="viewbooking.php">View all Bookings</a></li>
            <li><a href="viewroute.php">View all routes</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div id="content">
        <button class="toggle-sidebar" onclick="toggleSidebar()" style="background-color: yellow;">Show Sidebar</button>
        <!-- Your content here -->
    </div>

    <script>
        // Function to toggle sidebar visibility and button text
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var content = document.getElementById("content");
            var toggleButton = document.querySelector(".toggle-sidebar");

            // Check if sidebar is currently hidden
            if (sidebar.style.display === "none" || sidebar.style.display === "") {
                sidebar.style.display = "block"; // Show sidebar
                content.style.marginLeft = "250px"; // Adjust content margin
                toggleButton.textContent = "Hide Sidebar"; // Change button text
            } else {
                sidebar.style.display = "none"; // Hide sidebar
                content.style.marginLeft = "0"; // Adjust content margin
                toggleButton.textContent = "Show Sidebar"; // Change button text
            }
        }
    </script>
</body>
</html>
