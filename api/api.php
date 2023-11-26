<?php
$class  = filter_input(INPUT_GET, 'class',  FILTER_SANITIZE_STRING);
$method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING);
class Db {
    
    function __construct(){
       $servername = "localhost";
      $username = "root";
      $password = "";
      $dbName = "login_db";
  
       $this->conn = new mysqli($servername, $username, $password, $dbName);    
     
    }  
    // Methods
    function query() {
      $conn = $this->conn;
      $stmt = $conn->prepare("SELECT * FROM reviews ORDER BY review_id DESC");
         
    $stmt->execute();
    $result = $stmt->get_result();
     
    
      // output data of each row
      $table = [];

      while ($row = $result->fetch_assoc()) {
          $table[] = $row;
          
        
             
    }
    echo json_encode($table);


    


  }
    
    
  }

$db = new Db();
eval("$" . $class. "->" . $method . "();");
/*  
case "upload":
    $allowedFileTypes = array('image/jpg', 'image/png', 'image/gif','image/jpeg');
    $image = $_FILES['image'];
    echo $image["type"];
    if (!in_array($image['type'], $allowedFileTypes)) {
        $_SESSION["error"] = "This file type is not supported.";
        
        header('Location:ratecake.php');
        exit();

        
    }
    else {
         
    // Save the uloaded image file to a directory on the server
    move_uploaded_file($image['tmp_name'], 'assets/uploads/' . $image['name']);
    $review = $_POST["review"];
    $caption = $_POST["caption"];
    $likes = 0;
    $image_name = $image["name"];

    // Redirect the user to the main page
    $stmt = $conn->prepare("INSERT INTO reviews (img,review,likes,captions) VALUES (?, ?,?,?)");
    $stmt->bind_param("ssss", $image_name, $review,$likes,$caption);
     $stmt->execute();

    header('Location:ratecake.php');
    exit();
} 

*/
    
         
?>