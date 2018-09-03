<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Flash Card Quiz</title>
<link rel="stylesheet" type="text/css" href="../css/datumThings.css"/>
<link rel="stylesheet" type="text/css" href="../css/type.css"/>
<link rel="stylesheet" type="text/css" href="../css/card.css"/>
<style>
#maxCards {
	width: 50px;
	height: 25px;
 	margin: 3px;
}

p {
	margin: 2px 0 5px 0;
}

.cardSideOption {
	margin: 3px;
	border-radius: 8px;
	padding-left: 5px;
	padding-right: 5px;
}

#sideSelectionBox {
	margin: 2px 0 10px 0;

	-webkit-user-select: none; /* Safari 3.1+ */
	-moz-user-select: none; /* Firefox 2+ */
	-ms-user-select: none; /* IE 10+ */
	user-select: none; /* Standard syntax */
}

#responseCount {
	position: absolute;
  bottom: 0;
	right: 0;
	padding: 4px;
	border-top-left-radius: 4px;
	background: lightblue;
}

html, body {
	height: 100%;
	margin: 0;
}

body {
	background: rgb(49,83,105);
	background: linear-gradient(27deg, rgba(49,83,105,1) 7%, rgba(167,229,255,1) 100%);
	color: #222;
}

h1 {
	margin: 0;
	color: #345;
	letter-spacing:10px;
	padding-top:20px;
	padding-bottom:20px;
}

textarea {
	background: rgba(255,255,255, 0.3);
}

.sHeading {
	font-weight: bold;
}

#spMessage, #iwrMessage, #iwwMessage {
	font-weight: bold;
	font-style: italic;
 
}
</style>
</head>
<body>
<article>
<h1>Flash Card Quiz</h1>
<a href="../index.php">Back to home</a> <br><br>
<div> <span class="sHeading">Datum Type:</span> <span id="typeCon"></span></div>
<div id="sideSelectionBox">
<p class="sHeading">Select front side(green) and back side(blue):</p>
<span id="datum" class="cardSideOption">Datum</span>
<span id="meaning" class="cardSideOption">Meaning</span>
<span id="examples" class="cardSideOption">Examples</span>
<span id="play" class="cardSideOption">Play</span>
<span id="my_note" class="cardSideOption">My note</span>
<span id="context" class="cardSideOption">Context</span>
</div>


<div>
	<span class="sHeading">Max Cards:</span> <input type="number" id="maxCards" min="1" max="100">
</div>

<div>
	<span class="sHeading">Choose the pile:</span> <br>
	<input type="radio" value="startPile" id="startPile" name="pile"/>
	<label for="startPile">Start pile <span id="spMessage"></span></label> <br>
	<label for="spCondition" style="margin-left: 26px">Condition:</label> 
	<textarea id="spCondition"></textarea><span id="spConditionMessage"></span>
	<input type="radio" value="iwasrightPile" id="iwasrightPile" name="pile"/>
	<label for="iwasrightPile">I was right pile</label> <span id="iwrMessage"></span><br>
	<input type="radio" value="iwaswrongPile" id="iwaswrongPile" name="pile"/>
	<label for="iwaswrongPile">I was wrong pile</label> <span id="iwwMessage"></span>
</div>

<div>
	<span class="sHeading">Mode:</span> 
	<input type="radio" value="trial" id="trial" name="mode"/>
	<label for="trial" name="pile">Trial</label>
	<input type="radio" value="real" id="real" name="mode"/>
	<label for="real">Real</label>
</div>

<button type="button" id="startQuizButton">Start the Quiz</button>
<div id="responseCount"></div>
</article>
<script src="../js/ajax.js"></script>
<script src="../js/utility.js"></script>
<script src="../js/type.js"></script>
<script src="../js/type_utility.js"></script>
<script src="../js/synAyn.js"></script>
<script>
let typeCon = document.getElementById("typeCon");
let maxCardsInput = document.getElementById("maxCards");
let sideSelectionBox = document.getElementById("sideSelectionBox");
repSelects(type, typeCon, ["English", "Word"], handleSynAyn);
let startQuizButton = document.getElementById("startQuizButton");

let spRadio = document.getElementById("startPile");
let iwrRadio = document.getElementById("iwasrightPile");
let iwwRadio = document.getElementById("iwaswrongPile");
let trialRadio = document.getElementById("trial"); 
let realRadio = document.getElementById("real"); 

let spMessage = document.getElementById("spMessage");
let spConditionMessage = document.getElementById("spConditionMessage");
let iwrMessage = document.getElementById("iwrMessage");
let iwwMessage = document.getElementById("iwwMessage");

