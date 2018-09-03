

function currentTime() {
	let d = new Date();
	var date = new Date();
	h = date.getHours();
	if (h > 12) {
		h = h - 12;
	}
	h = ("0" + h).slice(-2);
	
	m = date.getMinutes();
	m = ("0" + m).slice(-2);
	
	s = date.getSeconds();
	s = ("0" + s).slice(-2);
	
	return h + ":" + m + ":" + s;
}

function showWarning(text) {
	outputNo++;
	let p = document.createElement("p");
	p.textContent = outputNo + " (" + currentTime() + ") => Warning: " + text;
	outputContainer.querySelector("#outputContent").insertAdjacentElement("afterbegin", p);
}
