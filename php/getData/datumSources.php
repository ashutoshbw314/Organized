<?php
include "../con.php";
include "../findout.php";

$datum_sources_result = mysqli_query($con, "select datum_source from datum_sources;");

$datum_sources_array = [];
while ($row = mysqli_fetch_row($datum_sources_result)) {
	array_push($datum_sources_array, $row[0]);
}

echo json_encode($datum_sources_array);
?>
