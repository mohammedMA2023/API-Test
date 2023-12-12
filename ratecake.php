<!DOCTYPE html>
<html lang="en">
    <head>
    <style>
/* Prevent scrolling */


#popupContainer {
    transition: opacity 0.3s;

    display:none;
  /* Make popup responsive */
  width: 90%;
  max-width: 500px;
  left: calc(50% - 250px);

  /* Add some padding to the top and bottom of the popup */
  padding: 20px 0;

  /* Make the popup look good */
  background-color: white;
  border: 1px solid black;
  border-radius: 10px;
  z-index: 10;

  /* Position the popup in the center of the screen */
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
/* Decrease the opacity of the body to 0.5 when the popup container is visible */


#popupContainer h1 {
  text-align: center;
  font-size: 24px;
}

#popupContainer .dropdown {
  margin-bottom: 20px;
}

#popupContainer .dropdown-content {
  position: relative;
  background-color: white;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}

#popupContainer .dropdown-content label {
  padding: 12px 16px;
  cursor: pointer;
}

#popupContainer .dropdown-content select, #popupContainer .dropdown-content input {
  width: 100%;
  padding: 12px 16px;
  margin-bottom: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

#popupContainer .dropdown-content button {
  padding: 10px 20px;
  border-radius: 5px;
  background-color: #007bff;
  color: #fff;
  font-size: 16px;
  font-weight: 400;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
}

#popupContainer .dropdown-content button:hover {
  background-color: #0069d9;
  color: #fff;
}

#popupContainer .dropdown-content button:active {
  background-color: #0059b3;
  color: #fff;
}

#popupContainer .dropdown-content label:hover {
  background-color: #f2f2f2;
}

/* Hide the popup by default */
#popupContainer {
  display: none;
}

/* Show the popup when the "Open Popup" button is clicked */
.open-popup {
  cursor: pointer;
}

.open-popup:hover {
  background-color: #f2f2f2;
}

.open-popup:active {
  background-color: #ccc;
}
.heart-button {

      position: relative;
      width: 40px;
      height: 30px;

      background-color: white;
      cursor: pointer;
    }


    </style>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Business Casual - Start Bootstrap Theme</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body onload="show()">
    <?php
        include "header.php";

    ?>


<section class="page-section cta">
            <div class="container">
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <div class="cta-inner bg-faded text-center rounded">
                            <h2 class="section-heading mb-5">
                                <span class="section-heading-upper">Don't Wanna Wait?</span>
                                <span class="section-heading-lower">Rate My Cake</span>
                            </h2>

<div style="display: inline-block; text-align: center;">
<form action="login.php" method="post" enctype="multipart/form-data">
<img id="blah" class="img-thumbnail rounded" style="background-color: #d2984f; max-width: 100%;" src="#" hidden>
  <br>
  <br>
<input style="width:100%;" id="userImg" type="file" name="image" />
  <br>

  <br>
  <input type="hidden" name="auth" value="upload"/>
  <br>
  <textarea name="caption" rows="10" cols="50" placeholder="Enter your review..." required maxlength="255"></textarea>

  <br>
  <br>

  <textarea name="review" rows="10" cols="50" placeholder="Enter the caption..." required maxlength="255"></textarea>

  <br>
  <br>
  <input style="width:100%;" type="submit" value="Upload" />
  <input type="hidden" id="width" name="width" value=" 0">
		<input type="hidden" id="time" name="time" value=" 0">
		<input type="hidden" id="height" name="height" value=" 0">
	  <input type="hidden" name="userid" placeholder="Enter your email..."><br>

    <input type="hidden" name="password" placeholder="Enter your password"><br>
<!-- This is the Image preview window -->


</form>
</div>
<?php
  if ((isset($_SESSION["error"])) && ($_SESSION["error"])){
    echo $_SESSION["error"];
    $_SESSION["error"] = "";


  }



?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
<div id="revs">




</div>
        <?php
            include "footer.php";
        ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    <script>
      // User Image Preview
