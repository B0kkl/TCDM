<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $accident_id = mysqli_real_escape_string($db, $_POST['accident_id']);
    $accident_date = mysqli_real_escape_string($db, $_POST['accident_date']);
    $obstraction_hit = mysqli_real_escape_string($db, $_POST['obstraction_hit']);
    $defects = mysqli_real_escape_string($db, $_POST['defects']);
    $vehicles_involved = mysqli_real_escape_string($db, $_POST['vehicles_involved']);
    $vehicle_name = mysqli_real_escape_string($db, $_POST['vehicle_name']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $driving_licence = mysqli_real_escape_string($db, $_POST['driving_licence']);
    $alcohol_test = mysqli_real_escape_string($db, $_POST['alcohol_test']);
    $username = mysqli_real_escape_string($db, $_POST['username']);

    // Update data in accident_table
    $updateAccidentQuery = "UPDATE accident_table
                            SET accident_date = '$accident_date',
                                obstraction_hit = '$obstraction_hit'
                            WHERE accident_id = '$accident_id'";

    $resultAccident = mysqli_query($db, $updateAccidentQuery);

    // Update data in vehicle_table
   $updateVehicleQuery = "UPDATE vehicle_table
                        SET defects = '$defects',
                            vehicles_involved = '$vehicles_involved',
                            vehicle_name = '$vehicle_name'
                        WHERE vehicle_id = (SELECT vehicle_id FROM accident_table WHERE accident_id = '$accident_id')";


    $resultVehicle = mysqli_query($db, $updateVehicleQuery);

    // Update data in offender_table
    $updateOffenderQuery = "UPDATE offender_table
                            SET name = '$name',
                                driving_licence = '$driving_licence',
                                alcohol_test = '$alcohol_test'
                            WHERE vehicle_id IN (SELECT vehicle_id FROM accident_table WHERE accident_id = '$accident_id')";

    $resultOffender = mysqli_query($db, $updateOffenderQuery);

    // Update data in officer_table
    $updateOfficerQuery = "UPDATE officer_table
                            SET username = '$username'
                            WHERE officer_id IN (SELECT officer_id FROM accident_table WHERE accident_id = '$accident_id')";

    $resultOfficer = mysqli_query($db, $updateOfficerQuery);

    // Check if all updates were successful
    if ($resultAccident && $resultVehicle && $resultOffender && $resultOfficer) {
      header('location: TCDMOffence.php');
    } else {
        echo "Error updating data: " . mysqli_error($db);
    }
}

// Close the database connection
mysqli_close($db);
?>
