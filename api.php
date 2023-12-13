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
            $table[] = $row;
        }

        echo json_encode($table);
    }

    function checkLike($revId,$uid){
        $conn = $this->conn();
        $selectStmt = $conn->prepare("SELECT * FROM posts WHERE rev = ? AND user = ?");
        $selectStmt->bind_param("ii", $revId, $uid);
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
                    $result = $this->checkLike($id,$intValue);                   
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
}

$db = new Db();
eval("$" . strtolower($class) . "->" . $method . "();");
?>