let spCondition = document.getElementById("spCondition");
let responseCountElt = document.getElementById("responseCount");
let responseCount = 0;
responseCountElt.textContent = "";

function handleSynAyn() {
	if (extractFullType(typeCon) == "English > Synonyms and Antonyms") {
		sideSelectionBox.style.display = "none";
	} else {
		sideSelectionBox.style.display = "block";
	}
}

let frontColor = "#beff5e";
let backColor = "#aadaff";
let frontOption = "meaning";
let backOption = "datum";

let curSelectedOption = null;
let curSelectedSide = null;

document.querySelector("#" + frontOption).style.backgroundColor = frontColor;
document.querySelector("#" + backOption).style.backgroundColor = backColor;

let cardSideOptions = document.querySelectorAll(".cardSideOption");
for (let i = 0; i < cardSideOptions.length; i++) {
	cardSideOptions[i].onclick = function() {
		let option = cardSideOptions[i];
		if ((frontOption == option.id || backOption == option.id) && option.id != curSelectedOption) {
			option.style.boxShadow = "0px 0px 4px 0px #000";
			
			option.id == frontOption ? curSelectedSide = "front" : curSelectedSide = "back";
		

			if (curSelectedOption != null) {
				document.querySelector("#" + curSelectedOption).style.boxShadow = "";
			}
			curSelectedOption = option.id;
		} else if (frontOption == option.id || backOption == option.id) {
			document.querySelector("#" + curSelectedOption).style.boxShadow = "";
			curSelectedOption = null;
			curSelectedSide = null;
		} else if (curSelectedSide != null) {
			document.querySelector("#" + curSelectedOption).style.backgroundColor = "";
			document.querySelector("#" + curSelectedOption).style.boxShadow = "";
			if (curSelectedSide == "front") {
				frontOption = option.id;
				option.style.backgroundColor = frontColor;
			} else {
				backOption = option.id;
				option.style.backgroundColor = backColor;
			}
			curSelectedSide = null;
			curSelectedOption = null;
		}
	}
}

let data = {};
getinfo("../php/getData/getAllSynsAynsForQuiz.php", 
		   "spCondition=" + encodeURIComponent(spCondition.value.trim()), data, "allSynsAyns", handleAllSynsAyns);

function handleAllSynsAyns() {
	let datumType = extractFullType(typeCon);
	if (datumType == "English > Synonyms and Antonyms") {
		data.allSynsAyns = JSON.parse(data.allSynsAyns);
		if (Array.isArray(data.allSynsAyns) == false) {
			spConditionMessage.textContent = data.allSynsAyns.error;
		}	else {
			spConditionMessage.textContent = "";
			let cards = [];
			for (let i = 0; i < data.allSynsAyns.length; i++) {
				let synAyn = data.allSynsAyns[i].synAyn;
				let id = data.allSynsAyns[i].id;
				let words = synAyn.match(/[^,|\s]+/g);
				for (let j = 0; j < words.length; j++) {
					let card = {};
					card.front = words[j];
					card.back = id;
					cards.push(card);
				}
			}

			
			getinfo("../php/getData/getAllSynAynCardsFromIWPiles.php", "", data, 
				"usedCards", excludeCardsFromIWPiles);

			function excludeCardsFromIWPiles() {
				data.usedCards = JSON.parse(data.usedCards);
				let availableCards = [];
				for (let i = 0; i < cards.length; i++) {
					let card = cards[i];
					let cardIsUsed = false;
					for (let j = 0; j < data.usedCards.length; j++) {
						if (card.front == data.usedCards[j].front) {
							cardIsUsed = true;
							break;
						} 
					}
					if (cardIsUsed == false) {
						availableCards.push(card);
					}	
				}
				if (getCurPile() == "start_pile") {
					data.synAynCards = availableCards;
					responseCount++;
					responseCountElt.textContent = "synAynCards received: " + responseCount;
				} 
				spMessage.textContent = "(" + availableCards.length + " cards)" ;
			}
			
			if (getCurPile() != "start_pile") {
				let pile = getCurPile();
				getinfo("../php/getData/getAllSynAynCardsFromIWPile.php", "pile=" + pile, data, 
					"synAynCards", function() {
					data.synAynCards = JSON.parse(data.synAynCards);
					responseCount++;
					responseCountElt.textContent = "synAynCards received: " + responseCount;
				});
			}
		}
	}
	setTimeout(function() {
		getinfo("../php/getData/getAllSynsAynsForQuiz.php", 
	   "spCondition=" + encodeURIComponent(spCondition.value.trim()), data, "allSynsAyns", handleAllSynsAyns);
	}, 800);	
}

