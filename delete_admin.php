<?php
// Include your database connection file
require_once('cann2.php');
require_once('envary.php');

// Check if adminId is set and not empty
if(isset($_POST['adminId']) && !empty($_POST['adminId'])) {
    // Retrieve adminId from the POST request
    $adminId = $_POST['adminId'];

    try {
        // Prepare SQL query to delete admin record
        $query = "DELETE FROM [Bus_Booking].[dbo].[admin] WHERE ID = ?";
        $params = array($adminId);

        // Execute SQL query
        $stmt = sqlsrv_query($conn, $query, $params);

        // Check if the query was successful
        if($stmt !== false) {
            // Send a success response
            http_response_code(200);
            echo "Admin deleted successfully!";
        } else {
            // Send an error response
            http_response_code(500);
            echo "Error deleting admin: " . sqlsrv_errors()[0]['message'];
        }
    } catch (Exception $e) {
        // Send an error response
        http_response_code(500);
        echo "An error occurred: " . $e->getMessage();
    }
} else {
    // Send an error response if adminId is not provided
    http_response_code(400);
    echo "Admin ID is missing.";
}
?>
