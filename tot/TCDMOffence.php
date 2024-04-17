<?php include 'server.php'; ?>
<?php error_reporting(E_ALL);
ini_set('display_errors', 1); ?>
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Traffic Crime Data Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="offence.css">
</head>

<body>
    <div class="container">
        <nav>
            
                <h1>Online Traffic Crime Data Management System - PHP</h1>
            
        </nav>
        <main></main>
        <div id="sidebar">
        <h2 class="fas fa-traffic-light black-icon"> T.C.D.M--SYSTEM</h2>
            <div class="menu-item" onclick="window.location.href='index.php'"><i class="fas fa-tachometer-alt black-icon"></i> Dashboard</div>
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
         <h2 class="offenceh2">OFFENCES</h2>
         <div id="button">
            <button id="create-btn">New Offence Record </button>
            </div> 
            <div class="search-container">
        <label for="search">Search:</label>
        <input type="text" id="search" placeholder="Search term name" onkeypress="handleSearch(event)">
    </div>
    

                   <div class="overlay" id="overlay-edit">
                    <div class="form-container1">
                        <span id="close-edit-btn">&times;</span>
     <!-- modal  for editbutton-->
        <h2>Edit Accident Details</h2>
        <!-- Edit form goes here -->

        <form id="editForm">
         
            <?php include 'sample.php'; ?>

    </form>
    
    </div>
</div>

 
                    <div class="data-table">
                    <table class="data-tabl" id="my-table-id">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Accident ID</th>
                                <th>Driving Licence</th>
                                <th>Obstraction hit</th>
                                <th>Vehicle Defects</th>
                                <th>Vehicles Involved</th>
                                <th>Alcohol Test</th>
                                <th>Vehicle Registration</th>
                                <th>Offinder Name</th>
                                <th>OfficerName</th>
                                <th>
                                    UpDAte
                                </th>
                                <th>
                                    Remove
                                </th>
                            </tr>
                        </thead>
                        <?php include 'dislay_data.php'; ?>
                    </table>
                </div>
                    <!-- <div id="button">
                    <button id="create-bt">Create New Table </button>
                    </div> -->
                <div class="overlay" id="overlay-create">
                    <div class="form-container">
                        <span id="close-create-btn">&times;</span>
                        <!-- Add your entry form elements here -->
                      
    <form action="TCDMOffence.php" method="post" id="add">
        <h2>Create Offence Record</h2>

        <!-- Accident Details -->
        <div class="input-group">
            <label>Accident Type</label>
            <select id="accidenttype" name="accidenttype" id="accidenttype" required="required">
                <option value="Fatal">Fatal</option>
                <option value="Serious Injury">Serious Injury</option>
                <option value="Minor Injury">Minor Injury</option>
                <option value="Damages Only">Damages Only</option>
                <option value="Animal Only">Animal Only</option>
            </select>
        </div>

        <div class="input-group">
            <label>Date</label>
            <input type="date" name="date"  id="date" required="required">
        </div>

        <div class="input-group">
            <label>Time</label>
            <input type="time" name="time" id="time" required="required">
        </div>

        <div class="input-group">
            <label>Weather</label>
            <select id="weather" name="weather" required="required">
                <option value="dry">Dry</option>
                <option value="wet">Rain/Wet</option>
                <option value="mist">Mist</option>
                <option value="windy">Windy</option>
                <option value="dusty">Dusty</option>
            </select>
        </div>

        <div class="input-group">
            <label>Obstruction Hit</label>
            <select id="hit" name="hit" required="required">
                <option value="Stationary vehicle">Stationary vehicle</option>
                <option value="Dropped cargo">Dropped cargo</option>
                <option value="Rocks, land slide">Rocks, land slide</option>
                <option value="Other obstruction">Other obstruction</option>
            </select>
        </div>

        <!-- Location Information -->
        <div class="input-group">
            <label>District</label>
            <input type="text" name="district"  id="district" required="required">
        </div>

        <div class="input-group">
            <label>Location</label>
            <input type="text" name="location"  id="location" required="required">
        </div>

        <div class="input-group">
            <label>Physical Feature</label>
            <input type="text" name="feature"  id="feature" required="required">
        </div>

        <!-- Offender Information -->
        <div class="input-group2">
            <label>Name of Offender</label>
            <input type="text" name="offender" id="offender" required="required">
        </div>

        <div class="input-group2">
            <label>Offender Address</label>
            <input type="text" name="address" id="address" required="required">
        </div>

        <div class="input-group2">
            <label>Driving Licence</label>
            <select id="licence" name="licence" required="required">
                <option value="Learner Driver">Learner Driver</option>
                <option value="Holder">Holder</option>
                <option value="Non-holder">Non-holder</option>
            </select>
        </div>

        <div class="input-group2">
            <label>Alcohol Test</label>
            <select id="test" name="test" required="required">
                <option value="Over legal limit">Over legal limit</option>
                <option value="No alcohol">No alcohol</option>
                <option value="Not Tested">Not Tested</option>
            </select>
        </div>

        <!-- Officer Details -->
        <div class="input-group2">
            <label>Station name</label>
            <select id="station" name="station" required="required">
                <option value="Soche-EastMW">Soche-EastMW</option>
                <option value="Soche-WestMW">Soche-WestMW</option>
                <option value="Soche-NorthMW">Soche-NorthMW</option>
                <option value="Soche-South">Soche-South</option>
            </select>
        </div>

        <!-- Road Conditions -->
        <div class="input-group2">
            <label>Road Conditions</label>
            <select id="condition" name="condition" required="required">
                <option value="Good / Fair">Good / Fair</option>
                <option value="Potholes">Potholes</option>
                <option value="Corrugated">Corrugated</option>
                <option value="Slippery">Slippery</option>
            </select>
        </div>

        <div class="input-group2">
            <label>Road Geometry</label>
            <select id="geometry" name="geometry">
                <option value="Straight road">Straight road</option>
                <option value="Curve">Curve</option>
                <option value="Roundabout">Roundabout</option>
                <option value="T junction">T junction</option>
                <option  value="Bridge">Bridge</option>
                <option value="N junction" >N junction</option>
                <option value="Rail crossing">Rail crossing</option>
                <option value="4junction">4 junction</option>
            </select>
        </div>

        <div class="input-group2">
            <label>Surface Type</label>
            <select id="surface" name="surface" id="surface" required="required">
                <option value="Gravel">Gravel</option>
                <option value="Earth">Earth</option>
                <option value="Bitumen">Bitumen</option>
            </select>
        </div>

        <!-- Vehicle Information -->
        <div class="input-group2">
            <label>Vehicle Name</label>
            <input type="text" name="vname" id="vname" required="required" >
        </div>

        <div class="input-group2">
            <label>Vehicle Defects</label>
            <select id="defects" name="defects" require="required">
                <option value="not known" >Not known</option>
                <option value="steering" >Steering</option>
                <option value="brakes" >Brakes</option>
                <option value="wheels">Wheels & Tyres</option>
                <option value="head lights">Headlights</option>
                <option value="brake lights">Brake lights, Indicators</option>
                <option value="windscreen">Windscreen</option>
                <option value="overloawded">Overloaded</option>
            </select>
        </div>

        <div class="input-group2">
            <label>Vehicles Involved</label>
            <input type="text" name="vinvolved" id="vinvolved" required="required">
        </div>

        <!-- Add more form elements as needed -->

        <button class="button2"   id="submit" name="submit"  type="submit" value="save" >Create</button>
    </form>
           



            </div>
     </div>                