function startQuiz() {
	let mode = (realRadio.checked == true ? "real" : "trial");
	let datumType = extractFullType(typeCon);
	if (datumType != "English > Synonyms and Antonyms") {
		let encodedData = "datumType=" + encodeURIComponent(datumType) + "&" +
				  "front=" + frontOption + "&" +
				  "back=" + backOption + "&" +
				  "limit=" + maxCardsInput.value + "&" +
					"pile=" + getCurPile() + "&" +
				  "spCondition=" + encodeURIComponent(spCondition.value.trim());
		getinfo("../php/getData/getRandomCards.php", encodedData, data, "cards", function() {
			data.cards = JSON.parse(data.cards);
			if (data.cards == null || data.cards.length == 0) {
				alert("No cards found");
			} else {
				launchQuiz(data.cards, mode);
			}
		});
	} else {
		let limit = Number(maxCardsInput.value);
		let allCards = data.synAynCards;
		if (allCards == undefined) {
			alert("Cards are loading. Please wait...");
		} else {
			if (allCards.length == 0) {
				alert("No cards found");
			} else {
				shuffle(allCards);
				if (limit > allCards.length) {
					limit = allCards.length;
				}
				data.shortSynAynCards = allCards.slice(0, limit);
				let longSynAynCards = [];
				for (let i = 0; i < data.shortSynAynCards.length; i++) {
					let sCard = data.shortSynAynCards[i];
					let lCard = {};
					let synAyn = "";
					for (let i = 0; i < data.allSynsAyns.length; i++) {
						if (data.allSynsAyns[i].id == sCard.back) {
							synAyn = data.allSynsAyns[i].synAyn;
						}
					}
					lCard.front = "What are the synonyms and antonyms of <b><i>" + sCard.front + "</i></b>?";
					lCard.back = [[synAyn, datumType]];
					longSynAynCards.push(lCard);
				}
				launchQuiz(longSynAynCards, mode);
			}
		}
	}
}

startQuizButton.onclick = startQuiz;

