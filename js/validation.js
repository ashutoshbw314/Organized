//depends on synAyn.js, type_utility.js

function validateDatumIdNotEmpty(elt, idExists) {
	if (elt.value.trim().length == 0) {
		alert("Datum Id field can't be empty");
		return false;
	}
	if (idExists == false) {
		alert("No datum of id " + elt.value.trim() + " exist in your data list");
		return false;
	}
	return true;
}

function validateDatumNotEmpty(elt) {
	if (elt.value.trim().length == 0) {
		alert("Datum field can't be empty");
		return false;
	}
	return true;
}

function validateNewWordNameNotEmpty(elt, newWordExists, isSame) {
	if (elt.value.trim().length == 0) {
		alert("New word name field can't be empty");
		return false;
	}
	if (newWordExists == true && !isSame) {
		alert("The new word name is not allowed because it's already in your word list.");
		return false;
	}
	return true;
}

function checkRC(name) {
	rcs = document.getElementsByName(name);
	for (let i = 0; i < rcs.length; i++) {
		if (rcs[i].type == "radio" && rcs[i].checked) {
			return rcs[i];
		} else if (rcs[i].checked) {
			return true;
		}
	}
	return false;
}

function validateWordExists(obj) {
	if (obj.wordExists == "0") {
		alert("Word doesn't exists!");
		return false;
	}
	return true;
}

function validateNewWordExists(obj) {
	if (obj.newWordExists == "1") {
		alert("New word name already exists!");
		return false;
	}
	return true;
}

function validateWordIsNotEmpty() {
	let value = document.getElementsByName("wordName")[0].value;
	
	if (value.trim().length == 0) {
		alert("Word name field can't be empty!");
		return false;
	}
	return true;	
}

function validateNewWordIsNotEmpty() {
	let value = document.getElementsByName("newWordName")[0].value;
	
	if (value.trim().length == 0) {
		alert("New word name field can't be empty!");
		return false;
	}
	return true;	
}

function validateFieldIsNotEmpty(field, message) {
	let value = field.value;
	
	if (value.trim().length == 0) {
		alert(message);
		return false;
	}
	return true;	
}

function validatePartsOfSpeech() {
	let r = checkRC("partsOfSpeech");
	if (r == false) {
		alert("Please select at least one parts of speech.");
		return false;
	} else {
		return true;
	}
}

function validateContext() {
	let value = document.getElementById("context").value;
	if (value.trim().length == 0) {
		alert("Context can't be empty");
		return false;
	}
	return true;	
}
/*
function validateSelectsInOuterCC(outerCC, message) {
	let i = 1;
	if (outerCC) {
		let wss = outerCC.querySelector(".mSelect");
		if (wss) {
			for (let i = 0; i < wss.childNodes.length; i++) {
				if (wss.value != "null") {
					return true;
				}
			}
		}
		alert(message);
	}
	return false;
}
*/
function validateSelectsInOuterCC(outerCC, message) {
	let i = 1;
	let spanBlocks = [];
	for (let i = 0; i < outerCC.childNodes.length; i++) {
		if(outerCC.childNodes[i].tagName != "DIV") {
			spanBlocks.push(outerCC.childNodes[i]);
		}
	}
	let allIsWell = false;
	for (let i = 0; i < spanBlocks.length; i++) {
		let sValue = spanBlocks[i].querySelector("select").value;
		let textElt = spanBlocks[i].querySelector("input");
		if (sValue != "null") {
			allIsWell = true;
			if (textElt) {
				if(!validateQuoteHash(textElt.value, 
				"Extra info is not allowed to contain quote or hash characters")) {
					return false;
				}
			}
		}
	}
	if (!allIsWell) {
		alert(message);
		return false;
	}
	return true;
}

