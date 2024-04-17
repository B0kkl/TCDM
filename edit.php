<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the accident_id from the URL parameter
    $accident_id = mysqli_real_escape_string($db, $_GET['accident_id']);

    // Fetch the data for the specific accident_id
    $query = "SELECT a.accident_date, a.obstraction_hit,
                     v.defects, v.vehicles_involved, v.vehicle_name,
                     o.name, o.driving_licence, o.alcohol_test,
                     of.username
              FROM accident_table a
              JOIN vehicle_table v ON a.vehicle_id = v.vehicle_id
              JOIN offender_table o ON a.vehicle_id = o.vehicle_id
              LEFT JOIN officer_table of ON a.officer_id = of.officer_id
              WHERE a.accident_id = '$accident_id'";

    $result = mysqli_query($db, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($db);
    } else {
        $row = mysqli_fetch_assoc($result);
    }
}

// Close the database connection
mysqli_close($db);
?>
<style >/* Form container */
.form-container {
    max-width: 500px;
    margin: 0 auto;
}

/* Form groups */
.input-group,
.input-group2 {
    margin-bottom: 20px;
}

/* Labels */
label {
    display: block;
    margin-bottom: 5px;
}

/* Inputs */
input[type="text"],
input[type="date"],
input[type="time"],
select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Submit button */
.button2 {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.button2:hover {
    background-color: #45a049;
}
/* Form container */
.form-container {
    max-width: 500px;
    margin: 0 auto;
}

/* Form groups */
.input-group,
.input-group2 {
    margin-bottom: 20px;
}

/* Labels */
label {
    display: block;
    margin-bottom: 5px;
}

/* Inputs */
input[type="text"],
input[type="date"],
input[type="time"],
input[type="submit"],
select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Submit button */
.button2 {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.button2:hover {
    background-color: #45a049;
}
</style>
    <h2>Edit Accident Data</h2>

    <form action="update.php" method="POST">
        <input type="hidden" name="accident_id" value="<?php echo $accident_id; ?>">

        <label for="accident_date">Accident Date:</label>
        <input type="text" name="accident_date" value="<?php echo $row['accident_date']; ?>"><br>

        <label for="obstraction_hit">Obstraction Hit:</label>
        <input type="text" name="obstraction_hit" value="<?php echo $row['obstraction_hit']; ?>"><br>

        <label for="defects">Defects:</label>
        <input type="text" name="defects" value="<?php echo $row['defects']; ?>"><br>

        <label for="vehicles_involved">Vehicles Involved:</label>
        <input type="text" name="vehicles_involved" value="<?php echo $row['vehicles_involved']; ?>"><br>

        <label for="vehicle_name">Vehicle Name:</label>
        <input type="text" name="vehicle_name" value="<?php echo $row['vehicle_name']; ?>"><br>

        <label for="name">Driver's Name:</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>

        <label for="driving_licence">Driving Licence:</label>
        <input type="text" name="driving_licence" value="<?php echo $row['driving_licence']; ?>"><br>

        <label for="alcohol_test">Alcohol Test:</label>
        <input type="text" name="alcohol_test" value="<?php echo $row['alcohol_test']; ?>"><br>

        <label for="username">Officer's Username:</label>
        <input type="text" name="username" value="<?php echo $row['username']; ?>"><br>

        <input type="submit" value="Update">
    </form>

