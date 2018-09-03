<?php
include "con.php";
include "findout.php";

$myLabel = trim($_POST["myLabel"]);

if ($myLabel != "") {
	$query = "insert into my_labels(label) values('" . addslashes($myLabel) . "' )";

	date_default_timezone_set("Asia/Dhaka");
	$result = mysqli_query($con, $query);

	if (!$result) {
		echo "(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo "(" . date("h:i:s") . ") => New label \"" . $myLabel . "\" has been created 🙂";
	}	
}
?>
