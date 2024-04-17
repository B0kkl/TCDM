<?php
// Establish connection to the database
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "project"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$date = $_POST['date'];
$name = $_POST['name'];
$offender = $_POST['offender'];
$vehicle = $_POST['vehicle'];
$accident_type = $_POST['accident_type'];
$officer_name = $_POST['officer_name'];
$age = $_POST['age'];
$email = $_POST['email'];
$district = $_POST['district'];
$station_name = $_POST['station_name'];

// Insert into 'datatable' table
$sql_datatable = "INSERT INTO datatable (date, name, offender, vehicle, accident)
                 VALUES ('$date', '$name', '$offender', '$vehicle', '$accident_type')";

// Insert into 'officers' table
$sql_officers = "INSERT INTO officers (officerName, age, email, District_Id)
                 VALUES ('$officer_name', '$age', '$email', (SELECT district_id FROM district WHERE name = '$district'))";

// Insert into 'station' table
$sql_station = "INSERT INTO station (name, district_id)
                VALUES ('$station_name', (SELECT district_id FROM district WHERE name = '$district'))";

// Execute queries
if ($conn->query($sql_datatable) === TRUE && $conn->query($sql_officers) === TRUE && $conn->query($sql_station) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>