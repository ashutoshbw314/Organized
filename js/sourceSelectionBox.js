function createSourceSelectionBox(prop, shortMiddleName, withInput, repData) {  //here data[prop] means data
	function sort(array) {
	  array.sort(function (a, b) {
	    return a.toLowerCase().localeCompare(b.toLowerCase());
	  });
	}

	let extraExists = false;
	if (repData) {
		extraExists = typeof repData[0] == "object" ? true : false;
	}

	function elt(type, id, name, className) {
		let element = document.createElement(type);
		if(id) element.id = id;
		if(name) element.name = name;
		if(className) element.className = className;
		return element;
	}

	let total = 0;
	let selectsValues = [];
	selectsValues[0] = "zerothElt";

	function createController() {
		let source = undefined;
		let extra = undefined;
		let container = elt("div", undefined, undefined, "controllerContainer");
		let outer = elt("div", undefined, undefined, "outerCC");
		outer.appendChild(container);
		let addButton = elt("button");
		addButton.type= "button";
		addButton.textContent = "Add label";

		addButton.onclick = addSelect.bind(null, data[prop], addButton);

		container.appendChild(addButton);

	
		if (repData) {
			if (extraExists) {
				for (let i = 0; i < repData.length; i++) {
					let source = repData[i].source;
					let extra = repData[i].extra;
					//console.log("extra: " + extra + (typeof extra));
					total++;
				        let box = createSelectBox(data[prop], withInput, source, extra);	
					
					selectsValues[total] = source;
					addButton.parentElement.insertAdjacentElement("beforebegin", box);
				}	
			} else {
				for (let i = 0; i < repData.length; i++) {
					total++;
				        let box = createSelectBox(data[prop], withInput, repData[i]);
					
					selectsValues[total] = repData[i];
					addButton.parentElement.insertAdjacentElement("beforebegin", box);
				}		
			}
		} 

		return outer;
	}

	function createOption(parentTag, value, selected) {
		let option = document.createElement("option");
		option.setAttribute("value", value);
		if (selected) option.setAttribute("selected", "selected");
		option.innerHTML = value;
		parentTag.appendChild(option);
	}

	function createSelectBox(array, withInput, value, extra) {
		let container = document.createElement("div");

		selectTag = document.createElement("select");
		selectTag.className = "multiSelect";
		selectTag.name = total + shortMiddleName;
		selectTag.oninput = addValueToArray.bind(null, selectTag, withInput);	
		sort(array);
		for (let i = 0; i < array.length; i++) {
			//let matched = (value != array[i] ? false : true);
			createOption(selectTag, array[i], false);
		}
		
		if (value == undefined) {
			createOption(selectTag, "null", true);
		} else {
			createOption(selectTag, "null", false);
			selectTag.value = value;
			if (array.indexOf(value) == -1) {
				showWarning("The label \"" + value + "\" doesn't exist in your current labels list");
			}
		}


		container.appendChild(selectTag);
		let inputText;
		if (withInput) {
			inputText = document.createElement("input");
			inputText.name = total + shortMiddleName + "t";
			inputText.type = "text";
			if (typeof extra == "string") {
				inputText.value = extra;
			} else if (extra === undefined) { 
				inputText.disabled = true;
			}
			inputText.style.width = "40px";
			container.appendChild(inputText);
		}

		let deleteSelectButton = document.createElement("span");
		deleteSelectButton.className = "deleteSelectButton";
		deleteSelectButton.textContent = "✕";

		deleteSelectButton.onclick = function() {
			let aSelectBox = deleteSelectButton.parentElement;
			let indexOfSB;
			for (indexOfSB = 0; indexOfSB < aSelectBox.parentElement.childNodes.length; indexOfSB++) {
				if (aSelectBox.parentElement.childNodes[indexOfSB] == aSelectBox) {
					break;
				}
			}

			aSelectBox.parentElement.removeChild(aSelectBox);

			total--;
			selectsValues.splice(indexOfSB + 1, 1);
		}
		
		container.appendChild(deleteSelectButton);
		return container;
	}


	function addSelect(array, addButton) {
		if (data[prop].length != 0) {
			if (selectsValues[total] != "null") {
				if (data[prop].length == selectsValues.length - 1) {
					alert("There are enough select boxes to select all the labelss. If you are looking for another label, please add it to my_labels table first.");
					return;
				}
				total++;
				selectsValues[total] = "null";
				//console.log(selectsValues);
				addButton.parentElement.insertAdjacentElement("beforebegin", createSelectBox(array , withInput));
			} else {
				if (data[prop].length == selectsValues.length - 1) {
					alert("There are enough select boxes to select all the labelss. If you are looking for another label, please add it to my_labels table first.");
					return;
				} else {
					alert("Please select an not-null label with the previous select element first.");
				}
			}
		} else {
			alert("There is no labels");
		}
	}


	function indexOf(childNodes, node) {
		for (let i = 0; i < childNodes.length; i++) {
		   if (childNodes[i] === node) return i;
		}
		return -1;
	}

	function addValueToArray(tag, withInput) {
			let bigContainer = tag.parentNode.parentNode;
			let spanContainer = tag.parentNode;
			let index = indexOf(bigContainer.childNodes, spanContainer);
		if (selectsValues.length == 0 || selectsValues.indexOf(tag.value) == -1) {
			selectsValues[index + 1] = tag.value;
		} else {
			selectsValues[index + 1] = tag.value;
			if (tag.value != "null") {
				alert("It already has been seleted. Please select a different label.");
				tag.value = "null";
				selectsValues[index + 1] = "null";
			} else {
				selectsValues[index + 1] = "null";
			}
		}

		if (withInput) {
			if (tag.value != "null") {
				tag.nextElementSibling.disabled = false;
			} else {
				tag.nextElementSibling.disabled = true;
			}
		}
	}
	return createController();
}
