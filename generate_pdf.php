<?php
require_once('C:/xampp/htdocs/TCDM/tcpdf/tcpdf.php');

// Include server.php for session management
include('server.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to connect to the database
    function connectToDatabase() {
        $db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');
        if (!$db) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $db;
    }

    // Function to retrieve officer information
    function getOfficerInfo($db, $username) {
        $query = "SELECT username FROM officer_table WHERE username = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $officerName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $officerName;
    }

    // Function to fetch single row data from the database
    function fetchSingleRow($db, $query) {
        $result = mysqli_query($db, $query);
        return mysqli_fetch_assoc($result);
    }

    // Connect to the database
    $connection = connectToDatabase();

    // Get officer information based on the logged-in username
    $loggedInUsername = $_SESSION['username']; 
    $officerName = getOfficerInfo($connection, $loggedInUsername);

    // Create a new PDF instance
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Malawi Police Service');
    $pdf->SetTitle('Traffic Crime Report');
    $pdf->SetSubject('Traffic Incident Report');
    $pdf->SetKeywords('Traffic, Crime, Report');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Malawi Police Service', '', array(0, 0, 0), array(255, 255, 255));
    $pdf->setFooterData(array(0, 64, 0), array(255, 255, 255));

    // Set margins
    $pdf->SetMargins(10, 10, 10, true);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', '', 12);

    // Output report title
    $pdf->SetFont('times', 'BU', 24);
    $pdf->Cell(0, 10, ' Traffic Incident Report', 0, 1, 'C');
    $pdf->Ln(10);

    // Fetch data based on the provided accident_id
    $accident_id = $_POST['accident_id'];
    $query = "SELECT a.accident_date, a.obstraction_hit,
                     v.defects, v.vehicles_involved, v.vehicle_name,
                     o.name, o.driving_licence, o.alcohol_test,
                     of.username,
                     w.name AS witness_name, w.contact, w.briefReport
              FROM accident_table a
              JOIN vehicle_table v ON a.vehicle_id = v.vehicle_id
              JOIN offender_table o ON a.vehicle_id = o.vehicle_id
              LEFT JOIN officer_table of ON a.officer_id = of.officer_id
              LEFT JOIN witness_table w ON v.vehicle_id = w.vehicle_id
              WHERE a.accident_id = '$accident_id'";

    // Fetch data from the database
    $accidentData = fetchSingleRow($connection, $query);

 // Output Incident Details
$pdf->SetFont('times', 'B', 16);
$pdf->Cell(0, 10, 'Incident Details:', 0, 1);
$pdf->SetFont('times', '', 12);
$incidentDetails = 'The incident occurred on ' . $accidentData['accident_date'];
if (isset($accidentData['accident_time'])) {
    $incidentDetails .= ' at ' . $accidentData['accident_time'];
}
$incidentDetails .= ', and the obstruction hit was ' . $accidentData['obstraction_hit'];
$pdf->MultiCell(0, 10, $incidentDetails, 0, 'L');
$pdf->Ln(15);


    // Output Vehicle Details
    $pdf->SetFont('times', 'B', 16);
    $pdf->Cell(0, 10, 'Vehicle Details:', 0, 1);
    $pdf->SetFont('times', '', 12);
    $pdf->MultiCell(0, 10, 'The vehicle involved, identified by its plate/name ' . $accidentData['vehicle_name'] . ', experienced ' . $accidentData['defects'] . ', and ' . $accidentData['vehicles_involved'] . ' were affected.', 0, 'L');
    $pdf->Ln(15);

    // Output Driver Details
    $pdf->SetFont('times', 'B', 16);
    $pdf->Cell(0, 10, 'Driver Details:', 0, 1);
    $pdf->SetFont('times', '', 12);
    $pdf->MultiCell(0, 10, 'The driver, ' . $accidentData['name'] . ', possessed a valid driver\'s license ' . $accidentData['driving_licence'] . ' and underwent ' . $accidentData['alcohol_test'] . ' for alcohol presence.', 0, 'L');
    $pdf->Ln(15);

    // Output Witness Details
    $pdf->SetFont('times', 'B', 16);
    $pdf->Cell(0, 10, 'Witness Details:', 0, 1);
    $pdf->SetFont('times', '', 12);
    $pdf->MultiCell(0, 10, 'The witness, ' . $accidentData['witness_name'] . ', was contacted at ' . $accidentData['contact'], 0, 'L');
    $pdf->Ln(15);

    // Output Brief Report
    $pdf->SetFont('times', 'B', 16);
    $pdf->Cell(0, 10, 'Description:', 0, 1);
    $pdf->SetFont('times', '', 12);
    $pdf->MultiCell(0, 10, 'Brief Report: ' . $accidentData['briefReport'], 0, 'L');
    $pdf->Ln(15);

    // Output Officer Details
    $pdf->SetFont('times', 'B', 16);
    $pdf->Cell(0, 10, 'Officer Details:', 0, 1);
    $pdf->SetFont('times', 'I', 12);
    $pdf->MultiCell(0, 10, 'Officer Username: ' . $accidentData['username'], 0, 'L');

    // Close the connection
    mysqli_close($connection);

    // Output the PDF as a download
    $pdf->Output('traffic_crime_report.pdf', 'D');
}
?>
