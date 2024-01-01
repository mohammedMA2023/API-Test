<?php
header("Content-Type: application/json");
$class = filter_input(INPUT_GET, 'class', FILTER_SANITIZE_STRING);
$method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING);

class Db {



    function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbName = "login_db";

        $this->conn = new mysqli($servername, $username, $password, $dbName);
    }

    function query() {
        $conn = $this->conn;
        $stmt = $conn->prepare("SELECT * FROM reviews ORDER BY review_id DESC");

        $stmt->execute();
        $result = $stmt->get_result();

        $table = [];
        $raw_data = file_get_contents("php://input");
        $data = json_decode($raw_data, true);

        while ($row = $result->fetch_assoc()) {
            $timestampFromDatabase = $row['time'];
            $formattedTime = (new DateTime($timestampFromDatabase))->format('d/m/Y H:i:s');
            $row["time"] = $formattedTime;
            $uid = filter_var($data["uid"], FILTER_VALIDATE_INT);
                $revId = filter_var($row["review_id"], FILTER_VALIDATE_INT);
            $res = $this->checkLike($conn,$revId,$uid);
            if ($res->num_rows > 0){

                $row["like"] = "like";

            }
            else{
                $row["like"] = "unlike";

            }

        $table[] = $row;
        }

        echo json_encode($table);
   }

    function checkLike($conn,$rev,$id){
        $conn = $this->conn;

        $selectStmt = $conn->prepare("SELECT * FROM posts WHERE rev = ? AND user = ?");
        $selectStmt->bind_param("ss", $rev, $id);
        $selectStmt->execute();
        $result = $selectStmt->get_result();

    return $result;




    }
    function like() {
        $raw_data = file_get_contents("php://input");
        $data = json_decode($raw_data, true);

        if (isset($data["data"])) {
            $conn = $this->conn;
            try {
                $intValue = filter_var($data["uid"], FILTER_VALIDATE_INT);
                $id = filter_var($data["data"], FILTER_VALIDATE_INT);

                if ($intValue === false || $id === false) {
                    echo "failed";
                } else {
                    $result = $this->checkLike($conn,$id,$intValue);
                    if ($result->num_rows > 0) {
                        $stmt = $conn->prepare("DELETE FROM posts WHERE rev = ? AND user = ?");
                        $stmt->bind_param("ii", $id, $intValue);
                        $stmt->execute();
                        $updateStmt = $conn->prepare("UPDATE reviews SET likes = likes - 1 WHERE review_id = ?");
                        $updateStmt->bind_param("i", $id);
                        $updateStmt->execute();
                        echo "unlike";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO posts (rev, user) VALUES (?, ?)");
                        $stmt->bind_param("ii", $id, $intValue);
                        $stmt->execute();
                        $updateStmt = $conn->prepare("UPDATE reviews SET likes = likes + 1 WHERE review_id = ?");
                        $updateStmt->bind_param("i", $id);
                        $updateStmt->execute();
                        echo "like";
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
    function mesg(){
       $conn = $this->conn;
       $raw_data = file_get_contents("php://input");
       $data = json_decode($raw_data, true);

        $uid = filter_var($data["uid"], FILTER_VALIDATE_INT);

        $message = $data["message"];
        $username = $data["uname"];
        $stmt = $conn->prepare("INSERT INTO messages (user_id,message_text,username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $uid, $message,$username);
        $stmt->execute();

    }
  function getMsg() {
        $conn = $this->conn;
        $stmt = $conn->prepare("SELECT * FROM messages");

        $stmt->execute();
        $result = $stmt->get_result();

        $table = [];

        while ($row = $result->fetch_assoc()) {
            $timestampFromDatabase = $row['timestamp'];
            $formattedTime = (new DateTime($timestampFromDatabase))->format('d/m/Y H:i:s');
            $row["timestamp"] = $formattedTime;

        $table[] = $row;
        }

        echo json_encode($table);
   }
   function getMenu(){
    $conn = $this->conn;
    $stmt = $conn->prepare("SELECT * FROM products");

        $stmt->execute();
        $result = $stmt->get_result();

        $table = [];

        while ($row = $result->fetch_assoc()) {

        $table[] = $row;
        }

        echo json_encode($table);


   }
   function basket(){
    $conn = $this->conn;
    $raw_data = file_get_contents("php://input");
       $data = json_decode($raw_data, true);

        $uid = filter_var($data["uid"], FILTER_VALIDATE_INT);

        $items = $data["items"];
        $listCount = count($items);

    for ($i = 0; $i < $listCount; $i++) {
    $currItem = $items[$i];
    // Query with a parameterized statement to select a specific product by product_id
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $currItem);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $price = $row['price'];
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO basket (user_id, product_id, quantity,price) VALUES (?, ?, ?,?)");
    $quantity = 1; // replace with the actual quantity
    $stmt->bind_param("iiii", $uid, $currItem, $quantity,$price);
    $stmt->execute();


    }


   }
  function getBasket(){
           $conn = $this->conn;
       $raw_data = file_get_contents("php://input");
       $data = json_decode($raw_data, true);

        $uid = filter_var($data["uid"], FILTER_VALIDATE_INT);

        $stmt = $conn->prepare("INSERT INTO messages (user_id,message_text,username) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $uid, $message,$username);
        $stmt->execute();



        $conn = $this->conn;
        $stmt = $conn->prepare("SELECT * FROM basket WHERE product_id = ?");
        $stmt->bind_param("i",$uid);
        $stmt->execute();
        $result = $stmt->get_result();

        $table = [[]];

        while ($row = $result->fetch_assoc()) {
        $sql = "SELECT SUM(price) AS total_price FROM your_table_name";
        $result = mysqli_query($your_mysql_connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $totalPrice = $row['total_price'];


        $table[0][] = $row;
        }

        echo json_encode($table);



  }
}

$db = new Db();
eval("$" . strtolower($class) . "->" . $method . "();");
?>