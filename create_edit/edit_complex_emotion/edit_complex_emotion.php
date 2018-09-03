<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Edit Complex Emotion</title>
<link rel="stylesheet" type="text/css" href="../../css/fimo.css"/>
<link rel="stylesheet" type="text/css" href="../../css/datumThings.css"/>
<link rel="stylesheet" type="text/css" href="../../css/output.css"/>
<link rel="stylesheet" type="text/css" href="../../css/type.css"/>
<style>
a {
 text-decoration: none;
}

a:hover {
	text-decoration: underline;
}
</style>
</head>
<body>
<h1>Edit Complex Emotion</h1>
<article>
<a href="../../index.php">Back to home</a> <br><br>
<div class="h">Complex emotion name</div>
<input type="text" id="inputsName"/> <span id="message"></span>

<div class="h">Description</div>
<div id="complexEmotionContainer"></div>
<button type="button" id="saveButton">Save</button>

<div id="outputContainer">
<div class="h">Outputs</div>
<div id="outputContent">

</div>
</div>

</article>
<script src="../../js/ajax.js"></script>
<script src="../../js/fimo.js"></script>
<script src="../../js/encode.js"></script>
<script src="../../js/validation.js"></script>
<script src="../../js/update.js"></script>
<script src="../../js/output.js"></script>
<script src="../../js/warning.js"></script>

<script>
let data = {};

let complexEmotionContainer = document.getElementById("complexEmotionContainer");
let outputContainer = document.getElementById("outputContainer");
let preCenElt = document.getElementById("inputsName");
let message = document.getElementById("message");

let saveButton = document.getElementById("saveButton");


getinfo("../../php/getData/basicEmotions.php", "" , data, "basicEmotions", updateBasicEmotions.bind(null, "basicEmotions"));
getinfo("../../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleTextInputs.bind(null, preCenElt, "complexEmotions"));


function parseJSON(prop) {
	data[prop] = JSON.parse(data[prop]);
}

function updateBasicEmotions(prop) {
	parseJSON(prop);
	let complexEmotion = complexEmotionContainer.querySelector(".mfe");
	if (complexEmotion) {
		updateBasicOnes(complexEmotion, data[prop], "e");
	}
	setTimeout(function() {
		getinfo("../../php/getData/basicEmotions.php", "" , data, "basicEmotions", updateBasicEmotions.bind(null, "basicEmotions"));
	}, 500);
}

function handleTextInputs(elt, prop) {
	parseJSON(prop);
	let complexEmotion = complexEmotionContainer.querySelector(".mfe");
	if (elt.value.trim() != "" && data[prop].indexOf(elt.value.trim()) == -1) {
		if (complexEmotion) {
			complexEmotionContainer.removeChild(complexEmotion);
		}
		message.textContent = "doesn't exists";
	} else {
		if (complexEmotion) {
			updateComplexOnes(complexEmotion, data[prop], "e");
		}
		message.textContent = "";
	}

	if (complexEmotion) {
		let newNameElt = complexEmotion.querySelector("input[type='text']");
		if (newNameElt) {
			let message1 = newNameElt.nextElementSibling;
			if (newNameElt.value.trim() != "" &&
			    data[prop].indexOf(newNameElt.value.trim()) != -1 &&
			    newNameElt.value.trim() != elt.value.trim()) {
				message1.textContent = "Not availabe";
			} else {
				message1.textContent = "";
			}
			if (newNameElt.value.trim().toLowerCase() == "null") {
				message1.textContent = "\"null\" is a reserved word and is not allowed";
			}
		}
	}
	setTimeout(function() {
		getinfo("../../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleTextInputs.bind(null, preCenElt, "complexEmotions"));
	}, 500);
}

function validatePreCenElt() {
	let txt = preCenElt.value.trim();
	if (txt.length == 0 || data["complexEmotions"].indexOf(txt) == -1) {
		alert("Found no complex emotion to be saved");
		return false;
	}
	return true;
}

function save() {
	if (
		validatePreCenElt() &&
		validateCEForEditor(preCenElt.value) &&
	    	validateRecursiveCE()) {
		getinfo("../../php/ce_editor.php", encodeFimo(complexEmotionContainer.querySelector(".mfe"), "e") + "&preCen=" + encodeURIComponent(preCenElt.value), data, "output", showOutput.bind(null, outputContainer));
	}
}

preCenElt.addEventListener("keyup", function () {
		getinfo("../../php/getData/ceDesc.php", "preCen=" + encodeURIComponent(preCenElt.value), data, "description", doTheMagic);
});

window.onload = function () {
		preCenElt.value = "";
}

let preValue = "";

function doTheMagic() {
	data.description = JSON.parse(data.description);
	let desc = data.description;
	let newValue = preCenElt.value.trim();
	if (preValue != newValue) {
		if (complexEmotionContainer.querySelector(".mfe")) {
		complexEmotionContainer.removeChild(complexEmotionContainer.querySelector(".mfe"));
		}
		if (desc) {
			let emoBox = createFimo("e", "basicEmotions", "complexEmotions", true, data.description);
			complexEmotionContainer.appendChild(emoBox);
		}
	}
	preValue = newValue;
}

saveButton.onclick = function() {
	save();
}

function validateRecursiveCE() {
	let cseEs = document.querySelectorAll(".cseEClass");
	let values = [];
	
	for (let i = 0; i < cseEs.length; i++) {
		values.push(cseEs[i].value);
	}
	
	let preCenValue = preCenElt.value.trim();
	let newName = document.getElementsByName("complexEmotionName")[0].value.trim();
	
	
	if (preCenValue == newName) {
		if (values.indexOf(newName) != -1) {
			alert("Recursive complex emotions are not allowed");
			return false;
		}
	} else {
		if (values.indexOf(preCenValue) != -1) {
			showWarning("The name of complex emotion \"" + preCenValue + "\" has changed and it contains the old named emotion which doesn't exist in database. It is recommended to create a complex emotion of that name or remove it as part of \"" + newName + "\" complex emotion.");
			return true;
		}
	}
	return true;
}
</script>
</body>
</html>
