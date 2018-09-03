<?php
include "con.php";
include "findout.php";


$query = "update datum_sources set datum_source='" . addslashes(trim($_POST["newDatumSource"])) . "' where binary datum_source='" . addslashes(trim($_POST["datumSource"])) . "';";

$result = mysqli_query($con, $query);

$updatedSources = "";
if ($result) {
	$datum_sources_result = mysqli_query($con, "select datum_source from datum_sources;");

	$datum_sources_array = [];
	while ($row = mysqli_fetch_row($datum_sources_result)) {
		array_push($datum_sources_array, $row[0]);
	}

	$updatedSources = json_encode($datum_sources_array);
}

date_default_timezone_set("Asia/Dhaka");

if (!$result) {
	  echo "0(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo "1#" . trim($_POST["newDatumSource"]) . "#" . $updatedSources . "#(" . date("h:i:s") . ") => \"" . trim($_POST["newDatumSource"]) . "\" datum source has been saved 🙂";
}	
?>
