<?php
include "con.php";
include "findout.php";

$preCen = trim($_POST["preCen"]);
$cen = extractCen();
$queryCen = "update complex_emotions set complex_emotion='" . addslashes($cen) . "', description='" . addslashes(extractFimo("e")) . "' where binary complex_emotion='" . addslashes($preCen) . "';";

$resultCen = mysqli_query($con, $queryCen);

date_default_timezone_set("Asia/Dhaka");

if (!$resultCen) {
	  echo "(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo "(" . date("h:i:s") . ") => \"" . $cen . "\" complex emotion has been saved 🙂";
}	

?>