userImg.onchange = evt => {
  const [file] = userImg.files;
  if (file) {
    blah.src = URL.createObjectURL(file);
    blah.hidden = false;
  }
};

// Time Validation Function
function validateTime() {
  var selectedTime = document.getElementById('timepicker').value;
  var parts = selectedTime.split(':');
  var hours = parseInt(parts[0]);
  var minutes = parseInt(parts[1]);

  // Restrict to 9 am to 5:30 pm
  if (hours < 9 || (hours === 17 && minutes > 30) || hours > 17) {
    alert('Please select a time between 9:00 am and 5:30 pm.');
    document.getElementById('timepicker').value = ''; // Clear the input
    return false;
  } else {
    return true;
  }
}

// Data Parsing Function
 function disp(jsn) {
  let obj = JSON.parse(jsn); // Correct variable name
let revs = document.getElementById("revs");

  let content = "";

  for (let i = 0; i < obj.length; i++) {
    let file = '"assets/uploads/' + obj[i]["img"] + '"';
    let id = "" + obj[i]["review_id"];
    let lId = "button-"+id;
    const myArray = JSON.parse(obj[i]["liked_by"]);
const elementToCheck = parseInt(getCookie("userid"));

const foundElement = (myArray.find(item => item === elementToCheck));

var likeValue;
if (foundElement !== undefined) {
   likeValue = "❤️";
} else {
  likeValue = "💙";

}
    content += `<section class='page-section cta' style="margin-top: 20px; position: relative;">
    <div class='container'>
        <div class='row'>
            <div class='col-xl-9 mx-auto'>
                <div style="position: absolute; top: 0; right: 0; padding: 10px; background-color: #f00; color: #fff;">
                        <!-- Content for the new element -->
                        <h2>`+obj[i]["time"]+`</h2>
                    </div>

                <div class='cta-inner bg-faded text-center rounded'>
                    <!-- New element added to the top right -->

                    <img class='img-thumbnail rounded' style='width: 500%; height: 500%;'  src=` + file + `'>
                    <br>
                    <br>
                    <h1 style="color: #000;">` + obj[i]['captions'] + `</h1>
                    <h3 style="color: #000;">` + obj[i]["review"] + `</h3>
                    <br>
                    <br>
                    <button id="`+lId+`" onclick="like(`+ id +`)" class="heart-button" style="color: #fff;">` + likeValue + `</button>
                    <h2 id=` + id + ` style="color: #000;">`+  obj[i]["likes"] + `</h2>
                </div>
            </div>
        </div>
    </div>
</section>
`;



  }
    revs.innerHTML = content;
}


function show(){
  fetch('http://10.201.209.94/api/db/query')
  .then(response => response.text())
  .then(text => disp(text)) // Remove duplicate function definition
  .catch(error => console.error('Error:', error));



}
setInterval(show,5000);
function getCookie(name) {
  let uId = <?php echo json_encode($_SESSION["userid"])?>;
  return uId;

}

// Example: Get the value of a cookie named "userid"
function l(id,action){
      var likeCount = document.getElementById(id);
      var lId = "button-"+""+id;

      var likeButton = document.getElementById(lId);


    if (action === "like"){
      likeCount.innerHTML = parseFloat(likeCount.innerHTML) + 1;
      likeButton.innerHTML = "❤️";
  }
  if (action === "unlike"){
      likeCount.innerHTML = parseFloat(likeCount.innerHTML) - 1;
      likeButton.innerHTML = "💙";
  }


}

function like(id) {
  const userIdValue = getCookie("userid");

  fetch('http://10.201.209.94/api/db/like', {
    method: 'POST',
    body: JSON.stringify({ data: id, uid: userIdValue })
  })
    .then(response => response.text()) // This returns a Promise
    .then(data => l(id, data)) // Handle the resolved value (text content)
    .catch(error => console.error('Fetch error:', error));
}
    </script>
    </body>
</html>