<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Create and Edit daum sources</title>
<link rel="stylesheet" type="text/css" href="../../css/datumThings.css"/>
<style>
select {
	width: 100%;
}

input[type="text"] {
	width: 100%;
}

#newSourceName {
	margin-top: 1px;
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
 
<h1>Create and Edit datum sources</h1>
<article>
	<a href="../../index.php">Back to home</a> <br><br>
	<div class="h">Create</div>
	<input type="text" id="inputsName" autocomplete="off"/>
	<span id="message"></span><br>
	<button type="button" id="createButton">Create</button>

	<div class="h">Edit</div>
	<select id="source"></select>
	<input type="text" id="newSourceName"/>
	<span id="message1"></span><br>
	<button type="button" id="saveButton">Save</button>

<div id="outputContainer">
Outputs:
<div id="outputContent">

</div>
</div>
</article>
<script src="../../js/ajax.js"></script>
<script src="../../js/encode.js"></script>
<script src="../../js/validation.js"></script>
<script src="../../js/warning.js"></script>
<script src="../../js/update.js"></script>
<script src="../../js/output.js"></script>
<script>
let sSelect = document.getElementById("source");
let saveButton = document.getElementById("saveButton");
let createButton = document.getElementById("createButton");
let newSourceNameElt = document.getElementById("newSourceName");
let outputContainer = document.getElementById("outputContainer");
let sNameElt = document.getElementById("inputsName");
let message = document.getElementById("message");
let message1 = document.getElementById("message1");

function sourceName() {
	return sNameElt.value;
}

data = {};


getinfo("../../php/getData/datumSources.php", "" , data, "datumSources", handleES);



data["datumSourcesUpdateTimes"] = 0;
function handleES() {
	data.datumSources = JSON.parse(data.datumSources);
	updateSelectTagSpecial(sSelect, data.datumSources, null, "datumSourcesUpdateTimes");
	if (data.datumSources.indexOf(sourceName().trim()) != -1) {
		message.textContent = "not available";
	} else if (sourceName().trim().toLowerCase() == "null") {
		message.textContent = "null a is reserved word and is not allowed";
	} else {
		message.textContent = "";
	}

	if ((data.datumSources.indexOf(newSourceNameElt.value.trim()) != -1) &&
	     newSourceNameElt.value.trim() != sSelect.value) {
		message1.textContent = "not available";
	} else if (newSourceNameElt.value.trim().toLowerCase() == "null") {
		message1.textContent = "null a is reserved word and is not allowed";
	} else {
		message1.textContent = "";
	}
	setTimeout(function() {
		getinfo("../../php/getData/datumSources.php", "" , data, "datumSources", handleES);
	}, 500);
}	

sSelect.oninput = function() {
	if (sSelect.value != "null") {
		newSourceNameElt.value = sSelect.value;
	} else {
		newSourceNameElt.value = "";
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
		updateSelectTag(sSelect, JSON.parse(data.output.split("#")[2]), "sourceSave");
		sSelect.value  = data.output.split("#")[1];
	}
}

function validateSelectNotNull(elt, name) {
	if (elt.value == "null") {
		alert("No " + name + " has been selected");
		return false;
	}
	return true;
}

function validateSNameElt() {
	let elt = sNameElt;
	let scExists = data.datumSources.indexOf(sourceName().trim()) != -1;

	if (elt.value.trim().length == 0) {
		alert("New datum source name field can't be empty");
		return false;
	}
	if (scExists) {
		alert("Datum source not available");
		return false;
	}
	if (elt.value.trim().toLowerCase() == "null") {
		alert("null a is reserved word and is not allowed");
		return false;
	}

	if (!validateQuoteHash(elt.value, "Datum source name is not allowed to contain double quote or hash character")) {
		return false;
	}

	return true;
}

function validateNewSourceNameElt() {
	let elt = newSourceNameElt;
	let isAvailable = (data.datumSources.indexOf(elt.value.trim()) == -1 ||
			  (elt.value.trim().toLowerCase() != "null" && elt.value.trim() == sSelect.value));

	if (sSelect.value == "null") {
		alert("No datum sources has been selected");
		return false;
	}

	if (elt.value.trim().length == 0) {
		alert("Datum source name field field can't be empty");
		return false;
	}
	if (!isAvailable) {
		alert("Datum source name not available");
		return false;
	}
	if (elt.value.trim().toLowerCase() == "null") {
		alert("null a is reserved word and is not allowed");
		return false;
	}

	if (!validateQuoteHash(elt.value, "Datum source name is not allowed to contain double quote or hash character")) {
		return false;
	}

	return true;
}

function create() {
	if (validateSNameElt()) {
		getinfo("../../php/datum_source_creator.php", "datumSource=" + encodeURIComponent(sourceName().trim()), data, "output", showOutput.bind(null, outputContainer));
	}
}

createButton.onclick = function() {
	create();
}

function save() {
	if (validateNewSourceNameElt()) {
		getinfo("../../php/datum_source_editor.php", "datumSource=" + encodeURIComponent(sSelect.value) + "&newDatumSource=" + encodeURIComponent(newSourceNameElt.value.trim()), data, "output", showOutputSpecial.bind(null, outputContainer));
	}
}

saveButton.onclick = function() {
	save();
}



window.onload = function() {
	sNameElt.value = "";
	newSourceNameElt.value = "";
}
</script>

</body>
</html>
