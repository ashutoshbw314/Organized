// note every basic fimo must be unique
function createFimo(fimo, basicProp, complexProp, cfbBool, replicationData) {
	let fimoId = 0;			

	function sort(array) {
	  array.sort(function (a, b) {
	    return a.toLowerCase().localeCompare(b.toLowerCase());
	  });
	}

	function createOption(parentTag, value, selected) {
		let option = document.createElement("option");
		option.setAttribute("value", value);
		if (selected) option.setAttribute("selected", "selected");
		option.innerHTML = value;
		parentTag.appendChild(option);
	}

	function setOptions(selectTag, basicProp, complexProp, type) {	//used to set primary options and complex options
		if (type == "basic") {
			let values = [];
			for (let pp in data[basicProp]) {	// pp -> primary property
				values.push(pp);
			}
			sort(values);
			for (let i = 0; i < values.length; i++) {
				createOption(selectTag, values[i], false);
			}
		} else if (type == "complex"){
			if (data[complexProp]) {
				let values = data[complexProp].slice(0);
				sort(values);
				for (let i = 0; i < values.length; i++) {	// pp -> primary property
					createOption(selectTag, values[i], false);
				}
			}
		}
		createOption(selectTag, "null", true);
	}

	function setSecondaryOptions(primarySelectTag, secondarySelectTag, tertiarySelectTag, intensityInputTag, intensityOutputTag, directionTag, fimo, basicProp) { 
		let pValue = primarySelectTag.value;

		while (secondarySelectTag.firstChild) {
			secondarySelectTag.removeChild(secondarySelectTag.firstChild);
		}

		while (tertiarySelectTag.firstChild) {
			tertiarySelectTag.removeChild(tertiarySelectTag.firstChild);
		}

		for (let pp in data[basicProp]) {    	
			if (pp == pValue) {
				let ppo = data[basicProp][pp]; 						// ppo -> primary property object
				let values = [];
				for (let sp in ppo) {					// sp -> secondary property
					values.push(sp);
				}
				sort(values);
				for (let i = 0; i < values.length; i++) {
					createOption(secondarySelectTag, values[i], false);
				}								
			}

		}
		createOption(secondarySelectTag, "null", true);
		createOption(tertiarySelectTag, "null", true);
		
		let cFimoName;
		if (cfbBool) {
			if (fimo == "f") {
				cFimoName = document.getElementsByName("complexFeelingName")[0];
			} else if (fimo == "e"){
				cFimoName = document.getElementsByName("complexEmotionName")[0];
			}
		}

		if (primarySelectTag.value == "null") {
			intensityInputTag.disabled = true;
			directionTag.disabled = true;
			intensityInputTag.value = 1;
			intensityOutputTag.textContent = "";
		} else {
			intensityInputTag.disabled = false;
			directionTag.disabled = false;
			if (intensityOutputTag.textContent == "") {
				intensityOutputTag.textContent = "1";
			}
		}
		
//The following if block disables cFimoName if no fimo is given
/*
		if (cfbBool) {
			let pseElts;
			if (fimo == "f") {
			 	pseElts = document.querySelectorAll(".pseFClass, .cseFClass");
			} else if (fimo == "e") {
				pseElts = document.querySelectorAll(".pseEClass, .cseEClass");
			}

			for (let i = 0; i < pseElts.length; i++) {
				if (pseElts[i].value != "null") {
					cFimoName.disabled = false;
					break;
				}
			} 

			for (let i = 0; i < pseElts.length; i++) {
				if (pseElts[i].value == "null") {
					if (i == pseElts.length - 1) {
						cFimoName.disabled = true;
						break;
					}
					continue;
				} else {
					break;
				}
			} 
		}
*/
	}

	function setTertiaryOptions(secondarySelectTag, tertiarySelectTag, basicProp) { 
		let sValue = secondarySelectTag.value;

		while (tertiarySelectTag.firstChild) {
			tertiarySelectTag.removeChild(tertiarySelectTag.firstChild);
		}

		for (let pp in data[basicProp]) {    	
			let ppo = data[basicProp][pp]; 						// ppo -> primary property object
			for (let sp in ppo) {					// sp -> secondary property
				if (sp == sValue) {
					let tertiaryArray = ppo[sp];
					let values = tertiaryArray.slice(0);
					sort(values);
					for (let i = 0; i < values.length; i++) {
						createOption(tertiarySelectTag, values[i], false);
					}
				}
			}				

		}
		createOption(tertiarySelectTag, "null", true);
	}

	function elt (type, name) {
		let element = document.createElement(type);
		if (name) {
			element.name = name;
		}
		return element;
	}

	function textElt (text) {
		let element = document.createTextNode(text);
		return element;
	}

	//let fimo_letters = ["d", "p", "s", "t", "i", "o", "c"];  // d means direction to positive or negative, c for complex

	function create_d_elt (rowNum, fimo) {
		let ds = elt("select", rowNum + fimo + "d");
		let po = createOption(ds, "+", true);
		let no = createOption(ds, "-", false);
		ds.disabled = true;
		return ds;
	}


	function setdi(cse, ise, outputValue, dse) {
		if (cse.value == "null") {
			ise.disabled = true;
			dse.disabled = true;
			ise.value = 0;
			outputValue.textContent = "";
		} else {
			ise.disabled = false;
			dse.disabled = false;
			if (outputValue.textContent == "") {
				outputValue.textContent = "1";
			}
		}
/*
		if (cfbBool) {
			let cFimoName;
			if (fimo == "f") {
				cFimoName = document.getElementsByName("complexFeelingName")[0];
			} else if (fimo == "e"){
				cFimoName = document.getElementsByName("complexEmotionName")[0];
			}

			let pseElts;
			if (fimo == "f") {
			 	pseElts = document.querySelectorAll(".pseFClass, .cseFClass");
			} else if (fimo == "e") {
				pseElts = document.querySelectorAll(".pseEClass, .cseEClass");
			}

			for (let i = 0; i < pseElts.length; i++) {
				if (pseElts[i].value != "null") {
					cFimoName.disabled = false;
					break;
				}
			} 

			for (let i = 0; i < pseElts.length; i++) {
				if (pseElts[i].value == "null") {
					if (i == pseElts.length - 1) {
						cFimoName.disabled = true;
						break;
					}
					continue;
				} else {
					break;
				}
			} 

		}
*/
	}

	function create_fimo_select_tool(rowNum, basicProp, complexProp, type, fimo) {
		let container = elt("div", false);
		let dse = create_d_elt (rowNum, fimo);		// direction select element: positve or negative
		dse.className = "dse";
		container.appendChild(dse);

		let ise = elt("input", rowNum + fimo + "i"); 		// intensity select element

		ise.className = "intensity";
		ise.disabled = true;
		ise.type = "range";
		ise.value = 0;
		ise.min = 1;
		ise.max = 100;
		ise.step = 1;
		ise.style.width = "100px";

		let outputValue = elt("span", false);

		if (type == "basic") {
			let pse = elt("select", rowNum + fimo + "p");  	//primary select element
			if (fimo == "f") {
				pse.className = "pseFClass";
			} else if (fimo == "e") {
				pse.className = "pseEClass";
			}
			let sse = elt("select", rowNum + fimo + "s"); 		//secondary select element
			let tse = elt("select", rowNum + fimo + "t");   	//tertiary select element
			if (fimo == "f") {
				tse.className = "tseFClass";
			} else if (fimo == "e") {
				tse.className = "tseEClass";
			}
			setOptions(pse, basicProp, complexProp, type);
			createOption(sse, "null", true);
			createOption(tse, "null", true);
			pse.onchange = setSecondaryOptions.bind(null, pse, sse, tse, ise, outputValue, dse, fimo, basicProp);
			sse.onchange = setTertiaryOptions.bind(null, sse, tse, basicProp);

			container.appendChild(pse);
			container.appendChild(textElt(" > "));
			container.appendChild(sse);
			container.appendChild(textElt(" > "));
			container.appendChild(tse);
			
		} else if (type == "complex") {
			let cse = elt("select", rowNum + fimo + "c");
			if (fimo == "f") {
				cse.className = "cseFClass";
			} else if(fimo == "e") {
				cse.className = "cseEClass";
			}
			setOptions(cse, basicProp, complexProp, type);
			cse.onchange = setdi.bind(null, cse, ise, outputValue, dse);	//setdi means set direction and intensity
			container.appendChild(cse);

		}


		

		container.appendChild(textElt(" Intensity: "));
		container.appendChild(ise);


		 
		container.appendChild(outputValue);

		ise.oninput = function() {
			outputValue.textContent = ise.value;
		};
		
	 
		return container;
	}

	function complexFimoBox(fimo) {		// naming system
		let div = elt("div", false);
		let label = elt("span", false);
		let textInput = elt("input", false);
		let message = elt("span", false);
		message.id = "message1";
		
		textInput.type = "text";
		if (fimo == "f") {
			label.textContent = "Name your complex feeling: ";
			textInput.name = "complexFeelingName";
		} else if (fimo == "e") {
			label.textContent = "Name your complex emotion: ";
			textInput.name = "complexEmotionName";
		}
		textInput.disabled = false;
		textInput.style.marginRight = "4px";
		div.appendChild(label);
		div.appendChild(textInput);
		div.appendChild(message);
		div.style.clear = "left";
		return div;
	}

	function validateFeelingToBeNotNull() {
		let i = 1;
		let a;
		let b;
		while(a = (document.getElementsByName(i + "fp")[0] || document.getElementsByName(i + "fc")[0])) {
			b = a;
			i++;
		}
		if (b != undefined && b.value == "null") {
			alert("Please first choose a feeling in the previous select option and then move on to the next one.");
			return false;
		}
		return true;
	}

	function validateEmotionToBeNotNull() {
		let i = 1;
		let a;
		let b;
		while(a = (document.getElementsByName(i + "ep")[0] || document.getElementsByName(i + "ec")[0])) {
			b = a;
			i++;
		}
		if (b != undefined && b.value == "null") {
			alert("Please first choose an emotion in the previous select option and then move on to the next one.");
			return false;
		}
		return true;
	}
	
	function attachFimo(button, button1, basicProp, complexProp, type, fimo, cfbBool) {		// this function adds a feeling or emotion select tool
		if (fimoId > 0) {
			if ((button.textContent == "A basic feeling" ||
			     button.textContent == "Another basic feeling")
			 && !validateFeelingToBeNotNull()) return;
		}

		if (fimoId > 0) {
			if ((button.textContent == "A basic emotion" ||
			     button.textContent == "Another basic emotion")
			 && !validateEmotionToBeNotNull()) return;
		}

		let parentElt = button.parentElement;
		let cfb = complexFimoBox(fimo);

		let deleteFimoButton = elt("button", false);
		deleteFimoButton.type = "button";
		deleteFimoButton.className = "negButton";
		if (fimo == "f") {
			deleteFimoButton.textContent = "Delete previous feeling";
		} else if (fimo == "e") {
			deleteFimoButton.textContent = "Delete previous emotion";
		}
		deleteFimoButton.addEventListener("click", deleteFimo.bind(null, deleteFimoButton, cfb, fimo, button, button1, cfbBool));

		let div = create_fimo_select_tool(++fimoId, basicProp, complexProp, type, fimo);
		if (fimo == "f") {
			div.id="feeling_" + fimoId;
			div.className = type + "Feeling";
		} else if (fimo == "e") {
			div.id="emotion_" + fimoId;
			div.className = type + "Emotion";
		}
		parentElt.insertBefore(div, button);
		
		if (fimoId == 1) {
			button.nextElementSibling.insertAdjacentElement("afterend", deleteFimoButton);
		}
		if (fimoId == 1 && cfbBool) {
			button.nextElementSibling.nextElementSibling.insertAdjacentElement("afterend", cfb);
		}
		
		if (fimo == "f") {
			if (type == "basic") {
				button.textContent = "Another basic feeling";
			} else if (type == "complex") {
				button1.textContent = "Another complex feeling";
			}
		} else if (fimo == "e") {
			if (type == "basic") {
				button.textContent = "Another basic emotion";
			} else if (type == "complex") {
				button1.textContent = "Another complex emotion";
			}
		}
	}

	function deleteFimo(button, cfb, fimo, buttonA, buttonB) {
		let parentElt = button.parentElement;
		if (fimo == "f") {
			let theFeelingElt = document.getElementById("feeling_" + fimoId);
			if (document.querySelectorAll(".basicFeeling").length == 1) {
				if (theFeelingElt.className == "basicFeeling") {
					buttonA.textContent = "A basic feeling";
				}
			}
			if (document.querySelectorAll(".complexFeeling").length == 1) {
				if (theFeelingElt.className == "complexFeeling") {
					buttonB.textContent = "A complex feeling";
				}
			}
			parentElt.removeChild(theFeelingElt);

		} else if (fimo == "e") {
			let theEmotionalElt = document.getElementById("emotion_" + fimoId);

			if (document.querySelectorAll(".basicEmotion").length == 1) {
				if (theEmotionalElt.className == "basicEmotion") {
					buttonA.textContent = "A basic emotion";	
				}
			}
			if (document.querySelectorAll(".complexEmotion").length == 1) {
				if (theEmotionalElt.className == "complexEmotion") {
					buttonB.textContent = "A complex emotion";
				}

			}
			parentElt.removeChild(theEmotionalElt);
		}
		if (fimoId == 1) {
			parentElt.removeChild(button);
			if (cfbBool) {
				parentElt.removeChild(cfb);
			}
		}
		fimoId--;
	}

/********************** Replication Tools are below *******************/
function getArrayFromSelect(s) {
	let sValues = [];
	for (let i = 0; i < s.childNodes.length; i++) {
		//if (s.childNodes[i].textContent != "null") {
			sValues.push(s.childNodes[i].textContent);
		//}
	}
	return sValues;
}

function setPseValue(pse, sse, tse, newPseValue, basicProp) {
	prePseValue = pse.value;

	pseValues = getArrayFromSelect(pse);
	
	if (pseValues.indexOf(newPseValue) != -1) {
		if (prePseValue != newPseValue) {
			pse.value = newPseValue;

			while (sse.firstChild) {
				sse.removeChild(sse.firstChild);
			}

			while (tse.firstChild) {
				tse.removeChild(tse.firstChild);
			}

			for (let pp in data[basicProp]) {    	
				if (pp == newPseValue) {
					let ppo = data[basicProp][pp]; 						// ppo -> primary property object
					let values = [];
					for (let sp in ppo) {					// sp -> secondary property
						values.push(sp);
					}
					sort(values);
					for (let i = 0; i < values.length; i++) {
						createOption(sse, values[i], false);
					}				
				}

			}
			createOption(sse, "null", true);
			createOption(tse, "null", true);		
		}
	} else {
		pse.value = "";
		while (sse.firstChild) {
			sse.removeChild(sse.firstChild);
		}

		while (tse.firstChild) {
			tse.removeChild(tse.firstChild);
		}
		showWarning("(fimo: " + fimo + ", fimoId: " + fimoId + ", type: basic): the primary value \"" + newPseValue + "\" doesn't exist"); 	

	}
}


function setSseValue(sse, tse, newSseValue, basicProp) {
 	preSseValue = sse.value;
	sseValues = getArrayFromSelect(sse);
	if (sseValues.indexOf(newSseValue) != -1) {
		if (preSseValue != newSseValue) {
			sse.value = newSseValue;

			while (tse.firstChild) {
				tse.removeChild(tse.firstChild);
			}
			
			for (let pp in data[basicProp]) {    	
				let ppo = data[basicProp][pp]; 						// ppo -> primary property object
				for (let sp in ppo) {					// sp -> secondary property
					if (sp == newSseValue) {
						let tertiaryArray = ppo[sp];
						let values = tertiaryArray.slice(0);
						sort(values);
						for (let i = 0; i < values.length; i++) {
							createOption(tse, values[i], false);
						}
					}
				}				
			}
			createOption(tse, "null", true);
		}
	} else {
		sse.value = "";
		while (tse.firstChild) {
			tse.removeChild(tse.firstChild);
		}
		if (newSseValue != "null") {
			showWarning("(fimo: " + fimo + ", fimoId: " + fimoId + ", type: basic): the secondary value \"" + newSseValue + "\" doesn't exist");	
		}
	}
}

function setTseValue(tse, newTseValue) {
 	preTseValue = tse.value;
	tseValues = getArrayFromSelect(tse);

	if (tseValues.indexOf(newTseValue) != -1) {
		if (preTseValue != newTseValue) {
			tse.value = newTseValue;
		}
	} else {
		tse.value = "";
		if (newTseValue != "null") {
			showWarning("(fimo: " + fimo + ", fimoId: " + fimoId + ", type: basic): the  tertiary value \"" + newTseValue + "\" doesn't exist");
		}
	}
}

function setCseValue(cse, newCseValue) {
 	preCseValue = cse.value;
	cseValues = getArrayFromSelect(cse);
	if (cseValues.indexOf(newCseValue) != -1) {
		if (preCseValue != newCseValue) {
			cse.value = newCseValue;
		}
	} else {
		cse.value = "";
		showWarning("(fimo: " + fimo + ", fimoId: " + fimoId + ", type: complex): the value \"" + newCseValue + "\" doesn't exist");
	}
}


function setFimo(fimoDiv, dataArray, basicProp) {
	if (fimoDiv.className == "basicFeeling" || fimoDiv.className == "basicEmotion") {	// type = basic
		let dse = fimoDiv.childNodes[0];
		let pse = fimoDiv.childNodes[1];
		let sse = fimoDiv.childNodes[3];
		let tse = fimoDiv.childNodes[5];
		let ise = fimoDiv.childNodes[7];
		let iseDisplay = fimoDiv.childNodes[8];
		
		dse.disabled = false;
		ise.disabled = false;
		dse.value = dataArray[0];
		setPseValue(pse, sse, tse, dataArray[1], basicProp);
		setSseValue(sse, tse, dataArray[2], basicProp);
		setTseValue(tse, dataArray[3], basicProp);
		ise.value = dataArray[4];
		iseDisplay.textContent = dataArray[4];
	} else if (fimoDiv.className == "complexFeeling" || fimoDiv.className == "complexEmotion") {
		let dse = fimoDiv.childNodes[0];
		let cse = fimoDiv.childNodes[1];
		let ise = fimoDiv.childNodes[3];
		let iseDisplay = fimoDiv.childNodes[4];
		
		dse.disabled = false;
		ise.disabled = false;
		dse.value = dataArray[0];
		setCseValue(cse, dataArray[1], basicProp);
		ise.value = dataArray[2];
		iseDisplay.textContent = dataArray[2];		
	}
}

function replicateFimo(fimoData, container, basicProp, complexProp, button) {
	for (let i = 0; i < fimoData.basic.length; i++) {
		fimoId++;

		let fimoSelectTool = create_fimo_select_tool(fimoId, basicProp, complexProp, "basic", fimoData.fimo);
		fimoSelectTool.id = (fimoData.fimo == "f" ? "feeling_" : "emotion_") + fimoId;
		fimoSelectTool.className = "basic" + (fimoData.fimo == "f" ? "Feeling" : "Emotion");
		setFimo(fimoSelectTool, fimoData.basic[i], basicProp);
		if (fimoId == 1) {
			container.insertAdjacentElement('afterbegin', fimoSelectTool);
		} else {
			let lastFimoSelectTool = container.querySelector("#" + (fimoData.fimo == "f" ? "feeling_" : "emotion_") + (fimoId - 1));
			lastFimoSelectTool.insertAdjacentElement("afterend", fimoSelectTool);
		}
	}

	for (let i = 0; i < fimoData.complex.length; i++) {
		fimoId++;

		let fimoSelectTool = create_fimo_select_tool(fimoId, basicProp, complexProp, "complex", fimoData.fimo);
		fimoSelectTool.id = (fimoData.fimo == "f" ? "feeling_" : "emotion_") + fimoId;
		fimoSelectTool.className = "complex" + (fimo == "f" ? "Feeling" : "Emotion");
		setFimo(fimoSelectTool, fimoData.complex[i], complexProp);
		if (fimoId == 1) {
			container.insertAdjacentElement('afterbegin', fimoSelectTool);
		} else {
			let lastFimoSelectTool = container.querySelector("#" + (fimoData.fimo == "f" ? "feeling_" : "emotion_") + (fimoId - 1));
			lastFimoSelectTool.insertAdjacentElement("afterend", fimoSelectTool);
		}
	}

}


/********************** Rules are above. The Game begins From Here *********************/
	let fieldset = elt("div", false);	//A div by any other name would smell as sweet
	fieldset.className = "mfe";
	let attachBasicFimoButton = elt("button", false);
	let attachComplexFimoButton = elt("button", false);
	attachBasicFimoButton.type = "button";
	attachComplexFimoButton.type = "button";
	
	fieldset.appendChild(attachBasicFimoButton);
	fieldset.appendChild(attachComplexFimoButton);

/*** Manges the deleteFimoButton and cfb => start ****/
	if (replicationData && (replicationData.basic.length + replicationData.complex.length > 0)) {
		let deleteFimoButton = elt("button", false);
		deleteFimoButton.type = "button";
		deleteFimoButton.className = "negButton";
		fieldset.appendChild(deleteFimoButton);

		if (fimo == "f") {
			deleteFimoButton.textContent = "Delete previous feeling";
		} else if (fimo == "e") {
			deleteFimoButton.textContent = "Delete previous emotion";
		}

		let cfb = complexFimoBox(fimo);

		if (cfbBool) {
			cfb.querySelector("input").disabled = false;
			attachBasicFimoButton.nextElementSibling.nextElementSibling.insertAdjacentElement("afterend", cfb);
		}

		deleteFimoButton.addEventListener("click", deleteFimo.bind(null, deleteFimoButton, cfb, fimo, attachBasicFimoButton, attachComplexFimoButton, cfbBool));
	}
	
/**** Manages the deleteFimoButton and cfb <= end ***/


	if (replicationData) {
		replicateFimo(replicationData, fieldset, basicProp, complexProp, attachBasicFimoButton);
	}

	if (fimo == "f") {
		if (replicationData) {
			if (replicationData.basic.length == 0) {
				attachBasicFimoButton.textContent = "A basic feeling";
			} else {
				attachBasicFimoButton.textContent = "Another basic feeling";
			}
			if (replicationData.complex.length == 0) {
				attachComplexFimoButton.textContent = "A complex feeling";
			} else {
				attachComplexFimoButton.textContent = "Another complex feeling";
			}
		} else {
			attachBasicFimoButton.textContent = "A basic feeling";
			attachComplexFimoButton.textContent = "A complex feeling";
		}
	} else if (fimo == "e") {
		if (replicationData) {
			if (replicationData.basic.length == 0) {
				attachBasicFimoButton.textContent = "A basic emotion";
			} else {
				attachBasicFimoButton.textContent = "Another basic emotion";
			}
			if (replicationData.complex.length == 0) {
				attachComplexFimoButton.textContent = "A complex emotion";
			} else {
				attachComplexFimoButton.textContent = "Another complex emotion";
			}
		} else {
			attachBasicFimoButton.textContent = "A basic emotion";
			attachComplexFimoButton.textContent = "A complex emotion";
		}
	}
	
	attachBasicFimoButton.addEventListener("click", attachFimo.bind(null, attachBasicFimoButton, attachComplexFimoButton, basicProp, complexProp, "basic", fimo, cfbBool));
	attachComplexFimoButton.addEventListener("click", attachFimo.bind(null, attachBasicFimoButton, attachComplexFimoButton, basicProp, complexProp, "complex", fimo, cfbBool));	
	
	return fieldset;
}
