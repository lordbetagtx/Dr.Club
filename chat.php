<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-sidebar">
            <div class="chat-header">
                <h3>Messages</h3>
            </div>
            <div class="chat-list" id="chatList">
                <!-- Chat contacts will be loaded here -->
            </div>
        </div>
        
        <div class="chat-main">
            <div class="chat-header">
                <div class="chat-user-info">
                    <img src="" id="chatUserAvatar" alt="User">
                    <div>
                        <h4 id="chatUserName"></h4>
                        <span class="status" id="chatUserStatus">Offline</span>
                    </div>
                </div>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be loaded here -->
            </div>
            
            <div class="chat-input">
                <input type="file" id="imageInput" accept="image/*" style="display: none;">
                <button type="button" id="attachImage">
                    <i class="fas fa-paperclip"></i>
                </button>
                <input type="text" id="messageInput" placeholder="Type a message...">
                <button type="button" id="sendMessage">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/chat.js"></script>
</body>
</html>
