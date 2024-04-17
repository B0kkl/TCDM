<?php include 'server.php'; ?>
<?php
// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit(); // Make sure to exit after a header redirect
} else {
    // Check the user's role
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

    // Logout functionality
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        unset($_SESSION['role']); // Also unset the role
        header("location: login.php");
        exit();
    }
}
?>

<?php
// Fetch data from the actual database
$query = "SELECT accident_id, accident_date FROM accident_table";
$result = mysqli_query($db, $query);

// Check if the query was successful
if (!$result) {
    echo "Error: " . mysqli_error($db);
} else {
    // Fetch data and store it in an array
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Convert PHP array to JSON for JavaScript consumption
    $data_json = json_encode($data);
}

// Close the database connection
mysqli_close($db);
?>
 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Traffic Crime Data Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
  body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            width: 100%;
        }

        .container {
            display: grid;
            height: 100vh;
            width: 100%;
            grid-template-columns: repeat(4, auto);
            grid-template-rows: 0.1fr 0.5fr 2.2fr 3.2fr;
            grid-template-areas:
                "nav nav nav nav"
                "main main main main"
                "sidebar content1 content1 content3"
                "footer footer footer footer";
            gap: 1rem;
        }

        nav {
            grid-area: nav;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-align: center;
            padding: 10px;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        main {
            grid-area: main;
            padding: 0px;
        }

        #sidebar {
            grid-area: sidebar;
            background: #2c3e50;
            color: white;
            padding: 20px;
            font-size: 18px;
            text-align: center;
            background-image: url('file:///C:/Users/user/Downloads/ml.jpg');
            border-radius: 5px;
            margin: 0;
            width: 150px;
        }
        #content1 {
    width: calc(100% - 150px);
    padding: 30px;
    margin: -50px;
    padding-left: 100px;
}

#content2,
#content3 {
    position: relative;
    width: calc(150px * 0.05); 
    background-color: #fff352;
}

        /* from */
        .content-item {
        background: #ecf0f1;
        color: #2c3e50;
        padding: 15px;
        border-radius: 5px;
        margin-top: 10px;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    .content-item i {
        font-size: 30px;
        margin-right: 20px;
    }

    .content-item p {
        margin: 0;
        font-size: 16px;
    }

    footer {
    grid-area: footer;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-areas: "copy by";
    gap: 1rem;
    padding: 15px;
    background-color: #34495e;
    color: white;
    border-radius: 5px;
    width: 100%;
    position: fixed;
    bottom: 0; /* Stick to the bottom of the viewport */
}

    p {
        margin: 0;
        font-size: 14px;
    }

    #by {
        text-align: right;
    }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 36px;
            margin-top: 20px;
        }

        .offenceh2 {
            font-size: 24pt;
            color: #34495e;
            text-align: left;
        }

        .menu-item,
        .menu-ite {
            background: #3498db;
            color: white;
            padding: 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            margin-bottom: 10px;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
        }

        .menu-item:hover,
        .menu-ite:hover {
            background: #fff352;
        }

        .menu-item i,
        .menu-ite i {
            margin-right: 10px;
            font-size: 24px;
        }
        /*form table*/
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .data-table th, .data-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ecf0f1;
    }
    .search-container {
        display: flex;
      justify-content: space-between;
      align-items: center;
       padding: 5px; 
        width: 50%;
        border: 2px;;
    }

.search-container label {
  font-weight: bold;
  margin-right: 10px; 
}

.search-container input[type="text"] {
  padding: 5px;
  width: 100%;
  border-style: dashed;
}
 
#create-btn {
        position: relative;
        padding: 10px;
        margin: 5px;
       background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer; 
        top: 50%;
        margin-left: 80%;
    }
        /*overlay */
        .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        align-items: center;
        justify-content: center;
        z-index: 1;
        /* overflow: auto; */
    }

    .form-container {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        height: 97%;
        width: 100%;
        margin: 29px;
    }
    form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
    box-sizing: border-box;
}

h2 {
    text-align:center;
    color: #333333;
    grid-column: span 4;
    margin-bottom: 20px;
}

.input-group,
.input-group2 {
    display: flex;
    flex-direction: column;
}

.input-group label,
.input-group2 label {
    margin-bottom: 5px;
    color: #555555;
}

.input-group input,
.input-group select,
.input-group2 input,
.input-group2 select {
    padding: 10px;
    box-sizing: border-box;
    margin-bottom: 10px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    width: 100%; /* Full width */
}

.button2 {
    background-color: #3498db;
    color: #ffffff;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    box-sizing: border-box;
    align-items: center;
 width: 100%;
}

.input-group2 {
    margin-bottom: 15px;
}

/* Additional styling for labels and form controls */
label {
    display: block;
    margin-bottom: 8px;
    color: #555555;
}

