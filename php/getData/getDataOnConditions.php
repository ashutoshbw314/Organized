<?php
include "../con.php"; 
include "../draw_table_for_data_viewer.php";

$condition = $_POST["condition"];
if (strlen($condition) != 0) {
	$condition = "where " . $condition;
}
$result = mysqli_query($con, "select id, datum_type, datum from organized " . $condition . ";");

if (!$result) {
	echo "<p>Error: " . mysqli_error($con) . " 🙁</p>\r\n";
} else {
	draw_table($result);
}
?>
