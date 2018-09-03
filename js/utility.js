function elt(type, className, id) {
	let elt = document.createElement(type);
	if (className) {
		elt.className = className;
	}
	if (id) {
		elt.id = id;
	}
	return elt;
}

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

function setOptions(select, valArr) {
	sort(valArr);
	for (let i = 0; i < valArr.length; i++) {
		createOption(select, valArr[i], false);
	}
	createOption(select, "null", true);
}

//https://stackoverflow.com/questions/6274339/how-can-i-shuffle-an-array
function shuffle(a) {
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}