function validateFimoNotToBeDuplicate(fimo) {
	let fimoIs;
	if (fimo == "f") {
		fimoIs = "feeling";
	} else if (fimo == "e") {
		fimoIs = "emotion";
	}
	let i = 1;
	let fimoBox;
	let array = [];
	while (fimoBox = document.getElementById(fimoIs + "_" + i)) {
		if (fimoBox.className == "basicFeeling" || fimoBox.className == "basicEmotion") {
			let dv = fimoBox.childNodes[0].value;
			let pv = fimoBox.childNodes[1].value;
			let sv = fimoBox.childNodes[3].value;
			let tv = fimoBox.childNodes[5].value;
			if (pv != "null") {
				
				let joinedValues = dv + pv + sv + tv;
				if (array.indexOf(joinedValues) != -1) {
					alert("Duplicate basic " + fimoIs + " exists! Please fix it first.");
					return false;
				} else {
					//console.log("woo ho");
					array.push(joinedValues);
					//return true;
				}
			}
		} else if (fimoBox.className == "complexFeeling" || fimoBox.className == "complexEmotion") {
			let dv = fimoBox.childNodes[0].value;
			let cv = fimoBox.childNodes[1].value;
			if (cv != "null") {
				let joinedValues = dv + cv;
				if (array.indexOf(joinedValues) != -1) {
					alert("Duplicate complex " + fimoIs + " exists! Please fix it first.");
					return false;
				} else {
					array.push(joinedValues);
					//return true;
				}
			}
		}
		i++;
	}

	return true;
	
}
/*
function validateMood() {
	let mood = document.getElementById("mood");
	if (mood.value == "null" || mood.value == "") {
		alert("Mood can't be null or empty");
		return false;
	} else {
		return true;
	}
}
*/
function validateRating() {
	if (checkRC("rating")) {
		return true;
	} else {
		alert("Please rate the word.");
		return false;
	}
}

function validateCFimoNameForEditor(fimo, oldValue) {
	let fimoIs;
	if (fimo == "f") {
		fimoIs = "feeling";
		let cfn = document.getElementsByName("complexFeelingName")[0];
		if (cfn == undefined) {
			alert("To save a complex emotion with no feelings or if you want to leave it as it is just click one of the buttons above which will selects nulls by default and give the new name. It is result of my fast codeing ;)");
			return false;
		}
		let cfValue = cfn.value.trim();
		//if (hasDoubleQuote(cfValue)) {
		//	alert("Double quotes are not allowed in complex feeling name");
		//	return false;
		//}
		if (cfValue.toLowerCase() == "null") {
			alert("\"null\" is a reserved word and is not allowed");
			return false;
		}
		if (data.complexFeelings.indexOf(cfValue) != -1 && cfValue != oldValue) {
			alert("The name \"" + cfValue.toLowerCase() + "\" is use in another complex feeling. Please give a new name");
			return false;
		} else if (cfValue == "") {
			alert("Please name your complex feeling");
			return false;
		}
	} else if (fimo == "e") {
		fimoIs = "emotion";
		let cen = document.getElementsByName("complexEmotionName")[0];
		if (cen == undefined) {
			alert("To save a complex emotion with no emotions or if you want to leave it as it is just click one of the buttons above which will selects nulls by default and give the new name. It is result of my fast codeing ;)");
			return false;
		}
		let ceValue = cen.value.trim();
		//if (hasDoubleQuote(ceValue)) {
		//	alert("Double quotes are not allowed in complex feeling name");
		//	return false;
		//}
		if (ceValue.toLowerCase() == "null") {
			alert("\"null\" is a reserved word and is not allowed");
			return false;
		}
		if (data.complexEmotions.indexOf(ceValue) != -1 && ceValue != oldValue) {
			alert("The name \"" + ceValue.toLowerCase() + "\" is use in another complex emotion. Please give a new name");
			return false;
		} else if (ceValue == "") {
			alert("Please name your complex emotion");
			return false;
		}
	}
	return true;
}

function hasDoubleQuote(text) {
	let rex = /\"/;
	return rex.test(text.trim().toLowerCase());
}

function hasHash(text) {
	let rex = /#/;
	return rex.test(text);
}

function validateQuoteHash(text, message) {
	if (hasDoubleQuote(text) || hasHash(text)) {
		alert(message);
		return false;
	}
	return true;
}

