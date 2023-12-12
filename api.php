<?php
header("Content-Type: application/json");
$class = filter_input(INPUT_GET, 'class', FILTER_SANITIZE_STRING);
$method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING);

class Db {

    private $conn;

    function __construct(){
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

        while ($row = $result->fetch_assoc()) {
            $timestampFromDatabase = $row['time'];
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

            $id = $data["data"];
            $uid = $data["uid"];

            try {
                // Attempt to convert the value to an integer
                $intValue = filter_var($uid, FILTER_VALIDATE_INT);
                       
                // Check if the conversion was successful
                if ($intValue === false) {
                    echo "failed";
                } else {
                    $selectStmt = $conn->prepare("SELECT * FROM posts WHERE rev = ? AND user = ?");
                    $selectStmt->bind_param("ii",$intValue,$id);
                    $selectStmt->execute();
                    $result = $selectStmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $reviewerName = json_decode($row["liked_by"]);

                        if (in_array($intValue, $reviewerName)){
                            $originalArray = $reviewerName;

                            $elementToRemove = $intValue;

                            $filteredArray = array_filter($originalArray, function ($value) use ($elementToRemove) {
                                return $value != $elementToRemove;
                            });

                            $filteredArray = array_values($filteredArray);
                            $filteredArray = json_encode($filteredArray);

                            $stmt = $conn->prepare("UPDATE reviews SET likes = likes - 1, liked_by = ? WHERE review_id = ?");
                            $stmt->bind_param("si", $filteredArray, $id);
                            $stmt->execute();

                            echo "unlike";
                        } else {
                            array_push($reviewerName, $intValue);
                            $reviewerName = json_encode($reviewerName);
                            $stmt = $conn->prepare("UPDATE reviews SET likes = likes + 1, liked_by = ? WHERE review_id = ?");
                            $stmt->bind_param("si", $reviewerName, $id);
                            $stmt->execute();
                            echo "like";
                        }
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
