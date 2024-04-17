<?php
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Set stamp image
        $stampImage = 'C:/xampp/htdocs/TCDM/paid.jpg'; // Path to the stamp image
        if (file_exists($stampImage)) {
            $this->Image($stampImage, 10, $this->getPageHeight() - 35, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else {
            // Handle error if the stamp image doesn't exist
            $this->Cell(0, 10, 'Error: Stamp image not found!', 0, false, 'C');
        }
    }
}

if (isset($_POST['gr_number'])) {
    // Retrieve data from the database based on the provided gr_number
    $gr_number = $_POST['gr_number'];
    $db = mysqli_connect('localhost', 'root', '', 'traffic_crime_db');

    // Fetch data associated with the provided gr_number
    $query = "SELECT * FROM receipt_table WHERE gr_number = '$gr_number'";
    $result = mysqli_query($db, $query);

    // Check if the query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Retrieve station name based on station_id
        $station_id = $row['station_id'];
        $station_query = "SELECT name FROM police_table WHERE station_id = '$station_id'";
        $station_result = mysqli_query($db, $station_query);
        $station_row = mysqli_fetch_assoc($station_result);
        $station_name = $station_row['name'];

        // Retrieve offender name based on offender_id
        $offender_id = $row['offender_id'];
        $offender_query = "SELECT name FROM offender_table WHERE offender_id = '$offender_id'";
        $offender_result = mysqli_query($db, $offender_query);
        $offender_row = mysqli_fetch_assoc($offender_result);
        $offender_name = $offender_row['name'];

        // Create new PDF document with a smaller page size
        $pdf = new MYPDF('P', 'mm', array(100, 150)); // Width: 100mm, Height: 150mm
        $pdf->AddPage();

        // Set content to include all data from the row
        $content = "
            <h1>Receipt Information</h1>
            <p>Date: {$row['date']}</p>
            <p>General Receipt Number: {$row['gr_number']}</p>
            <p>Fine: {$row['fine']}</p>
            <p>Station Name: {$station_name}</p>
            <p>Offender Name: {$offender_name}</p>
            ";

        // Write content to PDF
        $pdf->writeHTML($content, true, false, true, false, '');

        // Output PDF as a download
        $pdf->Output('receipt.pdf', 'D');
    } else {
        echo "Error: No data found for the provided General Receipt Number.";
    }

    // Close the database connection
    mysqli_close($db);
} else {
    echo "Error: Missing parameters.";
}
?>
