<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Data Viewer</title>
<link rel="stylesheet" type="text/css" href="../css/datumThings.css"/>
<link rel="stylesheet" type="text/css" href="../css/datumInfoCon.css"/>
<style>
article {
	width: 100%;
}

h1 {
	margin: 3px 0 3px 0;
}

#condition {
	width: 99%;
	font-family: mono;
	font-size: 20px;
	height: 25px;
}

#dataCon {
	width: 100%;
	display: flex;
	align-items: stretch;
}

#dataCon > div {
	width: 100px;
	margin-top: 5px;
}

#leftSideOfDataCon {
	flex-grow: 1;

}

#rightSideOfDataCon {
	flex-grow: 1.4;
	border-left: 1px solid #666;
 
}

table {
	width: 100%;
	border-collapse: collapse;
}

table td, table th{
	border: 1px solid #fff;
	padding: 2px 4px 2px 4px; 
	vertical-align: top;
}

td {
 white-space: pre-wrap;       /* css-3 */
 white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
 white-space: -pre-wrap;      /* Opera 4-6 */
 white-space: -o-pre-wrap;    /* Opera 7 */
 word-wrap: break-word;       /* Internet Explorer 5.5+ */
}

tr:not(:first-Child) {
	cursor: pointer;
}

table tr:nth-child(even) {background-color: #eee;}
table tr:nth-child(odd) {background-color: #ddd;}
 

table th {
	text-align: center;
	background-color: #677c60;
	color: #fff;
}

.tdDatum {
	width: 80%;
}

.tdDatumType {
	width: 100px;
}

#dataTable, #datumDetail {
	height: 400px;
	overflow: auto;
	border-top: 1px solid #666;
	border-bottom: 1px solid #666;

}

#datumDetail {
	padding-left: 6px;
}

#rightSideOfDataCon .h{
	margin-left: 6px;
}
</style>
</head>
<body>
<article> 
<h1>Data Viewer</h1> 
<a href="../index.php">Back to home</a> <br><br>
<div class="h">Condition</div>
<textarea id="condition"></textarea>
<div id="dataCon">
<div id="leftSideOfDataCon">
<div class="h">Data</div>
	<div id="dataTable"></div>
</div>
<div id="rightSideOfDataCon">
<div class="h">Datum detail</div>
	<div id="datumDetail"></div>
</div>
</div>
</article>
<script src="../js/ajax.js"></script>
<script src="../js/utility.js"></script>
<script src="../js/getDatumInfo.js"></script>
<script>
let data = {};
let conditionElt = document.querySelector("#condition");
let dataTableElt = document.querySelector("#dataTable");
let datumDetails = document.querySelector("#datumDetail");

function drawDataTable(condition) {
	getinfo("../php/getData/getDataOnConditions.php", "condition=" + encodeURIComponent(condition), data, "tableHTML", function() {
		dataTableElt.innerHTML = data.tableHTML;
		let theTable = document.querySelector("#theTable");
		let lastClickedTrNo = null;
		if (theTable) {
			let trs = document.querySelectorAll("tr:not(:first-Child)");
			for (let i = 0; i < trs.length; i++) {
				trs[i].onclick = function() {
					trs[i].style.backgroundColor = "#70ce52";
					lastClickedTrNo = i;
					
					let id = trs[i].querySelector(".tdId").textContent;
					
					while(datumDetails.firstChild) {
						datumDetails.removeChild(datumDetails.firstChild);
					}

					getDatumInfo(id, display, datumDetails)

					for (let j = 0; j < trs.length; j++) {
						if (i != j) {
							if ((j % 2) == 0) {
								trs[j].style.backgroundColor = "#eee";
							} else {
								trs[j].style.backgroundColor = "#ddd";
							}
						}
					}
				}
				trs[i].addEventListener("mouseover", function() {
					trs[i].style.backgroundColor = "#70ce52";
				});

				trs[i].addEventListener("mouseout", function() {
					if (lastClickedTrNo == null || i != lastClickedTrNo) {
						if ((i % 2) == 0) {
							trs[i]	.style.backgroundColor = "#eee";
						} else {
							trs[i].style.backgroundColor = "#ddd";
						}
					}
				});
			}
		}
	});
}

let dataShowedTimes = 0;
function showData() {
	let newValue = conditionElt.value.trim();
	if ((preValue != newValue) || dataShowedTimes == 0) {
		while(datumDetails.firstChild) {
			datumDetails.removeChild(datumDetails.firstChild);
		}

		drawDataTable(newValue);
		dataShowedTimes++;
	}
	preValue = newValue;
	setTimeout(showData, 500);
}

let preValue = "";
showData()

window.onload = function() {
	conditionElt.value = "";
}
</script>
</body>
</html>
