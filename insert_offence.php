<?php
function connectToDatabase() {
    $db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $db;
}

function insertDistrict($db, $district) {
    $check_query = "SELECT district_id FROM district WHERE district_name = ?";
    $check_stmt = mysqli_prepare($db, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $district);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) == 0) {
        $insert_query = "INSERT INTO district (district_name) VALUES (?)";
        $insert_stmt = mysqli_prepare($db, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "s", $district);
        mysqli_stmt_execute($insert_stmt);

        return mysqli_insert_id($db);
    } else {
        $fetch_query = "SELECT district_id FROM district WHERE district_name = ?";
        $fetch_stmt = mysqli_prepare($db, $fetch_query);
        mysqli_stmt_bind_param($fetch_stmt, "s", $district);
        mysqli_stmt_execute($fetch_stmt);
        mysqli_stmt_bind_result($fetch_stmt, $existing_district_id);
        mysqli_stmt_fetch($fetch_stmt);
        mysqli_stmt_close($fetch_stmt);

        return $existing_district_id;
    }
}

function insertLocation($db, $districtId, $location, $physicalFeature) {
    $location_query = "INSERT INTO location_table (district_id, name, physical_feature) VALUES (?, ?, ?)";
    $location_stmt = mysqli_prepare($db, $location_query);
    mysqli_stmt_bind_param($location_stmt, "iss", $districtId, $location, $physicalFeature);
    mysqli_stmt_execute($location_stmt);
    mysqli_stmt_close($location_stmt);

    return mysqli_insert_id($db);
}

function insertVehicle($db, $vehicleName, $defects, $vehiclesInvolved, $locationId) {
    $existingVehicleId = getExistingVehicleId($db, $locationId);

    if ($existingVehicleId === null) {
        $insert_query = "INSERT INTO vehicle_table (vehicle_name, defects, vehicles_involved, location_id) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($db, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "sssi", $vehicleName, $defects, $vehiclesInvolved, $locationId);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);

        return mysqli_insert_id($db);
    }

    return $existingVehicleId;
}
function getExistingVehicleId($db, $locationId) {
    $check_query = "SELECT vehicle_id FROM vehicle_table WHERE location_id = ?";
    $check_stmt = mysqli_prepare($db, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $locationId);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_bind_result($check_stmt, $existingVehicleId);
        mysqli_stmt_fetch($check_stmt);
        mysqli_stmt_close($check_stmt);

        return $existingVehicleId;
    }

    return null;
}


function insertRoadCondition($db, $locationId, $roadCondition, $roadGeometry, $surfaceType) {
    $road_condition_query = "INSERT INTO roadCondition_table (roadcondition, road_geometry, surface_type, location_id) VALUES (?, ?, ?, ?)";
    $road_condition_stmt = mysqli_prepare($db, $road_condition_query);
    mysqli_stmt_bind_param($road_condition_stmt, "sssi", $roadCondition, $roadGeometry, $surfaceType, $locationId);
    mysqli_stmt_execute($road_condition_stmt);
    mysqli_stmt_close($road_condition_stmt);
}


function insertPoliceStation($db, $districtId, $policeStationName) {
    $check_query = "SELECT name FROM police_table WHERE name = ?";
    $check_stmt = mysqli_prepare($db, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $policeStationName);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) == 0) {
        $police_station_query = "INSERT INTO police_table (district_id, name) VALUES (?, ?)";
        $police_station_stmt = mysqli_prepare($db, $police_station_query);
        mysqli_stmt_bind_param($police_station_stmt, "is", $districtId, $policeStationName);
        mysqli_stmt_execute($police_station_stmt);
        mysqli_stmt_close($police_station_stmt);
    }
    mysqli_stmt_close($check_stmt);
}

function insertOffender($db, $name, $drivingLicense, $alcoholTest, $vehicleId, $address) {
    $insert_query = "INSERT INTO offender_table (name, driving_licence, alcohol_test, vehicle_id,offender_address) VALUES (?, ?, ?, ?,?)";
    $insert_stmt = mysqli_prepare($db, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "sssis", $name, $drivingLicense, $alcoholTest, $vehicleId,$address);
    mysqli_stmt_execute($insert_stmt);
    mysqli_stmt_close($insert_stmt);

    return mysqli_insert_id($db);
}

// Function to get officer_id by username
$loggedInUsername =  	$_SESSION['username']; // Assuming you have a session variable storing the username

// Function to get officer_id by username
function getOfficerIdByUsername($db, $username) {
    $query = "SELECT officer_id FROM officer_table WHERE username = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $officerId);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $officerId;
}

// Function to insert an accident
function insertAccident($db, $officerId, $accidentType, $accidentDate, $accidentTime, $vehicleId, $obstractionHit, $weather) {
    $query = "INSERT INTO accident_table (officer_id, accident_type, accident_date, accident_time, vehicle_id, obstraction_hit, weather) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "isssiss", $officerId, $accidentType, $accidentDate, $accidentTime, $vehicleId, $obstractionHit, $weather);
    mysqli_stmt_execute($stmt);
    
    // Retrieve the last inserted ID
    $accidentId = mysqli_insert_id($db);
    
    mysqli_stmt_close($stmt);

    return $accidentId;
}
// Function to insert an witness
function insertWitness($db, $contact, $witness_name, $vehicleId, $briefReport,$accidentId)
{
    $query = "INSERT INTO witness_table (name, contact, vehicle_id, briefReport,accident_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssisi", $witness_name, $contact, $vehicleId, $briefReport,$accidentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


if (isset($_POST["submit"])) {
    $db = connectToDatabase();

    $location = $_POST['location'];
    $district = $_POST['district'];
    $physicalFeature = $_POST['feature'];

    $districtId = insertDistrict($db, $district);
    $locationId = insertLocation($db, $districtId, $location, $physicalFeature);

    $vehicleName = $_POST['vname'];
    $defects = $_POST['defects'];
    $vehiclesInvolved = $_POST['vinvolved'];
    $vehicleId = insertVehicle($db, $vehicleName, $defects, $vehiclesInvolved, $locationId);


    $roadCondition = $_POST['condition'];
    $roadGeometry = $_POST['geometry'];
    $surfaceType = $_POST['surface'];
    insertRoadCondition($db, $locationId, $roadCondition, $roadGeometry, $surfaceType);

    $policeStationName = $_POST['station'];
    insertPoliceStation($db, $districtId, $policeStationName);

    $address = $_POST['address'];
    $offenderName = $_POST['offender'];
    $drivingLicense = $_POST['licence'];
    $alcoholTest = $_POST['test'];
    insertOffender($db, $offenderName, $drivingLicense, $alcoholTest, $vehicleId, $address);

    $officerId = getOfficerIdByUsername($db, $loggedInUsername);
    $accidentType = $_POST['accidenttype'];
    $accidentDate = $_POST['date'];
    $accidentTime = $_POST['time'];
    $obstractionHit = $_POST['hit'];
    $weather = $_POST['weather'];
    $vehicleId = insertVehicle($db, $vehicleName, $defects, $vehiclesInvolved, $locationId);
    $accidentId = insertAccident($db, $officerId, $accidentType, $accidentDate, $accidentTime, $vehicleId, $obstractionHit, $weather);
    
    // Insert witness
    $contact = $_POST['con'];
    $witness_name = $_POST['witness'];
    $briefReport = $_POST['brief'];
    insertWitness($db, $contact, $witness_name, $vehicleId, $briefReport, $accidentId);
        
    
    mysqli_close($db);


    // exit();
}
?>