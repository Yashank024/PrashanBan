<?php
// Database connection parameters
$servername = "localhost";
$username = "username";
$password = "@2211";
$dbname = "prashanbaan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$teamid = $_POST['Team Id'];
$pass = $_POST['Password'];
$query = "SELECT * FROM stulist WHERE teamid = '$teamid'";
$result = mysqli_query($connection, $query);
// Check if any row was returned
if (mysqli_num_rows($result) > 0) {
    echo "Input exists in the database.";
} else {
    echo "Input does not exist in the database.";
}

$memname = $_POST['Member Name'];
$teamname = $_POST['Team Name'];
$query = "INSERT INTO stulist (memname, teamname) VALUES ('$memname', '$teamname')";

if (mysqli_query($connection, $query)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($connection);
}
// Close connection
$conn->close();
?>