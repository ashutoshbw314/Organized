<?php
include "../findout.php";
include "datumNames.php";

$new_datum_name = extractNewDatumName();

if ($new_datum_name !== "") {
	if (array_search($new_datum_name, $data) === 0 || array_search($new_datum_name, $data) > 0) {
 		echo "true";
	} else {
		echo "false";
	}
} else {
	echo "false";
}
?>
