<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form 	receipt_id
    $gr_number = mysqli_real_escape_string($db, $_POST['gr_number']);
    $date = mysqli_real_escape_string($db, $_POST['date']);
    $fine = mysqli_real_escape_string($db, $_POST['fine']);

    // Update data in accident_table
    $updateGRQuery = "UPDATE receipt_table
                            SET date = '$date',
                                fine = '$fine',
                                gr_number='$gr_number'
                                WHERE gr_number ='$gr_number'";
    $resultGR = mysqli_query($db, $updateGRQuery);

    // Check if all updates were successful
    if ($resultGR) {
        header('location: TCDMReceipt.php');
    } else {
        echo "Error updating data: " . mysqli_error($db);
    }
}

// Close the database connection
mysqli_close($db);
?>
