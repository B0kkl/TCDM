<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');
foreach ($_POST['accident_id'] as $key => $accident_id) {
    $accident_date = $_POST['accident_date'][$key];
    $obstraction_hit = $_POST['obstraction_hit'][$key];
    $defects = $_POST['defects'][$key];
    $vehicles_involved = $_POST['vehicles_involved'][$key];
    $vehicle_name = $_POST['vehicle_name'][$key];
    $driving_licence = $_POST['driving_licence'][$key];
    $alcohol_test = $_POST['alcohol_test'][$key];

    // Update values in accident_table
    $sqlAccident = "UPDATE accident_table SET
                    accident_date = '$accident_date',
                    obstraction_hit = '$obstraction_hit'
                    WHERE accident_id = $accident_id";

    if ($db->query($sqlAccident) === FALSE) {
        echo "Error updating accident_table: " . $db->error;
    }

    // Update values in vehicle_table
    $sqlVehicle = "UPDATE vehicle_table SET
                    defects = '$defects',
                    vehicles_involved = '$vehicles_involved',
                    vehicle_name = '$vehicle_name'
                    WHERE vehicle_id = (SELECT vehicle_id FROM accident_table WHERE accident_id = $accident_id)";

    if ($db->query($sqlVehicle) === FALSE) {
        echo "Error updating vehicle_table: " . $db->error;
    }

    // Update values in offender_table
    $sqlOffender = "UPDATE offender_table SET
                    driving_licence = '$driving_licence',
                    alcohol_test = '$alcohol_test'
                    WHERE vehicle_id = (SELECT vehicle_id FROM accident_table WHERE accident_id = $accident_id)";

    if ($db->query($sqlOffender) === FALSE) {
        echo "Error updating offender_table: " . $db->error;
    }
}
mysqli_close($db);
?>
