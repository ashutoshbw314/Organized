<?php
include "../con.php";

$id = $_POST["id"];
$col = $_POST["col"];

$r1 = mysqli_query($con, "select " . $col . " from organized where id=" . $id . ";");

$value = mysqli_fetch_row($r1)[0];

if ($value !== NULL) {
	echo $value;
} else {
	echo "";
}
?>
