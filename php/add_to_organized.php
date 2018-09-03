<?php
include "con.php";
include "findout.php";

$datum_source = extractDs();
$datum_source_detail = "";
if ($datum_source != "NULL") {
	$datum_source_detail = extractDsDetail();
}
$myLabels = $_POST["myLabels"];

$the_query = "insert into organized(datum, datum_type, my_labels, datum_source, datum_source_detail, context, meaning, examples, play, my_note, easyness_intensity, emotions) values(" 
. "'" . addslashes(extractDatum()) . "', "
. "'" . addslashes(extractDatumType()) . "', "
. "'" . addslashes($myLabels) . "', "
. $datum_source . ", "
. "'" . addslashes($datum_source_detail) . "', "
. "'" . addslashes(extractContext()) . "', "
. "'" . addslashes(extractMeaning()) . "', "
. "'" . addslashes(extractExamples()) . "', "
. "'" . addslashes(extractPlay()) . "', "
. "'" . addslashes(extractNote()) . "',"
. "" . extractEasyness() . ", "
. "'" . addslashes(extractFimo("e")) . "'"
. ");";

//echo $the_query;


$result = mysqli_query($con, $the_query);

date_default_timezone_set("Asia/Dhaka");

if (!$result) {
  echo "(" . date("h:i:s") . ") => " . "Error: " . mysqli_error($con) . " 🙁\r\n";
} else {
	$datum = extractDatum();
	$shortDatum = "" ;
	if (mb_strlen($datum, "UTF-8") > 10) {
		$shortDatum = mb_substr($datum, 0, 10, "UTF-8") . "...";
	} else {
		$shortDatum = $datum;
	}

	$query2 = "SELECT LAST_INSERT_ID();";
	$r2 = mysqli_query($con, $query2);
	$lastId = mysqli_fetch_row($r2)[0];

  echo "(" . date("h:i:s") . ") => \"" . $shortDatum . "\" has been added 🙂 It's id is: " . $lastId . "\r\n";
}
?>
