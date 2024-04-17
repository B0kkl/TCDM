<?php
$db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the accident_id from the URL parameter
    $accident_id = mysqli_real_escape_string($db, $_GET['accident_id']);

    // Fetch the data for the specific accident_id
    $query = "SELECT * FROM accident_table WHERE accident_id = '$accident_id'";

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <style>
        /* CSS for form */
body {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
}

/* Center the form */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

form div {
    margin-bottom: 15px;
    width: 100%;
}

label {
    display: block;
    font-weight: bold;
}

input[type="email"],
input[type="text"],
textarea {
    width: calc(100% - 30px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #2980b9;
}

    </style>
</head>
<body>
    <h2>Send Email</h2>
    <form method="post" action="send_process.php">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>
        </div>
        <div>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required>
                Accident Details:
                Accident Date: <?php echo $row['accident_date']; ?>
                Officer: <?php echo $row['officer_id']; ?>
                Accident Type: <?php echo $row['accident_type']; ?>
                Accident ID : <?php echo $row['accident_id']; ?>
                Accident Time: <?php echo $row['accident_time']; ?>
                Obstruction Hit: <?php echo $row['obstraction_hit']; ?>
                Vehicle_ID: <?php echo $row['vehicle_id']; ?>
                Weather: <?php echo $row['weather']; ?>
            </textarea>
        </div>
        <button type="submit">Send Email</button>
    </form>
</body>

</html>
