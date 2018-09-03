<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>MySQL Output</title>
		<link rel="stylesheet" href="../css/mysql_web_interface.css"/>

		<link rel="stylesheet" href="../codemirror-5.38.0/lib/codemirror.css" />
		<script src="../codemirror-5.38.0/lib/codemirror.js"></script>
		<script src="../codemirror-5.38.0/mode/sql/sql.js"></script>
		<link rel="stylesheet" href="../codemirror-5.38.0/addon/hint/show-hint.css" />
		<script src="../codemirror-5.38.0/addon/hint/show-hint.js"></script>
		<script src="../codemirror-5.38.0/addon/hint/sql-hint.js"></script>

		<style>
			.CodeMirror {
				border: 1px solid black;
		    		height: 75px;
		    		font-size: 15px;
			}
		</style>
	</head>
	<body>
<h1>MySQL Output</h1>	
<article>
<h3>Your statement was:</h3>
<?php
include "../php/con.php";
?>

<textarea id="code" name="code">
<?php
$the_query = $_POST["code"];
echo $the_query;
?>
</textarea>
<h3>Result:</h3>
<?php
include "../php/draw_table.php";
$result = mysqli_query($con, $the_query);
if (!$result) {
  echo "<p>Error: " . mysqli_error($con) . " 🙁</p>";
} else {
  echo "<div id='table_container'>";
  draw_table($result);
  echo "</div>";
}
?>
</article>
<script>
window.onload = function() {
  var mime = 'text/x-mysql';
  // get mime type
  if (window.location.href.indexOf('mime=') > -1) {
    mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
  }
  window.editor = CodeMirror.fromTextArea(document.getElementById('code'), {
    readOnly: "nocursor",
    mode: mime,
    indentWithTabs: true,
    smartIndent: true,
    lineNumbers: true,
    matchBrackets : true,
    autofocus: true,
    extraKeys: {"Ctrl-Space": "autocomplete"},
    hintOptions: {tables: {
      users: ["name", "score", "birthDate"],
      countries: ["name", "population", "size"]
    }}
  });
};
</script>
	</body>
</html>
