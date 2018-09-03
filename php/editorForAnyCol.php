<?php
include "con.php";
include "findout.php";

$col = $_POST["col"];
$category = $_POST["cat"];
$id = $_POST["id"];
$theValue = $_POST["theValue"];

if ($col == "emotions") {
	$theValue = extractFimo("e");
}



$query = "update organized set " . $col . "='" . addslashes($theValue) . "' where id='" . $id . "';";

$result = mysqli_query($con, $query);

date_default_timezone_set("Asia/Dhaka");

if (!$result) {
	  echo "(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁";
	} else {
		echo "(" . date("h:i:s") . ") => " . $category . " of id \"" . $id . "\" has been saved 🙂";
}	
?>
