let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
let dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
let colors = {"none": "#eee",
	      "color1": "#c6e48b",
	      "color2": "#7bc96f",
	      "color3": "#239a3b",
	      "color4": "#196127"};

function getStreaks(activities) {
	let streaks = [];
	if (activities.length == 0) {
		return streaks;
	}
	let lastActivity = activities[0];
	let currentStreak = [lastActivity];
	if (activities.length > 1) {
		for (let i = 1; i < activities.length; i++) {
			if (dateDiff(activities[i][0], lastActivity[0]) == 1) {
				currentStreak.push(activities[i]);
			} else {
				streaks.push(currentStreak);
				currentStreak = [activities[i]];
			}

			if (i == activities.length - 1) {
				streaks.push(currentStreak);
			}
			lastActivity = activities[i];
		}
	} else {
		streaks.push(currentStreak);
	}
	return streaks;
}

function CreateStreak(days, from, to, quantity) {
	this.days = days;
	this.from = from;
	this.to = to;
	this.quantity = quantity;
}

function getLongestStreaks(streaks) {
	let longestStreaks = [];
	if (streaks.length == 0) {
		return [new CreateStreak(0, null, null, 0)];
	}

	let maxLength = streaks[0].length;
	for (let i = 1; i < streaks.length; i++) {
		if (streaks[i].length > maxLength) {
			maxLength = streaks[i].length;
		}
	}
	
	for (let i = 0; i < streaks.length; i++) {
		if (streaks[i].length == maxLength) {
			longestStreaks.push(streaks[i]);
		}
	}
	
	let result = [];
	
	for (let i = 0; i < longestStreaks.length; i++) {
		let from = longestStreaks[i][longestStreaks[i].length - 1][0];
		let to = longestStreaks[i][0][0]; 
		let days = longestStreaks[i].length;
		let quantity = 0;
		for (let j = 0; j < longestStreaks[i].length; j++) {
			quantity += Number(longestStreaks[i][j][1]);
		}
		result.push(new CreateStreak(days, from, to, quantity));
	}	
	return result;
}

function getCurrentStreak(streaks) {
	if (streaks.length == 0 || streaks[0][0][0] != curDate()) {
		return new CreateStreak(0, null, null, 0);
	} else if (streaks[0][0][0] == curDate()) {
		let csArray = streaks[0];
		let from = csArray[csArray.length -1][0];
		let to = csArray[0][0];
		let days = csArray.length;
		let quantity = 0;
		for (let j = 0; j < csArray.length; j++) {
			quantity += Number(csArray[j][1]);
		}
		return new CreateStreak(days, from, to, quantity);
	}
}


function getTotalInfo(activity) {
	let startDate;
	if (activity.length > 0) {
		startDate = activity[activity.length - 1][0];
	}
	let endDate = curDate();
	
	let aPastRefDate = "2017-12-31";
	let diff1 = dateDiff(aPastRefDate, endDate);
	let weekStat = diff1 % 7;

	let startDateForCY = goBackward(endDate, 364 + weekStat);	// CY means current year
	let endDateForCY = endDate;

	while (true) {	
		if (startDate == undefined || !dateLT(startDate, startDateForCY)) {
			let days = dateDiff(startDateForCY, endDate) + 1;
			let from = startDateForCY;
			let to = endDate;
			let quantity = 0;
			for (let j = 0; j < activity.length; j++) {
				quantity += Number(activity[j][1]);
			}
			return new CreateStreak(days, from, to, quantity);
			break;
		}		

		endDateForCY = goBackward(startDateForCY, 1);
		startDateForCY = goBackward(endDateForCY, 364);

		if (has29Feb(startDateForCY, endDateForCY)) {  // The HABLU leap year check algorithm is running here
			startDateForCY = goBackward(endDateForCY, 365); 
		}
	}
}

function hasCurDate(activity, date) {
	for (let i = 0; i < activity.length; i++) {
		if (activity[i][0] == date) {
			return activity[i][1];
		}
	}
	return false;
}

function nameDate(date) { 
	let dateObj = new Date(date);
	let dayNo = dateObj.getDate();
	let monthNo = dateObj.getMonth();
	let year = dateObj.getFullYear();
	return monthNames[monthNo] + " " + dayNo + ", " + year;
}

 

