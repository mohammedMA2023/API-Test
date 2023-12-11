<?php
header("Content-Type: application/json");
$class = filter_input(INPUT_GET, 'class',  FILTER_SANITIZE_STRING);
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
          $timestampFromDatabase = $ro

          w['time'];
$formattedTime = (new DateTime($timestampFromDatabase))->format('d/m/Y H:i:s');
$row["time"] = $formattedTime;

          $table[] = $row;



}
    echo json_encode($table);











}
function like(){
    $raw_data = file_get_contents("php://input");
    $data = json_decode($raw_data, true);

    if (isset($data["data"])) {
        $conn = $this->conn;

        // Use prepared statement to prevent SQL injection
        $id = $data["data"];
        $uid = $data["uid"];
        //echo print_r($uid);
        $selectStmt = $conn->prepare("SELECT liked_by FROM reviews WHERE review_id = ?");
        $selectStmt->bind_param("i", $id);
        $selectStmt->execute();
        $result = $selectStmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $reviewerName = json_decode($row["liked_by"]);
    if (in_array($uid,$reviewerName)){
        $originalArray = $reviewerName;

// Element to remove
$elementToRemove = $uid;

// Use array_filter to remove the specified element
$filteredArray = array_filter($originalArray, function ($value) use ($elementToRemove) {
    return $value != $elementToRemove;
});

// Reset array keys if needed
$filteredArray = array_values($filteredArray);
$filteredArray = json_encode($filteredArray);

        $stmt = $conn->prepare("UPDATE reviews SET likes = likes - 1, liked_by = ? WHERE review_id = ?");
        $stmt->bind_param("ss",$filteredArray, $id);
        $stmt->execute();

        echo "unlike";
    }
    else{
    array_push($reviewerName,$uid);
    $reviewerName = json_encode($reviewerName);
        $stmt = $conn->prepare("UPDATE reviews SET likes = likes + 1, liked_by = ? WHERE review_id = ?");
        $stmt->bind_param("ss",$reviewerName, $id);
        $stmt->execute();
        echo "like";
    }

    }
    }
}
}
$db = new Db();
eval("$" . strtolower($class) . "->" . $method . "();");
?>