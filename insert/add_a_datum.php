<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Add a new datum</title>
<link rel="stylesheet" type="text/css" href="../css/fimo.css"/>
<link rel="stylesheet" type="text/css" href="../css/output.css"/>
<link rel="stylesheet" type="text/css" href="../css/datumThings.css"/>
<link rel="stylesheet" type="text/css" href="../css/multiSelect.css"/>
<link rel="stylesheet" type="text/css" href="../css/type.css"/>
<style>

h1 {
	text-align: center;
	margin-left: 20px;
}

article, #MEP, #source_and_detail {
	width: 100%;
	display: flex;
	align-items: stretch;
}

article > div, #MEP > div  {
	width: 100px;
}

#leftSide {
	flex-grow: 0.8;

}

#rightSide {
	flex-grow: 2;
	border-left: 1px solid #666;
	padding-left: 7px;
}

#bottom {
	margin-top: 10px;
}

select {
 	height: 40px;
	line-height: 15px;
	font-size: 15px;
}

#meaningCon {
	flex-grow: 1;
}

#examplesCon {
	flex-grow: 1;
	border-left: 1px solid #666;
	padding-left: 7px;
}

#playCon {
	flex-grow: 1;
	border-left: 1px solid #666;
	padding-left: 7px;
}

#sSelect {
	flex-grow: 1;
	margin: 0 5px 0 0;
	width: 150px;
}

#sDetail {
	flex-grow: 1;
	width: 50px;
	margin: 0 6px 0 0;
}

#leftSide > :not(#firstLeftDiv), #rightSide > :not(#MEP) {
	margin-top: 10px;
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
 
<h1>Add a datum</h1>
<a href="../index.php">Back to home</a> <br><br>
<article>
<div id="leftSide">
	<div id="firstLeftDiv">
		<div class="h">Datum type</div>
		<div id="typeCon"></div>
	</div>
	
	<div>
		<div class="h">* Datum</div>
		<textarea id="newDatumName"></textarea> <span id="message"></span>
	</div>

	<div>
		<div class="h">My labels</div>
		<div id="myLabelsContainer"></div>
	</div>

	<div>
		<div class="h">Source & context</div>
		<div id="source_and_detail">
			<select id="sSelect"></select>
			<input type="text" id="sDetail"/>
		</div>
		<textarea id="context" name="context"></textarea>
	</div>
</div>

<div id="rightSide">
	<div id="MEP">
		<div id="meaningCon">
			<div class="h">Meaning</div>
			<textarea id="meaning"></textarea>
		</div>
		<div id="examplesCon">
			<div class="h">Examples</div>
			<textarea id="examples"></textarea>
		</div>
		<div id="playCon">
			<div class="h">Play</div>
			<textarea id="play"></textarea>
		</div>
	</div>

	<div>
		<div class="h">My note about the datum</div>
		<textarea id="note" name="note"></textarea>
	</div>

	<div>
		<div class="h">Easyness</div>
		<div id="easynessIntensity">0</div>
		<div id="easynessInputBox">
			<span>Very hard</span>
			<input type="range" min="-100" max="100" value="0" step="1" id="easynessInput"/>
			<span>Very easy</span>
		</div>
	</div>

	<div>
		<div class="h">Emotions</div>
		<div id="emotionContainer"></div>
	</div>
</div>
</article>

<div id="bottom">
	<button type="button" id="addDatumButton">Add the datum</button>
	<div id="outputContainer">
	<div class="h">Outputs</div>
	<div id="outputContent"></div>
	</div>
</div>
<script>
let data = {};
</script>
<script src="../js/ajax.js"></script>
<script src="../js/fimo.js"></script>
<script src="../js/encode.js"></script>
<script src="../js/output.js"></script>
<script src="../js/update.js"></script>
<script src="../js/sourceSelectionBox.js"></script>
<script src="../js/utility.js"></script>
<script src="../js/warning.js"></script>
<script src="../js/type.js"></script>
<script src="../js/type_utility.js"></script>
<script src="../js/synAyn.js"></script>
<script src="../js/validation.js"></script>
<script>
let message = document.getElementById("message");
/**************  All datum related elements START *************/
// datum name
let newDatumNameElt = document.getElementById("newDatumName");

