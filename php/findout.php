<?php
function extractFimo($fimo) {
	$fimoBasicClue = ["d", "p", "s", "t", "i"];
	$fimoComplexClue = ["d", "c", "i"];

	$fiomRows = [];
	$basicRows = [];
	$complexRows = [];
	$basicRowIndex = 0;
	$complexRowIndex = 0;

	for ($i = 1; isset($_POST[$i . $fimo . "p"]) || isset($_POST[$i . $fimo . "c"]); $i++) {
		$row = array();
		if (isset($_POST[$i . $fimo . "p"])) { 
			if (isset($_POST[$i . $fimo . "d"])) {
				for ($j = 0; $j < 5; $j++) {
					$name = $i . $fimo . $fimoBasicClue[$j];
					$value = $_POST[$name];
					if ($value === "") {
						$value = "null";
					}
					$row[$j] = $value;
				}
				//echo json_encode($row) . "<br>";
				if ($row[1] != "null") {
					$basicRows[$basicRowIndex] = $row;
					$basicRowIndex++;
				}
			}
		} else if (isset($_POST[$i . $fimo . "d"])) {
			for ($j = 0; $j < 3; $j++) {
				$name = $i . $fimo . $fimoComplexClue[$j];
				$value = $_POST[$name];
				if ($value === "") {
					$value = "null";
				}
				$row[$j] = $value;
			}
			//echo json_encode($row) . "<br>";
			if ($row[1] != "null") {
				$complexRows[$complexRowIndex] = $row;
				$complexRowIndex++;
			}
		}
	}

	$fiomRows = [
		"fimo" => $fimo,
		"basic" => $basicRows,
		"complex" => $complexRows
	];

	return json_encode($fiomRows);
} 

function extractDatum() {
	return trim($_POST["datum"]);	
}

function extractNewDatumName() {
	return trim($_POST["newDatumName"]);
}

function extractDatumType() {
	return $_POST["datum_type"];
}

function extractMeaning() {
	return trim($_POST["meaning"]);
}

function extractExamples() {
	return trim($_POST["examples"]);
}

function extractPlay() {
	return trim($_POST["play"]);
}

function extractDs() {
	$source = $_POST["source"];
	if (strtolower($source) == "null") {
		return "NULL";
	}
	return "'" . addslashes($source) . "'";
}

function extractDsDetail() {
	return trim($_POST["sDetail"]);
}

function extractContext() {
	return trim($_POST["context"]);
}

function extractEasyness() {
	return $_POST["easynessIntensity"];
}

function extractNote() {
	return trim($_POST["note"]);
}

function extractCfn() {
	return trim($_POST["complexFeelingName"]);
}

function extractCen() {
	return trim($_POST["complexEmotionName"]);
}
?>
