<?php
include "con.php";
include "findout.php";

$datumSource = trim($_POST["datumSource"]);

if ($datumSource != "") {
	$query = "insert into datum_sources(datum_source) values('" . addslashes($datumSource) . "' )";

	date_default_timezone_set("Asia/Dhaka");
	$result = mysqli_query($con, $query);

	if (!$result) {
		echo "(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo "(" . date("h:i:s") . ") => New datum source \"" . $datumSource . "\" has been created 🙂";
	}	
}
?>
