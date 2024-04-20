<?php
session_start();

// Database connection parameters
$servername = "localhost"; // Change to your server name
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "prashanban"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$tid = $_POST['tid']; 
$password = $_POST['password'];
$memname = $_POST['memname'];
$tname = $_POST['tname'];

// Prepare SQL statement to prevent SQL injection
$sql = $conn->prepare("SELECT memname, teamname FROM module1 WHERE id=? AND password=?");
$sql->bind_param("ss", $tid, $password);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    // Fetch the data from the result
    $row = $result->fetch_assoc();
    
    // Check if memname and teamname are null in the table
    if ($row['memname'] === null && $row['teamname'] === null) {
        // Update member name and team name in the database
        $updateSql = $conn->prepare("UPDATE module1 SET memname=?, teamname=? WHERE id=? AND password=?");
        $updateSql->bind_param("ssss", $memname, $tname, $tid, $password);
        if ($updateSql->execute()) {
            // Store the input data in session variables
            $_SESSION['tid'] = $tid;
            $_SESSION['password'] = $password;
            // Redirect to the second HTML page
            header("Location: instruction.html");
            exit; // Make sure to exit to prevent further execution
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // Redirect to another HTML page
        header("Location: error.html");
        exit;
    }
} else {
    echo "Invalid ID or password";
    header("Location: login.html");
}

$conn->close();
?>
