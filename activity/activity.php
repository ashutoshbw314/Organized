<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Activity</title>
<link rel="stylesheet" type="text/css" href="../css/datumThings.css"/>
<link rel="stylesheet" type="text/css" href="../css/calendar.css"/>
<link rel="stylesheet" type="text/css" href="../css/activity.css"/>
<link rel="stylesheet" type="text/css" href="../css/note.css"/>
<link rel="stylesheet" type="text/css" href="../css/dataInfo.css"/>
<link rel="stylesheet" type="text/css" href="../css/datumInfoCon.css"/>
<link rel="stylesheet" type="text/css" href="../css/type.css"/>
<link rel="stylesheet" type="text/css" href="../css/output.css"/>
<style>
a {
 text-decoration: none;
}

a:hover {
	text-decoration: underline;
}
</style>
</head>
<body>
<h1>Activity calendar</h1>
<article>
<a href="../index.php">Back to home</a> <br><br>
<div class="h">Select datum type below to show activities of a particular type</div>
<div id="typeCon"></div><br>
<div class="h">Calendar</div>
<div id="about_activity">
	<div id="top">
	</div>
	<div id="stats">
		<div id="total">
			<span class="stat_title">Words learned</span>
			<span id="total_output"></span>
			<span id="total_range"></span>
		</div>
		<div id="longest_streaks">
			<span class="stat_title">Longest Streaks</span>
			<span id="longest_streaks_length"></span>
			<div id="longest_streaks_ranges"></div>

		</div>
		<div id="current_streaks">
			<span class="stat_title">Current Streak</span>
			<span id="current_streak_length"></span>
			<span id="current_streak_range"></span>		
		</div>
	</div>
</div>
<br>
<div id="outputContainer">
<div class="h">Outputs</div>
<div id="outputContent">

</div></div>

</article>
<script src="../js/ajax.js"></script>
<script src="../js/utility.js"></script>
<script src="../js/getDatumInfo.js"></script>
<script src="../js/activity.js"></script>
<script src="../js/note.js"></script>
<script src="../js/warning.js"></script>
<script src="../js/type.js"></script>
<script src="../js/type_utility.js"></script>
<script src="../js/encode.js"></script>

<script>
let outputNo = 0;
let data = {};
/*
let ranges = [16, 32, 48];

let activity = [
["2018-07-04","1"],
["2018-07-03","20"],
["2018-07-02","20"],
["2018-07-01","20"],

["2018-06-30","20"],
["2018-06-29","20"],

["2018-05-08","20"],
["2018-05-07","30"],
["2018-05-06","20"],
["2018-05-05","15"],

["2018-04-03","50"],
["2018-04-02","40"],
["2018-04-01","10"],


["2018-02-08","25"],

["2018-02-05","25"]
,
["2018-02-02","22"],
["2018-02-01","21"],


["2018-01-15","21"],
["2017-01-15","21"]];

let topDiv = document.getElementById("top");
let calendars = drawCalendars(activity, ranges);
topDiv.appendChild(calendars);

let lsCon = document.getElementById("longest_streaks");
let lsLenElt = document.getElementById("longest_streaks_length");
let streaks = getStreaks(activity);

let totalElt = document.getElementById("total_output");
let totalRange = document.getElementById("total_range");


let csLenElt = document.getElementById("current_streak_length");
let csRangeElt = document.getElementById("current_streak_range");

displayTotalInfo(totalElt, totalRange, activity);
displayLongestStreaks(lsCon, lsLenElt, streaks);
displayCurrentStreak(csLenElt, csRangeElt, streaks);
*/
let typeCon = document.getElementById("typeCon");
repSelects(type, typeCon, [], drawActivity);

let topDiv = document.getElementById("top");

let lsRangesCon = document.getElementById("longest_streaks_ranges");
let lsLenElt = document.getElementById("longest_streaks_length");

let totalElt = document.getElementById("total_output");
let totalRange = document.getElementById("total_range");


let csLenElt = document.getElementById("current_streak_length");
let csRangeElt = document.getElementById("current_streak_range");


function checkForUnavailableTypes() {
	getinfo("../php/getData/datum_types.php", "", data, "datum_types", doTheCheck);

	function doTheCheck() {
		data.datum_types = JSON.parse(data.datum_types);
		let unavailable_types = [];
		for (let i = 0; i < data.datum_types.length; i++) {
			let curType = data.datum_types[i].split(" > ");
			let matchedType = matchValues(type, curType);
			if (matchedType.length != curType.length) {
				unavailable_types.push(curType);
			}
		}
		for (let i = 0; i < unavailable_types.length; i++) {
			let curUT = unavailable_types[i];
			showWarning("\"" + curUT.join(" > ") + "\" type have entries but is not present in your current datum types.");
		}
	}
}
checkForUnavailableTypes();

function drawActivity() {
	getinfo("../php/getData/getActivityData.php", encodeTypeInfo(typeCon), data, "activity", drawActivityInternal);

	function drawActivityInternal() {
		let ranges = getRanges(typeCon, type);
		data.activity = JSON.parse(data.activity);
		let preCalendars = document.getElementById("calendars");
		if (preCalendars) {
			topDiv.removeChild(preCalendars);
		}
		let calendars = drawCalendars(data.activity, ranges, getCurrentType(typeCon));
		topDiv.appendChild(calendars);
		let streaks = getStreaks(data.activity);

		displayTotalInfo	(totalElt, 	totalRange, 	data.activity,	getCurrentType(typeCon));
		displayLongestStreaks	(lsRangesCon,	lsLenElt, 	streaks, 	getCurrentType(typeCon));
		displayCurrentStreak	(csLenElt, 	csRangeElt, 	streaks, 	getCurrentType(typeCon));
		getinfo("../php/getData/getDatesOfNotes.php", "", data, "noteDates", tickNoteDays);
	}
}

drawActivity();

function tickNoteDays() {
	data.noteDates = JSON.parse(data.noteDates);
	for (let i = 0; i < data.noteDates.length; i++) {
		let day = document.getElementById(data.noteDates[i]);
		if (day) {
			day.style.backgroundImage = "url(../pics/note.png)";
		}
	}
	
	let days = document.querySelectorAll(".day");
	for (let i = 0; i < days.length; i++) {
		days[i].addEventListener("click", function() {
			getinfo("../php/getData/getNoteOfDay.php", "date=" + days[i].id, data, "note", launchDayWindow.bind(null, days[i])); 
			//let nw = createDayWindow("Hello world", days[i].id, "save.php", "discard.php");
			//document.body.appendChild(nw);
		});
	}
}

function launchDayWindow(day) {
	if (day.style.backgroundImage != "") {
		let nw = createDayWindow(data.note, day, "../php/saveNote.php", "../php/discardNote.php",
		"../php/getData/getDatumAndIDsOfDay.php", typeCon);
		document.body.appendChild(nw);
	} else {
		let nw = createDayWindow(null, day, "../php/saveNote.php", "../php/discardNote.php",
		"../php/getData/getDatumAndIDsOfDay.php", typeCon);
		document.body.appendChild(nw);
	}
}
</script>
</body>
</html>
