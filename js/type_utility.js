function deleteAllEltsOfRight(elt) {
		let curElt = elt;
		while (curElt = curElt.nextElementSibling) {
			curElt.parentElement.removeChild(curElt);
			curElt = elt;
		}
}

function createArrow() {
	let arrow = elt("span", "arrow");
	arrow.textContent = ">";
	return arrow;
}

function createSelect(valuesObj, parentElt, doSomething) {
	let selectElt = elt("select");
	
	let valArr = [];

	if (Array.isArray(valuesObj)) return;

	for (let value in valuesObj) {
		if (value != "ranges") {
			valArr.push(value);
		}
	}
	
	if (valArr.length == 0) return;
	
	setOptions(selectElt, valArr);

	selectElt.oninput = function() {
		if (selectElt.value != "null") {
			deleteAllEltsOfRight(selectElt);
			createSelect(valuesObj[selectElt.value], parentElt, doSomething);
			if (doSomething != null) {
				doSomething();
			}
		} else {
			deleteAllEltsOfRight(selectElt);
			if (doSomething != null) {			
				doSomething();
			}
		}
	}
	
	if (parentElt.firstChild != null) {
		parentElt.appendChild(createArrow());
	}
	parentElt.appendChild(selectElt);
	return selectElt;
}

function matchValues(valuesObj, repData) {
	let commonData = [];
	let curObj = valuesObj;
	for (let i = 0; i < repData.length; i++) {
		curObj = curObj[repData[i]];
		if (curObj !== null && curObj !== undefined && repData.indexOf("ranges") == -1) {
			commonData.push(repData[i]);
			if (Array.isArray(curObj)) break;
		} else {
			break;
		}
	}
	return commonData;
}

function repSelects(valuesObj, parentElt, repData, doSomething) {
	let commonData = matchValues(valuesObj, repData);
	
	if (repData.length != commonData.length) {
		showWarning("Requested data: \"" + repData.join(" > ") + "\" doesn't have exact match"); 
	}

	if (commonData.length == 0) {
		commonData.push("null");
	}

	let curValuesObj = valuesObj;
	for (let i = 0; i < commonData.length; i++) {
		let selectElt = createSelect(curValuesObj, parentElt, doSomething);

		selectElt.value = commonData[i];

		curValuesObj = curValuesObj[commonData[i]];
		if (i == commonData.length - 1) {
			if (curValuesObj !== null && curValuesObj !== undefined && !Array.isArray(curValuesObj)) {
				let selectElt = createSelect(curValuesObj, parentElt, doSomething);
				selectElt.value = "null";				
			}
		}
	}
}

function getCurrentTypeValues(typeCon) {
	let values = [];
	let curSelect = typeCon.firstElementChild;
	while (curSelect) {
		if (curSelect.value != "null") {
			values.push(curSelect.value);
		}
		if (curSelect.nextElementSibling) {  // arrow
			curSelect = curSelect.nextElementSibling.nextElementSibling;
		} else {
			curSelect = null;
		}
	}
	return values;
}

function getRanges(typeCon, type) {
	let values = getCurrentTypeValues(typeCon);
	
	let curEntity = type;

	for (let i = 0; i < values.length; i++) {
		curEntity = curEntity[values[i]];
	}

	if (Array.isArray(curEntity)) {
		return curEntity;
	} else {
		return curEntity.ranges;
	}
}

function getCurrentType(typeCon) {
	let values = getCurrentTypeValues(typeCon);
	if (values.length == 0) {
		return "Data";
	} else {
		return values[values.length - 1];
	}
}	

function extractFullType(typeCon) {
	let values = [];
	let curSelect = typeCon.firstChild;
	while (curSelect) {
		if (curSelect.value != "null") {
			values.push(curSelect.value);
		}
		if (curSelect.nextElementSibling) {  // arrow
			curSelect = curSelect.nextElementSibling.nextElementSibling;
		} else {
			curSelect = null;
		}
	}
	return values.join(" > ");
}