function launchQuiz(cards, mode) {
	console.log(cards);
	let quizWindow = elt("div", null, "quizWindow");
	let cardElt = elt("div", null, "card");
	let frontSide = elt("div", null, "frontSide");
	let backSide = elt("div", null, "backSide");
	cardElt.appendChild(frontSide);
	cardElt.appendChild(backSide);


	/************ make the front side start ************/
	let frontContent = elt("div", "cardContent");
	frontContent.innerHTML = cards[0].front;
	frontContent.style.boxShadow = "inset 0 0 10px #beff5e";
	frontSide.appendChild(frontContent);
	/************ make the front side end ************/

	/************ make the back side start ************/
	let userAnswer = null;
	let leftArrow = elt("div", null, "leftArrow");
	leftArrow.textContent = "〈";
	let rightArrow = elt("div", null, "rightArrow");
	rightArrow.textContent = "〉";
	let backContent = elt("div", "cardContent");
	backContent.innerHTML = "<p class='backDatumType'>" + cards[0].back[0][1].split(" > ").join(" &gt; ") +
												  "</span> <p>" + cards[0].back[0][0] + "</p>";
	backContent.style.boxShadow = "inset 0 0 10px #aadaff";
	let iwasright = elt("div", null, "userAnswerPos");
	iwasright.textContent = "I was right";
	let iwaswrong = elt("div", null, "userAnswerNeg");
	iwaswrong.textContent = "I was wrong";

	iwasright.onclick = function() {
		userAnswer = true;
		iwasright.style.background = "#60bf5b";
		iwasright.style.boxShadow = "0px 0px 5px 0px green";
		iwasright.style.color = "#444";
		
		iwaswrong.style.background = "#555";
		iwaswrong.style.boxShadow = "";
	}

	iwaswrong.onclick = function() {
		userAnswer = false;
		iwaswrong.style.background = "#e85353";
		iwaswrong.style.boxShadow = "0px 0px 5px 0px red";

		iwasright.style.background = "#555";
		iwasright.style.boxShadow = "";
		iwasright.style.color = "#ddd";
	}

	backSide.appendChild(backContent);
	backSide.appendChild(iwasright);
	backSide.appendChild(iwaswrong);
	/************ make the back side end ************/

	let progressBar = createProgressBar(cards.length);
	let pCircles = progressBar.querySelectorAll(".progressCircle");
	let pSticks = progressBar.querySelectorAll(".progressStick");
	quizWindow.appendChild(progressBar);	

	let closeQuizButton = elt("div", null, "closeQuizButton");
	closeQuizButton.textContent = "✕";

	quizWindow.appendChild(cardElt);
	quizWindow.appendChild(closeQuizButton);

	closeQuizButton.onclick = function() {
		document.body.removeChild(quizWindow);
	}

	let curSide = "front";
	let curCardIndex = 0;
	let flipCounts = 0;

	addEventListener("keyup", function handleCard(event) {
		event.preventDefault();
		if (event.keyCode == 32) {
			if (curSide == "front") {
				frontSide.style.transform = "perspective(1000px) rotateY(-180deg)";
				backSide.style.transform = "perspective(1000px) rotateY(0deg)";
				curSide = "back";

				if (flipCounts == 0) {
					setTimeout(function () {
						if (cards[curCardIndex].back.length == 1) {
							backContent.innerHTML = "<p class='backDatumType'>" + 
																			cards[curCardIndex].back[0][1].split(" > ").join(" &gt; ") +
												 				 			"</span> <p>" + cards[curCardIndex].back[0][0] + "</p>";
						} else {
							let curContentNo = 0;
							backContent.innerHTML = "<p class='backDatumType'>" + 
																			cards[curCardIndex].back[0][1].split(" > ").join(" &gt; ") +
												  						"</span> <p>" + cards[curCardIndex].back[0][0] + "</p>";
							let contentNav = document.querySelector("#contentNavCon");
							if (contentNav == null) {
								contentNav = createContentNav(cards[curCardIndex].back.length);
								contentNav.childNodes[curContentNo].style.background = "rgba(255, 255, 255, 0.7)";

								let circles = contentNav.childNodes;
								for (let i = 0; i < circles.length; i++) {
									let circle = circles[i];
									circle.onclick = function() {
										for (let j = 0; j < circles.length; j++) {
											circles[j].style.background = "";
										}
										circles[i].style.background = "rgba(255, 255, 255, 0.7)";
										curContentNo = i;
										backContent.innerHTML = "<p class='backDatumType'>" + 
																						cards[curCardIndex].back[curContentNo][1].split(" > ").join(" &gt; ") +
												 										"</span> <p>" + cards[curCardIndex].back[curContentNo][0] + "</p>";
										if (curContentNo == 0) {
											backSide.removeChild(leftArrow);
											backSide.appendChild(rightArrow);
										} else if (curContentNo + 1 == cards[curCardIndex].back.length) {
											backSide.removeChild(rightArrow);
											backSide.appendChild(leftArrow);
										} else {
											backSide.appendChild(leftArrow);
											backSide.appendChild(rightArrow);
										}
									};
								}

								backSide.appendChild(contentNav);
							}
							backSide.appendChild(rightArrow);
							rightArrow.onclick = function() {
								curContentNo++;
								if (curContentNo + 1 == cards[curCardIndex].back.length) {
									backSide.removeChild(rightArrow);
								}

								for (let i = 0; i < contentNav.childNodes.length; i++) {
									contentNav.childNodes[i].style.background = "";
								}
								contentNav.childNodes[curContentNo].style.background = "rgba(255, 255, 255, 0.7)";

								backContent.innerHTML = "<p class='backDatumType'>" + 
																				cards[curCardIndex].back[curContentNo][1].split(" > ").join(" &gt; ") +
												 								"</span> <p>" + cards[curCardIndex].back[curContentNo][0] + "</p>";
								backSide.appendChild(leftArrow);
							}
							leftArrow.onclick = function() {
								curContentNo--;
								if (curContentNo == 0) {
									backSide.removeChild(leftArrow);
								}

								for (let i = 0; i < contentNav.childNodes.length; i++) {
									contentNav.childNodes[i].style.background = "";
								}
								contentNav.childNodes[curContentNo].style.background = "rgba(255, 255, 255, 0.7)";

								backContent.innerHTML = "<p class='backDatumType'>" + 
																				cards[curCardIndex].back[curContentNo][1].split(" > ").join(" &gt; ") +
												 								"</span> <p>" + cards[curCardIndex].back[curContentNo][0] + "</p>";
								backSide.appendChild(rightArrow);
							}
						}
					}, 50);
				}				
			} else {
				frontSide.style.transform = "perspective(1000px) rotateY(0deg)";
				backSide.style.transform = "perspective(1000px) rotateY(180deg)";
				curSide = "front";
				if (userAnswer != null) {
					flipCounts = 0;
					setTimeout(function () {
						iwasright.style.background = "#555";
						iwasright.style.boxShadow = "";
						iwasright.style.color = "#ddd";
						iwaswrong.style.background = "#555";
						iwaswrong.style.boxShadow = "";
						let contentNavCon = document.querySelector("#contentNavCon");
						let leftArrow = document.querySelector("#leftArrow");
						let rightArrow = document.querySelector("#rightArrow");
						if (contentNavCon) backSide.removeChild(contentNavCon);
						if (leftArrow) backSide.removeChild(leftArrow);
						if (rightArrow) backSide.removeChild(rightArrow);

						curCardIndex++;
						if (userAnswer) {
							pCircles[curCardIndex - 1].style.backgroundColor = "rgb(98, 244, 65)";
						} else {
							pCircles[curCardIndex - 1].style.backgroundColor = "rgb(244, 66, 66)";
						}
						if (!(curCardIndex + 1 > cards.length)) {
							frontContent.innerHTML = cards[curCardIndex].front;
						} else {
							quizWindow.removeChild(cardElt);
							let score = elt("div", null, "score");
							let totalGreens = 0;
							for (let i = 0; i < pCircles.length; i++) {
								if (pCircles[i].style.backgroundColor == "rgb(98, 244, 65)") totalGreens++;
							}
							score.textContent = Math.round((100 * totalGreens) / cards.length) + "% correct\r\n" +
												cards.length + " cards total\r\n" +
												totalGreens + " right\r\n" +
												(cards.length - totalGreens) + " wrong";
							quizWindow.appendChild(score);
							removeEventListener("keyup", handleCard);
						}
						if (mode == "real") {
								let pile = getCurPile();
								let card = cards[curCardIndex - 1];
								let datumType = extractFullType(typeCon);
								let frontCol = frontOption;
								let backCol = backOption;
								if (datumType == "English > Synonyms and Antonyms") {
									card = data.shortSynAynCards[curCardIndex - 1];
									frontCol = "";
									backCol = "datum";
								}
								let encodedData = "datumType=" + encodeURIComponent(datumType) + "&" +
																	"frontCol=" + frontCol + "&" +
																	"backCol=" + backCol + "&" +
																	"card=" + encodeURIComponent(JSON.stringify(card)) + "&" + 
																	"pile=" + pile + "&" +
																	"userAnswer=" + (userAnswer == true ? "right" : "wrong");
								getinfo("../php/saveQuizData.php", encodedData, data, "saveQuizResponse");
						}	
						userAnswer = null;
					}, 50);
				} else {
					flipCounts++;
				}
			}
		}
	});
	document.body.appendChild(quizWindow);	
}

