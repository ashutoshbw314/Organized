<?php
include "con.php";

$date = $_POST["date"];

$queryNote = "select note from notes_of_days where date='" . $date . "';";

$resultNote = mysqli_query($con, $queryNote);
$preNote = mysqli_fetch_row($resultNote)[0];


if ($preNote !== NULL) {
	$discardQuery = "delete from notes_of_days where date='" . $date . "';";

	$discardResult = mysqli_query($con, $discardQuery);
	if (!$discardResult) {
		echo mysqli_error($con);
	} else {
		echo "Your note has been discarded";
	}

} else {
	echo "You unsaved note has been discarded";
}
?>
