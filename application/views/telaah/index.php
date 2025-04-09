<div class="page-wrapper">
    <div class="container-fluid">
        <div class="page-header d-print-none" style="margin-top: 10px;">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        RAG
                    </div>
                    <h2 class="page-title">
                        SAPA Anggaran
                    </h2>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-8">
                <!-- Chat Container -->
                <div class="card" style="height: calc(100vh - 200px);">
                    <div class="card-body d-flex flex-column p-0">
                        <!-- Chat History -->
                        <div class="chat-history flex-grow-1 overflow-auto p-3" id="chatHistory">
                            <!-- AI Welcome Message -->
                            <div class="chat-message ai-message mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="chat-avatar me-2">
                                        <img src="./files/images/logo.png" alt="AI" class="avatar" width="35" height="35">
                                    </div>
                                    <div class="message-content">
                                        <div class="message-bubble p-3 bg-light rounded">
                                            <p class="mb-0">Hai, Saya SAPA Anggaran. Siap membantu Anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="chat-input border-top p-3">
                            <form id="chatForm" class="d-flex align-items-end gap-2">
                                <div class="flex-grow-1">
                                    <textarea class="form-control" id="messageInput" rows="1" placeholder="Ketik pesan Anda..." style="resize: none;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 14l11 -11"></path>
                                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"></path>
                                    </svg>
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Chat History Panel -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Percakapan</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="chat-history-list overflow-auto p-3" style="max-height: 200px;" id="chatHistoryList">
                            <!-- Chat history will be dynamically added here -->
                            <div class="text-muted text-center py-2">
                                Belum ada riwayat percakapan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Citation Panel -->
                <div class="card" style="height: calc(100vh - 450px);">
                    <div class="card-header">
                        <h3 class="card-title">Referensi</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="citation-list overflow-auto h-100 p-3" id="citationList">
                            <!-- Citations will be dynamically added here -->
                            <div class="text-muted text-center py-4">
                                Referensi akan muncul saat AI memberikan jawaban
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-history {
    background-color: #f8f9fa;
}

.message-bubble {
    max-width: 80%;
    word-wrap: break-word;
}

.user-message .message-bubble {
    background-color: #e9ecef !important;
}

.ai-message .message-bubble {
    background-color: #ffffff !important;
}

.citation-item {
    border-left: 3px solid #206bc4;
    background-color: #f8f9fa;
    margin-bottom: 10px;
    padding: 10px;
    font-size: 0.9em;
}

.citation-item:hover {
    background-color: #e9ecef;
}

.avatar {
    border-radius: 50%;
    object-fit: cover;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatHistory = document.getElementById('chatHistory');

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Handle form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            // Add user message to chat
            addUserMessage(message);
            // Clear input
            messageInput.value = '';
            messageInput.style.height = 'auto';
            // TODO: Send message to backend and handle response
        }
    });

    function addUserMessage(message) {
        const messageHtml = `
            <div class="chat-message user-message mb-3">
                <div class="d-flex align-items-start justify-content-end">
                    <div class="message-content">
                        <div class="message-bubble p-3 bg-light rounded">
                            <p class="mb-0">${escapeHtml(message)}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        chatHistory.insertAdjacentHTML('beforeend', messageHtml);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
</script>