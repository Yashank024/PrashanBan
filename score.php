<?php
session_start();

// Check if session variables are set
if (isset($_SESSION['tid']) && isset($_SESSION['password'])) {
    // Retrieve id and password from session variables
    $tid = $_SESSION['tid'];
    $password = $_SESSION['password'];

    // Now you can use $tid and $password in this file

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the score and lastQuestionTime from the POST request
        $score = isset($_POST['score']) ? $_POST['score'] : 0;
        $lastQuestionTime = isset($_POST['lastQuestionTime']) ? $_POST['lastQuestionTime'] : '00:00:00'; // Assuming the default value is '00:00:00'

        // Convert lastQuestionTime to hour:minute:second format
        list($hours, $minutes, $seconds) = explode(':', $lastQuestionTime);
        $formattedLastQuestionTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Store the score and lastQuestionTime in the session
        $_SESSION['score'] = $score;
        $_SESSION['lastQuestionTime'] = $lastQuestionTime;

        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $dbpassword = ""; // Changed variable name to avoid conflict
        $dbname = "prashanban"; // Replace 'your_database_name' with your actual database name

        // Create connection
        $conn = new mysqli($servername, $username, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute SQL statement to update score and lastQuestionTime in the database
        $stmt = $conn->prepare("UPDATE module1 SET result=?, submittime=? WHERE id=? AND password=?");
        $stmt->bind_param("ssss", $score, $formattedLastQuestionTime, $tid, $password);
        if ($stmt->execute()) {
            echo "Score and last question time stored successfully.";
        } else {
            echo "Error storing score and last question time: " . $conn->error;
        }

        $conn->close();
    } else {
        // If the request method is not POST, handle the error accordingly
        echo "Invalid request method.";
    }

} else {
    // Handle the case when session variables are not set
    echo "Session variables not set. Please login first.";
    // You might want to redirect the user to the login page here
}
?>
