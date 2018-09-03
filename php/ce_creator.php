<?php
include "con.php";
include "findout.php";

$cen = trim(extractCen());
if ($cen != "") {
	$queryCen = "insert into complex_emotions(complex_emotion, description) values('" . addslashes($cen) . "', '" . addslashes(extractFimo("e")) . "' )";

	date_default_timezone_set("Asia/Dhaka");
	$resultCen = mysqli_query($con, $queryCen);

	if (!$resultCen) {
		echo " (" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo " (" . date("h:i:s") . ") => \"" . $cen . "\" complex emotion has been created 🙂";
	}	
}
?>
