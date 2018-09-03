<?php
include "../con.php";
include "../findout.php";

$r = mysqli_query($con, "select id, datum from organized where binary datum_type=\"English > Synonyms and Antonyms\";");

$array = [];
while ($row = mysqli_fetch_row($r)) {
	array_push($array, ["id" => $row[0], "synAyn" => $row[1]]);
}

echo json_encode($array);
?>
