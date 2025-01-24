<?php
include 'connection.php';

//Stores the result of the operation (success or failure). 
//This will be sent back as a JSON response.
$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $studentName = trim($data['studentName']);
    $email = trim($data['email']);
    $studentId = trim($data['studentId']);
    $bookId = filter_var($data['bookId'], FILTER_VALIDATE_INT);
    $borrowDate = $data['borrowDate'];
    $returnDate = $data['returnDate'];
    $token = trim($data['token']);

    if (empty($studentName) || empty($email) || empty($studentId) || !$bookId || empty($borrowDate) || empty($returnDate)) {
        $response["status"] = "error";
        $response["message"] = "Invalid input data.";
    } else {
        $borrowDuration = (strtotime($returnDate) - strtotime($borrowDate)) / (60 * 60 * 24);

        if ($borrowDuration > 10 && empty($token)) {
            $response["status"] = "error";
            $response["message"] = "A token is required for a borrow period exceeding 10 days.";
        } else {

            $database->begin_transaction();

            try {
                $stmt = $database->prepare("SELECT count FROM books WHERE id = ?");
                $stmt->bind_param("i", $bookId);
                $stmt->execute();
                $stmt->bind_result($bookCount);
                $stmt->fetch();
                $stmt->close();

                if ($bookCount <= 0) {
                    $response["status"] = "unavailable";
                    $response["message"] = "The book is unavailable.";
                } else {
                    $stmt = $database->prepare("UPDATE books SET count = count - 1 WHERE id = ?");
                    $stmt->bind_param("i", $bookId);
                    $stmt->execute();

                    $database->commit();

                    $response["status"] = "success";
                    $response["message"] = "Book borrowed successfully!";
                }
            } catch (Exception $e) {
                $database->rollback();
                $response["status"] = "error";
                $response["message"] = $e->getMessage();
            }
        }
    }
}
$database->close();
echo json_encode($response);
?>