<?php 
// Include necessary files
require_once('cann2.php');
require_once('envary.php');
if ($ROLEZ !== 1) {
    // If user's role is not equal to 1, redirect to another page and display an alert
    echo '<script type="text/javascript">
        alert("You are not authorized to view this page!");
        window.location.href = "busdashboard.php";
    </script>';}

try {
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $ustaz = $_POST["username"];
        $rolez = $_POST["role"];
    
        // Generate hashed password
        $rik = 'stabuyct'.'123456';
        $hpazz = md5($rik);
    
        // Check if username, password, and role are provided
        if (!empty($ustaz) && !empty($rolez)) {
            // Check if the username already exists
            $check_query = "SELECT COUNT(*) AS num_rows FROM [Bus_Booking].[dbo].[admin] WHERE USERNAME = ?";
            $check_params = array($ustaz);
            $check_stmt = sqlsrv_query($conn, $check_query, $check_params);
            
            if ($check_stmt !== false) {
                $row = sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC);
                $num_rows = $row['num_rows'];
                
                if ($num_rows > 0) {
                    echo '<script>alert("Username already exists!");</script>';
                } else {
                    // Username does not exist, proceed with insertion
                    $query = "INSERT INTO [Bus_Booking].[dbo].[admin] (USERNAME, PASSWORD, ROLEZ) VALUES (?, ?, ?)";
                    $params = array($ustaz, $hpazz, $rolez);
                    
                    // Execute SQL query
                    $stmt = sqlsrv_query($conn, $query, $params);
                    
                    if ($stmt !== false) {
                        echo '<script>alert("Admin added successfully!");</script>';
                    } else {
                        // Handle query execution error
                        $errors = sqlsrv_errors();
                        echo '<script>alert("Error adding admin: ' . $errors[0]['message'] . '");</script>';
                    }
                }
            } else {
                // Handle query execution error
                $errors = sqlsrv_errors();
                echo '<script>alert("Error checking username: ' . $errors[0]['message'] . '");</script>';
            }
        } else {
            // Handle empty fields
            echo '<script>alert("Please provide username and role!");</script>';
        }
    }
} catch (Exception $e) {
    // Handle the exception here
    echo "An error occurred: " . $e->getMessage();
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 40px auto;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .form-container h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, 
        .form-group select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #008000;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #FFFF00;
            color: #000;
        }
        .admin-table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .admin-table th, .admin-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .admin-table th {
            background-color: #f2f2f2;
        }

        .delete-btn {
            background-color: #ff6347;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="sidebar">
            <?php include "sidebar.php";?>
        </div>
    <div class="container">
        <div class="form-container">
            <h2>Add Admin</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="">Select role</option>
                        <option value="1">SuperAdmin</option>
                        <option value="2">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Add Admin</button>
            </form>
        </div>

         <!-- Table to display added admins -->
         <table class="admin-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch admin data from the database and populate table rows
                $query = "SELECT ID, USERNAME, ROLEZ FROM [Bus_Booking].[dbo].[admin]";
                $stmt = sqlsrv_query($conn, $query);
                if ($stmt !== false) {
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $row['USERNAME'] . '</td>';
                        echo '<td>' . ($row['ROLEZ'] == 1 ? 'SuperAdmin' : 'Admin') . '</td>';
                        echo '<td><button type="button" class="delete-btn" data-id="' . $row['ID'] . '">Delete</button></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>




    <script>
    // Add event listener to delete buttons
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default action of the button
            
            var adminId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete the admin')) {
                // Send AJAX request to delete admin record
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Remove the deleted admin's row from the table
                            var row = button.parentNode.parentNode;
                            row.parentNode.removeChild(row);
                            alert('Admin with ID ' + adminId + ' has been successfully deleted.');
                        } else {
                            // Handle error
                            alert('Error deleting admin: ' + xhr.responseText);
                        }
                    }
                };
                xhr.open('POST', 'delete_admin.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('adminId=' + encodeURIComponent(adminId));
            }
        });
    });
</script>

</body>
</html>
