<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');
$errors = array();

// Check connection
if (!$db) {
    array_push($errors, "Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['delete_id'])) {
    $deleteId = mysqli_real_escape_string($db, $_POST['delete_id']);

    // Delete the record from the accident_table
    $deleteRoosterQuery = "DELETE FROM dutyroster 
                            WHERE officer_id = '$deleteId'";
    $deleteRoosterResult = mysqli_query($db, $deleteRoosterQuery);

}
// Fetch data 
$query = "SELECT  a.date, a.time, a.task, a.duration, a.location, a.officer_id
          FROM dutyroster a ";

$result = mysqli_query($db, $query);

// Check if the query was successful
if (!$result) {
    // Handle the case where the query was not successful
    array_push($errors, "Error: " . mysqli_error($db));
} else {
    // Fetch data and display in the HTML table
    echo '<tbody>';

    // Loop through the result set
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['date'] . '</td>
                <td>' . $row['time'] . '</td>
                <td>' . $row['task'] . '</td>
                <td>' . $row['duration'] . '</td>
                <td>' . $row['location'] . '</td>
                <td>' . $row['officer_id'] . '</td>';
                
                if ($role == 'admin') {
                    echo '<td>
                            <div class="edit-container">
                                <a href="editRooster.php?officer_id=' . $row['officer_id'] . '" style="color: blue;">Edit</a>
                            </div>
                          </td>';
             
                echo '<td>
                <form method="post" action="">
                    <input type="hidden" name="delete_id" value="' . $row['officer_id'] . '">
                    <button type="submit" id="delBtn" style="color: red;">Delete</button>
                </form>
              </td>
              </tr>';   
            }      
    }
}

// Display errors
if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<p>Error: $error</p>";
    }
}

// Close the database connection
mysqli_close($db);
?>
