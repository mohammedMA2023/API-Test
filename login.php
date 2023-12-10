<?php
session_start();
    $header = "";
    
    function assessPasswordSecurity($password) {
        // Check if the password contains at least 8 characters
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
    
        // Check if the password contains at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one uppercase letter.";
        }
    
        // Check if the password contains at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return "Password must contain at least one lowercase letter.";
        }
    
        // Check if the password contains at least one digit
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least one digit.";
        }
        // Check if the password contains at least one special character
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            return "Password must contain at least one special character.";
        }
    
        // Password is secure
        return null;
    }
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "login_db";
    $dbTable = "logins";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbName);
    if ($conn->connect_error) {;
            die();
    }
    
    $uPass = $_POST["password"];
// Hash the entered password
    $hashedPassword = password_hash($uPass, PASSWORD_BCRYPT);
    switch ($_POST['auth']){
        case "login":
            // Use prepared statement to query the database
    $uName = $_POST["userid"];
            $stmt = $conn->prepare("SELECT id,email, u_pass FROM $dbTable WHERE email = ?");
    $stmt->bind_param("s", $uName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $storedHashedPassword = $row["u_pass"];

            // Verify the entered password against the stored hashed password
            if ((password_verify($uPass, $storedHashedPassword)) && ($uName == $row["email"]) ) {
                $status = "loggedIn";
                $header = "home.php";
                echo json_encode($row);
                $newUserID = $row["id"];
                echo $newUserID;
                
                if (!isset($COOKIE["userid"])){
                setcookie("userid", $newUserID, time() + 60 * 60 * 24 * 365);
                echo $newUserID;
            }
            else{
                echo $_COOKIE["userid"];
                echo $newUserID;    


            }
            
} else {
                $status = "loggedOut";
                $_SESSION['error'] = "Error: these details are incorrect. Please try again.";
                $header = "index.php";
			}
    }
    } else {
        $status = "loggedOut";
        $_SESSION['error'] = "Error: these details are incorrect. Please try again.";
        $header = "index.php";

	}
    break;
    case "reg":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST["password"]; 
            // Assess password security
            $securityError = assessPasswordSecurity($password);
            if ($securityError !== null) {
           
                // Password is not secure, set session variable and redirect
                $_SESSION['error'] = $securityError;
                $header = "index.php";
                $status = "loggedOut";
            }
             // Password is secure, you can continue with registration or other actions.
            else{
        $email = $_POST["userid"];
        $password = $_POST["password"];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT email FROM $dbTable WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
            if ($result->num_rows > 0){
            // Email already exists, handle this case as needed
            $_SESSION['error'] = "Error: this email is already registered. Please try again.";
            $status = "loggedOut";
                        
            $header = "index.php";
         } else {
                // Email and password don't exist, proceed to insert the new user
                // Hash the password
                // Insert the new user into the database
                $stmt = $conn->prepare("INSERT INTO $dbTable (email,u_pass) VALUES (?, ?)");
                
                $stmt->bind_param("ss",$email, $hashedPassword);
                 $stmt->execute();
                    $status = "loggedIn";
                    $header = "home.php";
                    $newUserId = mysqli_insert_id($conn); 
                    echo $newUserId;
                    setcookie("userid", $newUserId, time() + 60 * 60 * 24 * 365);

                }
        }       
            }           
    break;
case "logout":
    session_destroy();
    header('location:index.php');
    exit();


break;
    
case "upload":
    $image = $_FILES['image'];
    
    $allowedFileTypes = array('image/jpg', 'image/png', 'image/gif','image/jpeg');
    if (!in_array($image['type'], $allowedFileTypes)) {
        $_SESSION["error"] = "This file type is not supported.";
    }
    else {
        
    // Save the uloaded image file to a directory on the server
    $inputString = $image['name'];
// Generate a random salt
$salt = bin2hex(random_bytes(16)); // Adjust the length of the salt as needed

// Combine the input string and the salt
$stringWithSalt = $inputString . $salt;

// Hash the combined string
$hash = hash("sha256", $stringWithSalt);

    move_uploaded_file($image['tmp_name'], 'assets/uploads/' .$hash);
    $review = $_POST["review"];
    $caption = $_POST["caption"];
    $likes = 0;
    
    // Redirect the user to the main page
    $stmt = $conn->prepare("INSERT INTO reviews (img,review,likes,captions) VALUES (?, ?,?,?)");
    $stmt->bind_param("ssss",$hash, $review,$likes,$caption);
     $stmt->execute();
     }
     $header = "ratecake.php";
$status = "loggedIn";


break; 

    }
$_SESSION["status"] = $status;
$conn->close();
header("location:".$header);
exit();
?>