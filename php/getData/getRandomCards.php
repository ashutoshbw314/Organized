<?php
include "../con.php"; 


$front = $_POST["front"];
$back = $_POST["back"];
$datum_type = $_POST["datumType"];
$limit = $_POST["limit"];
$pile = $_POST["pile"];
$spCondition = $_POST["spCondition"];
if ($spCondition !== NULL && $spCondition != "") {
	$spCondition = "and " . $spCondition; 
} 

$table = "";
if ($pile == "iwasright_pile") {
	$table = "iwr_pile";
} else if ($pile == "iwaswrong_pile") {
	$table = "iww_pile";
}


if ($datum_type != "English > Synonyms and Antonyms") {
	if ($pile == "start_pile") {
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

		$cardSelectQuery = "select " . $front . " from organized where datum_type like binary '" .
				   addslashes($datum_type) . "%' and binary " . $front. "!='' and binary " . $back . "!='' " .
		    		   $spCondition . " " . $cardExcludeCondition . " group by " . $front . " order by rand() limit " . $limit . ";";

		$csResult = mysqli_query($con, $cardSelectQuery);

		$cards = [];
		$frontArray = [];
		$backArray = [];

		// fill the frontArray
		while ($row = mysqli_fetch_row($csResult)) {
			array_push($frontArray, $row[0]);
		}
		
		// fill the backArray
		for ($i = 0; $i < sizeOf($frontArray); $i++) {
			$backContent = [];
			$backSelectQuery = "select " . $back . ", datum_type from organized where datum_type like binary '" .
					   addslashes($datum_type) . "%' and binary " . $front. "!='' and binary " . $back . "!='' " .
			    		   $spCondition . " and binary " . $front . "='" . addslashes($frontArray[$i]) . 
					   "' order by " . $back . " asc;";
			$bsResult = mysqli_query($con, $backSelectQuery);
			while ($row = mysqli_fetch_row($bsResult)) {
				array_push($backContent, [$row[0], $row[1]]);
			}
			array_push($backArray, $backContent);
		}
		
		for ($i = 0; $i < sizeOf($frontArray); $i++) {
			array_push($cards, [
				"front" => $frontArray[$i],
				"back" => $backArray[$i]
			]);
		}
		
		echo json_encode($cards);
	} else {
			$query = "select card from " . $table . " where binary datum_type = '" .
						 					addslashes($datum_type) . "' and binary front_col='" . $front . "' and binary back_col='" . $back . "' order 											by rand() limit " . $limit . ";";
			$cardsResult = mysqli_query($con, $query);
			$cards = [];
			while ($row = mysqli_fetch_row($cardsResult)) {
				array_push($cards, json_decode($row[0]));
			}		
			echo json_encode($cards);
	}
}
?>
