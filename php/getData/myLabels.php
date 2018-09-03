<?php
include "../con.php";
include "../findout.php";

$my_labels_result = mysqli_query($con, "select label from my_labels;");

$my_labels_array = [];
while ($row = mysqli_fetch_row($my_labels_result)) {
	array_push($my_labels_array, $row[0]);
}

echo json_encode($my_labels_array);
?>
