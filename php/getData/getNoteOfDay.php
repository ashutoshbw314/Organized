<?php
include "../con.php"; 

$mysql_result = mysqli_query($con, "select note from notes_of_days where date(date)='" . $_POST["date"] . "';");

$note = mysqli_fetch_row($mysql_result)[0];


if ($note !== NULL) {
	echo $note;
} else {
	echo "";
}
?>
