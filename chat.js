class ChatApp {
    constructor() {
        this.currentChatUser = null;
        this.userId = getCurrentUserId(); // Get from session
        this.initializeEventListeners();
        this.loadChatList();
        this.startPolling();
    }
    
    initializeEventListeners() {
        $('#sendMessage').click(() => this.sendMessage());
        $('#messageInput').keypress((e) => {
            if (e.which === 13) this.sendMessage();
        });
        $('#attachImage').click(() => $('#imageInput').click());
        $('#imageInput').change((e) => this.handleImageUpload(e));
    }
    
    sendMessage() {
        const message = $('#messageInput').val().trim();
        if (!message || !this.currentChatUser) return;
        
        $.ajax({
            url: 'api/send-message.php',
            method: 'POST',
            data: {
                receiver_id: this.currentChatUser,
                message: message
            },
            success: (response) => {
                if (response.success) {
                    this.appendMessage({
                        message: message,
                        sender_id: this.userId,
                        created_at: new Date().toISOString()
                    });
                    $('#messageInput').val('');
                }
            }
        });
    }
    
    loadMessages(userId) {
        $.ajax({
            url: 'api/get-messages.php',
            data: { user_id: userId },
            success: (response) => {
                $('#chatMessages').empty();
                response.messages.forEach(msg => this.appendMessage(msg));
                this.scrollToBottom();
            }
        });
    }
    
    appendMessage(message) {
        const isOwn = message.sender_id == this.userId;
        const messageHtml = `
            <div class="message ${isOwn ? 'own' : 'other'}">
                <div class="message-content">
                    ${message.image_url ? `<img src="${message.image_url}" class="message-image">` : ''}
                    <p>${message.message}</p>
                    <span class="timestamp">${this.formatTime(message.created_at)}</span>
                </div>
            </div>
        `;
        $('#chatMessages').append(messageHtml);
        this.scrollToBottom();
    }
    
    startPolling() {
        setInterval(() => {
            if (this.currentChatUser) {
                this.checkNewMessages();
            }
        }, 2000);
    }
    
    checkNewMessages() {
        $.ajax({
            url: 'api/check-new-messages.php',
            data: { user_id: this.currentChatUser },
            success: (response) => {
                if (response.new_messages) {
                    response.new_messages.forEach(msg => this.appendMessage(msg));
                }
            }
        });
    }
}

// Initialize chat app
$(document).ready(() => {
    new ChatApp();
});
