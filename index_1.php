<?php
include ("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom Box Layout</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>


<div class="body">
<div class="container-vertical">
  <div class="box01">
    <img src="image01.jpg" alt="Image 01">
  </div>
  <div class="box1" id="book-list"></div>
  <div class="box2" id="edit-book"></div>
</div>

<div class="container-horizontal">
  <div class="box-horizontal1" id="token-box"></div>
  <div class="box-horizontal2" id="token-box2"></div>
</div>

<div class="container-row">
  <div class="box-row">
    <img src="image1.webp" alt="Image 1">
  </div>
  <div class="box-row">
    <img src="image2.webp" alt="Image 2">
  </div>
  <div class="box-row">
    <img src="image3.webp" alt="Image 3">
  </div>
</div>

<div class="container-row">
  <div class="box-large">
    <form action="borrow_book.php" method="post" class="borrow-form">
      <h2>Student Information</h2>
      <label for="student-name">Student Full Name:</label>
      <input type="text" id="student-name" name="student-name">

      <label for="email">Email:</label>
      <input type="email" id="email" name="email">

      <label for="student-id">Student ID (Format: 22-46714-1):</label>
      <input type="text" id="student-id" name="student-id" pattern="\d{2}-\d{5}-\d" title="Enter a valid ID in the format XX-XXXXX-X" >

      <label for="dropdown-book-name">Book Name:</label>
      <select id="dropdown-book-name" name="dropdown-book-name">
        <option value="">Select a book</option> 
      </select>

      <label for="borrow-date">Borrow Date:</label>
      <input type="date" id="borrow-date" name="borrow-date" required>

      <label for="return-date">Return Date:</label>
      <input type="date" id="return-date" name="return-date" required>


      <label for="token">Token (For 10 days):</label>
      <select id="token" name="token">
        <option value="">Select a token</option> 
      </select>

      <button type="submit">SUBMIT</button>
    </form>
  </div>
  <div class="box-small">
    <form id="book-form" action="add_book.php" method="POST" class="borrow-form">
      <h2>Book Information</h2>
    <label for="book-name">Book Name:</label>
    <input type="text" id="book-name" name="book-name"  required>

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required>

    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="isbn"  required>

    <label for="count">Count:</label>
    <input type="number" id="count" name="count" min="1" required>

    <button type="submit">ADD BOOK</button>

  </form>
  </div>
</div>
</div>
</div>
<script>
  fetch('data.json')
    .then((response) => response.json())
    .then((jsonData) => {
      const tokenBox = document.getElementById("token-box");
      jsonData.tokens.forEach((token) => {
        const tokenElement = document.createElement("div");
        tokenElement.textContent = token;
        tokenBox.appendChild(tokenElement);
      });
    })
    .catch((error) => console.error('Error loading JSON:', error));
</script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Fetch and populate books in the dropdown
    const fetchBooks = async () => {
      try {
        const response = await fetch("get_books_dropdown.php"); // Fetch book data from the PHP script
        const books = await response.json();

        const bookSelect = document.getElementById("dropdown-book-name");
        bookSelect.innerHTML = '<option value="">Select a book</option>'; // Reset dropdown

        // Add each book as an option in the dropdown
        books.forEach((book) => {
          const option = document.createElement("option");
          option.value = book.id; // Use the book's ID as the value
          option.textContent = book.book_name; // Display the book name
          bookSelect.appendChild(option);
        });
      } catch (error) {
        console.error("Error fetching books:", error);
      }
    };

    // Fetch and populate tokens in the dropdown
    const fetchTokens = async () => {
      try {
        const response = await fetch("data.json"); // Fetch token data from JSON
        const jsonData = await response.json();

        const tokenSelect = document.getElementById("token");
        tokenSelect.innerHTML = '<option value="">Select a token</option>'; // Reset dropdown

        // Add each token as an option in the dropdown
        jsonData.tokens.forEach((token) => {
          const option = document.createElement("option");
          option.value = token; // Use the token value
          option.textContent = token; // Display the token text
          tokenSelect.appendChild(option);
        });
      } catch (error) {
        console.error("Error fetching tokens:", error);
      }
    };

    // Call the fetchBooks and fetchTokens when the page loads
    fetchBooks();
    fetchTokens();
  });
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
  const bookListDiv = document.getElementById("book-list");
  const form = document.getElementById("book-form");
  const editBookDiv = document.getElementById("edit-book");

  // Fetch and display books
  const fetchBooks = async () => {
    try {
      const response = await fetch("get_books.php"); // Fetch book data from the backend
      const books = await response.json();

      // Clear the content of box1
      bookListDiv.innerHTML = "<h3>Book List</h3>";

      // Create a list of books
      const ul = document.createElement("ul");
      books.forEach((book) => {
        const li = document.createElement("li");
        li.textContent = `${book.book_name} by ${book.author} (ISBN: ${book.isbn}) - Count: ${book.count} `;

        // Create a select button for each book
        const selectButton = document.createElement("button");
        selectButton.textContent = "Edit";
        selectButton.onclick = () => showEditForm(book); // Show the selected book in the edit form
        li.appendChild(selectButton);

        ul.appendChild(li);
      });

      // Append the list to box1
      bookListDiv.appendChild(ul);
    } catch (error) {
      console.error("Error fetching books:", error);
    }
  };

  // Function to show book details in box2 for editing
  const showEditForm = (book) => {
    // Clear the box2 content
    editBookDiv.innerHTML = `
      <h3>Edit Book</h3>
      <label for="edit-book-name">Book Name:</label>
      <input type="text" id="edit-book-name" value="${book.book_name}" required>
      <label for="edit-count">Count:</label>
      <input type="number" id="edit-count" value="${book.count}" min="1" required>
      <button id="update-book">Update</button>
    `;

    // Add event listener to update button
    const updateButton = document.getElementById("update-book");
    updateButton.onclick = () => updateBook(book.id);
  };

  // Function to update the book in the database
  const updateBook = async (bookId) => {
    const updatedName = document.getElementById("edit-book-name").value;
    const updatedCount = document.getElementById("edit-count").value;

    if (!updatedName || !updatedCount) {
      alert("Please fill in all fields.");
      return;
    }

    try {
      // Send the updated book data to the backend
      const response = await fetch("update_book.php", {
        method: "POST",
        body: JSON.stringify({ id: bookId, book_name: updatedName, count: updatedCount }),
        headers: { "Content-Type": "application/json" },
      });

      const data = await response.json();

      if (data.status === "success") {
        alert("Book updated successfully!");
        fetchBooks(); // Refresh the book list
        editBookDiv.innerHTML = ""; // Clear the edit form
      } else {
        alert("Error updating book: " + data.message);
      }
    } catch (error) {
      console.error("Error updating book:", error);
      alert("Error updating book. Please try again.");
    }
  };

  // Initial fetch to display books when the page loads
  fetchBooks();

  // Handle form submission using AJAX
  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent the default form submission

    const formData = new FormData(form); // Get form data
    try {
      // Send form data to backend PHP script (add_book.php)
      const response = await fetch("add_book.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json(); // Parse the JSON response

      if (data.status === "success") {
        alert("Book added successfully!"); // Show success message as a prompt
        fetchBooks(); // Refresh the book list dynamically
        form.reset(); // Reset form fields after successful submission
      } else {
        alert(data.message); // Show error message if any
      }
    } catch (error) {
      console.error("Error adding book:", error);
      alert("Error adding book. Please try again."); // Show error message if AJAX fails
    }
  });
});
</script>

