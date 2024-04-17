<!DOCTYPE html>
<html>
<head>
    <title>Traffic Crime Report</title>
</head>
<body>
    <h1>Traffic Crime Report</h1>
<form action="generate_pdf.php" method="post">
    <label for="columns">Select columns to include:</label><br><br>
    <input type="checkbox" id="accident_type" name="columns[]" value="accident_type">
    <label for="accident_type">Accident Type</label><br>
    
    <input type="checkbox" id="accident_date" name="columns[]" value="accident_date">
    <label for="accident_date">Accident Date</label><br>
    
    <input type="checkbox" id="offender_id" name="columns[]" value="offender_id">
    <label for="offender_id">Offender ID</label><br>
    
    <input type="checkbox" id="name" name="columns[]" value="name">
    <label for="name">Name</label><br><br>
    
    <button type="submit">Generate PDF</button>
</form>
</body>
</html>