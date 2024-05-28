<?php
// Include necessary files
require_once('cann2.php');
require_once('envary.php');

if ($rolez != 1) {
    // If user's role is not equal to 1, redirect to another page and display an alert
    echo '<script type="text/javascript">
        alert("You are not authorized to view this page!");
        window.location.href = "busdashboard.php";
    </script>';
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $editRouteId = $_POST["editRouteId"];
        $editRouteDescription = $_POST["editRouteDescription"];
        $editAmount = $_POST["editAmount"];
        $editSeatCapacity = $_POST["editSeatCapacity"];
        $editStandCapacity = $_POST["editStandCapacity"];

        // Update the route details in the database
        $query = "UPDATE [Bus_Booking].[dbo].[Routes] SET description = ?, amount = ?, seat_capacity = ?, stand_capacity = ? WHERE rid = ?";
        $params = array($editRouteDescription, $editAmount, $editSeatCapacity, $editStandCapacity, $editRouteId);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt !== false) {
            echo '<script>alert("Route details updated successfully!"); window.history.back();</script>';

        } else {
            // Handle query execution error
            $errors = sqlsrv_errors();
            echo '<script>alert("Error updating route details: ' . $errors[0]['message'] . '");window.history.back();</script>';
        }
    } else {
        // Redirect to the previous page if accessed directly without form submission
        header("Location: index.php");
        exit;
    }
} catch (Exception $e) {
    // Handle the exception here
    echo "An error occurred: " . $e->getMessage();
}
?>
