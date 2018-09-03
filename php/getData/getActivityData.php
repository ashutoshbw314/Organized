<?php
include "../con.php"; 

$datum_type = $_POST["datum_type"]; 

$mysql_result = mysqli_query($con, "select date(time_of_insert) as the_date, count(id) as total from organized where datum_type like binary \"" . addslashes($datum_type) . "%\" group by date(time_of_insert) order by the_date desc;");

$activity_data = [];

$cur_year = "";
while ($row = mysqli_fetch_row($mysql_result)) {
	$activity_data[sizeOf($activity_data)] = [$row[0], $row[1]];
}
 
echo json_encode($activity_data);
?>
