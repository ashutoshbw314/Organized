<?php
include "../con.php";
include "../findout.php";

$iwr_result = mysqli_query($con, "select card from iwr_pile where binary datum_type=\"English > Synonyms and Antonyms\";");

$iww_result = mysqli_query($con, "select card from iww_pile where binary datum_type=\"English > Synonyms and Antonyms\";");

$cards = [];
while ($row = mysqli_fetch_row($iwr_result)) {
	array_push($cards, json_decode($row[0]));
}

while ($row = mysqli_fetch_row($iww_result)) {
	array_push($cards, json_decode($row[0]));
}
echo json_encode($cards);
?>
