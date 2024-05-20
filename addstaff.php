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
        $staffy = $_POST["staffy"];
        $midl = $_POST["midl"];
        $srna = $_POST["srna"];
        $firs= $_POST["firs"];
    
        // Check if username, password, and role are provided
        if (!empty($staffy) && !empty($srna) && !empty($firs)) {
            // Check if the username already exists
            $check_query = "SELECT COUNT(*) AS num_rows FROM [Bus_Booking].[dbo].[stafflist] WHERE STAFFNUMBER = ?";
            $check_params = array($staffy);
            $check_stmt = sqlsrv_query($conn, $check_query, $check_params);
            
            if ($check_stmt !== false) {
                $row = sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC);
                $num_rows = $row['num_rows'];
                
                if ($num_rows > 0) {
                    echo '<script>alert("staffnumber already exists!");</script>';
                } else {
                    // Username does not exist, proceed with insertion
                    $query = "INSERT INTO [Bus_Booking].[dbo].[stafflist] (STAFFNUMBER, SURNAME,FIRSTNAME,MIDDLENAME) VALUES (?, ?, ?,?)";
                    $params = array($staffy,$srna,$firs,$midl);
                    
                    // Execute SQL query
                    $stmt = sqlsrv_query($conn, $query, $params);
                    
                    if ($stmt !== false) {
                        echo '<script>alert("staff added successfully!");</script>';
                    } else {
                        // Handle query execution error
                        $errors = sqlsrv_errors();
                        echo '<script>alert("Error adding staff: ' . $errors[0]['message'] . '");</script>';
                    }
                }
            } else {
                // Handle query execution error
                $errors = sqlsrv_errors();
                echo '<script>alert("Error checking STAFFNUMBER: ' . $errors[0]['message'] . '");</script>';
            }
        } else {
            // Handle empty fields
            echo '<script>alert("Please provide the required credentials !");</script>';
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
            <h2>Add Staff</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="staff ID">Staff ID:</label>
                    <input type="text" id="staffy" name="staffy" required>
                </div>

                <div class="form-group">
                    <label for="Surname">SURNAME:</label>
                    <input type="text" id="srna" name="srna" required>
                </div>

                <div class="form-group">
                    <label for="Firstname">FIRST NAME:</label>
                    <input type="text" id="firs" name="firs" required>
                </div>

                <div class="form-group">
                    <label for="middlename">MIDDLE NAME:</label>
                    <input type="text" id="midl" name="midl" >
                </div>

                <button type="submit" class="btn-submit">Add Staff</button>
            </form>
        </div>

        </script>
</body>
</html>
