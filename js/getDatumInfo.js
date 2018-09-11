// css: datumInfoCon.css
// js: ajax.js, utility.js

function encodeIdCol(id, col) {
	return "id=" + id + "&col=" + col;
}

function getDatumInfo(id, action, parentElt) {
	let phpAddress = "../php/getData/getFromOrganized.php";
	let info = {};
	info.id = id;
	getinfo(phpAddress, encodeIdCol(id, "datum"), info, "datum",
		function () {
			getinfo(phpAddress, encodeIdCol(id, "datum_type"), info, "datumType",
				function() {
					if (info.datumType.length == 0) {
						info.datumType = "empty";
					}
					getinfo(phpAddress, encodeIdCol(id, "my_labels"), info, "myLabels",
						function() {
							info.myLabels = JSON.parse(info.myLabels);
							getinfo(phpAddress, encodeIdCol(id, "datum_source"), info, "datumSource",
								function() {
									getinfo(phpAddress, encodeIdCol(id, "datum_source_detail"), info, "datumSourceDetail",
										function() {
											getinfo(phpAddress, encodeIdCol(id, "context"), info, "context",
												function() {
													getinfo(phpAddress, encodeIdCol(id, "meaning"), info, "meaning",
														function() {
															getinfo(phpAddress, encodeIdCol(id, "examples"), info, "examples",
																function() {
																	getinfo(phpAddress, encodeIdCol(id, "play"), info, "play",
																		function() {
																			getinfo(phpAddress, encodeIdCol(id, "my_note"), info, "myNote",
																				function() {
																					getinfo(phpAddress, encodeIdCol(id, "easyness_intensity"), info, "easynessIntensity",
																						function() {
																							getinfo(phpAddress, encodeIdCol(id, "emotions"), info, "emotions",
																								function() {
																									info.emotions = JSON.parse(info.emotions);
																									action(info, parentElt);
																							});
																					});
																			});																		
																	});																
															});
													});	
											});
									});
							});	
					});			
			});
	});
}

