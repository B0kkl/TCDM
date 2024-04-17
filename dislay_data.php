<?php
// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

// Check if the delete button is clicked
if (isset($_POST['delete_id']) && isset($_POST['delete_vehicle_id'])) {
    $deleteId = mysqli_real_escape_string($db, $_POST['delete_id']);
    $deleteVehicleInvolved = mysqli_real_escape_string($db, $_POST['delete_vehicle_id']);
    
    // Retrieve the vehicle_id corresponding to the vehicles_involved value
    $vehicleIdQuery = "SELECT vehicle_id FROM vehicle_table WHERE vehicles_involved = '$deleteVehicleInvolved'";
    $vehicleIdResult = mysqli_query($db, $vehicleIdQuery);
    
    if (!$vehicleIdResult) {
        // Handle the case where the query for retrieving vehicle_id was not successful
        echo "Error retrieving vehicle_id: " . mysqli_error($db);
    } else {
        // Fetch the vehicle_id
        $row = mysqli_fetch_assoc($vehicleIdResult);
        $deleteVehicleId = $row['vehicle_id'];

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
                               WHERE vehicle_id = '$deleteVehicleId'";
        $deleteVehicleResult = mysqli_query($db, $deleteVehicleQuery);

        if (!$deleteVehicleResult) {
            // Handle the case where the delete query for vehicle_table was not successful
            echo "Error deleting record from vehicle_table: " . mysqli_error($db);
        }
    }
}


// Fetch data 
$query = "SELECT a.accident_date, a.accident_id, a.obstraction_hit,
                 v.defects, v.vehicles_involved, v.vehicle_name,
                 o.name, o.driving_licence, o.alcohol_test,o.offender_id,
                 of.username
          FROM accident_table a
          JOIN vehicle_table v ON a.vehicle_id = v.vehicle_id
          JOIN offender_table o ON a.vehicle_id = o.vehicle_id
          LEFT JOIN officer_table of ON a.officer_id = of.officer_id";


$result = mysqli_query($db, $query);


// Check if the query was successful
if (!$result) {
    // Handle the case where the query was not successful
    array_push($errors, "Error: " . mysqli_error($db));
} else {
    // Fetch data and display in the HTML table
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['accident_date'] . '</td>
                <td>' . $row['accident_id'] . '</td>
                <td>' . $row['driving_licence'] . '</td>
                <td>' . $row['obstraction_hit'] . '</td>
                <td>' . $row['defects'] . '</td>
                <td>' . $row['vehicles_involved'] . '</td>
                <td>' . $row['alcohol_test'] . '</td>
                <td>' . $row['vehicle_name'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['offender_id'] . '</td>
                <td>' . $row['username'] . '</td>
                <td>
                    <div class="edit-container">
                        <a href="send.php?accident_id=' . $row['accident_id'] . '" style="color: green;">Send</a>
                    </div>
                </td>
                <td>
                    <div class="edit-container">
                        <a href="edit.php?accident_id=' . $row['accident_id'] . '" style="color: blue;">Edit</a>
                    </div>
                </td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="delete_id" value="' . $row['accident_id'] . '">
                        <input type="hidden" name="delete_vehicle_id" value="' . $row['vehicles_involved'] . '">
                        <button type="submit" id="delBtn" style="color: red;">Delete</button>
                    </form>
                </td>
                <td>
                    <form method="post" action="generate_pdf.php">
                        <input type="hidden" name="accident_id" value="' . $row['accident_id'] . '">
                        <button type="submit" name="download" id="downloadBtn">
                        <i class="fas fa-download"></i> 
                            Download PDF
                        </button>
                    </form>
                </td>
            </tr>';
    }
    
    echo '</tbody>';
}

// Close the database connection
mysqli_close($db);

// Display errors
if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<p>Error: $error</p>";
    }
}
?>
