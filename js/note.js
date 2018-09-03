// depends on utility.js, ajax.js, activity.js(nameDate function), getDatumInfo.js, encode.js
// need data variable to hold data from mysql

function createDataWidget(data, curType) {
	let dataWidget = elt("div", null, "dataWidget");
	let dataH = elt("div", null, "dataH");
	dataH.textContent = curType + " added on this day:"; 
	 
	let dataCon = elt("div", null, "dataCon");
	
	let datumInfoBigCon = elt("div", null, "datumInfoBigCon");
	let datumInfoH = elt("div", null, "datumInfoH");
	datumInfoH.textContent = "Information about the selected " + curType;	// Have to make interactive
	
	// Add datum info here.
	for (let i = 0; i < data.length; i++) {
		let datum = elt("span", "datum");
		let shortDatum = "";
		if (data[i].datum.length > 50) {
			shortDatum = data[i].datum.slice(0, 50) + "...";
		} else {
			shortDatum = data[i].datum;
		}

		if (i != data.length - 1) {
			let comma = document.createTextNode(", ");
			datum.textContent = shortDatum;
			dataCon.appendChild(datum);
			dataCon.appendChild(comma);			
		} else {
			datum.textContent = shortDatum;
			dataCon.appendChild(datum);
		}

		datum.addEventListener("click", function() {
			let datumElts = document.querySelectorAll(".datum");
			for (let i = 0; i < data.length; i++) {
				if(datumElts[i] != datum) {
					datumElts[i].style.background = "";
				}
			}
			let datumInfoCon = datumInfoBigCon.querySelector("#datumInfoCon");
			if (datumInfoCon != null) {
				datumInfoBigCon.removeChild(datumInfoCon);
				getDatumInfo(data[i].id, display, datumInfoBigCon);
			} else {
				getDatumInfo(data[i].id, display, datumInfoBigCon);
			}
			datum.style.background = "linear-gradient(141deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%)";
		});

	}

	dataWidget.appendChild(dataH);
	dataWidget.appendChild(dataCon);
	dataWidget.appendChild(datumInfoH);
	dataWidget.appendChild(datumInfoBigCon);
	return dataWidget;
}


function createDayWindow(noteText, day, phpForSave, phpForDiscard, phpForData, typeCon) {  // in case where no note, just pass null to that argument
	let dayWindowCon = elt("div", "dayWindowCon");
	let dayWindowWrap= elt("div", "dayWindowWrap");
	let closeButton = elt("div", null, "closeButton");
	closeButton.textContent = "+";

	closeButton.onclick = function(event) {
		let revertButton = dayWindowCon.querySelector(".revertNoteButton");
		let saveButton = dayWindowCon.querySelector(".saveNoteButton");
		if (revertButton && revertButton.disabled == false) {
			let flag = confirm("You have unsaved note. It will be lost if you close the window before saving. Are you sure?");
			if (flag) {
				document.body.removeChild(dayWindowCon);
			}
		} else if (revertButton == null && saveButton != null) {
			let textarea = dayWindowCon.querySelector(".noteTextarea");
			if (textarea.value != "") {
				let flag = confirm("You have unsaved note. It will be lost if you close the window before saving. Are you sure?");
				if (flag) {
					document.body.removeChild(dayWindowCon);
				}
			} else {
				document.body.removeChild(dayWindowCon);
			}
		} else {
			document.body.removeChild(dayWindowCon);
		}
	};

	let dayWindow = elt("div", null, "dayWindow");	

	let dateH = elt("div", null, "dateH");
	dateH.textContent = nameDate(day.id);
	
	let noteH = elt("div", null, "noteH");
	noteH.textContent = "Note of the day:";

	dayWindowWrap.appendChild(noteH);

	
	dayWindowWrap.appendChild(createNoteWidget(noteText, day, phpForSave, phpForDiscard));

	getinfo(phpForData, "date=" + day.id + "&" + encodeTypeInfo(typeCon), data, "data", function() {
		data.data = JSON.parse(data.data);
		// have to write function to sort data.data array based on data.data.datum

		data.data.sort(function(a, b){
		    var nameA = a.datum.toLowerCase(), nameB = b.datum.toLowerCase()
		    if (nameA < nameB) //sort string ascending
			return -1 
		    if (nameA > nameB)
			return 1
		    return 0 //default return value (no sorting)
		});

		dayWindowWrap.appendChild(createDataWidget(data.data, getCurrentType(typeCon)));
	});

	dayWindow.appendChild(dateH);
	dayWindow.appendChild(closeButton);
	dayWindow.appendChild(dayWindowWrap);	
	
	dayWindowCon.appendChild(dayWindow);

	return dayWindowCon;
}


function findWidth(text) {
	let span = elt("span");
	span.textContent = text;
	span.style.position = "fixed";
	span.style.fontFamily = "myFontEnglish, myFontBangla";
	span.style.bottom = "-1000px";
	span.style.fontSize = "15px";
	span.style.left = "-1000px";
	document.body.appendChild(span);
	let width = span.getBoundingClientRect().width;
	document.body.removeChild(span);
	return width;	
}

function showStatus(text) {
	let statusCon = elt("div", "statusCon");
	statusCon.style.width = findWidth(text) + 20 + "px";

	let status = elt("div", "status");
	status.textContent = text;

	statusCon.appendChild(status);

	document.body.appendChild(statusCon);

	setTimeout(function() {
		status.style.background = "#333";
		status.style.color = "#ddd";
		status.style.width = findWidth(text) + 20 + "px";
	}, 20);

	setTimeout(function() {
		status.style.background = "rgba(0,0,0,0)";
		status.style.color = "rgba(0,0,0,0)";
		status.style.width = "0px";
	}, 1800);

	setTimeout(function() {
		document.body.removeChild(statusCon);
	}, 2000);
}

