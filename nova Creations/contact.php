<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted correctly
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Database connection details
    $servername = "127.0.0.1"; // Use IP instead of 'localhost'
    $username = "root";        // MySQL root user
    $password = "";            // No password for root
    $dbname = "contact_form_db"; // Database name
    $port = 3307;              // Change this if MySQL runs on a different port

    // Create a new MySQL connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check database connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert form data
    $stmt = $conn->prepare("INSERT INTO contact_form_submissions (name, email, message) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Thank you! Your message has been save successfully.'); window.location.href='home.html';</script>";
    } else {
        echo "<script>alert('Error submitting the form. Please try again.'); window.history.back();</script>";
    }
   
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
