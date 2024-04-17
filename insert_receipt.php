<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Function to insert data into receipt_table
function insertReceiptRoster($db, $date, $gr_number, $fine, $officerId, $accident_id, $stationId, $offenderId, &$errors) {
    try {
        // Check if the officer_id exists in the officer_table
        $checkQuery = "SELECT officer_id FROM officer_table WHERE officer_id = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "i", $officerId);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // If the officer_id doesn't exist, add an error to the array
        if (mysqli_stmt_num_rows($checkStmt) == 0) {
            $errors[] = "Error: Officer ID does not exist.";
            mysqli_stmt_close($checkStmt);
            return;
        }

        // Check if the accident_id exists in the accident_table
        $checkQuery = "SELECT accident_id FROM accident_table WHERE accident_id = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "i", $accident_id);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // If the accident_id doesn't exist, add an error to the array
        if (mysqli_stmt_num_rows($checkStmt) == 0) {
            $errors[] = "Error: Accident ID does not exist.";
            mysqli_stmt_close($checkStmt);
            return;
        }
        
        // Check if station_id exists
        $checkQuery = "SELECT station_id FROM police_table WHERE station_id = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "i", $stationId);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // If the station_id doesn't exist, add an error to the array
        if (mysqli_stmt_num_rows($checkStmt) == 0) {
            $errors[] = "Error: Station ID does not exist.";
            mysqli_stmt_close($checkStmt);
            return;
        }

        // Check if the offender_id exists in the offender_table
        $checkQuery = "SELECT offender_id FROM offender_table WHERE offender_id = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "i", $offenderId);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // If the offender_id doesn't exist, add an error to the array
        if (mysqli_stmt_num_rows($checkStmt) == 0) {
            $errors[] = "Error: Offender ID does not exist.";
            mysqli_stmt_close($checkStmt);
            return;
        }

        mysqli_stmt_close($checkStmt);

        // Proceed with the insertion into receipt_table
        $sql = "INSERT INTO receipt_table (date, gr_number, fine, officer_id, accident_id, station_id, offender_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ssiiiii", $date, $gr_number, $fine, $officerId, $accident_id, $stationId, $offenderId);
        mysqli_stmt_execute($stmt);

        // Check for insertion errors and add them to the array
        if (mysqli_stmt_errno($stmt)) {
            $errors[] = "Error inserting data into receipt_table: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } catch (mysqli_sql_exception $exception) {
        $errors[] = "MySQL Error: " . $exception->getMessage();
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $date = $_POST["date"];
    $officerId = $_POST["officer_id"];
    $accident_id = $_POST["accident_id"];
    $gr_number = $_POST["gr_number"];
    $fine = $_POST["fine"];
    $stationId = $_POST["station"];
    $offenderId = $_POST['offender_id'];

    $errors = array();
    // Insert data into receipt_table
    insertReceiptRoster($db, $date, $gr_number, $fine, $officerId, $accident_id, $stationId, $offenderId, $errors);

    // Check for errors
    if (!empty($errors)) {
        // Convert errors array to JSON for JavaScript usage
        $errors_json = json_encode($errors);
        echo "<script>
                var errors = $errors_json;
                alert(errors.join('\\n'));
              </script>";
    } else {

        exit();
    }
}

// Close the database connection
$db->close();
?>
