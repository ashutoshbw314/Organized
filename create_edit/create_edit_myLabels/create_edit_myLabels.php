<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Create and edit my labels</title>
<link rel="stylesheet" type="text/css" href="../../css/datumThings.css"/>
<style>
select {
	width: 180px;
}

a {
 text-decoration: none;
}

a:hover {
	text-decoration: underline;
}
</style>
</head>
<body>
 
<h1>Create and edit my labels</h1>
<article>
<a href="../../index.php">Back to home</a> <br><br>
<div class="h">Create new label</div>
<input type="text" id="inputsName" autocomplete="off"/>
<span id="message"></span><br>
<button type="button" id="createButton">Create</button>


<div class="h">Edit label</div>
<select id="labelSelect"></select><br>

<input type="text" id="newLabelName"/>
<span id="message1"></span><br>
<button type="button" id="saveButton">Save</button>

<div id="outputContainer">
<div class="h">Outputs</div>
<div id="outputContent"></div>
</div>
</article>
<script src="../../js/ajax.js"></script>
<script src="../../js/encode.js"></script>
<script src="../../js/validation.js"></script>
<script src="../../js/update.js"></script>
<script src="../../js/output.js"></script>
<script src="../../js/warning.js"></script>
<script>
let labelSelect = document.getElementById("labelSelect");
let saveButton = document.getElementById("saveButton");
let createButton = document.getElementById("createButton");
let newLabelNameElt = document.getElementById("newLabelName");
let outputContainer = document.getElementById("outputContainer");
let labelNameElt = document.getElementById("inputsName");
let message = document.getElementById("message");
let message1 = document.getElementById("message1");

function myLabelName() {
	return labelNameElt.value;
}

data = {};


getinfo("../../php/getData/myLabels.php", "" , data, "myLabels", handleMyLabels);


data["myLabelsUpdateTimes"] = 0;
function handleMyLabels() {
	data.myLabels = JSON.parse(data.myLabels);
	updateSelectTagSpecial(labelSelect, data.myLabels, null, "myLabelsUpdateTimes");
	if (data.myLabels.indexOf(myLabelName().trim()) != -1) {
		message.textContent = "not available";
	} else if (myLabelName().trim().toLowerCase() == "null") {
		message.textContent = "null a is reserved word and is not allowed";
	} else {
		message.textContent = "";
	}

	if ((data.myLabels.indexOf(newLabelNameElt.value.trim()) != -1) &&
	     newLabelNameElt.value.trim() != labelSelect.value) {
		message1.textContent = "not available";
	} else if (newLabelNameElt.value.trim().toLowerCase() == "null") {
		message1.textContent = "null a is reserved word and is not allowed";
	} else {
		message1.textContent = "";
	}
	setTimeout(function() {
		getinfo("../../php/getData/myLabels.php", "" , data, "myLabels", handleMyLabels);
	}, 500);
}	

labelSelect.oninput = function() {
	if (labelSelect.value != "null") {
		newLabelNameElt.value = labelSelect.value;
	} else {
		newLabelNameElt.value = "";
	}
}


function showOutputSpecial(outputContainer) {
	outputNo++;
	let pre = document.createElement("pre");
	if (data.output[0] == "0") {
		pre.textContent = outputNo + " " + data.output.slice(1);
	} else if (data.output[0] == "1") {
		pre.textContent = outputNo + " " + data.output.split("#")[3];
	}
	outputContainer.querySelector("#outputContent").insertAdjacentElement("afterbegin", pre);

	let status = (Number(data.output[0]) == 1 ? true : false);
	
	if (status) {
		updateSelectTag(labelSelect, JSON.parse(data.output.split("#")[2]), "myLabelsSave");
		labelSelect.value  = data.output.split("#")[1];
	}
}



function validateMyLabelNameElt() {
	let elt = labelNameElt;
	let myLabelExists = data.myLabels.indexOf(myLabelName().trim()) != -1;

	if (elt.value.trim().length == 0) {
		alert("New label name field field can't be empty");
		return false;
	}
	if (myLabelExists) {
		alert("The label name is not available");
		return false;
	}
	if (elt.value.trim().toLowerCase() == "null") {
		alert("null a is reserved word and is not allowed");
		return false;
	}

	if (!validateQuoteHash(elt.value, "label name is not allowed to contain double quote or hash character")) {
		return false;
	}

	return true;
}


function validateNewLabelNameElt() {
	let elt = newLabelNameElt;
	let isAvailable = (data.myLabels.indexOf(elt.value.trim()) == -1 ||
			  (elt.value.trim().toLowerCase() != "null" && elt.value.trim() == labelSelect.value));

	if (labelSelect.value == "null") {
		alert("No label name has been selected");
		return false;
	}

	if (elt.value.trim().length == 0) {
		alert("Label name field field can't be empty");
		return false;
	}
	if (!isAvailable) {
		alert("The label name is not available");
		return false;
	}
	if (elt.value.trim().toLowerCase() == "null") {
		alert("null a is reserved word and is not allowed");
		return false;
	}

	if (!validateQuoteHash(elt.value, "Label name is not allowed to contain double quote or hash character")) {
		return false;
	}

	return true;
}



function create() {
	if (validateMyLabelNameElt()) {
		getinfo("../../php/myLabel_creator.php", "myLabel=" + encodeURIComponent(myLabelName().trim()), data, "output", showOutput.bind(null, outputContainer));
	}
}

createButton.onclick = function() {
	create();
}


function save() {
	if (validateNewLabelNameElt()) {
		getinfo("../../php/myLabel_editor.php", "myLabel=" + encodeURIComponent(labelSelect.value.trim()) + "&newName=" + encodeURIComponent(newLabelNameElt.value.trim()), data, "output", showOutputSpecial.bind(null, outputContainer));
	}
}

saveButton.onclick = function() {
	save();
}/*
*/

window.onload = function() {
	labelNameElt.value = "";
	newLabelNameElt.value = "";
}
</script>

</body>
</html>
