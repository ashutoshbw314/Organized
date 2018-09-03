<?php
include "../con.php";

$r1 = mysqli_query($con, "select id from organized where id=" . $_POST["id"] . ";");

$exists = mysqli_fetch_row($r1)[0];

if ($exists) {
	echo "true";
} else {
	echo "false";
}
?>
