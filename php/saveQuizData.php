<?php
include "con.php";


$datumType = $_POST["datumType"];
$frontCol = $_POST["frontCol"];
$backCol = $_POST["backCol"];
$card = $_POST["card"];
$userAnswer = $_POST["userAnswer"];
$pile = $_POST["pile"];
$phpCard = json_decode($card);

$table = "";
if ($userAnswer == "right") {
	$table = "iwr_pile";
} else {
	$table = "iww_pile";
}

if ($pile == "start_pile") {
	$insertQuery = "insert into " . $table . "(datum_type, front_col, back_col, card) values('" .
		       addslashes($datumType) . "', '" . $frontCol . "', '" . $backCol . "', '" . 
		       addslashes($card) . "');";

	$insertResult = mysqli_query($con, $insertQuery);
} else if ($pile == "iwasright_pile") {
	if ($userAnswer == "wrong") {
		$cardsInfo = "select id, card from iwr_pile where binary datum_type='" . addslashes($datumType) . "';";

		$r = mysqli_query($con, $cardsInfo);

		$id = "";
		while ($row = mysqli_fetch_row($r)) {
			$testId = $row[0];
			$testCard = json_decode($row[1]);
			if ($phpCard == $testCard) {
				$id = $testId;
				break; 	
			}
		}

		mysqli_query($con, "delete from iwr_pile where id=" . $id . ";");
		mysqli_query($con, "insert into iww_pile(datum_type, front_col, back_col, card) values('" .
		       addslashes($datumType) . "', '" . $frontCol . "', '" . $backCol . "', '" . 
		       addslashes($card) . "');");
	}
} else if ($pile == "iwaswrong_pile") {
	if ($userAnswer == "right") {
		$cardsInfo = "select id, card from iww_pile where binary datum_type='" . addslashes($datumType) . "';";

		$r = mysqli_query($con, $cardsInfo);

		$id = "";
		while ($row = mysqli_fetch_row($r)) {
			$testId = $row[0];
			$testCard = json_decode($row[1]);
			if ($phpCard == $testCard) {
				$id = $testId;
				break; 	
			}
		}

		mysqli_query($con, "delete from iww_pile where id=" . $id . ";");
		mysqli_query($con, "insert into iwr_pile(datum_type, front_col, back_col, card) values('" .
		       addslashes($datumType) . "', '" . $frontCol . "', '" . $backCol . "', '" . 
		       addslashes($card) . "');");
	}
}
?>
