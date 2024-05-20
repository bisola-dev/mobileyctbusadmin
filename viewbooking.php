<?php
require_once('cann2.php');
require_once('envary.php');

// Initialize variables
$staffy = "";
$staffIdError = "";
$walletQuery = "";
$Balance = 0;
$staffname = ""; // Initialize error message variable

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate staff ID
    if (empty($_POST["staffId"])) {
        $staffIdError = "Staff ID is required";
    } else {
        $staffy = $_POST["staffId"];
        $walletQuery = "SELECT * FROM [Bus_Booking].[dbo].[Transactions] WHERE staffid = ?";
        
        // Execute the wallet query
        $params = array($staffy);
        $walletResult = sqlsrv_query($conn, $walletQuery, $params);

        if ($walletResult === false) {
            die("Error executing wallet query: " . print_r(sqlsrv_errors(), true));
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Transactions</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <style>
          <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            overflow-x: hidden; /* Prevent horizontal scrollbar */
        }

        .container {
            display: flex;
            justify-content: center; /* Center items horizontally */
            align-items: center;
            flex-direction: column; /* Stack items vertically */
            height: auto; /* Reduce height to bring it up */
          width: 100%; /* Full width */
          margin-top: 10px; /* Add some top margin */
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 45%; /* Adjusted width */
            text-align: center;
             margin: 0 auto; /* Center the form */
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: calc(100% - 22px); /* Adjusted for padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #008000;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #FFFF00;
            color: #000;
        }

        /* Table styles */
        table {
    width: 100%; /* Adjusted width to fill the container */
    border-collapse: collapse;
    border: 1px solid #ccc;
    margin-bottom: 20px; /* Add space between form and table */
}

th, td {
    padding: 8px;
    border: 1px solid #ccc;
    text-align: left;
    max-width: 150px; /* Limit the maximum width of table cells */
    overflow: hidden; /* Hide overflowing content */
    text-overflow: ellipsis; /* Add ellipsis for overflowed text */
    white-space: nowrap; /* Prevent text wrapping */
}

th {
    background-color: #008000;
    color: #fff;
}
        /* New table styles */
.wallet-table {
    width: 30%; /* Adjusted width */
    border-collapse: collapse;
    border: 1px solid #ccc;
    margin-top: 20px; /* Add space between previous table and new table */
}

.wallet-table th, .wallet-table td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
    font-weight: bold;
}

.wallet-table th {
    background-color: #008000;
    color: #fff;
}



    </style>
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <?php include "sidebar.php"; ?>
    </div>

    <p><b>WELCOME, Admin <?php echo $uzname; ?></b></p>

    <div class="form-container">
        <form action="" method="POST">
            <div class="form-group">
                <label for="staffId">Search Bookings by Staff ID:</label>
                <input type="text" id="staffId" name="staffId" placeholder="Enter Staff ID">
                <span class="error"><?php echo $staffIdError; ?></span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Search</button>
            </div>
        </form>
    </div>

    <p><i>View bookings</i></p>

    <?php 
if (!empty($walletQuery)) {
    if (sqlsrv_has_rows($walletResult)) {
        echo '<div style="overflow-x:auto;">';
        echo '<table id="myTable" class="display">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Staff ID</th>';
        echo '<th>Seat Number</th>';
        echo '<th>Booking Date</th>';
        echo '<th>Route Description</th>';
        echo '<th>Amount</th>';
        echo '<th>View Booking</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = sqlsrv_fetch_array($walletResult, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['staffid'] . '</td>';
            echo '<td>' . $row['ticket_type'] . ' ' . $row['seat_no'] . '</td>';
            echo '<td>' . $row['booking_date']->format('Y-m-d') . '</td>';
            echo '<td>';
            
            $rid = $row['rid'];
            $query2 = "SELECT description, amount FROM [Bus_Booking].[dbo].[Routes] WHERE rid = ?";
            $params2 = array($rid);
            $result2 = sqlsrv_query($conn, $query2, $params2);

            if ($result2 !== false && sqlsrv_has_rows($result2)) {
                while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
                    $description = $row2['description'];
                    $amount = $row2['amount'];
                    echo $description;
                }
            } else {
                echo "No route description found for RID: $rid";
            }
            
            echo '</td>';
            echo '<td>' . $amount . '</td>';

            // SQL query to fetch data
            $query = "SELECT SURNAME, FIRSTNAME, MIDDLENAME FROM [Bus_Booking].[dbo].[stafflist] WHERE STAFFNUMBER = ?";
            $params = array($staffy);
            $rezn = sqlsrv_query($conn, $query, $params);

            // Check if query was successful
            if ($rezn !== false) {
                // Check if any rows were returned
                if (sqlsrv_has_rows($rezn)) {
                    // Fetch data
                    $staff_row = sqlsrv_fetch_array($rezn, SQLSRV_FETCH_ASSOC);
                    // Extract values from the fetched row
                    $surname = $staff_row['SURNAME'];
                    $firstname = $staff_row['FIRSTNAME'];
                    $middlename = $staff_row['MIDDLENAME'];

                    // Combine names
                    $staffname = $surname . ' ' . $firstname . ' ' . $middlename;
                } else {
                    echo "No record found for staff number: " . $row['staffid'];
                }
            } else {
                // Handle query execution error
                echo "Error executing query: " . sqlsrv_errors();
            }
            
            // Encode data for URL
            $encoded_staffname = base64_encode($staffname);
            $encoded_staffid = base64_encode($row['staffid']);
            $encoded_seat_no = base64_encode($row['seat_no']);
            $encoded_ticket_type = base64_encode($row['ticket_type']);
            $encoded_booking_date = base64_encode($row['booking_date']->format('Y-m-d'));
            $encoded_description = base64_encode($description);
            $encoded_amount = base64_encode($amount);

            // Construct link
            $link = "view.php?staffid=$encoded_staffid&staffname=$encoded_staffname&seat_no=$encoded_seat_no&ticket_type=$encoded_ticket_type&booking_date=$encoded_booking_date&description=$encoded_description&amount=$encoded_amount";
            echo '<td><a href="' . $link . '"><button>View Details</button></a></td>';
            
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo "No records found for staff ID: $staffy.";
    }
} else {
    echo "Error: No 'staffid' parameter provided.";
}
?>

                                
        
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
</script>

</body>
</html>
