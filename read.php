<?php
// read.php
include 'config.php';

if(isset($_GET['id'])) {
    // Fetch a single user's data for editing
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found.']);
    }
} else {
    // Fetch all users for display in the table
    $sql = "SELECT * FROM users ORDER BY id DESC";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>".htmlspecialchars($row['id'])."</td>
                    <td>".htmlspecialchars($row['first_name'])."</td>
                    <td>".htmlspecialchars($row['last_name'])."</td>
                    <td>".htmlspecialchars($row['phone'])."</td>
                    <td>".htmlspecialchars($row['email'])."</td>
                    <td>".htmlspecialchars($row['address'])."</td>
                    <td>
                        <button class='action-btn edit-btn' onclick='editUser(".$row['id'].")'>Edit</button>
                        <button class='action-btn delete-btn' onclick='deleteUser(".$row['id'].")'>Delete</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No users found.</td></tr>";
    }
}

$conn->close();
?>
