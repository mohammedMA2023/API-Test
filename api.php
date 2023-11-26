<?php
$class  = filter_input(INPUT_GET, 'class',  FILTER_SANITIZE_STRING);
$method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING);
class Fruit {
    // Properties
    public $name;
    public $color;
  
    // Methods
    function set_name($name) {
      $this->name = $name;
    }
    function get_name() {
     echo "hi";
    }
  }
$fruit = new Fruit();
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