let data = {};
data.datum_type = null;

function deleteAllChilds(elt) {
	while (elt.firstChild != null) {
		elt.removeChild(elt.firstChild);
	}
}

function heading(text) {
	let h = elt("div", "h");
	h.textContent = text;
	return h;
}

function clearAllAndShowTypeAndDatum(datumText, con, col, id, manageOtherThings) {
	deleteAllChilds(con);

	if (col != "datum_type") {
		getinfo("../php/getData/getFromOrganized.php", "id=" + id + "&col=datum_type", data, 
		"datum_type", function() {
			let dtH = heading("Datum type");
			let dType = elt("div", null, "datum_type");
			dType.textContent = data.datum_type;

			con.appendChild(dtH);
			con.appendChild(dType);

			if (col != "datum") {
				let datumH = heading("Datum");
				let datum = elt("div", null, "theDatum");
				datum.textContent = datumText;

				con.appendChild(datumH);
				con.appendChild(datum);
			}

			manageOtherThings();		
		});
	} else {
		// show datum
		let datumH = heading("Datum");
		let datum = elt("div", null, "theDatum");
		datum.textContent = datumText;

		con.appendChild(datumH);
		con.appendChild(datum);

		manageOtherThings();
	}
}

let category_to_col = {
	"Datum": "datum",
	"Datum type": "datum_type",
	"My labels": "my_labels",
	"Source": "datum_source", 
	"Source detail": "datum_source_detail", 
	"Context": "context", 
	"Meaning": "meaning",
	"Examples": "examples",
	"Play": "play",
	"My note": "my_note",
	"Easyness": "easyness_intensity",
	"Emotions": "emotions"
};

function showDatumAndEditStuffs(id, category, con, showRest) {
	getinfo("../php/getData/getFromOrganized.php", "id=" + id + "&col=datum", data, "datum", function() {
		let col = category_to_col[category];
		clearAllAndShowTypeAndDatum(data.datum, con, col, id, function() {
			getinfo("../php/getData/getFromOrganized.php", "id=" + id + "&col=" + col , data, col,
			function () { 
				if (col == "my_labels" || col == "emotions") {
					data[col] = JSON.parse(data[col]);
				} else if (col == "datum_type") {
					if (data[col] != "") {
						data[col] = data[col].split(" > ");
					} else {
						data[col] = [];
					}
				}
				showRest.bind(null, col)();
			});
		});
	});
}


