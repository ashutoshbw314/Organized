<?php
include "../con.php";

$preCen = trim($_POST["preCen"]);

$queryCen = "select description from complex_emotions where binary complex_emotion='" . addslashes($preCen) . "';";

$resultCen = mysqli_query($con, $queryCen);

$desc = mysqli_fetch_row($resultCen)[0];
if ($desc) {
	echo $desc;
} else {
	echo "null";
}
?>
