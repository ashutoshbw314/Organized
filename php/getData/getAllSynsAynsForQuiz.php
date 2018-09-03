<?php
include "../con.php";
include "../findout.php";

$spCondition = $_POST["spCondition"];
if ($spCondition !== NULL && $spCondition != "") {
	$spCondition = " and " . $spCondition; 
} 

$r = mysqli_query($con, "select id, datum from organized where binary datum_type=\"English > Synonyms and Antonyms\" " .
                        $spCondition . ";");

if ($r) {
	$array = [];
	while ($row = mysqli_fetch_row($r)) {
		array_push($array, ["id" => $row[0], "synAyn" => $row[1]]);
	}

	echo json_encode($array);
} else {
	echo json_encode(["error" => mysqli_error($con)]);
}
?>
