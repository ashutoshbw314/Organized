<?php
include "../con.php";
include "../findout.php";

$pile = $_POST["pile"];
$table = "";
if ($pile == "iwasright_pile") {
	$table = "iwr_pile";
} else {
	$table = "iww_pile";
}

$r = mysqli_query($con, "select card from " . $table . " where binary datum_type=\"English > Synonyms and Antonyms\";");

$cards = [];
while ($row = mysqli_fetch_row($r)) {
	array_push($cards, json_decode($row[0]));
}

echo json_encode($cards);
?>
