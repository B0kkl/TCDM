<?php

// Assuming you have a database connection
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the 'search' parameter is set
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];

    // Perform the search query (customize based on your table structure)
    $query = "SELECT name, driving_licence, offender_address FROM offender_table WHERE name LIKE '%$searchTerm%'";
    $result = mysqli_query($db, $query);

    // Display the results in the search modal
    while ($row = mysqli_fetch_assoc($result)) {   
        echo "<tr>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['driving_licence']}</td>";
        echo "<td>{$row['offender_address']}</td>";
        echo "</tr>";
    }
}

mysqli_close($db);

?>