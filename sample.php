<?php

$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

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
    // Display data in HTML form
    echo '<form action="update_data.php" method="post">'; // Assuming you have a separate PHP file for updating data
    echo '<table class="data-tabl" id="my-actual-table-id">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Accident ID</th>
                    <th>Driving Licence</th>
                    <th>Obstraction hit</th>
                    <th>Vehicle Defects</th>
                    <th>Vehicles Involved</th>
                    <th>Alcohol Test</th>
                    <th>Vehicle Registration</th>
                    <th>Offender Name</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td><input type="text" name="accident_date[]" value="' .$row['accident_date'] . '"></td>
                <td><input type="text" name="accident_id[]" value="' . $row['accident_id'] . '"></td>
                <td><input type="text" name="driving_licence[]" value="' . $row['driving_licence'] . '"></td>
                <td><input type="text" name="obstraction_hit[]" value="' . $row['obstraction_hit'] . '"></td>
                <td><input type="text" name="defects[]" value="' . $row['defects'] . '"></td>
                <td><input type="text" name="vehicles_involved[]" value="' . $row['vehicles_involved'] . '"></td>
                <td><input type="text" name="alcohol_test[]" value="' . $row['alcohol_test'] . '"></td>
                <td><input type="text" name="vehicle_name[]" value="' . $row['vehicle_name'] . '"></td>
                <td><input type="text" name="vehicle_name[]" value="' . $row['name'] . '"></td>
              </tr>';
    }

    echo '</tbody></table>';
    
    echo '<input type="submit" value="Update Data">';
    echo '</form>';
}

// Close the database connection
mysqli_close($db);
?>