</div>  
<?php include 'insert_offence.php';?>

         <footer>
                <p>&copy; 2023 All Rights Reserved</p>
                <p id="by">OTCDM-PHP by: (Chrissy & Hussein) v1.0</p>
            </footer>
        </div>   
<script>
    // Function to toggle overlay display
    function toggleOverlay(overlayId) {
        var overlay = document.getElementById(overlayId);
        var displayValue = overlay.style.display === 'flex' ? 'none' : 'flex';
        overlay.style.display = displayValue;
    }

    // Create button click event for opening the create overlay
    document.getElementById('create-btn').addEventListener('click', function () {
        toggleOverlay('overlay-create');
    });

    // Edit button click event for opening the edit overlay
    document.getElementById('editBtn').addEventListener('click', function () {
        toggleOverlay('overlay-edit');
    });

    // Close overlay on close button click (create overlay)
    document.getElementById('close-create-btn').addEventListener('click', function () {
        toggleOverlay('overlay-create');
    });

    // Close overlay on close button click (edit overlay)
    document.getElementById('close-edit-btn').addEventListener('click', function () {
        toggleOverlay('overlay-edit');
    });

    // Close overlay on click outside the form (create overlay)
    window.addEventListener('click', function (event) {
        var overlay = document.getElementById('overlay-create');
        if (event.target === overlay) {
            toggleOverlay('overlay-create');
        }
    });

    // Close overlay on click outside the form (edit overlay)
    window.addEventListener('click', function (event) {
        var overlay = document.getElementById('overlay-edit');
        if (event.target === overlay) {
            toggleOverlay('overlay-edit');
        }
    });
</script>
</body>
</html>