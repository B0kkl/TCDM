<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the accident_id from the URL parameter
    $officer_id = mysqli_real_escape_string($db, $_GET['officer_id']);

    // Fetch the data for the specific accident_id
    $query = "SELECT  a.date, a.time, a.task, a.duration, a.location, a.officer_id
    FROM dutyroster a ";

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

    <form action="updateRooster.php" method="POST">
        <input type="hidden" name="officer_id" value="<?php echo $officer_id; ?>">

        <label for="date">Date:</label>
        <input type="text" name="date" value="<?php echo $row['date']; ?>"><br>

        <label for="obstraction_hit"> Time:</label>
        <input type="text" name="time" value="<?php echo $row['time']; ?>"><br>

        <label for="defects">Task:</label>
        <input type="text" name="task" value="<?php echo $row['task']; ?>"><br>

        <label for="vehicles_involved">Duration :</label>
        <input type="text" name="duration" value="<?php echo $row['duration']; ?>"><br>

        <label for="vehicle_name">Location :</label>
        <input type="text" name="location" value="<?php echo $row['location']; ?>"><br>

        <input type="submit" value="Update">
    </form>