window.onload = function() {
	maxCardsInput.value = "5";
	spRadio.checked = true;
	realRadio.checked = true;
}

function createProgressBar(n) {
	let con = elt("div", null, "progressBarCon");
	con.style.width = ((15 * n) + (26 * (n -1))) + "px"; 
	for (let i = 0; i < n; i++) {
		let circle = elt("div", "progressCircle");
		let stick = elt("div", "progressStick");
		con.appendChild(circle);
		if (i != n -1) {
			con.appendChild(stick);
		}
	}
	return con;
}

function createContentNav(n) {
	let con = elt("div", null, "contentNavCon");
	con.style.width = ((8 * n) + (10 * (n -1))) + "px"; 
	for (let i = 0; i < n; i++) {
		let circle = elt("div", "navCircle");
		con.appendChild(circle);
	}
	return con;
}


/*********** Handle Pile Stats **********/
function getCurPile() {
	if (spRadio.checked) {
		return "start_pile";
	} else if (iwrRadio.checked) {
		return "iwasright_pile";
	} else if (iwwRadio.checked) {
		return "iwaswrong_pile";
	}
}

function encodeDataForPileStats() {
	let datumType = extractFullType(typeCon);
	let encodedData = "datumType=" + encodeURIComponent(datumType) + "&" +
		  "front=" + frontOption + "&" +
		  "back=" + backOption + "&" +
		  "spCondition=" + encodeURIComponent(spCondition.value.trim());
	return encodedData;
}

getinfo("../php/getData/getPileStats.php", encodeDataForPileStats(), data, "pileStats", handlePileStats);

function handlePileStats() {
	data.pileStats = JSON.parse(data.pileStats);

	let datumType = extractFullType(typeCon);
	if (datumType != "English > Synonyms and Antonyms") {
		spMessage.textContent = data.pileStats[0][0];
		spConditionMessage.textContent = data.pileStats[0][1];
	}
		iwrMessage.textContent = data.pileStats[1];
		iwwMessage.textContent = data.pileStats[2];
	setTimeout(function() {
		getinfo("../php/getData/getPileStats.php", encodeDataForPileStats(), data, "pileStats", handlePileStats);
	}, 800);	
}
</script>
</body>
</html>
