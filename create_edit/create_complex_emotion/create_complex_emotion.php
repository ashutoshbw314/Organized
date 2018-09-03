<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Create Complex Emotion</title>
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
<h1>Create Complex Emotion</h1>
<article>
<a href="../../index.php">Back to home</a> <br><br>
<div class="h">Create complex emotion</div>
<div id="complexEmotionContainer"></div>
<button type="button" id="createButton">Create</button>
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
let emotionContainer = createFimo("e", "basicEmotions", "complexEmotions", true);

let createButton = document.getElementById("createButton");

complexEmotionContainer.appendChild(emotionContainer);


getinfo("../../php/getData/basicEmotions.php", "" , data, "basicEmotions", handleBasicEmotionsCrazyFuncName);
getinfo("../../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleInput);


function handleBasicEmotionsCrazyFuncName() {
	putBasicOnesInUse(emotionContainer, data, "e");
	setTimeout(function() {
		getinfo("../../php/getData/basicEmotions.php", "" , data, "basicEmotions", handleBasicEmotionsCrazyFuncName);
	}, 500);
}

function handleInput() {
	putComplexOnesInUse(emotionContainer, data, "e");
	let complexEmotion = complexEmotionContainer.querySelector(".mfe");
	if (complexEmotion) {
		let nameElt = complexEmotion.querySelector("input[type='text']");
		if (nameElt) {
			let message1 = nameElt.nextElementSibling;
			if (nameElt.value.trim() != "" &&
			    data.complexEmotions.indexOf(nameElt.value.trim()) != -1) {
				message1.textContent = "not availabe";
			} else {
				message1.textContent = "";
			}
			if (nameElt.value.trim().toLowerCase() == "null") {
				message1.textContent = "\"null\" is a reserved word and is not allowed";
			}
		}
	}
	setTimeout(function() {
		getinfo("../../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleInput);
	}, 500);	
}


function createCE(validateFunc) {
	if (validateFunc()) {
		getinfo("../../php/ce_creator.php", encodeFimo(emotionContainer, "e"), data, "output", showOutput.bind(null, outputContainer));
	}
}

createButton.onclick = function() {
	createCE(validateCCE);
}
</script>
</body>
</html>
