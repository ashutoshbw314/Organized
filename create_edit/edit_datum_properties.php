<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Edit your labels of a datum</title>
<link rel="stylesheet" type="text/css" href="../css/datumThings.css"/>
<link rel="stylesheet" type="text/css" href="../css/multiSelect.css"/>
<link rel="stylesheet" type="text/css" href="../css/output.css"/>
<link rel="stylesheet" type="text/css" href="../css/edit.css"/>
<link rel="stylesheet" type="text/css" href="../css/type.css"/>
<link rel="stylesheet" type="text/css" href="../css/fimo.css"/>
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
<h1>Edit anything</h1>
<article>
<a href="../index.php">Back to home</a> <br><br>
<div class="h">Specify what you want to edit</div>
<span>I want to edit the <select id="editCategorySelect"></select> of the datum of id <input type="text" id="datumId" autocomplete="off"/> </span> <span id="message"></span>

<div id="editCon">
<div id="editStuffsCon"></div>

</div>

<div id="outputContainer">
<div class="h">Outputs</div>
<div id="outputContent">

</div>
</div>

</article>
<script src="../js/ajax.js"></script>
<script src="../js/fimo.js"></script>
<script src="../js/encode.js"></script>
<script src="../js/validation.js"></script>
<script src="../js/update.js"></script>
<script src="../js/output.js"></script>
<script src="../js/warning.js"></script>
<script src="../js/sourceSelectionBox.js"></script>
<script src="../js/utility.js"></script>
<script src="../js/type.js"></script>
<script src="../js/type_utility.js"></script>
<script src="../js/synAyn.js"></script>
<script src="../js/edit.js"></script>
<script>
let ecSelect = document.getElementById("editCategorySelect");
let datumIdElt = document.getElementById("datumId");
let editCon = document.getElementById("editCon");
let editStuffsCon =  document.getElementById("editStuffsCon");
let message =  document.getElementById("message");
let es = [
"Datum",
"Datum type",
"My labels",
"Source",
"Source detail",
"Context",
"Meaning",
"Examples",
"Play",
"My note",
"Easyness",
"Emotions"
];

setOptions(ecSelect, es);
data.activeId = null;

function launchEditWidget(mayBeId, category, editCon) {
	let intReg = /^\d+$/;
	let id = null;
	if (intReg.test(mayBeId.trim())) {
		id = mayBeId.trim();
	}
	if ((id == null && mayBeId.trim().length != 0) || id == "0") {
		message.textContent = "(invalid id)";
		deleteAllChilds(editStuffsCon);
		let saveButton = editCon.querySelector("#saveButton");
		if (saveButton) {
			editCon.removeChild(saveButton);
		}
		data.activeId = null;
	} else if (mayBeId.trim().length != 0) {
		getinfo("../php/getData/idExists.php", "id=" + id, data, "idExists", function() {
			data.idExists = JSON.parse(data.idExists);
			if (data.idExists && category != "null") {
				createEditWidget(id, category, editCon);
				data.activeId = id;
			} else {
				deleteAllChilds(editStuffsCon);
				let saveButton = editCon.querySelector("#saveButton");
				if (saveButton) {
					editCon.removeChild(saveButton);
				}
				if (data.idExists == false) {
					message.textContent = "(id doesn't exist)";
					data.activeId = null;
				}
			}
		});
		
	} else {  
		deleteAllChilds(editStuffsCon);
		let saveButton = editCon.querySelector("#saveButton");
		if (saveButton) {
			editCon.removeChild(saveButton);
		}
		data.activeId = null;
	}
}

ecSelect.oninput = function() {
	launchEditWidget(datumIdElt.value, ecSelect.value, editCon);
};


function reallyLaunchEditWidget() {
	if (ecSelect.value == "null") {
		data.datum_type == null;
	}
	let newValue = datumIdElt.value.trim();
	if (preValue != newValue) {
		message.textContent = "";
//		console.log("P: " + preValue + ", N: " + newValue);
		launchEditWidget(datumIdElt.value, ecSelect.value, editCon);
	}
	preValue = newValue;
	setTimeout(reallyLaunchEditWidget, 500);
}

let preValue = "";
reallyLaunchEditWidget()

/*************** handle synonyms and antonyms ******************/
getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAyns", checkAllSynsAyns);

function checkAllSynsAyns() {
	if (typeof data.allSynsAyns == "string") {
		data.allSynsAyns = JSON.parse(data.allSynsAyns);
	}

	
	let datumElt = document.querySelector("#datum");
	let dMessage = null;
	let datumTypeElt = document.querySelector("#datum_type");
	let datumType = null;
	if (datumElt) {
		datumType = datumTypeElt.textContent;
		dMessage = document.querySelector("#datumMessage");
	}

	if (datumElt != null && dMessage != null && datumType == "English > Synonyms and Antonyms") {
		if (datumElt.value.trim().length != 0 &&
		    checkSynAynSyntax(datumElt.value, dMessage) &&
		    data.activeId != null) {
			let words = extract_all_words_of_an_entry(getSynAynArray(datumElt.value));
			for (let i = 0; i < data.allSynsAyns.length; i++) {
				let curObj = data.allSynsAyns[i];
			        if (curObj.id != data.activeId) {
					let curSynAynArr = getSynAynArray(curObj.synAyn);
					let curWords = extract_all_words_of_an_entry(curSynAynArr);
					for (let j = 0; j < words.length; j++) {
						if (curWords.indexOf(words[j]) != -1) {
							dMessage.textContent = "\"" + words[j] + "\" exists in another datum of \"English > Synonyms and Antonyms\" type of id " + curObj.id;
							setTimeout(function() {
								getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAyns", checkAllSynsAyns);
							}, 500);
							return;
						}
					}
				}
			}
			if (datumElt.value.match(/[^,|\s]+/g).length < 2) {
				dMessage.textContent = "At least two word must be given.";
			}
		}
	}
	if (dMessage) {
		if (dMessage.textContent.indexOf("exists in another datum of \"English > Synonyms and Antonyms\" type of id") != -1) {
			dMessage.textContent = "";
		}
	}
	setTimeout(function() {
		getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAyns", checkAllSynsAyns);
	}, 500);
}

</script>
</body>
</html>
