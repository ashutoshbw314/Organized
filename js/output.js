let outputNo = 0;

function showOutput(outputContainer) {
	outputNo++;
	let p = document.createElement("pre");
	p.textContent = outputNo + " " + data.output;
	outputContainer.querySelector("#outputContent").insertAdjacentElement("afterbegin", p);
}