getinfo("../php/getData/myLabels.php", "" , data, "allMyLabels", handleAllMyLabels);
getinfo("../php/getData/datumSources.php", "" , data, "datumSources", handleDatumSources);
getinfo("../php/getData/basicEmotions.php", "" , data, "basicEmotions", handleBasicEmotions);
getinfo("../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleComplexEmotions);



data["datumSourcesUpdateTimes"] = 0; 
function handleDatumSources() {
	let sSelect = document.querySelector("#sSelect");
	data.datumSources = JSON.parse(data.datumSources);
	if (sSelect) {
		updateSelectTagSpecial(sSelect, data.datumSources, null, "datumSourcesUpdateTimes");
	}
	setTimeout(function() {
		getinfo("../php/getData/datumSources.php", "" , data, "datumSources", handleDatumSources);
	}, 500);
}

function handleAllMyLabels() {
	data.allMyLabels = JSON.parse(data.allMyLabels);
	let my_labels_con = document.getElementById("my_labels");
	if (my_labels_con != null) {
		let multiSelects = my_labels_con.querySelectorAll(".multiSelect");
		for (let i = 0; i < multiSelects.length; i++) {
			updateSelectTag(multiSelects[i], data.allMyLabels);
		}
	}
	setTimeout(function() {
		getinfo("../php/getData/myLabels.php", "" , data, "allMyLabels", handleAllMyLabels);
	}, 500);
}

function handleBasicEmotions() {
	data.basicEmotions = JSON.parse(data.basicEmotions);
	let emoBox = document.querySelector("#emotions");
	if (emoBox) {
		putBasicOnesInUse(emoBox.parentElement, data, "e");
	}
	setTimeout(function() {
		getinfo("../php/getData/basicEmotions.php", "" , data, "basicEmotions", handleBasicEmotions);
	}, 500);
}

function handleComplexEmotions() {
	data.complexEmotions = JSON.parse(data.complexEmotions);
	let emoBox = document.querySelector("#emotions");
	if (emoBox) {
		putComplexOnesInUse(emoBox.parentElement, data, "e");
	}
	setTimeout(function() {
		getinfo("../php/getData/complexEmotions.php", "" , data, "complexEmotions", handleComplexEmotions);
	}, 500);
}

function save(id, category, elt) {
	let col = category_to_col[category];
	let outputCon = document.querySelector("#outputContainer");
	if (col == "emotions") {
		if (validateFimoNotToBeDuplicate("e")) {
			getinfo("../php/editorForAnyCol.php", "id=" + id + "&col=" + col + "&cat=" + category + "&" + encodeByCol(col, elt), data, "output", showOutput.bind(null, outputCon));
		} else return;
	} else if (col == "datum" && data.datum_type == "English > Synonyms and Antonyms") {

		getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAynsForSave", checkAllSynsAynsForSave.bind(null, id, col, category, elt, outputCon));

	} else if (col == "datum_type") {

		let typeCon = document.querySelector("#datum_type");
		if (typeCon) {
			let datumType = extractFullType(typeCon);
			if (datumType == "English > Synonyms and Antonyms") {
				getinfo("../php/getData/getAllSynsAyns.php", "" , data, "allSynsAynsForSavingType", checkAllSynsAynsForSavingType.bind(null, id, col, category, elt, outputCon));
			} else {
				getinfo("../php/editorForAnyCol.php", "id=" + id + "&col=" + col + "&cat=" + category + "&" + encodeByCol(col, elt), data, "output", showOutput.bind(null, outputCon));
			}
		} else {
			alert("Things are not ready yet. Please wait.");
			return;
		}

	} else {
		//console.log("saving from outside");
		getinfo("../php/editorForAnyCol.php", "id=" + id + "&col=" + col + "&cat=" + category + "&" + encodeByCol(col, elt), data, "output", showOutput.bind(null, outputCon));
	}
}

function createEditWidget(id, category, conElt) {
	let innerCon = conElt.querySelector("#editStuffsCon");
	let encodedColValue = "";
	showDatumAndEditStuffs(id, category, innerCon, function(col) {
		if (col == "meaning" || col == "examples" || col == "play" || col == "my_note" || col == "context" || col == "datum" || col == "datum_source_detail") {
			let colH = heading(category);
			let editElt = elt(col != "datum_source_detail" ? "textarea" : "input", null, col);
			if (col == "datum_source_detail") {
				editElt.type = "text";
			}
			editElt.value = data[col];
			innerCon.appendChild(colH);
			innerCon.appendChild(editElt);
			if (col == "datum") {
				let dMessage = elt("span", null, "datumMessage");
				innerCon.appendChild(dMessage);
			}
		} else if (col == "easyness_intensity") {		
			let easyH = heading(category);
			let intensity = elt("div", null, "easynessIntensity");
			let inputBox = elt("div", null, "easynessInputBox");
			let spanL = elt("span");
			let spanR = elt("span");
			let range = elt("input", null, col);
			
			let intensityValue = "0";
			if (data[col].length != 0) {
				intensityValue = data[col];
			}

			intensity.textContent = intensityValue;
			spanL.textContent = "Very hard";
			spanR.textContent = "Very easy";
			range.type = "range";
			range.min = -100;
			range.max = 100;
			range.value = intensityValue;
			range.step = 1;
			
			range.oninput = function() {
				intensity.textContent = range.value;
			}

			innerCon.appendChild(easyH);
			innerCon.appendChild(intensity);
			inputBox.appendChild(spanL);
			inputBox.appendChild(range);
			inputBox.appendChild(spanR);
			innerCon.appendChild(inputBox);
		} else if (col == "datum_type") {
			let dtH = heading(category);
			let datumType = elt("div", null, col);
			repSelects(type, datumType, data[col]);
			innerCon.appendChild(dtH);
			innerCon.appendChild(datumType);		
		} else if (col == "my_labels") {
			let mlH = heading(category);
			let mlBox = createSourceSelectionBox("allMyLabels", "ml", false, data[col]);
			mlBox.id = col;
			innerCon.appendChild(mlH);
			innerCon.appendChild(mlBox);
		} else if (col == "emotions") {
			let emoH = heading(category);
			let emoBox = createFimo("e", "basicEmotions", "complexEmotions", false, data[col]);
			emoBox.id = col;
			innerCon.appendChild(emoH);
			innerCon.appendChild(emoBox);
		} else if (col == "datum_source") {
			let sourceH = heading(category);
			let sSelect = elt("select", null, col);
			innerCon.appendChild(sourceH);
			innerCon.appendChild(sSelect);
			data["datumSourcesUpdateTimes"] = 0; 
			updateSelectTagSpecial(sSelect, data.datumSources, null, "datumSourcesUpdateTimes");
			
			if (data.datumSources.indexOf(data[col]) == -1 && data[col].length != 0) {
				showWarning("\"" + data[col] + "\" doesn't exist in your dataum source list");
			}
			if (data[col] != "") { 
				sSelect.value = data[col];
			} else {
				sSelect.value = "null";
			}
		}

		let saveButton = conElt.querySelector("#saveButton");
		if (saveButton == null) {
			saveButton = elt("button", null, "saveButton");
			saveButton.type = "button";
			saveButton.textContent = "Save";
			conElt.appendChild(saveButton);
		}

		saveButton.onclick = save.bind(null, id, category, document.querySelector("#" + col));
	});
}