function validateCFimoName(fimo) {
	let fimoIs;
	if (fimo == "f") {
		fimoIs = "feeling";
		let cfn = document.getElementsByName("complexFeelingName")[0];
		if (cfn == undefined) {
			alert("To create a complex emotion with no feelingsjust click one of the buttons above which will select nulls by default and give a name . It is result of my fast codeing ;)");
			return false;
		}
		let cfValue = cfn.value.trim();
		if (cfValue.toLowerCase() == "null") {
			alert("\"null\" is a reserved word and is not allowed");
			return false;
		}
		//if (hasDoubleQuote(cfValue)) {
		//	alert("Double quotes are not allowed in complex feeling name");
		//	return false;
		//}
		if (data.complexFeelings.indexOf(cfValue) != -1) {
			alert("The \"" + cfValue + "\" already in your complex feeling list. Please give a new name");
			return false;
		} else if (cfValue == "") {
			alert("Please name your complex feeling");
			return false;
		}
	} else if (fimo == "e") {
		fimoIs = "emotion";
		let cen = document.getElementsByName("complexEmotionName")[0];
		if (cen == undefined) {
			alert("To create a complex emotion with no emotions just click one of the buttons above which will select nulls by default and give a name. It is result of my fast codeing ;)");
			return false;
		}
		let ceValue = cen.value.trim();
		if (ceValue.toLowerCase() == "null") {
			alert("\"null\" is a reserved word and is not allowed");
			return false;
		}
		//if (hasDoubleQuote(ceValue)) {
		//	alert("Double quotes are not allowed in complex feeling name");
		//	return false;
		//}
		if (data.complexEmotions.indexOf(ceValue) != -1) {
			alert("The \"" + ceValue + "\" already in your complex feeling list. Please give a new name");
			return false;
		} else if (ceValue == "") {
			alert("Please name your complex emotion");
			return false;
		}
	}
	return true;
}


function validateCCF() {  //CCF : Create Complex Feeling
	return (
		validateFimoNotToBeDuplicate("f")	&&
		validateCFimoName("f")		
	);
}

function validateCCE() {
	return (
		validateFimoNotToBeDuplicate("e")	&&
		validateCFimoName("e")		
	);
}

function validateCEForEditor(oldValue) {
	return (
		validateFimoNotToBeDuplicate("e")	&&
		validateCFimoNameForEditor("e", oldValue)		
	);
}

function validateCFForEditor(oldValue) {
	return (
		validateFimoNotToBeDuplicate("f")	&&
		validateCFimoNameForEditor("f", oldValue)		
	);
}

function validateSADR(s, a, d, r) {
	if (!(!hasHash(s.value) && !hasHash(a.value) && !hasHash(d.value) && !hasHash(r.value))) {
		alert("'#' is not allowed in synonyms, antonyms, derivatives and related words fields");
		return false;
	}
	return true;
}
/*
function validateForm() {
	return (
		validateWordName()	 		&&
		validatePartsOfSpeech()  		&&
		validateExamples()	 		&&
		validateCollocations()	 		&&
		validateAppropriatePreposition()	&&
		validatePhrasalVerbs()			&&
		validateIdiom()				&&
		validateSelectsInOuterCC(myLabelSelectionBox, "No lables selected")		&&
		validateSelectsInOuterCC(wordSourceSelectionBox, "No sources selected")		&&
		validateSelectsInOuterCC(wordMeaningSourceSelectionBox, "No sources selected")	&&
		validateContext()			&&
		validateRating()			&&
		validateMood()				&&
		validateFimoNotToBeDuplicate("f")	&&
		validateFimoNotToBeDuplicate("e")	
	);
}
*/

function validateNewWordNameForAddAWord(elt, newWordExists) {
	if (elt.value.trim().length == 0) {
		alert("New Word name field can't be empty");
		return false;
	}
	if (newWordExists) {
		alert("This name arealy exists in your word list");
		return false;
	}
	return true;
}

function validateSynAyn(mElt) {
	let text = mElt.textContent;
	if (text.indexOf("At least two word must be given.") != -1 ||
	    text.indexOf("Invalid synonym antonym syntax") != -1 ||
	    text.indexOf("appears multiple times. multiple existence of the same word") != -1 ||
	    text.indexOf("\" exists in another datum of \"English > Synonyms and Antonyms\" type of id") != -1) {
		alert("About the datum: " + text);
		return false;
	}
	return true;
}