function drawDay(size, x, y, date, totalWords, ranges, curType) {
	let day = document.createElement("div");
	let tooltipText = document.createElement("span");
	tooltipText.className = "tooltiptext";
	tooltipText.innerHTML = "<b>" + (totalWords == 0 ? "No" : totalWords) + " " + curType + " on</b> <span class='ttDateText'>" + nameDate(date) + "</span>";
	day.appendChild(tooltipText);
	day.className = "day";

	if (totalWords == 0) {
		day.style.backgroundColor = colors.none;
		//day.style.border = "1px solid " + colors.none;
	} else if (totalWords > 0 && totalWords <= ranges[0]) {
		day.style.backgroundColor = colors.color1;
		//day.style.border = "1px solid " + colors.color1;
	} else if (totalWords > ranges[0] && totalWords <= ranges[1]) {
		day.style.backgroundColor = colors.color2;
		//day.style.border = "1px solid " + colors.color2;
	} else if (totalWords > ranges[1] && totalWords <= ranges[2]) {
		day.style.backgroundColor = colors.color3;
		//day.style.border = "1px solid " + colors.color3;
	} else {
		day.style.backgroundColor = colors.color4;
		//day.style.border = "1px solid " + colors.color4;
	} 


	day.style.width = size + "px";
	day.style.height = size + "px";
	day.style.left = x + "px";
	day.style.top = y + "px";
	day.id = date;
	day.addEventListener("click", function() {
		let days = document.querySelectorAll(".day");
		for (let i = 0; i < days.length; i++) {
			if(days[i] != day) {
				days[i].style.boxShadow = "";
			}
		}
		day.style.boxShadow = "0px 0px 2px 0px blue";
	});
	return day;
}

function drawMonthName(calendar, name, lastTwoYearDigits, x) {
	let mName = document.createElement("div");
	mName.className = "monthName";
	if (lastTwoYearDigits) {
		mName.textContent = name + "-" + lastTwoYearDigits;
	} else {
		mName.textContent = name;
	}
	mName.style.left = x + "px";
	mName.style.top = 5 + "px";
	calendar.appendChild(mName);
}

function drawDayName(calendar, name, y) {
	let dName = document.createElement("div");
	dName.className = "dayName";
	dName.textContent = name;
	dName.style.left = 5 + "px";
	dName.style.top = y + "px";	
	calendar.appendChild(dName);
}

function drawCalendar(startDate, endDate, activity, ranges, curType) {
	let size = 10;
	let padding = 2;
	let calendar = document.createElement("div");
	calendar.className = "calendar";
	
	let curDate = startDate;

	for (let i = 0; curDate != nextDate(endDate); i++, curDate = nextDate(curDate)) {
		let xi =  Math.floor(i / 7);
		let yi = (i % 7);
		let x = xi * (size + padding) + 35;
		let y = yi * (size + padding) + 22;
		let totalWords = hasCurDate(activity, curDate) == false ? 0 : Number(hasCurDate(activity, curDate));
		let day = drawDay(size, x, y, curDate, totalWords, ranges, curType);

		let dateObj = new Date(curDate);
		let dayNo = dateObj.getDate();
		let dayNoInWeek = dateObj.getDay();
		let monthNo = dateObj.getMonth();
		let lastTwoYearDigits = String(dateObj.getFullYear()).slice(2);

		if ((dayNo >= 1 && dayNo <= 7) && yi == 0) {
			if (monthNo == 0) {
				drawMonthName(calendar, monthNames[monthNo], lastTwoYearDigits, x);
			} else {
				drawMonthName(calendar, monthNames[monthNo], null, x);
			}
		}

		if (xi == 0) {
			if (yi == 1 || yi == 3 || yi == 5) {
				drawDayName(calendar, dayNames[dayNoInWeek], y);
			} 
		}		

		calendar.appendChild(day);
	}
	

	return calendar;
}

function extractNumber(stylePxNum) {
	return Number(stylePxNum.match(/\d*/)[0]);
}

