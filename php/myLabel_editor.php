<?php
include "con.php";
include "findout.php";


$query = "update my_labels set label='" . addslashes(trim($_POST["newName"])) . "' where binary label='" . addslashes(trim($_POST["myLabel"])) . "';";

$result = mysqli_query($con, $query);

$updatedMyLabels = "";
if ($result) {
	$myLabel_result = mysqli_query($con, "select label from my_labels;");

	$myLabels_array = [];
	while ($row = mysqli_fetch_row($myLabel_result)) {
		array_push($myLabels_array, $row[0]);
	}

	$updatedMyLabels = json_encode($myLabels_array);
}

date_default_timezone_set("Asia/Dhaka");

if (!$result) {
	  echo "0(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo "1#" . trim($_POST["newName"]) . "#" . $updatedMyLabels . "#(" . date("h:i:s") . ") => \"" . trim($_POST["newName"]) . "\" label has been saved 🙂";
}	
?>