function createNoteWidget(noteText, day, phpForSave, phpForDiscard) {
/*
	How to style node widget:
	.noteTextarea
	.noteDiv
	.addNoteButton
	.saveNoteButton
	.editNoteButton
	.discardNoteButton
*/

	// butons
	let addNoteButton = elt("button", "addNoteButton");
	addNoteButton.type = "button";
	addNoteButton.textContent = "Add a note";

	let saveNoteButton = elt("button", "saveNoteButton");
	saveNoteButton.type = "button";
	saveNoteButton.textContent = "Save";

	let editNoteButton = elt("button", "editNoteButton");
	editNoteButton.type = "button";
	editNoteButton.textContent = "Edit";

	let revertNoteButton = elt("button", "revertNoteButton");
	revertNoteButton.type = "button";
	revertNoteButton.textContent = "Revert";


	let discardNoteButton = elt("button", "discardNoteButton");
	discardNoteButton.type = "button";
	discardNoteButton.textContent = "Discard";

	// functions for buttons
	function addNote() {
		let noteTextarea = elt("textarea", "noteTextarea");
		addNoteButton.insertAdjacentElement("beforebegin", noteTextarea);

		noteTextarea.focus();
		noteTextarea.insertAdjacentElement("afterend", discardNoteButton);
		noteTextarea.insertAdjacentElement("afterend", saveNoteButton);

		saveNoteButton.onclick = function() {
			saveNote(noteTextarea);
		}

		discardNoteButton.onclick = function() {
			discardNote(noteTextarea);
		}	

		addNoteButton.parentElement.removeChild(addNoteButton);
	}

	function saveNote(textarea) {
		let height = textarea.getBoundingClientRect().height;
		let scrollTop = textarea.scrollTop;
		let div = elt("div", "noteDiv");
		div.textContent = textarea.value; 
		div.style.height  = height + "px";
		textarea.parentElement.replaceChild(div, textarea);
		div.scrollTop = scrollTop;

		//console.log("hello");
		getinfo(phpForSave, "note=" + encodeURIComponent(textarea.value) + "&date=" + day.id, data, 
			"noteSaveResponse", () => {showStatus(data.noteSaveResponse)});

		editNoteButton.onclick = function() {
			editNote(div);
		}

		discardNoteButton.onclick = function() {
			discardNote(div);
		}	
		
		if (revertNoteButton.parentElement) {
			revertNoteButton.parentElement.removeChild(revertNoteButton);
		}
		saveNoteButton.parentElement.replaceChild(editNoteButton, saveNoteButton);
		day.style.backgroundImage = "url(../pics/note.png)";
	}

	function editNote(div) {
		let height = div.getBoundingClientRect().height;
		let scrollTop = div.scrollTop;

		let textareaNew = elt("textarea", "noteTextarea");
		textareaNew.value = div.textContent;
		textareaNew.style.height = height + "px";
		div.parentElement.replaceChild(textareaNew, div);
		textareaNew.scrollTop = scrollTop;

		saveNoteButton.onclick = function() {
			saveNote(textareaNew);
		}
		
		discardNoteButton.onclick = function() {
			discardNote(textareaNew);
		}	
		
		textareaNew.oninput = function() {
			if (textareaNew.value == div.textContent) {
				revertNoteButton.disabled = true;
			} else {
				revertNoteButton.disabled = false;
			}
		}

		revertNoteButton.onclick = function() {
			revertNote(div.textContent, textareaNew);
			revertNoteButton.disabled = true;
		}

		revertNoteButton.disabled = true;
		editNoteButton.parentElement.replaceChild(saveNoteButton, editNoteButton);
		saveNoteButton.insertAdjacentElement("afterend", revertNoteButton);
	}

	function revertNote(preNote, textarea) {
		textarea.value = preNote;
	}

	function discardNote(noteElt) {
		if (confirm("Are you sure to discard the note?")) {
			getinfo(phpForDiscard, "date=" + day.id, data,
				"noteDiscardResponse", () => {showStatus(data.noteDiscardResponse)});

			noteElt.parentElement.removeChild(noteElt);
			if (saveNoteButton.parentElement) {
				saveNoteButton.parentElement.removeChild(saveNoteButton);
			} else {
				editNoteButton.parentElement.removeChild(editNoteButton);
			}
			if (revertNoteButton.parentElement) {
				revertNoteButton.parentElement.removeChild(revertNoteButton);
			}
			discardNoteButton.parentElement.replaceChild(addNoteButton, discardNoteButton);
			day.style.backgroundImage = "";
		}
	}

	addNoteButton.onclick = addNote;
	// final part
	let noteWidget = elt("div", "noteWidget");
	
	if (noteText != null) {
		let div = elt("div", "noteDiv");
		div.textContent = noteText;
		noteWidget.appendChild(div);

		editNoteButton.onclick = function() {
			editNote(div);
		};

		discardNoteButton.onclick = function() {
			discardNote(div);
		}

		noteWidget.appendChild(editNoteButton);
		noteWidget.appendChild(discardNoteButton);
	} else {
		noteWidget.appendChild(addNoteButton);
	}

	return noteWidget;
}
