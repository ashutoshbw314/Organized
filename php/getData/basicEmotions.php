<?php
include "../con.php"; 
$emotions_result = mysqli_query($con, "select pe, se, te from basic_emotions;");

$emotions_array = [];
while ($row = mysqli_fetch_row($emotions_result)) {
	for ($i = 0; $i < 3; $i++) {
 		if ($i == 0) {								//primary value
			if (!pv_exists("emotions_array", $row[0])) {
				add_pv("emotions_array", $row[0]);
			}
		} elseif ($i == 1) {						//secondary value
			if (!sv_exists("emotions_array", $row[0], $row[1])) {
				add_sv("emotions_array", $row[0], $row[1]);
			}
		} else {									//tertiary value
			add_tv("emotions_array", $row[0], $row[1], $row[2]);
		}
		//echo $row[$i];
	}
		//echo "<br>";
}
 


function add_pv($arr_name, $pv) {
	$GLOBALS[$arr_name][$pv] = array();
}

function add_sv($arr_name, $pv, $sv) {
	$GLOBALS[$arr_name][$pv][$sv] = array();
}

function add_tv($arr_name, $pv, $sv, $tv) {
	array_push($GLOBALS[$arr_name][$pv][$sv], $tv);
}

function pv_exists($arr_name, $pv) {
	return array_key_exists($pv, $GLOBALS[$arr_name]);
}

function sv_exists($arr_name, $pv, $sv) {					//fimo names must to unambiguous
	return array_key_exists($sv, $GLOBALS[$arr_name][$pv]);
}



echo json_encode($emotions_array);
?>
