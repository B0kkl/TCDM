<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

$errors = array();

function insertDutyRoster($db, $date, $time, $task, $duration, $location, $officerId, &$errors) {
    // Check if the officer_id exists in the officer_table
    $checkQuery = "SELECT officer_id FROM officer_table WHERE officer_id = ?";
    $checkStmt = mysqli_prepare($db, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "i", $officerId);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    // If the officer_id doesn't exist, add an error to the array
    if (mysqli_stmt_num_rows($checkStmt) == 0) {
        array_push($errors, "Error: Officer ID does not exist.");
        return;
    }

    // Close the check statement
    mysqli_stmt_close($checkStmt);

    // Proceed with the insertion into dutyroster table
    $query = "INSERT INTO dutyroster (date, time, task, duration, location, officer_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $date, $time, $task, $duration, $location, $officerId);
    mysqli_stmt_execute($stmt);

    // Check for insertion errors and add them to the array
    if (mysqli_stmt_errno($stmt)) {
        array_push($errors, "Error inserting duty roster: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}

if (isset($_POST["submit"])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $task = $_POST['task'];
    $duration = $_POST['duration'];
    $location = $_POST['location'];
    $officerId = $_POST['officer_id'];

    insertDutyRoster($db, $date, $time, $task, $duration, $location, $officerId, $errors);

    // Additional processing or redirection can be added here if needed.
}

mysqli_close($db);
?>
