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
                <div class="col-auto">
                    <div class="btn-list">
                        <button type="button" class="btn btn-outline-secondary" id="newChatBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Chat Baru
                        </button>
                        <button type="button" class="btn btn-outline-info" id="healthCheckBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                            </svg>
                            Status
                        </button>
                    </div>
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
                            <div class="text-center py-4" id="loadingWelcome">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="text-muted mt-2">Memuat pesan selamat datang...</div>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="chat-input border-top p-3">
                            <form id="chatForm" class="d-flex align-items-end gap-2">
                                <div class="flex-grow-1">
                                    <textarea class="form-control" id="messageInput" rows="1" placeholder="Ketik pesan Anda..." style="resize: none;" disabled></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" id="sendButton" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 14l11 -11"></path>
                                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"></path>
                                    </svg>
                                    <span class="button-text">Kirim</span>
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
                    <div class="card-body p-3">
                        <div class="chat-history-list overflow-auto" style="max-height: 200px; height:200px" id="chatHistoryList">
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

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<style>
    .chat-history {
        background-color: #f8f9fa;
        min-height: 0;
    }

    .message-content {
        max-width: 80%;
    }

    .message-bubble {
        width: fit-content;
        word-wrap: break-word;
        white-space: normal;
    }

    .message-bubble p {
        margin-bottom: 0.5rem;
        white-space: pre-line;
    }

    .message-bubble p:last-child {
        margin-bottom: 0;
    }

    .message-bubble ul,
    .message-bubble ol {
        margin-bottom: 0.5rem;
        padding-left: 1.5rem;
    }

    .message-bubble li {
        margin-bottom: 0.25rem;
    }

    .user-message {
        justify-content: flex-end;
        width: 100%;
    }

    .user-message .message-bubble {
        border-radius: 15px 15px 0 15px !important;
    }

    .ai-message .message-bubble {
        background-color: #ffffff !important;
        border-radius: 0 15px 15px 15px !important;
        border: 1px solid #e9ecef;
    }

    .citation-item {
        border-left: 3px solid #206bc4;
        background-color: #f8f9fa;
        margin-bottom: 10px;
        padding: 10px;
        font-size: 0.9em;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .citation-item:hover {
        background-color: #e9ecef;
    }

    .avatar {
        border-radius: 50%;
        object-fit: cover;
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        font-style: italic;
        color: #6c757d;
    }

    .typing-dots {
        display: flex;
        gap: 0.25rem;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #6c757d;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            transform: translateY(0);
            opacity: 0.4;
        }

        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    #messageInput:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(32, 107, 196, 0.25);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .chat-history-item {
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        transition: background-color 0.2s;
        border: 1px solid transparent;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-history-item:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .chat-history-item.active {
        background-color: #206bc4;
        color: white !important;
    }

    .chat-history-content {
        flex: 1;
        min-width: 0;
        /* Untuk memungkinkan text truncation */
    }

    .chat-history-actions {
        display: flex;
        gap: 0.25rem;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .chat-history-item:hover .chat-history-actions {
        opacity: 1;
    }

    .chat-history-item.active .chat-history-actions {
        opacity: 1;
    }

    .delete-chat-btn {
        background: none;
        border: none;
        color: #dc3545;
        padding: 0.25rem;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-chat-btn:hover {
        background-color: #dc3545;
        color: white;
    }

    .chat-history-item.active .delete-chat-btn {
        color: #fff;
    }

    .chat-history-item.active .delete-chat-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .success-message {
        color: #198754;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .connection-status {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .connection-status.online {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }

    .connection-status.offline {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c2c7;
    }

    .message-timestamp {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .user-message .message-timestamp {
        color: #ffffff;
    }

    .citation-badge {
        display: inline-block;
        background-color: #206bc4;
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 0.375rem;
        height: 1rem;
        margin-bottom: 0.5rem;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // DOM Elements
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatHistory = document.getElementById('chatHistory');
        const sendButton = document.getElementById('sendButton');
        const newChatBtn = document.getElementById('newChatBtn');
        const healthCheckBtn = document.getElementById('healthCheckBtn');
        const chatHistoryList = document.getElementById('chatHistoryList');
        const citationList = document.getElementById('citationList');
        const toastContainer = document.getElementById('toastContainer');

        // State Management
        let currentSessionId = null;
        let isSubmitting = false;
        let chatHistoryData = [];
        let connectionStatus = 'unknown';

        // Configuration
        const API_BASE_URL = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '') + '/api';
        const MAX_RETRY_ATTEMPTS = 3;
        const RETRY_DELAY = 1000;

        // Utility Functions
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function formatTimestamp(date) {
            const dateObj = date instanceof Date ? date : new Date(date);

            if (isNaN(dateObj.getTime())) {
                return 'Invalid Date';
            }

            return dateObj.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function generateId() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }

        // Toast Notification System
        function showToast(message, type = 'info', duration = 5000) {
            const toastId = generateId();
            const bgClass = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            } [type] || 'bg-info';

            const toastHtml = `
                <div class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${escapeHtml(message)}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                delay: duration
            });
            toast.show();

            // Auto remove after duration
            setTimeout(() => {
                if (toastElement && toastElement.parentNode) {
                    toastElement.remove();
                }
            }, duration + 500);
        }

        // Connection Status Management
        function updateConnectionStatus(status) {
            connectionStatus = status;
            let statusElement = document.querySelector('.connection-status');

            if (!statusElement) {
                statusElement = document.createElement('div');
                statusElement.className = 'connection-status';
                document.body.appendChild(statusElement);
            }

            if (status === 'online') {
                statusElement.className = 'connection-status online';
                statusElement.textContent = '🟢 Terhubung';
            } else {
                statusElement.className = 'connection-status offline';
                statusElement.textContent = '🔴 Terputus';
            }

            // Auto hide after 3 seconds if online
            if (status === 'online') {
                setTimeout(() => {
                    if (statusElement && statusElement.parentNode) {
                        statusElement.remove();
                    }
                }, 3000);
            }
        }

        // API Request Handler with Retry Logic
        async function makeApiRequest(endpoint, options = {}, retryCount = 0) {
            try {
                const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        ...options.headers
                    },
                    ...options
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                updateConnectionStatus('online');
                return await response.json();
            } catch (error) {
                console.error(`API request failed (attempt ${retryCount + 1}):`, error);

                if (retryCount < MAX_RETRY_ATTEMPTS) {
                    await new Promise(resolve => setTimeout(resolve, RETRY_DELAY * (retryCount + 1)));
                    return makeApiRequest(endpoint, options, retryCount + 1);
                }

                updateConnectionStatus('offline');
                throw error;
            }
        }

        // Message Formatting Functions
        function applyInlineMarkdown(text) {
            let content = escapeHtml(text);

            // Bold: **text** or __text__
            content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            content = content.replace(/__(.*?)__/g, '<strong>$1</strong>');

            // Italic: *text* or _text_ (avoiding conflicts with bold and lists)
            content = content.replace(/(?<!\*)\*(?!\s|\*)([^*]+?)(?<!\s|\*)\*(?!\*)/g, '<em>$1</em>');
            content = content.replace(/(?<!_)_(?!_)([^_]+?)(?<!_)_(?!_)/g, '<em>$1</em>');

            // Code: `text`
            content = content.replace(/`([^`]+)`/g, '<code>$1</code>');

            return content;
        }

        function formatAIMessage(message) {
            if (!message) return '';

            message = message.replace(/\r\n/g, '\n').replace(/\r/g, '\n');
            message = message.replace(/[ \t]+/g, ' ');
            message = message.replace(/\n{3,}/g, '\n\n');
            message = message.trim();

            const lines = message.split('\n');
            let htmlOutput = '';
            let inList = null;
            let listContent = '';
            let paragraphBuffer = [];

            const processParagraphBuffer = () => {
                if (paragraphBuffer.length > 0) {
                    const paragraphText = paragraphBuffer.join('\n');
                    if (paragraphText.trim() !== '') {
                        const processedParaText = applyInlineMarkdown(paragraphText);
                        htmlOutput += `<p>${processedParaText.replace(/\n/g, '<br>')}</p>`;
                    }
                    paragraphBuffer = [];
                }
            };

            for (let i = 0; i < lines.length; i++) {
                const line = lines[i];

                if (line.trim() === "") {
                    processParagraphBuffer();
                    if (inList) {
                        listContent += `</${inList}>`;
                        htmlOutput += listContent;
                        listContent = '';
                        inList = null;
                    }
                    continue;
                }

                const olMatch = line.match(/^\s*\d+\.\s+(.*)/);
                const ulMatch = line.match(/^\s*[\*\-]\s+(.*)/);

                if (olMatch) {
                    processParagraphBuffer();
                    if (inList !== 'ol') {
                        if (inList) listContent += `</${inList}>`;
                        listContent += '<ol>';
                        inList = 'ol';
                    }
                    const itemText = olMatch[1].trim();
                    listContent += `<li>${applyInlineMarkdown(itemText)}</li>`;
                } else if (ulMatch) {
                    processParagraphBuffer();
                    if (inList !== 'ul') {
                        if (inList) listContent += `</${inList}>`;
                        listContent += '<ul>';
                        inList = 'ul';
                    }
                    const itemText = ulMatch[1].trim();
                    listContent += `<li>${applyInlineMarkdown(itemText)}</li>`;
                } else {
                    if (inList) {
                        listContent += `</${inList}>`;
                        htmlOutput += listContent;
                        listContent = '';
                        inList = null;
                    }
                    paragraphBuffer.push(line);
                }
            }

            processParagraphBuffer();
            if (inList) {
                listContent += `</${inList}>`;
                htmlOutput += listContent;
            }

            return htmlOutput;
        }

        // Message Display Functions
        function addUserMessage(message, timestamp = new Date(), sessionId = currentSessionId) {
            const messageId = generateId();
            const formattedTimestamp = formatTimestamp(timestamp);
            const safeMessage = escapeHtml(message).replace(/\n/g, '<br>');

            const messageHtml = `
                <div class="chat-message user-message mb-3" data-message-id="${messageId}">
                    <div class="d-flex justify-content-end">
                        <div class="message-content">
                            <div class="message-bubble p-3 bg-primary text-white">
                                <div class="message-text">${safeMessage}</div>
                                <div class="message-timestamp text-end opacity-75">${formattedTimestamp}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', messageHtml);
            scrollChatToBottom();

            // Add to history with sessionId
            addToChatHistory({
                id: messageId,
                type: 'user',
                message: message,
                timestamp: timestamp,
                sessionId: sessionId
            });
        }

        function addAIMessage(message, citations = [], isError = false, timestamp = new Date(), sessionId = currentSessionId) {
            const messageId = generateId();
            const formattedTimestamp = formatTimestamp(timestamp);
            const messageClass = isError ? 'alert alert-danger' : '';
            const formattedHtmlMessage = isError ? `<p>${escapeHtml(message)}</p>` : formatAIMessage(message);

            const messageHtml = `
                <div class="chat-message ai-message mb-3" data-message-id="${messageId}">
                    <div class="d-flex align-items-start">
                        <div class="chat-avatar me-2">
                            <img src="./files/images/logo.png" alt="AI" class="avatar" width="35" height="35" onerror="this.style.display='none'">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble p-3 ${messageClass}">
                                <div class="message-text">${formattedHtmlMessage}</div>
                                <div class="message-timestamp">${formattedTimestamp}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', messageHtml);

            if (!isError && citations.length > 0) {
                updateCitations(citations);
            }

            scrollChatToBottom();

            // Add to history with sessionId
            addToChatHistory({
                id: messageId,
                type: 'ai',
                message: message,
                citations: citations,
                timestamp: timestamp,
                isError: isError,
                sessionId: sessionId
            });
        }

        function displayUserMessage(message, timestamp = new Date(), sessionId = currentSessionId) {
            const messageId = generateId();
            const formattedTimestamp = formatTimestamp(timestamp);
            const safeMessage = escapeHtml(message).replace(/\n/g, '<br>');

            const messageHtml = `
                <div class="chat-message user-message mb-3" data-message-id="${messageId}">
                    <div class="d-flex justify-content-end">
                        <div class="message-content">
                            <div class="message-bubble p-3 bg-primary text-white">
                                <div class="message-text">${safeMessage}</div>
                                <div class="message-timestamp text-end opacity-75">${formattedTimestamp}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', messageHtml);
            scrollChatToBottom();
        }

        function displayAIMessage(message, citations = [], isError = false, timestamp = new Date(), sessionId = currentSessionId) {
            const messageId = generateId();
            const formattedTimestamp = formatTimestamp(timestamp);
            const messageClass = isError ? 'alert alert-danger' : '';
            const formattedHtmlMessage = isError ? `<p>${escapeHtml(message)}</p>` : formatAIMessage(message);

            const messageHtml = `
                <div class="chat-message ai-message mb-3" data-message-id="${messageId}">
                    <div class="d-flex align-items-start">
                        <div class="chat-avatar me-2">
                            <img src="./files/images/logo.png" alt="AI" class="avatar" width="35" height="35" onerror="this.style.display='none'">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble p-3 ${messageClass}">
                                <div class="message-text">${formattedHtmlMessage}</div>
                                <div class="message-timestamp">${formattedTimestamp}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', messageHtml);

            if (!isError && citations.length > 0) {
                updateCitations(citations);
            }

            scrollChatToBottom();
        }

        function addTypingIndicator() {
            const typingHtml = `
                <div class="chat-message ai-message mb-3" id="typingIndicator">
                    <div class="d-flex align-items-start">
                        <div class="chat-avatar me-2">
                            <img src="./files/images/logo.png" alt="AI" class="avatar" width="35" height="35" onerror="this.style.display='none'">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble p-3 bg-white border">
                                <div class="typing-indicator">
                                    <span>AI sedang mengetik</span>
                                    <div class="typing-dots">
                                        <div class="typing-dot"></div>
                                        <div class="typing-dot"></div>
                                        <div class="typing-dot"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', typingHtml);
            scrollChatToBottom();
        }

        function removeTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        function scrollChatToBottom() {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        // Citation Management
        function updateCitations(citations) {
            if (citations.length === 0) {
                citationList.innerHTML = `
                    <div class="text-muted text-center py-4">
                        Tidak ada referensi untuk pesan ini
                    </div>
                `;
                return;
            }

            const citationsHtml = citations.map((citation, index) => `
                <div class="citation-item" data-citation-index="${index}">
                    <div class="citation-badge">Referensi ${index + 1}</div>
                    <div class="fw-bold">${escapeHtml(citation.title)}</div>
                    <div class="text-muted mt-1">${escapeHtml(citation.text.substring(0, 200))}${citation.text.length > 200 ? '...' : ''}</div>
                </div>
            `).join('');

            citationList.innerHTML = citationsHtml;

            // Add click handlers for citations
            citationList.querySelectorAll('.citation-item').forEach(item => {
                item.addEventListener('click', function() {
                    const index = this.dataset.citationIndex;
                    const citation = citations[index];
                    showCitationModal(citation);
                });
            });
        }

        function showCitationModal(citation) {
            const modalHtml = `
                <div class="modal fade" id="citationModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${escapeHtml(citation.title)}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="citation-content">
                                    ${escapeHtml(citation.text).replace(/\n/g, '<br>')}
                                </div>
                                ${citation.source ? `<div class="mt-3 text-muted"><strong>Sumber:</strong> ${escapeHtml(citation.source)}</div>` : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal
            const existingModal = document.getElementById('citationModal');
            if (existingModal) {
                existingModal.remove();
            }

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = new bootstrap.Modal(document.getElementById('citationModal'));
            modal.show();

            // Auto remove modal after it's hidden
            document.getElementById('citationModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // Chat History Management
        function addToChatHistory(messageData) {
            chatHistoryData.push(messageData);
            updateChatHistoryDisplay();
            saveChatHistoryToStorage();
        }

        function deleteChatSession(sessionId, event) {
            event.stopPropagation();

            if (confirm('Apakah Anda yakin ingin menghapus percakapan ini?')) {
                // Hapus semua pesan dari session ini
                chatHistoryData = chatHistoryData.filter(msg => msg.sessionId !== sessionId);

                // Jika session yang dihapus adalah session yang sedang aktif
                if (currentSessionId === sessionId) {
                    // Clear chat area
                    chatHistory.innerHTML = '';
                    citationList.innerHTML = `
                        <div class="text-muted text-center py-4">
                            Referensi akan muncul saat AI memberikan jawaban
                        </div>
                    `;
                    currentSessionId = null;
                }

                // Update display dan simpan ke storage
                updateChatHistoryDisplay();
                saveChatHistoryToStorage();

                showToast('Percakapan berhasil dihapus', 'success');
            }
        }

        function updateChatHistoryDisplay() {
            if (chatHistoryData.length === 0) {
                chatHistoryList.innerHTML = `
                    <div class="text-muted text-center py-2">
                        Belum ada riwayat percakapan
                    </div>
                `;
                return;
            }

            // Create a map of unique sessions
            const sessions = new Map();
            chatHistoryData.forEach(message => {
                if (message.sessionId && message.type === 'user') {
                    if (!sessions.has(message.sessionId)) {
                        sessions.set(message.sessionId, {
                            message: message.message,
                            timestamp: message.timestamp
                        });
                    }
                }
            });

            // Convert sessions map to array and sort by timestamp in descending order
            const sortedSessions = Array.from(sessions.entries())
                .sort((a, b) => new Date(b[1].timestamp) - new Date(a[1].timestamp));

            let historyHtml = '';

            sortedSessions.forEach(([sessionId, data]) => {
                const messagePreview = data.message.length > 50 ?
                    data.message.substring(0, 50) + '...' :
                    data.message;
                const messageTime = formatTimestamp(new Date(data.timestamp));

                historyHtml += `
                    <div class="chat-history-item ${sessionId === currentSessionId ? 'active' : ''}" data-session-id="${sessionId}">
                        <div class="chat-history-content">
                            <div class="fw-bold">${escapeHtml(messagePreview)}</div>
                            <div class="${sessionId === currentSessionId ? 'text-white-50' : 'text-muted'} small">${messageTime}</div>
                        </div>
                        <div class="chat-history-actions">
                            <button type="button" class="delete-chat-btn" data-session-id="${sessionId}" title="Hapus percakapan">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });

            chatHistoryList.innerHTML = historyHtml;

            // Add click handlers
            chatHistoryList.querySelectorAll('.chat-history-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Jangan trigger jika click pada tombol delete
                    if (e.target.closest('.delete-chat-btn')) {
                        return;
                    }

                    const sessionId = this.dataset.sessionId;
                    loadChatSession(sessionId);
                });
            });

            // Add delete button handlers
            chatHistoryList.querySelectorAll('.delete-chat-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const sessionId = this.dataset.sessionId;
                    deleteChatSession(sessionId, e);
                });
            });
        }

        function loadChatSession(sessionId) {
            currentSessionId = sessionId;

            chatHistory.innerHTML = '';

            const sessionMessages = chatHistoryData.filter(msg => msg.sessionId === sessionId);

            sessionMessages.forEach(msg => {
                if (msg.type === 'user') {
                    const timestamp = new Date(msg.timestamp);
                    displayUserMessage(msg.message, msg.timestamp, msg.sessionId);
                } else if (msg.type === 'ai') {
                    const timestamp = new Date(msg.timestamp);
                    displayAIMessage(msg.message, msg.citations || [], msg.isError || false, msg.timestamp, msg.sessionId);
                }
            });

            updateChatHistoryDisplay();
        }

        function saveChatHistoryToStorage() {
            try {
                localStorage.setItem('chatHistory', JSON.stringify(chatHistoryData));
            } catch (error) {
                console.warn('Failed to save chat history to localStorage:', error);
            }
        }

        function loadChatHistoryFromStorage() {
            try {
                const stored = localStorage.getItem('chatHistory');
                if (stored) {
                    chatHistoryData = JSON.parse(stored);
                    updateChatHistoryDisplay();
                }
            } catch (error) {
                console.warn('Failed to load chat history from localStorage:', error);
            }
        }

        // API Functions
        async function getWelcomeMessage() {
            try {
                const response = await makeApiRequest('/welcome', {
                    method: 'POST'
                });

                if (response.success) {
                    currentSessionId = response.session_id;

                    // Remove loading indicator
                    const loadingWelcome = document.getElementById('loadingWelcome');
                    if (loadingWelcome) {
                        loadingWelcome.remove();
                    }

                    if (response.message) {
                        addAIMessage(response.message);
                    }

                    // Enable input
                    messageInput.disabled = false;
                    sendButton.disabled = false;
                    messageInput.focus();
                } else {
                    throw new Error(response.message || 'Failed to get welcome message');
                }
            } catch (error) {
                console.error('Welcome message error:', error);

                // Remove loading indicator
                const loadingWelcome = document.getElementById('loadingWelcome');
                if (loadingWelcome) {
                    loadingWelcome.remove();
                }

                addAIMessage('Maaf, terjadi kesalahan saat memuat pesan selamat datang. Silakan coba lagi nanti.', [], true);
                showToast('Gagal memuat pesan selamat datang: ' + error.message, 'error');

                // Still enable input so user can try
                messageInput.disabled = false;
                sendButton.disabled = false;
            }
        }

        async function sendMessage(message) {
            if (isSubmitting || !message.trim()) return;

            isSubmitting = true;
            const originalButtonText = sendButton.querySelector('.button-text').textContent;

            try {
                // Update UI
                sendButton.disabled = true;
                sendButton.querySelector('.button-text').textContent = 'Mengirim...';

                // Add user message
                addUserMessage(message);

                // Clear input
                messageInput.value = '';
                messageInput.style.height = 'auto';

                // Show typing indicator
                addTypingIndicator();

                const response = await makeApiRequest('/chat', {
                    method: 'POST',
                    body: JSON.stringify({
                        message: message,
                        session_id: currentSessionId
                    })
                });

                // Remove typing indicator
                removeTypingIndicator();

                if (response.success) {
                    currentSessionId = response.session_id;
                    addAIMessage(response.message, response.citations || []);

                    if (response.citations && response.citations.length > 0) {
                        showToast(`Ditemukan ${response.citations.length} referensi`, 'info', 3000);
                    }
                } else {
                    throw new Error(response.message || 'Failed to send message');
                }

            } catch (error) {
                console.error('Send message error:', error);
                removeTypingIndicator();
                addAIMessage(`Maaf, terjadi kesalahan: ${error.message}`, [], true);
                showToast('Gagal mengirim pesan: ' + error.message, 'error');
            } finally {
                isSubmitting = false;
                sendButton.disabled = false;
                sendButton.querySelector('.button-text').textContent = originalButtonText;
                messageInput.focus();
            }
        }

        async function checkHealth() {
            try {
                healthCheckBtn.disabled = true;
                healthCheckBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengecek...';

                const response = await makeApiRequest('/health');

                if (response.success) {
                    showToast('Layanan berjalan normal', 'success');
                    updateConnectionStatus('online');
                } else {
                    throw new Error(response.message || 'Health check failed');
                }
            } catch (error) {
                console.error('Health check error:', error);
                showToast('Layanan tidak dapat diakses: ' + error.message, 'error');
                updateConnectionStatus('offline');
            } finally {
                healthCheckBtn.disabled = false;
                healthCheckBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                    </svg>
                    Status
                `;
            }
        }

        function startNewChat() {
            if (confirm('Apakah Anda yakin ingin memulai percakapan baru?')) {
                // Clear current chat
                chatHistory.innerHTML = '';
                citationList.innerHTML = `
                    <div class="text-muted text-center py-4">
                        Referensi akan muncul saat AI memberikan jawaban
                    </div>
                `;

                currentSessionId = null;

                // Show loading and get welcome message
                chatHistory.innerHTML = `
                    <div class="text-center py-4" id="loadingWelcome">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-muted mt-2">Memuat pesan selamat datang...</div>
                    </div>
                `;

                messageInput.disabled = true;
                sendButton.disabled = true;

                getWelcomeMessage();
                showToast('Percakapan baru dimulai', 'info');
            }
        }

        // Event Listeners
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                if (e.shiftKey) {
                    return true;
                } else {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!isSubmitting) {
                        sendMessage(this.value);
                    }
                    return false;
                }
            }
        });

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!isSubmitting) {
                sendMessage(messageInput.value);
            }
        });

        newChatBtn.addEventListener('click', startNewChat);
        healthCheckBtn.addEventListener('click', checkHealth);

        // Auto-save message as user types (debounced)
        let saveTimeout;
        messageInput.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                localStorage.setItem('draftMessage', this.value);
            }, 1000);
        });

        // Load draft message on page load
        const draftMessage = localStorage.getItem('draftMessage');
        if (draftMessage) {
            messageInput.value = draftMessage;
            messageInput.style.height = 'auto';
            messageInput.style.height = Math.min(messageInput.scrollHeight, 120) + 'px';
        }

        // Clear draft when message is sent
        function clearDraft() {
            localStorage.removeItem('draftMessage');
        }

        // Initialize
        loadChatHistoryFromStorage();
        await getWelcomeMessage();

        // Periodic health check
        setInterval(checkHealth, 5 * 60 * 1000);

        console.log('RAG Chat initialized successfully');
    });
</script>
