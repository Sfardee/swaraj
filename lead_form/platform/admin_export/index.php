<?php
include_once('../storage/db.php');

$filename = "survey_response.csv";
$fp = fopen('php://output', 'w');

$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='swaraj_lead_form' AND TABLE_NAME='survey_response'";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_row($result)) {
	$header[] = $row[0];
}	
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

$query = "SELECT * FROM survey_response ORDER BY submission_datetime DESC";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_row($result)) {
	fputcsv($fp, $row);
}
exit;
?>