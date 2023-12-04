<?php
// Start the session
session_start();
    // Logging code
    // Get the type of request (GET or POST).
    $reqMethods = $_SERVER["REQUEST_METHOD"];
    // Get important server data, like the server name, IP, port, and software.
    $serverName = $_SERVER["SERVER_NAME"];
    $serverPort = $_SERVER["SERVER_PORT"];
    $serverIp = $_SERVER["SERVER_ADDR"];
    $serverSoftware = $_SERVER["SERVER_SOFTWARE"];
    // Access the user agent, which includes data about the user's platform.
    // If-elseif-else block to figure out the user's platform and then set the variable of the platform variable to get the user's platform.
    $platform = "";
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    // Check for common platforms
        if (strpos($userAgent, 'Windows') !== false) {
            $platform = "Windows";
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            $platform = "MacOS";
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $platform = "Linux";
        } else {
            $platform = "Unknown";
        }
    } else {
        $platform = "User agent information not available.";
    }
    // This will access the client's IP, name, and port.
    $ip = $_SERVER["REMOTE_ADDR"];
    $clientName = gethostbyaddr($ip);
    $clientPort = $_SERVER['REMOTE_PORT'];
    date_default_timezone_set("Europe/London");
    $date = date('d-m-Y H:i:s');
    // Check if the referer variable is set and not empty
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $clientScriptUrl = $_SERVER['HTTP_REFERER'];
    } else {
        $clientScriptUrl = "Unknown";
    }
    // This will collect some additional information about the server and client.
    // The scriptName variable will hold the name of the server script.
    // The acceptLanguage variable will hold the accept language of the client.
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    // Get the resolution of the client's screen.
    $width = $_POST['width'];
    $height = $_POST['height'];
    $res = "$width x $height";
    // Get the time the user spent on the website and the time it took to complete the form.
    $time = $_POST['time'];
    // Write all collected data to the log.txt file.
    $file = 'log.txt';

    $current = file_get_contents($file);
    $uName = $_POST["userid"];
    $newLine = "Request Method: $reqMethods\n" . "Request Params:" . json_encode($_POST) . "\n" . "User: " . $uName . "\n" . "Cient IP: " . $ip . "\nClient Name: $clientName\n Client Port: $clientPort\n Client Platform: $platform\n Client Accept Language: $acceptLanguage\n Client Script URL: $clientScriptUrl\n Server Name: $serverName\n Server IP: $serverIp\n Server Port: $serverPort\n Server Script Name: $scriptName\n Server Software: $serverSoftware" . "Current Date: " . $date . "\n" .  "\n resolution: $res\n time spent on site: $time seconds" . "\r\n\n";
    $current = $current . $newLine;
    file_put_contents($file, $current);
    
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
    $stmt = $conn->prepare("SELECT email, u_pass FROM logins WHERE email = ?");
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
                $_SESSION["status"] = $status;
                $conn->close();
				header("location:home.php");
				exit();
            } else {
                $_SESSION["status"] = $status;
                $status = "loggedOut";
                $_SESSION['error'] = "Error: these details are incorrect. Please try again.";
                header("location:index.php");
				$conn->close();
                exit();
            }
        }
    } else {
        $status = "loggedOut";
        $_SESSION['error'] = "Error: these details are incorrect. Please try again.";
        $_SESSION["status"] = $status;
        header("location:index.php");
		$conn->close();
    
        exit();
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
                header("location:index.php");
               exit();
            }
             // Password is secure, you can continue with registration or other actions.
            else{
        $email = $_POST["userid"];
        $password = $_POST["password"];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT email FROM logins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
            if ($result->num_rows > 0){
            // Email already exists, handle this case as needed
            $_SESSION['error'] = "Error: this email is already registered. Please try again.";
                header("location:index.php");
                $conn->close();
                exit();
        } else {

                // Email and password don't exist, proceed to insert the new user
                // Hash the password
                // Insert the new user into the database
                $stmt = $conn->prepare("INSERT INTO logins (email, u_pass) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $hashedPassword);
                 $stmt->execute();
                    $_SESSION['status'] = "loggedIn";
                    $conn->close();
                    header("location:home.php");
                
                exit();
            }
        }       
            }           
    break;
case "logout":
    session_destroy(); 
header("location:index.php");  
exit();
    
case "upload":
    $allowedFileTypes = array('image/jpg', 'image/png', 'image/gif','image/jpeg');
    $image = $_FILES['image'];
    $type = $image["type"];
    $name = $image["name"];
    $size = $image["size"];
    $supported = "";

    if (!in_array($image['type'], $allowedFileTypes)) {
        $_SESSION["error"] = "This file type is not supported.";
        $supported = "No";
        //header('Location:ratecake.php');
        //exit();

        
    }
    else {
        $supported = "Yes";
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
    $image_name = $image["name"];

    // Redirect the user to the main page
    $stmt = $conn->prepare("INSERT INTO reviews (img,review,likes,captions) VALUES (?, ?,?,?)");
    $stmt->bind_param("ssss",$hash, $review,$likes,$caption);
     $stmt->execute();

    //header('Location:ratecake.php');
    //exit();
}
echo ' <table style="width: 50%; margin: 0 auto; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">File Name</th>
            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . $name . '</td>
        </tr>
        <tr>
            <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">File Type</th>
            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . $type . '</td>
        </tr>
        <tr>
            <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">File Size</th>
            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . $size . ' Bytes</td>
        </tr>
        <tr>
            <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">File Support</th>
            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . $supported . '</td>
        </tr>
    </table>
    <form action="ratecake.php" action="post">
<input style="text-align:center;" type="submit" value="Done">

</form>

';

    }
?>