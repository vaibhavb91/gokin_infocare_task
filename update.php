<?php
// update.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

// Function to sanitize input data
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$userId = intval($_POST['userId'] ?? 0);
$firstName = test_input($_POST['firstName'] ?? '');
$lastName = test_input($_POST['lastName'] ?? '');
$phone = test_input($_POST['phone'] ?? '');
$email = test_input($_POST['email'] ?? '');
$address = test_input($_POST['address'] ?? '');

// Validate inputs
if ($userId <= 0) {
    echo "Invalid user ID.";
    exit;
}

if(!preg_match("/^[a-zA-Z ]+$/", $firstName)){
    echo "Invalid first name.";
    exit;
}

if(!preg_match("/^[a-zA-Z ]+$/", $lastName)){
    echo "Invalid last name.";
    exit;
}

if(!preg_match("/^\d{10}$/", $phone)){
    echo "Invalid phone number. It should be 10 digits.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

// Check if email already exists for other users
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $userId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Email already exists.";
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, email = ?, address = ? WHERE id = ?");
$stmt->bind_param("sssssi", $firstName, $lastName, $phone, $email, $address, $userId);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
