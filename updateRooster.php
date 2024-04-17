<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $officer_id = mysqli_real_escape_string($db, $_POST['officer_id']);
    $date = mysqli_real_escape_string($db, $_POST['date']);
    $time = mysqli_real_escape_string($db, $_POST['time']);
    $task = mysqli_real_escape_string($db, $_POST['task']);
    $duration = mysqli_real_escape_string($db, $_POST['duration']);
    $location = mysqli_real_escape_string($db, $_POST['location']);

    // Update data in accident_table
    $updateRoosterQuery = "UPDATE dutyroster
                            SET date = '$date',
                                time = '$time',
                                task='$task',
                                duration='$duration',
                                location='$location'
                                WHERE officer_id = '$officer_id'";

    $resultAccident = mysqli_query($db, $updateRoosterQuery);

    // Check if all updates were successful
    if ($resultAccident) {
        echo "Data updated successfully!";
    } else {
        echo "Error updating data: " . mysqli_error($db);
    }
}

// Close the database connection
mysqli_close($db);
?>
