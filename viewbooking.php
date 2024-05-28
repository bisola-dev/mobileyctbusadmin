<?php
require_once('cann2.php');
require_once('envary.php');

// Initialize variables
$staffy = "";
$staffIdError = "";
$walletQuery = "SELECT * FROM [Bus_Booking].[dbo].[Transactions]"; // Query to select all transactions
$Balance = 0;
$staffname = ""; // Initialize error message variable

// Execute the wallet query to fetch all transactions
$allTransactionsResult = sqlsrv_query($conn, $walletQuery);

if ($allTransactionsResult === false) {
    die("Error executing wallet query: " . print_r(sqlsrv_errors(), true));
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            overflow-x: hidden; /* Prevent horizontal scrollbar */
        }

        .container {
            width: 80%; /* Adjusted width */
            margin: 20px auto; /* Center container */
            margin-left:15%; /* Push content to the right */
            display: flex;
            justify-content: flex-end; /* Shift content to the right */
            flex-direction: column; /* Align items in a column */
            align-items: center; /* Center items horizontally */
        }

        .welcome-message {
            text-align: center; /* Center align the text */
            margin-top: 20px; /* Add margin to the top */
        }

        /* Table styles */
        table {
            width: 100%; /* Adjusted width to fill the container */
            border-collapse: collapse;
            border: 1px solid #ccc;
            margin-bottom: 20px; /* Add space between form and table */
        }

        th, td {
            padding: 10px; /* Add padding to cells */
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #008000;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
<div class="welcome-message">
        <p><b>WELCOME, Admin <?php echo $uzname; ?></b></p>
    </div>


    <div class="sidebar">
        <?php include "sidebar.php"; ?>
    </div>

    <div class="form-container">
        <p><i>View all bookings</i></p>
        <table id="myTable">
            <thead>
            <tr>
                <th>Staff ID</th>
                <th>Seat Number</th>
                <th>Booking Date</th>
                <th>Route Description</th>
                <th>Amount</th>
                <th>Staff Name</th>
                <th>View Booking</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = sqlsrv_fetch_array($allTransactionsResult, SQLSRV_FETCH_ASSOC)) {
                // Display each row of data
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

                // Prepare and execute SQL query to fetch staff details
                $staffQuery = "SELECT SURNAME, FIRSTNAME, MIDDLENAME FROM [Bus_Booking].[dbo].[stafflist] WHERE STAFFNUMBER = ?";
                $staffParams = array($row['staffid']);
                $staffResult = sqlsrv_query($conn, $staffQuery, $staffParams);

                if ($staffResult !== false) {
                    // Fetch staff details
                    $staffRow = sqlsrv_fetch_array($staffResult, SQLSRV_FETCH_ASSOC);
                    $surname = $staffRow['SURNAME'];
                    $firstname = $staffRow['FIRSTNAME'];
                    $middlename = $staffRow['MIDDLENAME'];
                    $stafffname = $surname . ' ' . $firstname;
                    $staffname = $surname . ' ' . $firstname . ' ' . $middlename;

                    // Display staff name
                    echo "<td>$stafffname</td>";
                } else {
                    echo "Error fetching staff details: " . print_r(sqlsrv_errors(), true);
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
            ?>
            </tbody>
        </table>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

</body>
</html>