// datum type
let typeCon = document.getElementById("typeCon");
repSelects(type, typeCon, []);

// My labels
let myLabelsContainer = document.getElementById("myLabelsContainer");

// Source of datum
let sSelect = document.getElementById("sSelect");
let sDetail = document.getElementById("sDetail");

// Context
let context = document.getElementById("context");

// meaning
let meaning = document.getElementById("meaning");

// examples
let examples = document.getElementById("examples");

// play
let play = document.getElementById("play");

// Easyness
let easynessInput = document.getElementById("easynessInput");
let easynessIntensity = document.getElementById("easynessIntensity");

// Emotion

let emotionContainer = document.getElementById("emotionContainer");
let emotionTools = createFimo("e", "basicEmotions", "complexEmotions", false);

emotionContainer.appendChild(emotionTools);

// My Note
let note = document.getElementById("note");
/**************  All datum related elements END ***************/
let addDatumButton = document.getElementById("addDatumButton");


function newDatumName() {
	return newDatumNameElt.value;
}

easynessInput.oninput = function() {
	easynessIntensity.textContent =  easynessInput.value;
}


function refresh() {
	getinfo("../php/getData/newDatumExists.php", "newDatumName=" + encodeURIComponent(newDatumName()) , data, "newDatumExists", handleNewDatumName);
	getinfo("../php/getData/myLabels.php", "" , data, "myLabels", handleMyLabels);
	getinfo("../php/getData/datumSources.php", "" , data, "datumSources", handleDatumSources);

	getinfo("../php/getData/basicEmotions.php", "" , data, "basicEmotions", handleBasicEmotions);
	getinfo("../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleComplexEmotions);
	getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAyns", checkAllSynsAyns.bind(null, true));
}

function handleBasicEmotions() {
	putBasicOnesInUse(emotionContainer, data, "e");
	setTimeout(function() {
		getinfo("../php/getData/basicEmotions.php", "" , data, "basicEmotions", handleBasicEmotions);
	}, 500);	
}

function handleComplexEmotions() {
	putComplexOnesInUse( emotionContainer, data, "e");
	setTimeout(function() {
		getinfo("../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleComplexEmotions);
	}, 500);	
}

function checkAllSynsAyns(repeat, func) {
	if (typeof data.allSynsAyns == "string") {
		data.allSynsAyns = JSON.parse(data.allSynsAyns);
	}
	if (extractFullType(typeCon) == "English > Synonyms and Antonyms") {
		if (newDatumNameElt.value.trim().length != 0 &&
		    checkSynAynSyntax(newDatumNameElt.value, message)) {
			let words = extract_all_words_of_an_entry(getSynAynArray(newDatumNameElt.value));
			for (let i = 0; i < data.allSynsAyns.length; i++) {
				let curObj = data.allSynsAyns[i];
				let curSynAynArr = getSynAynArray(curObj.synAyn);
				let curWords = extract_all_words_of_an_entry(curSynAynArr);
				for (let j = 0; j < words.length; j++) {
					if (curWords.indexOf(words[j]) != -1) {
						message.textContent = "\"" + words[j] + "\" exists in another datum of \"English > Synonyms and Antonyms\" type of id " + curObj.id;
						if (repeat) {
							setTimeout(function() {
								getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAyns", checkAllSynsAyns.bind(null, true));
							}, 500);
						} else {
							func();
						}
						return;
					}
				}
			}
			if (newDatumNameElt.value.match(/[^,|\s]+/g).length < 2) {
				message.textContent = "At least two word must be given.";
			}
		}
	}
	if (message.textContent.indexOf("exists in another datum of \"English > Synonyms and Antonyms\" type of id") != -1) {
		message.textContent = "";
	}
	if (repeat) {
		setTimeout(function() {
			getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAyns", checkAllSynsAyns.bind(null, true));
		}, 500);
	} else {
		func();
	}
}

function handleNewDatumName() {
	data.newDatumExists = JSON.parse(data.newDatumExists);
	if (newDatumName().trim().length != 0) {
		if (data.newDatumExists && extractFullType(typeCon) != "English > Synonyms and Antonyms") {
			message.textContent = " the datum already exists. It's okay to have multiple similar datum except for synonyms and antonyms type. Just make sure that they don't represent the same thing.";	
		} else if (extractFullType(typeCon) != "English > Synonyms and Antonyms") {
			message.textContent = "";
		}
	} else {
		message.textContent = "";
	}
	setTimeout(function() {
		getinfo("../php/getData/newDatumExists.php", "newDatumName=" + encodeURIComponent(newDatumName()) , data, "newDatumExists", handleNewDatumName);
	}, 500);
}

function handleMyLabels() {
	data.myLabels = JSON.parse(data.myLabels);
	
	if (!myLabelsContainer.querySelector(".outerCC")) {
		let myLabelsBox = createSourceSelectionBox("myLabels", "ml", false);
		myLabelsContainer.appendChild(myLabelsBox);
	} else {
		let myLabelsBox = myLabelsContainer.querySelector(".outerCC");
		let myLabelSelects = myLabelsBox.querySelectorAll(".multiSelect")
		for (let i = 0; i < myLabelSelects.length; i++) {
			updateSelectTag(myLabelSelects[i], data.myLabels);
		} 
	}
	setTimeout(function() {
		getinfo("../php/getData/myLabels.php", "" , data, "myLabels", handleMyLabels);
	}, 500);
}

data["datumSourcesUpdateTimes"] = 0; 
function handleDatumSources() {
	data.datumSources = JSON.parse(data.datumSources);
	updateSelectTagSpecial(sSelect, data.datumSources, null, "datumSourcesUpdateTimes");
	setTimeout(function() {
		getinfo("../php/getData/datumSources.php", "" , data, "datumSources", handleDatumSources);
	}, 500);
}

sSelect.oninput = function() {
	if (sSelect.value == "null") {
		sDetail.disabled = true;
	} else {
		sDetail.disabled = false;
	}
}


addDatumButton.onclick = function() {
	if (validateDatumNotEmpty(newDatumNameElt)) {
		checkAllSynsAyns(false, function() {
			if (validateSynAyn(message) && validateFimoNotToBeDuplicate("e")) {
				getinfo("../php/add_to_organized.php", getAllEncodedResult(), data, "output", showOutput.bind({}, outputContainer));
			}
		});	
	}
}
/*
addDatumButton.onclick = function() {
	if (validateAll()) {
		getinfo("../php/add_to_organized.php", getAllEncodedResult(), data, "output", showOutput.bind({}, outputContainer));
	};
}
*/
window.onload = function () {
	refresh();
	//clear new datum name
	newDatumNameElt.value = "";

	// clears source detail
	sDetail.disabled = true;
	sDetail.value = "";

	// clears meaning, examples, and play
	meaning.value = "";	
	examples.value = "";
	play.value = "";
	
	//clears context
	context.value = "";

	//clears easyness
 	easynessInput.value = 0;

	// clears my note
	note.value = "";
}

function getAllEncodedResult() {
	let  myLabelsBox = myLabelsContainer.querySelector(".outerCC");
	return encodeAll(newDatumNameElt, 
		typeCon,
		myLabelsBox,
		sSelect, sDetail,//wsBox,
		context,
		meaning,
		examples,
		play,
		easynessInput,
		emotionTools, 
		note);
}
</script>

</body>
</html>
