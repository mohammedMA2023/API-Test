<?php
		session_start();
		if ((isset($_SESSION["status"])) && ($_SESSION["status"] == "loggedOut")){
			header("location:index.php");
			exit();
	}



		

  ?>
<!DOCTYPE html>
<HTML>
<head>
	
	<title>Test Signon - v0.3</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
</head>

<body onload="getScreen()">
	<br>
	<br>	
	<div class="container border">		
	<form name='form1' id='form1' action="login.php" method="post">
	<br>

	<input type="hidden" id="width" name="width" value=" 0">
		<input typ++
		
		e="hi 
<br>
	</form>
		
</div>

	
	<script>
	function changeUi(){
		
		if (document.forms["form1"]["auth"].value == "login"){
			
			document.forms["form1"]["auth"].value ="reg";
			document.forms["form1"]["sub"].value ="Register";
			document.getElementById("changeUi").text
			document.querySelector('#changeUi').innerHTML = 'Log in';


		}
		else if (document.forms["form1"]["auth"].value == "reg"){
			
			document.forms["form1"]["auth"].value ="login";
			document.forms["form1"]["sub"].value ="Log In";
			
			document.querySelector('#changeUi').innerHTML = 'Register';


		}
		




	}
	var startDate;

function stopTime() {
  let startTime = startDate.getTime();
  var dateNow = new Date();
  var timeNow = dateNow.getTime();
  var timediff = timeNow - startTime;
  document.forms["form1"]["time"].value = timediff / 1000;
  
}

function start() {
  startDate = new Date();
}

function getScreen() {
  if (!startDate) {
    start();
  }
  document.forms['form1']['width'].value = screen.width;
  document.forms['form1']['height'].value = screen.height;
}

	</script>

</body>

</html>


