<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');
$errors = array();

// Check connection
if (!$db) {
    array_push($errors, "Connection failed: " . mysqli_connect_error());
}

// Delete record if delete_id is set
if (isset($_POST['delete_id'])) {
    $deleteId = mysqli_real_escape_string($db, $_POST['delete_id']);

    // Delete the record from the receipt_table
    $deleteReceiptQuery = "DELETE FROM receipt_table 
                            WHERE gr_number = '$deleteId'";
    $deleteReceiptResult = mysqli_query($db, $deleteReceiptQuery);

    // Check if the deletion was successful
    if (!$deleteReceiptResult) {
        array_push($errors, "Error deleting record: " . mysqli_error($db));
         }else{     // Redirect after successful deletion
    exit();
    }
}

// Fetch data 
$query = "SELECT  date , gr_number , fine, station_id, offender_id
          FROM receipt_table";

$result = mysqli_query($db, $query);

// Check if the query was successful
if ($result) {
    // Fetch data and display in the HTML table
    echo '<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>General Receipt N0</th>
        <th>Fine</th>
        <th>Station ID</th>
        <th>Offender Name</th> 
        <th>Update</th>
        <th>Remove</th>  
        <th>Download</th>
    </tr>
    </thead>
    <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
        <td>' . (isset($row['date']) ? $row['date'] : '') . '</td>
        <td>' . (isset($row['gr_number']) ? $row['gr_number'] : '') . '</td>
        <td>' . (isset($row['fine']) ? $row['fine'] : '') . '</td>
        <td>' . (isset($row['station_id']) ? $row['station_id'] : '') . '</td>
        <td>' . (isset($row['offender_id']) ? $row['offender_id'] : '') . '</td>
        <td>
            <div class="edit-container">
                <a href="editReceipt.php?gr_number=' . $row['gr_number'] .  '" style="color: blue;">Edit</a>
        </td>
        <td>
            <form method="post" action="">
                <input type="hidden" name="delete_id" value="' . $row['gr_number'] . '">
                <button type="submit" id="delBtn" style="color: red;">Delete</button>
            </form>
        </td>
        <td>
            <form method="post" action="generat_pdf.php">
                <input type="hidden" name="gr_number" value="' . $row['gr_number'] . '">
                <button type="submit" name="download" id="downloadBtn"><i class="fas fa-download"></i> 
                Download PDF
            </button>
            </form>
        </td></tr>';
    }
    echo '</tbody></table>';
} else {
    array_push($errors, "Error fetching records: " . mysqli_error($db));
}

if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo "<p>Error: $error</p>";
    }
}
// Close the database connection
mysqli_close($db);
?>
