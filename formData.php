<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

$query = "SELECT a.vehicles_involved, v.vehicle_id
          FROM accident_table a
          JOIN vehicle_table v ON a.vehicles_involved = v.vehicle_name";

$result = mysqli_query($db, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $vehiclesInvolved = $row['vehicles_involved'];
        $vehicleId = $row['vehicle_id'];
        
        echo "For vehicles involved: $vehiclesInvolved, the corresponding vehicle ID is: $vehicleId<br>";
    }
} else {
    echo "Error fetching data: " . mysqli_error($db);
}

// Rest of your code...
?>