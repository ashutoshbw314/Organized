<?php
include "con.php";
$note = $_POST["note"];
$date = $_POST["date"];

$queryNote = "select note from notes_of_days where date='" . $date . "';";

$resultNote = mysqli_query($con, $queryNote);
$preNote = mysqli_fetch_row($resultNote)[0];


if ($preNote !== NULL) {
	$updateQuery = "update notes_of_days set note='" . addslashes($note) . "' where date='" . $date . "';";

	$updateResult = mysqli_query($con, $updateQuery);
	if (!$updateResult) {
		echo mysqli_error($con);
	} else {
		echo "Your note has been saved";
	}

} else {
	$insertQuery = "insert into notes_of_days(note, date) values('" . addslashes($note) . "', '" . $date . "');";
	$insertResult = mysqli_query($con, $insertQuery);
	if (!$insertResult) {
		echo mysqli_error($con);
	} else {
		echo "Your note has been saved";
	}
}
?>
