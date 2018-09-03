<?php
include "../con.php"; 

$datum_type = $_POST["datumType"];
$front = $_POST["front"];
$back = $_POST["back"];


$spCondition = $_POST["spCondition"];
if ($spCondition !== NULL && $spCondition != "") {
	$spCondition = "and " . $spCondition; 
} 


$stats = [];

/*********** find start pile info start *************/
$iwrCardQuery = "select card from iwr_pile where binary datum_type = '" .
		   addslashes($datum_type) . "' and binary front_col='" . $front . "' and binary back_col='" . $back . "';";
$iwwCardQuery = "select card from iww_pile where binary datum_type = '" .
		   addslashes($datum_type) . "' and binary front_col='" . $front . "' and binary back_col='" . $back . "';";	


$iwrCardsResult = mysqli_query($con, $iwrCardQuery);
$iwwCardsResult = mysqli_query($con, $iwwCardQuery);
$iwFronts = [];

while ($row = mysqli_fetch_row($iwrCardsResult)) {
	array_push($iwFronts, json_decode($row[0], true)["front"]);
}

while ($row = mysqli_fetch_row($iwwCardsResult)) {
	array_push($iwFronts, json_decode($row[0], true)["front"]);
}

$cardExcludeCondition = "";

for ($i = 0; $i < sizeOf($iwFronts); $i++) {
	$cardExcludeCondition = $cardExcludeCondition . " and binary " . $front . " != '" . addslashes($iwFronts[$i]) . "'"; 
}

$spQuery = "";
$spMessage = "";
if ($datum_type != "English > Synonyms and Antonyms") {
	$spQuery = "select count(cards) from (select count(id) as cards from organized where datum_type like binary '" . addslashes($datum_type) . "%' and binary " . $front. "!='' and binary " . $back . "!='' " . $spCondition . " " . $cardExcludeCondition . " group by " . $front . ") as demo";
} 

$spResult = mysqli_query($con, $spQuery);
if ($spResult) {
	if ($datum_type != "English > Synonyms and Antonyms") {
		$spMessage = "(" .  mysqli_fetch_row($spResult)[0] . " cards)";
	} 
	$spConditionMessage = "";
} else {
	if ($datum_type != "English > Synonyms and Antonyms") {
		$spMessage = "(0 cards)";
	} 
	$spConditionMessage = mysqli_error($con);
}

/*********** find start pile info end  *************/

/*********** find i was right pile info start  *************/
$iwrQuery = "select count(id) from iwr_pile where binary datum_type='" . addslashes($datum_type) . 
	    "' and binary front_col='" . $front . "' and binary back_col='" . $back . "';";

if ($datum_type == "English > Synonyms and Antonyms") {
	$iwrQuery = "select count(id) from iwr_pile where binary datum_type='" . addslashes($datum_type) . "';";
}

$iwrResult = mysqli_query($con, $iwrQuery);

$iwrMessage = "(" .  mysqli_fetch_row($iwrResult)[0] . " cards)";

/*********** find i was right pile info end  *************/

/*********** find i was wrong pile info start  *************/
$iwwQuery = "select count(id) from iww_pile where binary datum_type='" . addslashes($datum_type) . 
	    "' and binary front_col='" . $front . "' and binary back_col='" . $back . "';";

if ($datum_type == "English > Synonyms and Antonyms") {
	$iwwQuery = "select count(id) from iww_pile where binary datum_type='" . addslashes($datum_type) . "';";
}

$iwwResult = mysqli_query($con, $iwwQuery);

$iwwMessage = "(" .  mysqli_fetch_row($iwwResult)[0] . " cards)";

/*********** find i was wrong pile info end  *************/

array_push($stats, [$spMessage, $spConditionMessage]);
array_push($stats, $iwrMessage);
array_push($stats, $iwwMessage);

echo json_encode($stats);
?>
