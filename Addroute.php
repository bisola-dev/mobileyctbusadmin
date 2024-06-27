<?php 
// Include necessary files
require_once('cann2.php');
require_once('envary.php');
if ($rolez != 1) {
    // If user's role is not equal to 1, redirect to another page and display an alert
    echo '<script type="text/javascript">
        alert("You are not authorized to view this page!");
        window.location.href = "busdashboard.php";
    </script>';}

try {
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $route = $_POST["route"];
        $amt1 = $_POST["amt1"];
        $sit1 = $_POST["sit1"];
        $sit2= $_POST["sit2"];
    
        // Check if username, password, and role are provided
        if (!empty($route) && !empty($amt1) && !empty($sit1)&& !empty($sit2)) {
            // Check if the username already exists
            $check_query = "SELECT COUNT(*) AS num_rows FROM [Bus_Booking].[dbo].[Routes] WHERE description= ?";
            $check_params = array($route);
            $check_stmt = sqlsrv_query($conn, $check_query, $check_params);
            
            if ($check_stmt !== false) {
                $row = sqlsrv_fetch_array($check_stmt, SQLSRV_FETCH_ASSOC);
                $num_rows = $row['num_rows'];
                
                if ($num_rows > 0) {
                    echo '<script>alert("Route Description already exists!");</script>';
                } else {
                    // Username does not exist, proceed with insertion
                    $query = "INSERT INTO [Bus_Booking].[dbo].[Routes] (description, amount,seat_capacity,stand_capacity) VALUES (?, ?, ?,?)";
                    $params = array($route,$amt1,$sit1,$sit2);
                    
                    // Execute SQL query
                    $stmt = sqlsrv_query($conn, $query, $params);
                    
                    if ($stmt !== false) {
                        echo '<script>alert("Route added successfully!");</script>';
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
            margin: 20px auto; /* Slightly reduce margin for better visibility */
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin-bottom: 10px; /* Add margin to separate from table */
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
            margin-top: 5px;
            background-color: #fff; /* Add background color to table */
        }

        .admin-table th, .admin-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .admin-table th {
            background-color: #008000; /* Change header background color */
            color: #fff; /* Change header text color */
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
          /* CSS for the edit form pop-up */
    #editFormContainer {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        z-index: 9999;
    }

    /* Add this CSS to your existing styles */
.btn-cancel {
    background-color: #ff6347;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px; /* Add margin to separate from the "Save Changes" button */
}

.btn-cancel:hover {
    background-color: #ff0000;
}
   /* Responsive adjustments */
   @media screen and (max-width: 768px) {
            table {
                font-size: 12px; /* Decrease font size for smaller screens */
            }

            .container {
                padding: 4px; /* Reduce padding for smaller screens */
            }
        }

    </style>
</head>
<body>
    <div class="sidebar">
            <?php include "sidebar.php";?>
        </div>
    <div class="container">
        <div class="form-container">
            <h2>Add Route</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="route Description">Route Description:</label>
                    <input type="text" id="route" name="route" required>
                </div>

                <div class="form-group">
                <label for="Amount">Amount:</label>
                <input type="number" id="amt" name="amt1" required step="1">
                 </div>

                <div class="form-group">
                    <label for="seatcapacity">Seat Capacity:</label>
                    <input type="number" id="sit1" name="sit1" required>
                </div>

                <div class="form-group">
                    <label for="standcapacity">Stand Capacity:</label>
                    <input type="number" iid="sit2" name="sit2" required>
                </div>

                <button type="submit" class="btn-submit">Add Route</button>
            </form>
        </div>
        
   <!-- Display routes table -->
   <?php
        // Fetch routes data
        $query = "SELECT * FROM [Bus_Booking].[dbo].[Routes]";
        $result = sqlsrv_query($conn, $query);

        if ($result === false) {
            // Handle query execution error
            $errors = sqlsrv_errors();
            echo '<script>alert("Error fetching routes data: ' . $errors[0]['message'] . '");</script>';
        } else {
            // Display routes table
            echo '<table class="admin-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Route Description</th>';
            echo '<th>Amount</th>';
            echo '<th>Seat Capacity</th>';
            echo '<th>Stand Capacity</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>' . $row['amount'] . '</td>';
                echo '<td>' . $row['seat_capacity'] . '</td>';
                echo '<td>' . $row['stand_capacity'] . '</td>';
                echo '<td><button class="edit-btn" onclick="openEditPopup(' . $row['rid'] . ', \'' . $row['description'] . '\', ' . $row['amount'] . ', ' . $row['seat_capacity'] . ', ' . $row['stand_capacity'] . ')">Edit</button></td>';

                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        }

        // Free result and close connection
        sqlsrv_free_stmt($result);
        sqlsrv_close($conn);
        ?>

<!-- Add this HTML at the end of your body tag -->
<div id="editFormContainer" style="display: none;">
    <div class="form-container">
        <h2>Edit Route</h2>
        <form id="editForm" action="edit_route.php" method="post">
            <!-- Inputs for editing route details -->
            <input type="hidden" id="editRouteId" name="editRouteId" value="">
            <div class="form-group">
                <label for="editRouteDescription">Route Description:</label>
                <input type="text" id="editRouteDescription" name="editRouteDescription" required>
            </div>

            <div class="form-group">
                <label for="editAmount">Amount:</label>
                <input type="number" id="editAmount" name="editAmount" required step="1">
            </div>

            <div class="form-group">
                <label for="editSeatCapacity">Seat Capacity:</label>
                <input type="number" id="editSeatCapacity" name="editSeatCapacity" required>
            </div>

            <div class="form-group">
                <label for="editStandCapacity">Stand Capacity:</label>
                <input type="number" id="editStandCapacity" name="editStandCapacity" required>
            </div>

            <button type="submit" class="btn-submit">Save Changes</button>
            <button type="button" class="btn-cancel" onclick="closeEditPopup()">Cancel</button>
        </form>
    </div>
</div>

<script>
    // Function to open the edit popup and populate the route details
    function openEditPopup(routeId, routeDescription, amount, seatCapacity, standCapacity) {
        document.getElementById('editRouteId').value = routeId;
        document.getElementById('editRouteDescription').value = routeDescription;
        document.getElementById('editAmount').value = amount;
        document.getElementById('editSeatCapacity').value = seatCapacity;
        document.getElementById('editStandCapacity').value = standCapacity;
        document.getElementById('editFormContainer').style.display = 'block';
    }

    // Function to close the edit popup
    function closeEditPopup() {
        document.getElementById('editFormContainer').style.display = 'none';
    }
</script>

</body>
</html>