input,
select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    margin-bottom: 10px;
    border: 1px solid #cccccc;
    border-radius: 4px;
}
    #close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
   select, input{
        width: auto;
    }
    .data-table{
        overflow: auto;
    }
    .offenceh2 {
    font-size: 28px;
    color: #3498db; /* Header color */
    text-align: center; /* Center the text */
    margin-bottom: 20px; /* Add some space at the bottom */
    border-bottom: 2px solid #3498db; /* Add a bottom border */
    padding-bottom: 5px; /* Adjust the padding at the bottom */
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
}

.data-table th,
.data-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
}

.data-table thead {
    background-color: #3498db;
    color: white;
}

.data-table tbody tr:hover {
    background-color: #f5f5f5;
}

.data-table tbody td {
    color: #333;
}

.data-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}
@media only screen and (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                grid-template-areas:
                    "nav"
                    "main"
                    "sidebar"
                    "content1"
                    "content2"
                    "content3"
                    "footer";
            }

            #sidebar {
                width: auto;
                margin-bottom: 20px;
            }
            .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 70px; /* Add margin bottom to create space between the table and footer */
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
        overflow-x: auto; /* Add overflow property to handle excess content horizontally */
        padding: 0 20px;
    }
        }
 </style>
</head>

<body>
    <div class="container">
        <nav>
            
                <h1>Online Traffic Crime Data Management System - PHP</h1>
            
        </nav>
        <main></main>
        <div id="sidebar">
        <h2 class="fas fa-traffic-light black-icon"> T.C.D.M--SYSTEM</h2>
            <div class="menu-item"onclick="window.location.href='TCDMReport.php'"><i class="fas fa-chart-bar black-icon"></i> Reports</div>
            <div class="menu-item" onclick="window.location.href='TCDMOffence.php'"><i class="fas fa-file-alt black-icon"></i> Offence Records</div>
            <div class="menu-item"onclick="window.location.href='TCDMReceipt.php'"><i class="fas fa-receipt black-icon"></i> Receipt</div>
            <div class="menu-item"onclick="window.location.href='TCDMDutyRooster.php'"><i class="fas fa-calendar black-icon"></i> Duty Roster</div>      
       <?php
if ($role == 'admin') {
    echo '<div class="menu-item" onclick="window.location.href=\'register.php\'"><i class="fas fa-calendar black-icon"></i> Register Officer</div>';
}
?>
            <div class="menu-item" ><a href="index.php?logout='1'"><i class="fas fa-cogs black-icon"></i> LogOut</a></div>
        </div>

<div id="content1">
         <h2 class="offenceh2">Report</h2>
   
<style>
 h1 {
        text-align: center;
    }
    .chart-container {
        margin-top: 20px;
    }
    .chart {
        border: 1px solid #ccc;
        border-radius: 5px;
        overflow: hidden;
    }
    .chart-header {
        background-color: #f0f0f0;
        padding: 10px;
    }
    .chart-title {
        margin: 0;
        font-size: 18px;
    }
    .chart-body {
        padding: 20px;
    }
    .chart-item {
        margin-bottom: 10px;
    }
    .chart-label {
        display: inline-block;
        width: 150px;
    }
    .chart-bar {
        display: inline-block;
        width: calc(100% - 150px);
        background-color: #007bff;
        color: #fff;
        padding: 5px;
        border-radius: 3px;
    }
</style>
</head>
<body>
        <div class="chart-container">
            <div class="chart">
                <div class="chart-header">
                    <h2 class="chart-title">Distribution of Accident Types</h2>
                </div>
                <div class="chart-body">
                    <?php
                        // Connect to the database
                        $db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');
                        
                        // Check connection
                        if (!$db) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Query database to fetch accident type distribution data
                        $sql = "SELECT accident_type, COUNT(*) as count FROM accident_table GROUP BY accident_type";
                        $result = mysqli_query($db, $sql);

                        // Display data
                        if (mysqli_num_rows($result) > 0) {
                            $totalAccidents = getTotalAccidents($db);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="chart-item">
                                        <span class="chart-label">' . $row['accident_type'] . ':</span>
                                        <div class="chart-bar" style="width: ' . ($row['count'] / $totalAccidents * 100) . '%;">' . round($row['count'] / $totalAccidents * 100, 2) . '%</div>
                                    </div>';
                            }
                        } else {
                            echo "0 results";
                        }

                        // Close database connection
                        mysqli_close($db);

                        // Function to get total number of accidents
                        function getTotalAccidents($connection) {
                            $sql = "SELECT COUNT(*) as total FROM accident_table";
                            $result = mysqli_query($connection, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                return $row['total'];
                            } else {
                                return 0;
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
            </div>
     </div>                
         <footer>
                <p>&copy; 2023 All Rights Reserved</p>
                <p id="by">OTCDM-PHP by: (Chrissy & Hussein) v1.0</p>
            </footer>
        </div>  
</body>

</html>