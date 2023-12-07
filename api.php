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
        $selectStmt = $conn->prepare("SELECT liked_by FROM reviews WHERE review_id = ?");
        $selectStmt->bind_param("i", $id);
        $selectStmt->execute();
        $result = $selectStmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $reviewerName = json_decode($row["liked_by"]);
    $reviewerName[0] = 1;
     

    // Use the reviewer's name here
}
        $stmt = $conn->prepare("UPDATE reviews SET likes = likes + 1 WHERE review_id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->execute();
            echo $reviewerName;
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Missing 'data' in the request"]);
    }
}


}


$db = new Db();
eval("$" . strtolower($class) . "->" . $method . "();");

?>