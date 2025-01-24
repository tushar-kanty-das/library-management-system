<?php
include ("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt</title>
</head>
<body>
  <h1>Receipt</h1>
  <div id="receipt-container"></div>
  <h2>Used Token</h2>
  <div id="token-box2"></div>

  <script>
    // Retrieve receipt data from sessionStorage
    const receiptData = JSON.parse(sessionStorage.getItem("receipt"));

    if (receiptData) {
      // Display receipt information
      const receiptContainer = document.getElementById("receipt-container");
      receiptContainer.innerHTML = `
        <p><strong>Student Name:</strong> ${receiptData.studentName}</p>
        <p><strong>Email:</strong> ${receiptData.email}</p>
        <p><strong>Book Name:</strong> ${receiptData.bookName}</p>
        <p><strong>Borrow Date:</strong> ${receiptData.borrowDate}</p>
        <p><strong>Return Date:</strong> ${receiptData.returnDate}</p>
      `;
      // If a token was used, show it
      if (receiptData.token !== "N/A") {
        const tokenBox2 = document.getElementById("token-box2");
        const tokenElement = document.createElement("div");
        tokenElement.textContent = `Used Token: ${receiptData.token}`;
        tokenBox2.appendChild(tokenElement);
      }
    } else {
      alert("No receipt data found.");
    }
  </script>
</body>
</html>
