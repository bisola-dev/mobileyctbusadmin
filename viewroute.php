<?php
// Include necessary files
require_once('cann2.php');
require_once('envary.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST["selectedrid"]) || empty($_POST["date"])) {
        $paraError = "Route and Date are required";
    } else {
        // Retrieve input values
        $routeId = $_POST["selectedrid"];
        $date = $_POST["date"];

        // Prepare SQL query
        $transQuery = "SELECT * FROM [Bus_Booking].[dbo].[Transactions] WHERE rid = ? AND booking_date = ?";

        // Execute SQL query
        $params = array($routeId, $date);
        $transResult = sqlsrv_query($conn, $transQuery, $params);

        // Check for query execution errors
        if ($transResult === false) {
            die("Error executing transaction query: " . print_r(sqlsrv_errors(), true));
        }

        // Fetch the result set
        $transData = array();
        while ($row = sqlsrv_fetch_array($transResult, SQLSRV_FETCH_ASSOC)) {
            $transData[] = $row;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
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
            margin-left: auto; /* Align the table to the right */
            margin-right: auto; /* Align the table to the left */
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #008000;
            color: #fff;
        }

         /* Responsive adjustments */
@media screen and (max-width: 768px) {
    table {
        font-size: 12px; /* Decrease font size for smaller screens */
        width: 100%; /* Ensure table fills the container */
        overflow-x: auto; /* Enable horizontal scrolling */
        display: block; /* Ensure table behaves like a block element */
    }

    th, td {
        padding: 8px;
        text-align: left;
    }

    .container {
        padding: 4px; /* Reduce padding for smaller screens */
    }
}

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
                <label for="route">Route:</label>
                <select id="route" name="selectedrid" required>
                    <option value="">Select Route</option>
                    <?php
                    // Fetch and display routes
                    $query = "SELECT * FROM [Bus_Booking].[dbo].[Routes]";
                    $result = sqlsrv_query($conn, $query);
                    if ($result === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                        $route_id = $row['rid']; // Adjust column name if needed
                        $route_name = $row['description']; // Adjust column name if needed
                        echo "<option value='$route_id'>$route_name</option>";
                    }
                    // Free the statement
                    sqlsrv_free_stmt($result);
                    ?>
                </select>

                <label for="date">Select Date:</label>
                <input type="text" id="date" name="date">
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Search</button>
            </div>
        </form>
    </div>


<p><i>View route listing</i></p>
<?php
// Check if transaction results are available
if (isset($transData) && !empty($transData)) {
    echo '<div style="overflow-x:auto;">';
    echo '<table id="myTable" class="display">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Staff ID</th>';
    echo '<th>Ticket Type</th>';
    echo '<th>Seat Number</th>';
    echo '<th>Booking Date</th>';
    echo '<th>Route Description</th>';
    echo '<th>Amount</th>';
    echo '<th>Staff Name</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($transData as $row) {
        echo '<tr>';
        echo '<td>' . $row['staffid'] . '</td>';
        echo '<td>' . $row['ticket_type'] . '</td>';
        echo '<td>'  . $row['seat_no'] . '</td>';
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

        // Fetch and display staff details
        $staffQuery = "SELECT SURNAME, FIRSTNAME, MIDDLENAME FROM [Bus_Booking].[dbo].[stafflist] WHERE STAFFNUMBER = ?";
        $staffParams = array($row['staffid']);
        $staffResult = sqlsrv_query($conn, $staffQuery, $staffParams);

        if ($staffResult !== false) {
            $staffRow = sqlsrv_fetch_array($staffResult, SQLSRV_FETCH_ASSOC);
            $surname = $staffRow['SURNAME'];
            $firstname = $staffRow['FIRSTNAME'];
            $middlename = $staffRow['MIDDLENAME'];
            $stafffname = $surname . ' ' . $firstname . ' ' . $middlename;

            echo "<td>$stafffname</td>";
            echo '</tr>';
        } else {
            echo "Error fetching staff details: " . print_r(sqlsrv_errors(), true);
        }
    }

    echo '</tbody>';
    echo '</table>';
    echo '<button id="download-btn">Download Table</button>';
    echo '</div>';
} else {
    echo "No records found for the selected route and date.";
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>


<script>
    document.getElementById("download-btn").addEventListener("click", function() {
        var table = document.getElementById("myTable");
        var rows = table.rows;
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var row = [];
            for (var j = 0; j < rows[i].cells.length; j++) {
                row.push(rows[i].cells[j].innerText);
            }
            csv.push(row.join(","));
        }
        var csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        var currentDate = new Date().toISOString().slice(0, 10); // Get current date in YYYY-MM-DD format
        var filename = "route_data_" + currentDate + ".csv";
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>

<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        responsive: true,
        "order": [[ 2, 'asc' ]] // Sort by the second column (index 1) in ascending order
    });
});

    $(function() {
        $("#date").datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>

</body>
</html>