function display(data, parentElt) {
	let con = elt("div", null, "datumInfoCon");
	let leftSide = elt("div", null, "leftSide");	
	let rightSide = elt("div", null, "rightSide");	
	let conFlex = elt("div", null, "conFlex");


	// datum and type
	let dtBox = elt("div", "datumInfoItemBox");
	let datum = elt("div", null, "theDatum");
	let datumTypeId = elt("span", null, "datumTypeId");
	datum.textContent = data.datum;
	datumTypeId.innerHTML = "Type: <b>" + data.datumType + "</b>, id: <b>" + data.id + "</b>";

	dtBox.appendChild(datumTypeId);
	dtBox.appendChild(datum);
	con.appendChild(dtBox);

	let contentFullElts = [];

	// my labels
	if (data.myLabels.length != 0) {
		let mlBox = elt("div", "datumInfoItemBox");
		let mlCaption = elt("span", "caption");
		mlCaption.textContent = "My labels";
		let ml = elt("div", null, "ml");
		ml.textContent = data.myLabels.join(", ");
		mlBox.appendChild(mlCaption);	
		mlBox.appendChild(ml);
		contentFullElts.push(mlBox);
	}


	// source and source detail
	if (data.datumSource != "" && data.datumSource != "null") {
		let dsBox = elt("div", "datumInfoItemBox");
		let sourceCaption = elt("span", "caption");
		sourceCaption.textContent = "Source and source detail";
		let sd = elt("div", null, "sourceAndDetail");
		let innerHTML = "Source: <b>" + data.datumSource + "</b><br>";

		if (data.datumSourceDetail != "") { 
			 innerHTML +=	"Source detail: <b>" + data.datumSourceDetail + "</b>";
		}
		sd.innerHTML = innerHTML;
		dsBox.appendChild(sourceCaption);	
		dsBox.appendChild(sd);
		contentFullElts.push(dsBox);
	}

	// context
	if (data.context != "") {
		let contextBox = elt("div", "datumInfoItemBox");
		let contextCaption = elt("span", "caption");
		contextCaption.textContent = "Context";
		let context = elt("div", null, "contextActv");
		context.textContent = data.context;
		contextBox.appendChild(contextCaption);	
		contextBox.appendChild(context);
		contentFullElts.push(contextBox);
	}

	// meaning
	if (data.meaning != "") {
		let mnBox = elt("div", "datumInfoItemBox");
		let mnCaption = elt("span", "caption");
		mnCaption.textContent = "Meaning";
		let meaning = elt("div", null, "meaningActv");
		meaning.textContent = data.meaning;
		mnBox.appendChild(mnCaption);	
		mnBox.appendChild(meaning);
		contentFullElts.push(mnBox);
	}

	// examples
	if (data.examples != "") {
		let exmBox = elt("div", "datumInfoItemBox");
		let exmCaption = elt("span", "caption");
		exmCaption.textContent = "Examples";
		let examples = elt("div", null, "examplesActv");
		examples.textContent = data.examples;
		exmBox.appendChild(exmCaption);	
		exmBox.appendChild(examples);
		contentFullElts.push(exmBox);
	}

	// play
	if (data.play != "") {
		let playBox = elt("div", "datumInfoItemBox");
		let playCaption = elt("span", "caption");
		playCaption.textContent = "Play";
		let play = elt("div", null, "playActv");
		play.textContent = data.play;
		playBox.appendChild(playCaption);	
		playBox.appendChild(play);
		contentFullElts.push(playBox);
	}

	// note
	if (data.myNote != "") {
		let noteBox = elt("div", "datumInfoItemBox");
		let noteCaption = elt("span", "caption");
		noteCaption.textContent = "My Note";
		let note = elt("div", null, "myNote");
		note.textContent = data.myNote;
		noteBox.appendChild(noteCaption);	
		noteBox.appendChild(note);
		contentFullElts.push(noteBox);
	}

	// easyness
	if (data.easynessIntensity != "" && data.easynessIntensity != "0") {
		let eiBox = elt("div", "datumInfoItemBox");
		let eiCaption = elt("span", "caption");
		eiCaption.textContent = "Easyness";
		let ei = elt("div", null, "ei");
		ei.textContent = data.easynessIntensity;
		eiBox.appendChild(eiCaption);	
		eiBox.appendChild(ei);
		contentFullElts.push(eiBox);
	}


	// emotion
	function getBasicEmo(p, s, t) {
		let a = [t, s, p];
		for (let i = 0; i < a.length; i++) {
			if (a[i] != "null") {
				return a[i];
			}
		}
	}
	if (data.emotions != null && data.emotions.basic.length + data.emotions.complex.length > 0) {
		let emoBox = elt("div", "wordInfoItemBox");
		let emoCaption = elt("span", "caption");
		emoCaption.textContent = "Emotions";
		let emo = elt("div", null, "emo");
		for (let i = 0; i < data.emotions.basic.length; i++) {
			let basicEmo = elt("div");
			let currBasicData = data.emotions.basic[i];
			
			basicEmo.textContent =
			(currBasicData[0]) + " " + 
			getBasicEmo(currBasicData[1], currBasicData[2], currBasicData[3]) + " " +
			(currBasicData[4]);
			emo.appendChild(basicEmo);
		}
		for (let i = 0; i < data.emotions.complex.length; i++) {
			let complexEmo = elt("div");
			let currComplexData = data.emotions.complex[i];
			
			complexEmo.textContent =
			currComplexData[0] + " " + 
			currComplexData[1] + " " +
			currComplexData[2];
			emo.appendChild(complexEmo);
		}
		emoBox.appendChild(emoCaption);	
		emoBox.appendChild(emo);
		contentFullElts.push(emoBox);
	}


	for (let i = 0; i < contentFullElts.length; i++) {
		if (i <= contentFullElts.length / 2) {
			leftSide.appendChild(contentFullElts[i]);
		} else {
			rightSide.appendChild(contentFullElts[i]);
		} 
	}

	conFlex.appendChild(leftSide);
	if (rightSide.childNodes.length != 0) {
		conFlex.appendChild(rightSide);
	}
	con.appendChild(conFlex);

	parentElt.appendChild(con);
/*
*/
}
