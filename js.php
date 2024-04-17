<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accident Type Distribution Report</title>
<style>
   body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
    }
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
    <div class="container">
        <h1>Accident Type Distribution</h1>
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
</body>
</html>