function drawCalendars(activity, ranges, curType) {
	let container = document.createElement("div");
	container.id = "calendars";
	let startDate;
	if (activity.length > 0) {
		startDate = activity[activity.length - 1][0];
	}
	let endDate = curDate();
	
	let aPastRefDate = "2017-12-31";
	let diff1 = dateDiff(aPastRefDate, endDate);
	let weekStat = diff1 % 7;
	let startDateForCY = goBackward(endDate, 364 + weekStat);	// CY means current year
	let endDateForCY = endDate;

	while (true) {
		let cy = drawCalendar(startDateForCY, endDateForCY, activity, ranges, curType)
		container.appendChild(cy);
		
		if (startDate == undefined || !dateLT(startDate, startDateForCY)) {
			break;
		}		

		endDateForCY = goBackward(startDateForCY, 1);
		startDateForCY = goBackward(endDateForCY, 364);

		if (has29Feb(startDateForCY, endDateForCY)) {  // The HABLU leap year check algorithm is running here
			startDateForCY = goBackward(endDateForCY, 365); 
		}
	}
	return container;
}
//drawCalendars(activity, ranges);
/*
let a = drawCalendar("2018-01-01", "2018-12-31", activity, ranges);
let b = drawCalendar("2019-01-01", "2019-12-31", activity, ranges);
document.body.appendChild(a);
document.body.appendChild(b);
*/
function nextDate(aDate) {
	let dateObj = new Date(aDate);
	let nextDate = new Date(dateObj.getTime() + (24 * 60 * 60 * 1000));
	let year = nextDate.getFullYear();
	let month = nextDate.getMonth() + 1;
	month = ("0" + month).slice(-2);
	let date = nextDate.getDate();
	date = ("0" + date).slice(-2);
	return year + "-" + month + "-" + date;
}

function preDate(aDate) {
	let dateObj = new Date(aDate);
	let preDate = new Date(dateObj.getTime() - (24 * 60 * 60 * 1000));
	let year = preDate.getFullYear();
	let month = preDate.getMonth() + 1;
	month = ("0" + month).slice(-2);
	let date = preDate.getDate();
	date = ("0" + date).slice(-2);
	return year + "-" + month + "-" + date;
}

function goBackward(date, days) {
	let pastDate = date;
	for (let i = 0; i < days; i++) {
		pastDate = preDate(pastDate);
	}
	return pastDate;
}

function dateLT(dateA, dateB) {
    let m1 = (new Date(dateA)).getTime();
    let m2 = (new Date(dateB)).getTime();
    if (m1 < m2) {
        return true;
    }
    return false;
}

function dateDiff(startDate, endDate) {
	let days = 0;
	for (let i = startDate; i != endDate; i = nextDate(i)) {
		days++;
	}
	return days;
}

function has29Feb(startDate, endDate) {
	if (startDate.indexOf("-02-29") != -1 || endDate.indexOf("-02-29") != -1 ) {
		return true;
	}
	for (let date = startDate; date != endDate; date = nextDate(date)) {
		if (date.indexOf("-02-29") != -1) {
			return true;	
		}
	}
	return false;	
}

function curDate() {
	let curDate = new Date();
	let year = curDate.getFullYear();
	let month = curDate.getMonth() + 1;
	month = ("0" + month).slice(-2);
	let date = curDate.getDate();
	date = ("0" + date).slice(-2);
	return year + "-" + month + "-" + date;
}

/********* Display Function **********/

function displayLongestStreaks(rangesCon, lenElt, streaks, curType) {
	let lss = getLongestStreaks(streaks);
	lenElt.textContent = lss[0].days + " days"

	while(rangesCon.firstChild) {
		rangesCon.removeChild(rangesCon.firstChild);
	}

	for (let i = 0; i < lss.length; i++) {
		if (lss[i].days != 0) { 
			let span = document.createElement("span");
			span.className = "longest_streak_range";
			span.innerHTML = nameDate(lss[i].from) + " &#x2012; " + nameDate(lss[i].to) + ": <div>" + lss[i].quantity + 
					" " + curType + "</div>";
			rangesCon.appendChild(span);
		}
	}
}

function displayTotalInfo(totalElt, rangeElt, activity, curType) {
	let totalStatElt = totalElt.previousElementSibling;
	totalStatElt.textContent = curType;
	let info = getTotalInfo(activity);
	totalElt.textContent = info.quantity + " total";
	rangeElt.innerHTML = nameDate(info.from) + " &#x2012; " + nameDate(info.to);
}


function displayCurrentStreak(lenElt, rangeElt, streaks, curType) {
	let cs = getCurrentStreak(streaks)
	lenElt.textContent = cs.days + " days";
	if (cs.from) {
		rangeElt.innerHTML = nameDate(cs.from) + " &#x2012; " + nameDate(cs.to)+ ": <div>" + cs.quantity + 
				" " + curType+ "</div>";
	} else {
		rangeElt.textContent = "";
	}
}
