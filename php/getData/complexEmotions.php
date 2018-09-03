<?php
include "../con.php"; 
$complex_emotions_result = mysqli_query($con, "select complex_emotion from complex_emotions;");

$complex_emotions_array = [];
while ($row = mysqli_fetch_row($complex_emotions_result)) {
	array_push($complex_emotions_array, $row[0]);
}

echo json_encode($complex_emotions_array);
?>
