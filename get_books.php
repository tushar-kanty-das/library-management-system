<?php
include ("connection.php");

$result = $database->query("SELECT * FROM books");
$books = [];
if ($result->num_rows > 0) {
    //The fetch_assoc() method fetches each row from the result.
    while ($row = $result->fetch_assoc()) {
        $books[] = $row; 
    }
}

header('Content-Type: application/json');
echo json_encode($books);

$database->close();
?>