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
        overflow: auto; 
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
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 15px;
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
    width: 100%; 
}
.input-group textarea {
    padding: 10px;
    box-sizing: border-box;
    margin-bottom: 10px;
    border: 1px solid #cccccc;
    border-radius: 4px;
    width: 100%; 
   
}

.button2 {
    background-color: #3498db;
    color: #ffffff;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: auto;
    box-sizing: border-box;
    margin: 10px;
    grid-column: span 4;
}

.input-group2 {
    margin-bottom: 15px;
}

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
    .offenceh2 {
    font-size: 28px;
    color: #3498db;
    text-align: center;
    margin-bottom: 20px; 
    border-bottom: 2px solid #3498db;
    padding-bottom: 5px;
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
        .centered {
    text-align: center;
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
         <h2 class="offenceh2">OFFENCES</h2>
         <div id="button">
            <button id="create-btn">New Offence Record </button>
            </div> 
            <div class="search-container">
        <label for="search">Search:</label>
        <input type="text" id="search" placeholder="Search term name" onkeypress="handleSearch(event)">
    </div>

    <div id="search-results"></div>

    <script>
        function handleSearch(event) {
            // Check if Enter key is pressed (key code 13)
            if (event.key === 'Enter') {
        // Clear previous results before making a new request
        document.getElementById('search-results').innerHTML = '';

                let searchTerm = document.getElementById('search').value;

                // Perform AJAX request to fetch search results
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Update the results container with the response
                            document.getElementById('search-results').innerHTML = xhr.responseText;
                        } else {
                            console.error('Error:', xhr.status, xhr.statusText);
                        }
                    }
                };

                xhr.open('POST', 'search.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('search=' + searchTerm);
            }
            function hideAlert() {
            document.getElementById('alertBox').style.display = 'none';
        }
        }
    </script>
 
 
                    <div class="data-table">
                    <table class="data-tabl">
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
                                <th>Offinder ID</th>
                                <th>OfficerName</th>
                                <th colspan="3" style="text-align: center;">Action</th>
                                <th>Download</th>
                        </thead>
                        <?php include 'dislay_data.php'; ?>
                    </table> 
 </div>
                <div class="overlay" id="overlay">
                    <div class="form-container">
                        <span id="close-btn">&times;</span>
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
            <select id="hit"  name="district"  id="district" required="required">
                <option value="Soche">Soche</option>
            </select>
        </div>

        <div class="input-group">
            <label>Location</label>
            <select id="task" name="location"  id="location" required="required">
                <option value="Kwacha-Roundabout">Kwacha-Roundabout</option>
                <option value="Kamba">Kamba</option>
                <option value="Njamba-Primary">Njamba-Primary</option>
                <option value="Chinyonga">chinyonga</option>
                <option value="Kwakudya">Kwakudya</option>
            </select>
        </div>

        <div class="input-group">
            <label>Physical Feature Hit</label>
            <select id="task" name="feature"  id="feature" required="required">
                <option value="Concrete">Concrete</option>
                <option value="Head to Head">Head to Head</option>
                <option value="Electric Pole">Electric Pole</option>
                <option value="Tree">Tree</option>
                <option value="Shop">Shop</option>
            </select>
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
            <select id="defects" name="vinvolved" id="vinvolved" required="required">
                <option value="1" >1</option>
                <option value="2" >2</option>
                <option value="3" >3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value=" 6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
            </select>
        </div>


<div class="input-group2">
            <label>Name of Witness</label>
            <input type="text" name="witness" id="witness" required="required">
        </div>

        <div class="input-group2">
            <label>Witness Contact</label>
            <input type="text" name="con" id="con" required="required">
        </div>

        <div class="input-group">
            <label for="brief-story">Brief Story:</label>
            <textarea id="brief" name="brief" rows="6" placeholder="Explain what happened here..."></textarea>
        </div>

        <button class="button2" id="submitBtn" name="submit" type="submit" value="save">Create</button>

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
            document.getElementById('create-btn').addEventListener('click', function () {

        document.getElementById('overlay').style.display = 'flex';
    });

    // Close overlay on close button click
    document.getElementById('close-btn').addEventListener('click', function () {
        document.getElementById('overlay').style.display = 'none';
    });

    // Close overlay on click outside the form
    window.addEventListener('click', function (event) {
        var overlay = document.getElementById('overlay');
        if (event.target === overlay) {
            overlay.style.display = 'none';
        }
    });
        </script>
</body>
</html>