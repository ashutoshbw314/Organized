<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>MySQL Web Interface</title>
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
		    		height: 200px;
		    		font-size: 15px;
			}
		</style>
	</head>
	<body>
<article>
		<h1>MySQL Web Interface</h1>	
		<a href="../index.php">Back to home</a> 
		<p>Enter a MySQL statement:</p>
		<form method="POST" action="mysql_output.php" target="_blank">
<textarea id="code" name="code"></textarea>
		<input type="submit" name="submit" value="Run"/>
		</form>
</article>
<script>
window.onload = function() {
  var mime = 'text/x-mysql';
  // get mime type
  if (window.location.href.indexOf('mime=') > -1) {
    mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
  }
  window.editor = CodeMirror.fromTextArea(document.getElementById('code'), {
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
