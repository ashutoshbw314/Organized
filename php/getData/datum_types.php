<?php
include "../con.php";

$r1 = mysqli_query($con, "select datum_type from organized group by datum_type;");
$datum_types = [];
while ($row = mysqli_fetch_row($r1)) {
	array_push($datum_types, trim($row[0]));
}

echo json_encode($datum_types);
?>
