<?php
include "../con.php"; 

$datum_type = $_POST["datum_type"];

$result = mysqli_query($con, "select id, datum from organized where date(time_of_insert)=\"" . $_POST["date"] . "\" and datum_type like binary \"" . addslashes($datum_type) . "%\";");


$aboutData = [];
while ($row = mysqli_fetch_row($result)) {
	array_push($aboutData, ["id" => $row[0], "datum" => $row[1]]);
}

echo json_encode($aboutData);
?>
