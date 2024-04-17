<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

// Check if the delete button is clicked
if (isset($_POST['delete_id']) && isset($_POST['delete_vehicle_id'])) {
    $deleteId = mysqli_real_escape_string($db, $_POST['delete_id']);
    $deleteVehicleId = mysqli_real_escape_string($db, $_POST['delete_vehicle_id']);

    // Delete the record from the accident_table
    $deleteAccidentQuery = "DELETE FROM accident_table 
                            WHERE accident_id = '$deleteId' AND vehicle_id = '$deleteVehicleId'";
    $deleteAccidentResult = mysqli_query($db, $deleteAccidentQuery);

    if (!$deleteAccidentResult) {
        // Handle the case where the delete query for accident_table was not successful
        echo "Error deleting record from accident_table: " . mysqli_error($db);
    }

    // Delete the record from the vehicle_table
    $deleteVehicleQuery = "DELETE FROM vehicle_table 
                           WHERE vehicles_involved = '$deleteVehicleId'";
    $deleteVehicleResult = mysqli_query($db, $deleteVehicleQuery);

    if (!$deleteVehicleResult) {
        // Handle the case where the delete query for vehicle_table was not successful
        echo "Error deleting record from vehicle_table: " . mysqli_error($db);
    }
}

// Fetch data 
$query = "SELECT a.accident_date, a.accident_id, a.obstraction_hit,
                 v.defects, v.vehicles_involved, v.vehicle_name,
                 o.name, o.driving_licence, o.alcohol_test,
                 of.username
          FROM accident_table a
          JOIN vehicle_table v ON a.vehicle_id = v.vehicle_id
          JOIN offender_table o ON a.vehicle_id = o.vehicle_id
          LEFT JOIN officer_table of ON a.officer_id = of.officer_id";

$result = mysqli_query($db, $query);

// Check if the query was successful
if (!$result) {
    // Handle the case where the query was not successful
    echo "Error: " . mysqli_error($db);
} else {
    // Fetch data and display in the HTML table
    echo '<tbody>';

    // Loop through the result set
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['accident_date'] . '</td>
                <td>' . $row['accident_id'] . '</td>
                <td>' . $row['driving_licence'] . '</td>
                <td>' . $row['obstraction_hit'] . '</td>
                <td>' . $row['defects'] . '</td>
                <td>' . $row['vehicles_involved'] . '</td>
                <td> ' . $row['alcohol_test'] . '</td>
                <td>' . $row['vehicle_name'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['username'] . '</td>
                <td><a href="sample.php?accident_id=' . $row['accident_id'] . '">Edit</a></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="delete_id" value="' . $row['accident_id'] . '">
                        <input type="hidden" name="delete_vehicle_id" value="' . $row['vehicles_involved'] . '">
                        <button type="submit" id="delBtn" >Delete</button>
                    </form>
                </td>
              </tr>';
    }

    echo '</tbody>';
}

// Close the database connection
mysqli_close($db);
?>
