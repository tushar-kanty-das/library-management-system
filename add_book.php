<?php
include 'connection.php';

//Store the result of the operation (success or failure). 
//This will be sent back as a JSON response.
$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bookName = trim($_POST['book-name']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    //filter_var() with FILTER_VALIDATE_INT: Ensures count is integer.
    $count = filter_var($_POST['count'], FILTER_VALIDATE_INT);

    if (empty($bookName) || empty($author) || empty($isbn) || $count === false || $count < 1) {
        $response["status"] = "error";
        $response["message"] = "Please fill out all fields with valid data.";
    } else {
        //Ensures that the values are treated as data only.
        $stmt = $database->prepare("INSERT INTO books (book_name, author, isbn, count) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            $response["status"] = "error";
            $response["message"] = "Error preparing the query: " . $database->error;
        } else {
            //"sssi" indicates the types of the parameters.
            //bind_param attaches the variables to the placeholders.
            $stmt->bind_param("sssi", $bookName, $author, $isbn, $count);

            if ($stmt->execute()) {
                $bookId = $stmt->insert_id;
                $response["status"] = "success";
                $response["message"] = "Book added successfully!";
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