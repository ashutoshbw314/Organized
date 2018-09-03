<?php
include "../con.php";

$r1 = mysqli_query($con, "select datum from organized;");
$data = [];
while ($row = mysqli_fetch_row($r1)) {
	array_push($data, trim($row[0]));
}

?>