<script>
document.querySelector(".borrow-form").addEventListener("submit", async (event) => {
  event.preventDefault(); // Prevent default form submission

  const studentName = document.getElementById("student-name").value.trim();
  const email = document.getElementById("email").value.trim();
  const studentId = document.getElementById("student-id").value.trim();
  const bookId = document.getElementById("dropdown-book-name").value;
  const borrowDate = new Date(document.getElementById("borrow-date").value);
  const returnDate = new Date(document.getElementById("return-date").value);
  const token = document.getElementById("token").value;

  // Validate required fields
  if (!studentName || !email || !studentId || !bookId || !borrowDate || !returnDate) {
    alert("Please fill out all required fields.");
    return;
  }

  // Calculate borrow duration
  const borrowDuration = (returnDate - borrowDate) / (1000 * 60 * 60 * 24);

  if (borrowDuration > 10 && !token) {
    alert("A token is required for a borrow period exceeding 10 days.");
    return;
  }

  try {
    const response = await fetch("borrow_book.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        studentName,
        email,
        studentId,
        bookId,
        borrowDate: borrowDate.toISOString().split("T")[0],
        returnDate: returnDate.toISOString().split("T")[0],
        token,
      }),
    });

    const result = await response.json();

    if (result.status === "success") {
      alert("Book borrowed successfully!");

      // Handle token updates in the current window (moving it to token-box2)
      if (token) {
        const tokenBox2 = document.getElementById("token-box2");
        const tokenElement = document.createElement("div");
        tokenElement.textContent = token;
        tokenBox2.appendChild(tokenElement);

        // Remove the token from the dropdown
        const tokenDropdown = document.getElementById("token");
        const optionToRemove = Array.from(tokenDropdown.options).find(
          (option) => option.value === token
        );
        if (optionToRemove) tokenDropdown.removeChild(optionToRemove);
      }

      // Store receipt details in sessionStorage for the new window
      sessionStorage.setItem("receipt", JSON.stringify({
        studentName,
        email,
        bookName: "Book Name",  // You might want to retrieve this from the database
        borrowDate: borrowDate.toISOString().split("T")[0],
        returnDate: returnDate.toISOString().split("T")[0],
        token: token || "N/A",
      }));

      // Open the new window with the receipt details
      window.open("receipt_page.html", "_blank", "width=600,height=400");
    } else if (result.status === "unavailable") {
      alert(result.message); // Inform the user the book is out of stock
    } else {
      alert(`Error: ${result.message}`); // Handle other errors
    }
  } catch (error) {
    console.error("Error submitting the form:", error);
    alert("An error occurred while processing your request.");
  }
});

</script>
</body>
</html>
