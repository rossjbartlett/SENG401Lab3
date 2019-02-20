<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../styles.css">
	<title>Task1</title>
</head>

<body>
<br>

<div class="form_div">
<form method=POST>
  Name: <input type="text" id="searchVal" class="input"><br>
  Sector:
  <select id="sector">
  <option value="ANY" selected="selected">Any</option>
    <option value="NW">NW</option>
    <option value="NE">NE</option>
    <option value="SW">SW</option>
    <option value="SE">SE</option>
  </select>
  <br><br>
    Format:<br>
    <input type="radio" name="format" value="table" checked> Table
    <input type="radio" name="format" value="CSV"> CSV
    <input type="radio" name="format" value="JSON"> JSON
    <input type="radio" name="format" value="XML"> XML

<br>

  <input type="button" onclick="search()" value="Search"><br>
</form>
</div>

<hr>

<div id="result"></div>

</body>

<script>

function search() {
    var val = document.getElementById("searchVal").value;
    var dropdown = document.getElementById("sector");
    var sector = dropdown.options[dropdown.selectedIndex].text;

    var format = document.querySelector('input[name="format"]:checked').value;
    var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    	if (this.readyState == 4 && this.status == 200) {
      		 document.getElementById("result").innerHTML = this.responseText;
    	}
	};
    xhttp.open("POST", "search_table.php", true);
	//Send the proper header information along with the request
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
	//send the values in each of the input forms
	xhttp.send("searchVal="+ val + "&sector=" + sector + "&format="+ format);
}

</script>

</html>

