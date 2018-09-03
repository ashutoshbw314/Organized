function encodeFimo (container, fimo) {
	let nv = "";

	let basicOnes = container.getElementsByClassName("basic" + (fimo == "f" ? "Feeling" : "Emotion"));
	let complexOnes = container.getElementsByClassName("complex" + (fimo == "f" ? "Feeling" : "Emotion"));

	let total = 0;
	for (let i = 0; i < basicOnes.length; i++) {
		let aBasicOne = basicOnes[i];

		total++;
		let dse = aBasicOne.childNodes[0];
		let pse = aBasicOne.childNodes[1];
		let sse = aBasicOne.childNodes[3];
		let tse = aBasicOne.childNodes[5];
		let ise = aBasicOne.childNodes[7];

		nv += encodeURIComponent(dse.name) + "=" + encodeURIComponent(dse.value) + "&";
		nv += encodeURIComponent(pse.name) + "=" + encodeURIComponent(pse.value) + "&";
		nv += encodeURIComponent(sse.name) + "=" + encodeURIComponent(sse.value) + "&";
		nv += encodeURIComponent(tse.name) + "=" + encodeURIComponent(tse.value) + "&";
		if (total == basicOnes.length + complexOnes.length) {
			nv += encodeURIComponent(ise.name) + "=" + encodeURIComponent(ise.value);
		} else {
			nv += encodeURIComponent(ise.name) + "=" + encodeURIComponent(ise.value) + "&";
		}
	}

	for (let i = 0; i < complexOnes.length; i++) {
		total++;
		let aComplexOne = complexOnes[i];
		let dse = aComplexOne.childNodes[0];
		let cse = aComplexOne.childNodes[1];
		let ise = aComplexOne.childNodes[3];

		nv += encodeURIComponent(dse.name) + "=" + encodeURIComponent(dse.value) + "&";
		nv += encodeURIComponent(cse.name) + "=" + encodeURIComponent(cse.value) + "&";

		if (total == basicOnes.length + complexOnes.length) {
			nv += encodeURIComponent(ise.name) + "=" + encodeURIComponent(ise.value);
		} else {
			nv += encodeURIComponent(ise.name) + "=" + encodeURIComponent(ise.value) + "&";
		}
	}
	
	let cFimoN = document.getElementsByName("complex" + (fimo == "f" ? "Feeling" : "Emotion") + "Name")[0];
	if (cFimoN) {
		nv += "&" + encodeURIComponent(cFimoN.name) + "=" + encodeURIComponent(cFimoN.value);
	}
	return nv;
}

function encodeLists(box) {
	let values = [];

	let multiSelects = box.querySelectorAll(".multiSelect");
	for (let i = 0; i < multiSelects.length; i++) {
		let value = multiSelects[i].value;
		if (value != "null") {
			values.push(value);
		}
	}		

	return encodeURIComponent(JSON.stringify(values));
}

function encodeEasyness(ei) {
	return "easynessIntensity=" + ei.value;
}

function encodeDatum(datumElt) {
	return "datum=" + encodeURIComponent(datumElt.value.trim());
}

function encodeDatumSource(sourceElt, sDetailElt) {
	let source = sourceElt.value;
	let detail = sDetailElt.value.trim();
	if (source == "null") {
		detail = "";
	}
	return "source=" + encodeURIComponent(source) + "&sDetail=" + encodeURIComponent(detail);
}

function encodeTypeInfo(typeCon) {
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
	return "datum_type=" + encodeURIComponent(values.join(" > "));
}

function encodeAll(datumElt,
		   typeCon,
		   myLabelsBox,
		   sSelect, sDetailElt,//wsBox,
		   context,
		   meaning,
		   examples,
		   play,
		   easynessInput,
		   emotionTools,
		   note
		   ) {
	result = "";
	result += encodeDatum(datumElt) + "&";
	result += encodeTypeInfo(typeCon) + "&";
	result += "myLabels=" + encodeLists(myLabelsBox) + "&";
	result += encodeDatumSource(sSelect, sDetailElt) + "&";
	result += "context=" + encodeURIComponent(context.value.trim()) + "&";
	result += "meaning=" + encodeURIComponent(meaning.value.trim()) + "&";
	result += "examples=" + encodeURIComponent(examples.value.trim()) + "&";
	result += "play=" + encodeURIComponent(play.value.trim()) + "&";
	result += encodeEasyness(easynessInput) + "&";
	result += encodeFimo(emotionTools, "e") + "&";
	result += "note=" + encodeURIComponent(note.value.trim());
	return result;
}

function encodeByCol(col, elt) {
	if (col == "context" || col == "meaning" || col == "examples" || col == "play" || col == "my_note" ||
	    col == "datum_source" || col == "datum_source_detail" || col == "easyness_intensity" || col == "datum") {
		return "theValue=" + encodeURIComponent(elt.value.trim());
	} else if (col == "my_labels") {
		let val = encodeLists(elt);
		return "theValue=" + val;
	} else if (col == "datum_type") {
		let val = encodeTypeInfo(elt);
		return "theValue=" + val.slice(val.indexOf("=") + 1);
	} else if (col == "emotions") {
		let val = encodeFimo(elt, "e");
		return val;
	}
}
