<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$senderId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'];
$message = $_POST['message'];

$sql = "INSERT INTO chat_messages (sender_id, receiver_id, message, created_at) 
        VALUES (?, ?, ?, NOW())";
$stmt = $db->prepare($sql);

if ($stmt->execute([$senderId, $receiverId, $message])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to send message']);
}
?>
