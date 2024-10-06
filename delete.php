<?php
// delete.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

if(isset($_POST['id'])) {
    $id = intval($_POST['id']);
    if ($id <= 0) {
        echo "Invalid user ID.";
        exit;
    }

    // Delete using prepared statements
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "success";
        } else {
            echo "User not found.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No user ID provided.";
    exit;
}
?>
