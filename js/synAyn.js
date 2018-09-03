function extractParts(text) {
	let parts = text.split(",");
	for (let i = 0; i < parts.length; i++) {
		parts[i] = parts[i].trim();
	}
	return parts;
}

function getSynAynArray(text) {
	let r = /^\s*((( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)|\|(( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)|(( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)\|(( *[^\s,|]+ *)(,( *[^\s,|]+ *))*))((\r?\n)+\s*((( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)|\|(( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)|(( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)\|(( *[^\s,|]+ *)(,( *[^\s,|]+ *))*)))*\s*$/;
	let array = [];
	
	if (!r.test(text)) {
		return null;
	} else {
		let lines = text.split(/\r?\n/);
		for (let i = 0; i < lines.length; i++) {
			let obj = {};
			let line = lines[i].trim();
			if (line.length != 0) {
				let synAyns = line.split("|");
				if (synAyns.length == 1) {
					obj.a = extractParts(synAyns[0]);
					obj.b = null;
				} else {
					if (synAyns[0].trim().length == 0) {
						obj.a = null;
						obj.b = extractParts(synAyns[1]);
					} else {
						obj.a = extractParts(synAyns[0]);
						obj.b = extractParts(synAyns[1]);
					}
				}
			}
			array.push(obj);
		}
		return array;
	}
}

function extract_all_words_of_an_entry(arr) {
	let words = [];
	if (arr == null) {
		return words;
	}

	for (let i = 0; i < arr.length; i++) {
		let obj = arr[i];
		if (obj.a) {
			for (let j = 0; j < obj.a.length; j++) {
				words.push(obj.a[j]);
			}
		}

		if (obj.b) {
			for (let j = 0; j < obj.b.length; j++) {
				words.push(obj.b[j]);
			}
		}
	}
	return words;
}

function check_for_multiple_existence_of_word(words) {
	for (let i = 0; i < words.length; i++) {
		let word = words[i];
		let times = 1;
		for (let j = 0; j < words.length; j++) {
			if (i != j && word.toLowerCase() == words[j].toLowerCase()) {
				times++;
				return word;
			}
		}
	}
	return false;
}

function checkSynAynSyntax(text, mElt) {
	if (text.trim().length != 0) {
		let synAynArr = getSynAynArray(text);
		if (synAynArr == null) {
			mElt.textContent = "Invalid synonym antonym syntax";
			return false;
		}
		let words = extract_all_words_of_an_entry(synAynArr);
		let theWord = check_for_multiple_existence_of_word(words);
		if (theWord) {
			mElt.textContent = "\"" +theWord + "\" appears multiple times. multiple existence of the same word is not allowed";	
			return false;
		}
	}

	if (mElt.textContent.indexOf("exists in another datum of \"English > Synonyms and Antonyms\" type of id") == -1) {
		mElt.textContent = "";
	}
	return true;
}

function checkAllSynsAynsForSave(id, col, category, elt, outputCon) {
	if (typeof data.allSynsAynsForSave == "string") {
		data.allSynsAynsForSave = JSON.parse(data.allSynsAynsForSave);
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
			for (let i = 0; i < data.allSynsAynsForSave.length; i++) {
				let curObj = data.allSynsAynsForSave[i];
			        if (curObj.id != data.activeId) {
					let curSynAynArr = getSynAynArray(curObj.synAyn);
					let curWords = extract_all_words_of_an_entry(curSynAynArr);
					for (let j = 0; j < words.length; j++) {
						if (curWords.indexOf(words[j]) != -1) {
							dMessage.textContent = "\"" + words[j] + "\" exists in another datum of \"English > Synonyms and Antonyms\" type of id " + curObj.id;
							validateSynAyn(dMessage);
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

	if (dMessage.textContent.indexOf("exists in another datum of \"English > Synonyms and Antonyms\" type of id") != -1) {
		dMessage.textContent = "";
	}

	// save statement below
	//console.log("Saving from  syn ayn");
	if (validateSynAyn(dMessage)) {
		getinfo("../php/editorForAnyCol.php", "id=" + id + "&col=" + col + "&cat=" + category + "&" + encodeByCol(col, elt), data, "output", showOutput.bind(null, outputCon));
	}

}

function checkAllSynsAynsForSavingType(id, col, category, elt, outputCon) {
	if (typeof data.allSynsAynsForSavingType == "string") {
		data.allSynsAynsForSavingType = JSON.parse(data.allSynsAynsForSavingType);
	}

	
	let datumElt = document.querySelector("#theDatum");
	let dMessage = document.createElement("span");
	let datumTypeElt = document.querySelector("#datum_type");
	let datumType = extractFullType(datumTypeElt);

	if (datumElt != null && dMessage != null && datumType == "English > Synonyms and Antonyms") {
		if (datumElt.textContent.trim().length != 0 &&
		    checkSynAynSyntax(datumElt.textContent, dMessage) &&
		    data.activeId != null) {
			let words = extract_all_words_of_an_entry(getSynAynArray(datumElt.textContent));
			for (let i = 0; i < data.allSynsAynsForSavingType.length; i++) {
				let curObj = data.allSynsAynsForSavingType[i];
			        if (curObj.id != data.activeId) {
					let curSynAynArr = getSynAynArray(curObj.synAyn);
					let curWords = extract_all_words_of_an_entry(curSynAynArr);
					for (let j = 0; j < words.length; j++) {
						if (curWords.indexOf(words[j]) != -1) {
							dMessage.textContent = "\"" + words[j] + "\" exists in another datum of \"English > Synonyms and Antonyms\" type of id " + curObj.id;
							validateSynAyn(dMessage);
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

	if (dMessage.textContent.indexOf("exists in another datum of \"English > Synonyms and Antonyms\" type of id") != -1) {
		dMessage.textContent = "";
	}

	// save statement below
	//console.log("Saving from  syn ayn");
	//console.log(dMessage);
	if (validateSynAyn(dMessage)) {
		getinfo("../php/editorForAnyCol.php", "id=" + id + "&col=" + col + "&cat=" + category + "&" + encodeByCol(col, elt), data, "output", showOutput.bind(null, outputCon));
	}
}
