<?php
include "../con.php"; 

$mysql_result = mysqli_query($con, "select date(date) from notes_of_days;");

$dates = [];

while ($row = mysqli_fetch_row($mysql_result)) {
	array_push($dates, $row[0]);
}
 
echo json_encode($dates);
?>
