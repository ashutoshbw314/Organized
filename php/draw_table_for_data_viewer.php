<?php
$columns_int_types = array();  
function draw_table_heading ($theResult) {
	$info = mysqli_fetch_fields($theResult);
	echo "<tr>\r\n";
	foreach ($info as $val) {
		echo "\t<th>" . $val->name . "</th>\r\n";
		$type = $val->type;
		if ($type == 3) {
			array_push($GLOBALS['columns_int_types'], true);
		} else {
			array_push($GLOBALS['columns_int_types'], false);
		}
	}	
	echo "</tr>\r\n";
}

function draw_table_row ($theRow) {
	$total_column_num = sizeOf($theRow);
	echo "<tr>\r\n";
	for ($i = 0; $i < $total_column_num; $i++) {
		if ($GLOBALS['columns_int_types'][$i]) {
			echo "\t<td style='text-align: right;' class='tdId'>" . $theRow[$i] . "</td>\r\n";	
		} else {
			echo "\t<td class='" . ($i == 1 ? "tdDatumType" : "tdDatum") . "'>" . $theRow[$i] . "</td>\r\n";
		}
	}
	echo "</tr>\r\n";
}
function draw_table ($theResult) {
	$has_columns_drawn = false;

/*	while ($row = mysqli_fetch_array($theResult)) {
		if ($is_drawing_columns == false) {
			$is_drawing_columns = true;
			draw_table_heading($row);
		}
		draw_table_row($row);
	}*/
	
	do {
		$row = mysqli_fetch_row($theResult);
		if ($has_columns_drawn == false && $row != NULL) {
			$has_columns_drawn = true;
			//echo "<img id='taTaTaa' src='taTaTaa_with_text.png' width='200'>\r\n";
			echo "<table id='theTable'>\r\n";
			draw_table_heading($theResult);
		}

		if ($row != NULL) { 
			draw_table_row($row);
		} else if ($has_columns_drawn) {
			echo "</table>\r\n";
		}	

	} while ($row);
}
?>
