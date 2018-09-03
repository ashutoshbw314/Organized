function sort(array) {
  array.sort(function (a, b) {
    return a.toLowerCase().localeCompare(b.toLowerCase());
  });
}

function createOption(parentTag, value, selected) {
	let option = document.createElement("option");
	option.setAttribute("value", value);
	if (selected) option.setAttribute("selected", "selected");
	option.textContent = value;
	parentTag.appendChild(option);
}

function setOptions(selectTag, a)  {
	let values = [];
	values = a.slice(0);
	sort(values);
	for (let i = 0; i < values.length; i++) {
		createOption(selectTag, values[i], false);
	}
	createOption(selectTag, "null", true);
}
 

function arrayEqual(a1, a2) {
	if (a1.length != a2.length) {
		return false;
	}
	for (let i = 0; i < a1.length; i++) {
		if (a2.indexOf(a1[i]) == -1) {
			return false;
		}
	}
	return true;
}


function updateSelectTag(s, updatedValues, type) {
	let value = s.value;
	let sValues = [];

	for (let i = 0; i < s.childNodes.length; i++) {
		if (s.childNodes[i].textContent != "null") {
			sValues.push(s.childNodes[i].textContent);
		}
	}

	if (!arrayEqual(sValues, updatedValues)) {
		while (s.firstChild) {
		    s.removeChild(s.firstChild);
		}
		setOptions(s, updatedValues);
		if (value != "" && value != "null" && updatedValues.indexOf(value) != -1) {
			s.value = value;
		}

		if (value != "" && value != "null" && updatedValues.indexOf(value) == -1) {
			s.value = "";
			if (type != "sourceSave" && type != "myLabelsSave") {
				showWarning(" The \"" + value + "\" doesn't exist in name=\"" + s.name + "\", id=\"" + s.id + "\"");
			}
		}

		if (value == "") {
			s.value = value;
		}
	}

}
function updateSelectTagSpecial(s, updatedValues, type, updateTimesPropName) {
	doTheUpdate(s, updatedValues, type);
	function doTheUpdate(s, updatedValues, type) {
		let value = s.value;

		let sValues = [];

		for (let i = 0; i < s.childNodes.length; i++) {
			if (s.childNodes[i].textContent != "null") {
				sValues.push(s.childNodes[i].textContent);
			}
		}

		if (!arrayEqual(sValues, updatedValues)) {
			while (s.firstChild) {
			    s.removeChild(s.firstChild);
			}
			setOptions(s, updatedValues);
			if (value != "" && value != "null" && updatedValues.indexOf(value) != -1) {
				s.value = value;
			}

			if (value != "" && value != "null" && updatedValues.indexOf(value) == -1) {
				s.value = "";
				//if (type != "datum") {
					showWarning(" The \"" + value + "\" doesn't exist in name=\"" + s.name + "\", id=\"" + s.id + "\"");
				//}
			}

			if (value == "" && data[updateTimesPropName] != 0) {  
				// on first run select gets chance to have null value set by setOption function
				// here is for the rest
				s.value = value;
//console.log("HELLO");
			}
			data[updateTimesPropName] = data[updateTimesPropName] + 1;
		} else if (data[updateTimesPropName] == 0 && sValues.length == 0) {
			createOption(s, "null", true);
			data[updateTimesPropName] = data[updateTimesPropName] + 1;
		} 
	}
}


function pValues(data) {
	let values = [];
	for (let pp in data) {    	
		values.push(pp);
	}
	return values;
}

function sValues(data, pv) {
	let values = [];
	for (let pp in data) {    	
		if (pp == pv) {
			let ppo = data[pp]; 						// ppo -> primary property object
			for (let sp in ppo) {					        // sp -> secondary property
				values.push(sp);
			}				
		}
	}
	return values;
}

function tValues(data, sv) {
	let values = [];
	for (let pp in data) {    	
		let ppo = data[pp]; 							// ppo -> primary property object
		for (let sp in ppo) {							// sp -> secondary property
			if (sp == sv) {
				values = ppo[sp];
			}
		}				
	}
	return values;	
}

function updateBasicOnes(container, data, fimo) {
	let basicOnes = container.querySelectorAll(fimo == "f" ? ".basicFeeling" : ".basicEmotion");

	for (let i = 0; i < basicOnes.length; i++) {
		let aBasicOne = basicOnes[i];

		let pse = aBasicOne.childNodes[1];
		let sse = aBasicOne.childNodes[3];
		let tse = aBasicOne.childNodes[5];

		let pv = aBasicOne.childNodes[1].value;
		let sv = aBasicOne.childNodes[3].value;
		let tv = aBasicOne.childNodes[5].value;

		updateSelectTag(pse, pValues(data));
		updateSelectTag(sse, sValues(data, pv));
		updateSelectTag(tse, tValues(data, sv));
	}
}


function updateComplexOnes(container, complexData, fimo) {
	let complexOnes = container.querySelectorAll((fimo == "f" ? ".complexFeeling" : ".complexEmotion"));
	for (let i = 0; i < complexOnes.length; i++) {
		let aComplexOne = complexOnes[i];
		let cse = aComplexOne.childNodes[1];
		updateSelectTag(cse, complexData);
	}
}




function putBasicOnesInUse(container, data, fimo) {
	let prop = "basic" + (fimo == "f" ? "Feelings" : "Emotions");
	if (typeof data[prop] == "string") {
		data[prop] = JSON.parse(data[prop]);
	}
	updateBasicOnes(container, data[prop], fimo);
}

function putComplexOnesInUse(container, data, fimo) {
	let prop = "complex" + (fimo == "f" ? "Feelings" : "Emotions");
	if (typeof data[prop] == "string") {
		data[prop] = JSON.parse(data[prop]);
	}
	updateComplexOnes(container, data[prop], fimo);
}
