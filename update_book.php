<?php
include 'connection.php';

//Store the result of the operation (success or failure). 
//This will be sent back as a JSON response.
$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true); // Get the data sent via POST

    $bookId = $data['id'];
    $bookName = trim($data['book_name']);
    $count = filter_var($data['count'], FILTER_VALIDATE_INT);

    if (empty($bookName) || $count === false || $count < 1) {
        $response["status"] = "error";
        $response["message"] = "Invalid input.";
    } else {
        $stmt = $database->prepare("UPDATE books SET book_name = ?, count = ? WHERE id = ?");
        if ($stmt === false) {
            $response["status"] = "error";
            $response["message"] = "Error preparing the query: " . $database->error;
        } else {
            $stmt->bind_param("sii", $bookName, $count, $bookId);

            if ($stmt->execute()) {
                $response["status"] = "success";
                $response["message"] = "Book updated successfully!";
            } else {
                $response["status"] = "error";
                $response["message"] = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$database->close();
echo json_encode($response);
?>