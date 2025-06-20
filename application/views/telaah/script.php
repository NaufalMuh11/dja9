<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const chatLayout = document.getElementById('chatLayout');
        const collapseBtn = document.getElementById('collapseBtn');
        const mobileHistoryBtn = document.getElementById('mobileHistoryBtn');
        const backdrop = document.querySelector('.offcanvas-backdrop');

        if (collapseBtn) {
            collapseBtn.addEventListener('click', () => {
                chatLayout.classList.toggle('collapsed');
            });
        }

        if (mobileHistoryBtn) {
            mobileHistoryBtn.addEventListener('click', () => {
                chatLayout.classList.add('offcanvas-show');
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', () => {
                chatLayout.classList.remove('offcanvas-show');
            });
        }

        const historySearchInput = document.getElementById('historySearchInput');
        if (historySearchInput) {
            historySearchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const historyItems = document.querySelectorAll('#chatHistoryList .chat-history-item');
                historyItems.forEach(item => {
                    const itemText = item.textContent.toLowerCase();
                    item.style.display = itemText.includes(searchTerm) ? 'flex' : 'none';
                });
            });
        }

        // DOM Elements
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatHistory = document.getElementById('chatHistory');
        const sendButton = document.getElementById('sendButton');
        const newChatBtn = document.getElementById('newChatBtn');
        const chatHistoryList = document.getElementById('chatHistoryList');
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

        function generateId() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }

        /**
         * Mengubah objek error teknis menjadi pesan yang ramah untuk pengguna.
         */
        function getFriendlyErrorMessage(error) {
            const errorMessage = error.message || '';
            console.error("Original error:", errorMessage); // For debugging

            if (errorMessage.includes('HTTP 503') || errorMessage.includes('HTTP 502')) {
                return "Layanan sedang tidak tersedia atau dalam pemeliharaan. Mohon coba lagi beberapa saat.";
            }
            if (errorMessage.includes('HTTP 500')) {
                return "Maaf, terjadi gangguan pada server kami. Tim teknis kami telah diberitahu. Silakan coba lagi.";
            }
            if (errorMessage.includes('Failed to fetch')) {
                return "Gagal terhubung ke server. Mohon periksa koneksi internet Anda.";
            }
            if (errorMessage.includes('timeout')) {
                return "Waktu respons server habis. Jaringan mungkin sedang padat, silakan coba lagi.";
            }

            // Pesan default untuk error lainnya
            return "Aduh, terjadi sedikit kendala teknis. Silakan coba lagi.";
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

            setTimeout(() => {
                if (toastElement && toastElement.parentNode) {
                    toastElement.remove();
                }
            }, duration + 1000);
        }

        // Universal Modal Function
        function showConfirmationModal(options) {
            const {
                title = 'Konfirmasi',
                    message = 'Apakah Anda yakin?',
                    type = 'info', // 'info', 'danger', 'warning'
                    confirmText = 'Ya',
                    cancelText = 'Batal',
                    onConfirm = () => {},
                    onCancel = () => {}
            } = options;

            const modalId = 'universalConfirmModal';

            // Remove existing modal if any
            const existingModal = document.getElementById(modalId);
            if (existingModal) {
                existingModal.remove();
            }

            const statusClass = {
                'info': 'bg-info',
                'danger': 'bg-danger',
                'warning': 'bg-warning'
            } [type] || 'bg-info';

            const iconSvg = {
                'info': `<path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12.802 2.165l5.575 2.389c.48 .206 .863 .589 1.07 1.07l2.388 5.574c.22 .512 .22 1.092 0 1.604l-2.389 5.575c-.206 .48 -.589 .863 -1.07 1.07l-5.574 2.388c-.512 .22 -1.092 .22 -1.604 0l-5.575 -2.389a2.036 2.036 0 0 1 -1.07 -1.07l-2.388 -5.574a2.036 2.036 0 0 1 0 -1.604l2.389 -5.575c.206 -.48 .589 -.863 1.07 -1.07l5.574 -2.388a2.036 2.036 0 0 1 1.604 0z" />
                <path d="M12 16v.01" />
                <path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />`,
                'danger': `<path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M12 9v2m0 4v.01" />
                  <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />`,
                'warning': `<path stroke="none" d="M0 0h24v24H0z" fill="none" />
                   <path d="M12 9v2m0 4v.01" />
                   <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />`
            } [type] || '';

            const textClass = type === 'danger' ? 'text-danger' :
                type === 'warning' ? 'text-warning' : 'text-info';

            const buttonClass = type === 'danger' ? 'btn-danger' :
                type === 'warning' ? 'btn-warning' : 'btn-info';

            const modalHtml = `
                <div class="modal" id="${modalId}" tabindex="-1">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="modal-status ${statusClass}"></div>
                            <div class="modal-body text-center py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 ${textClass} icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    ${iconSvg}
                                </svg>
                                <div>${escapeHtml(message)}</div>
                            </div>
                            <div class="modal-footer">
                                <div class="w-100">
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn w-100" data-bs-dismiss="modal" id="cancelBtn">${escapeHtml(cancelText)}</button>
                                        </div>
                                        <div class="col">
                                            <button class="btn ${buttonClass} w-100" data-bs-dismiss="modal" id="confirmBtn">${escapeHtml(confirmText)}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modal = new bootstrap.Modal(document.getElementById(modalId));
            const modalElement = document.getElementById(modalId);

            // Event listeners
            modalElement.querySelector('#confirmBtn').addEventListener('click', () => {
                onConfirm();
            });

            modalElement.querySelector('#cancelBtn').addEventListener('click', () => {
                onCancel();
            });

            // Clean up when modal is hidden
            modalElement.addEventListener('hidden.bs.modal', function() {
                this.remove();
            });

            modal.show();
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
            const safeMessage = escapeHtml(message).replace(/\n/g, '<br>');

            const messageHtml = `
                <div class="chat-message user-message mb-3" data-message-id="${messageId}">
                    <div class="d-flex justify-content-end">
                        <div class="message-content">
                            <div class="message-bubble p-3 bg-primary text-white">
                                <div class="message-text">${safeMessage}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', messageHtml);
            scrollChatToBottom();
        }

        function addAIMessage(message, options = {}) {
            const config = {
                citations: [],
                isError: false,
                retryable: false,
                originalQuery: null,
                timestamp: new Date(),
                sessionId: currentSessionId,
                saveToHistory: true,
                ...options
            };

            const messageId = generateId();
            const messageClass = config.isError ? 'error-bubble' : '';
            const formattedHtmlMessage = config.isError ? `<p>${escapeHtml(message)}</p>` : formatAIMessage(message);

            let actionsHtml = '';
            if (config.isError && config.retryable && config.originalQuery) {
                actionsHtml = `
                    <div class="message-actions mt-2 pt-2 border-top">
                        <button class="btn btn-sm btn-outline-danger btn-retry" data-original-query="${escapeHtml(config.originalQuery)}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-reload" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1.002 7.935 1.007 9.425 4.747"></path>
                                <path d="M20 4v5h-5"></path>
                            </svg>
                            Coba Lagi
                        </button>
                    </div>
                `;
            }

            const messageHtml = `
                <div class="chat-message ai-message mb-3" data-message-id="${messageId}">
                    <div class="d-flex align-items-start">
                        <div class="chat-avatar me-2">
                            <img src="./files/images/logo.png" alt="AI" class="avatar" width="35" height="35" onerror="this.style.display='none'">
                        </div>
                        <div class="message-content">
                            <div class="message-bubble p-3 ${messageClass}">
                                <div class="message-text">${formattedHtmlMessage}</div>
                                ${actionsHtml}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            chatHistory.insertAdjacentHTML('beforeend', messageHtml);

            scrollChatToBottom();

            if (config.saveToHistory && !config.isError) {
                addToChatHistory({
                    id: messageId,
                    type: 'assistant',
                    message: message,
                    citations: [],
                    timestamp: config.timestamp,
                    isError: config.isError,
                    sessionId: config.sessionId
                });
            }
        }

        function addTypingIndicator() {
            const typingHtml = `
                <div class="chat-message ai-message mb-3" id="typingIndicator">
                    <div class="d-flex align-items-start">
                        <div class="chat-avatar me-2">
                            <img src="./files/images/logo.png" alt="AI" class="avatar" width="35" height="35" onerror="this.style.display='none'">
                        </div>
                        <div class="message-content">
                            <div class="p-2 ps-0">
                                <div class="typing-indicator">
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

        // Chat History Management
        function addToChatHistory(messageData) {
            chatHistoryData.push(messageData);
            updateChatHistoryDisplay();

            // Save to database dengan struktur yang sesuai model
            if (messageData.sessionId && !messageData.isError) {
                saveMessageToDatabase(messageData).catch(error => {
                    console.warn('Message not saved to database:', error);
                });
            }
        }

        async function deleteChatSession(sessionId) {
            try {
                const deleteResult = await deleteChatSessionFromDatabase(sessionId);
                if (deleteResult.success) {
                    showToast('Percakapan berhasil dihapus', 'success');
                } else {
                    showToast('Gagal menghapus percakapan: ' + (deleteResult.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Unexpected error during chat session deletion:', error);
                showToast('Terjadi kesalahan tidak terduga saat menghapus', 'error');
            } finally {
                const wasCurrentSession = currentSessionId === sessionId;
                await loadChatHistoryFromDatabase();
                const sessionStillExists = chatHistoryData.some(msg => msg.sessionId === sessionId);
                if (wasCurrentSession && !sessionStillExists) {
                    currentSessionId = null;
                    chatHistory.innerHTML = `<div class="text-center py-4" id="loadingWelcome"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>`;
                    await getWelcomeMessage();
                }
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

            const sortedSessions = Array.from(sessions.entries())
                .sort((a, b) => new Date(b[1].timestamp) - new Date(a[1].timestamp));

            let historyHtml = '';

            sortedSessions.forEach(([sessionId, data]) => {
                const messagePreview = data.message.length > 50 ?
                    data.message.substring(0, 50) + '...' :
                    data.message;

                historyHtml += `
                    <div class="chat-history-item ${sessionId === currentSessionId ? 'active' : ''}" data-session-id="${sessionId}">
                        <div class="chat-history-content">
                            <div class="fw-bold">${escapeHtml(messagePreview)}</div>
                        </div>
                        <div class="chat-history-actions">
                            <button type="button" class="delete-chat-btn btn btn-ghost-danger border-0 p-2" data-session-id="${sessionId}" title="Hapus percakapan">
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
                    if (e.target.closest('.delete-chat-btn')) {
                        return;
                    }
                    const sessionId = this.dataset.sessionId;
                    loadChatSession(sessionId);
                });
            });

            // Modified delete button handlers - use modal instead of confirm
            chatHistoryList.querySelectorAll('.delete-chat-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const sessionId = this.dataset.sessionId;
                    const clickedButton = this;
                    clickedButton.disabled = true;

                    showConfirmationModal({
                        title: 'Hapus Percakapan',
                        message: 'Apakah Anda yakin ingin menghapus percakapan ini? Tindakan ini tidak dapat dibatalkan.',
                        type: 'danger',
                        confirmText: 'Hapus',
                        cancelText: 'Batal',
                        onConfirm: () => {
                            deleteChatSession(sessionId)
                                .catch(() => {
                                    if (document.body.contains(clickedButton)) {
                                        clickedButton.disabled = false;
                                    }
                                });
                        },
                        onCancel: () => {
                            console.log('Delete cancelled');
                            if (document.body.contains(clickedButton)) {
                                clickedButton.disabled = false;
                            }
                        }
                    });
                });
            });
        }

        function loadChatSession(sessionId) {
            currentSessionId = sessionId;
            chatHistory.innerHTML = '';
            const sessionMessages = chatHistoryData.filter(msg => msg.sessionId === sessionId);
            sessionMessages.forEach(msg => {
                if (msg.type === 'user') {
                    const messageId = generateId();
                    const safeMessage = escapeHtml(msg.message).replace(/\n/g, '<br>');

                    const messageHtml = `
                        <div class="chat-message user-message mb-3" data-message-id="${messageId}">
                            <div class="d-flex justify-content-end">
                                <div class="message-content">
                                    <div class="message-bubble p-3 bg-primary text-white">
                                        <div class="message-text">${safeMessage}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    chatHistory.insertAdjacentHTML('beforeend', messageHtml);
                } else if (msg.type === 'assistant') {
                    addAIMessage(msg.message, {
                        citations: [],
                        isError: msg.isError || false,
                        sessionId: msg.sessionId,
                        timestamp: msg.timestamp,
                        saveToHistory: false
                    });
                }
            });
            scrollChatToBottom();
            updateChatHistoryDisplay();
        }

        // Save chat session to database
        async function saveChatSessionToDatabase(sessionData) {
            try {
                const dbSessionData = {
                    session_id: sessionData.session_id,
                    title: sessionData.title || 'Chat Session',
                };

                const response = await makeApiRequest('/chat/save-session', {
                    method: 'POST',
                    body: JSON.stringify(dbSessionData)
                });

                if (!response.success) {
                    throw new Error(response.message || 'Failed to save chat session');
                }

                return response;
            } catch (error) {
                console.error('Failed to save chat session:', error);
                showToast('Gagal menyimpan percakapan: ' + error.message, 'error');
                throw error;
            }
        }

        // Load chat sessions from database
        async function loadChatSessionsFromDatabase() {
            try {
                const response = await makeApiRequest('/chat/sessions', {
                    method: 'GET'
                });

                if (!response.success) {
                    throw new Error(response.message || 'Failed to load chat sessions');
                }

                return response.sessions || [];
            } catch (error) {
                console.error('Failed to load chat sessions:', error);
                showToast('Gagal memuat riwayat percakapan: ' + error.message, 'warning');
                return [];
            }
        }

        // Delete chat session from database
        async function deleteChatSessionFromDatabase(sessionId) {
            try {
                const response = await makeApiRequest(`/chat/session/${sessionId}`, {
                    method: 'DELETE'
                });

                if (!response.success) {
                    return {
                        success: false,
                        error: response.message || 'Failed to delete chat session'
                    };
                }

                return {
                    success: true,
                    data: response
                };
            } catch (error) {
                // Return error info instead of throwing
                return {
                    success: false,
                    error: error.message || 'Network error occurred'
                };
            }
        }

        // Save individual message to database
        async function saveMessageToDatabase(messageData) {
            try {
                const dbData = {
                    session_id: messageData.sessionId,
                    role: messageData.type === 'user' ? 'user' : 'assistant',
                    content: messageData.message,
                    reference: null
                };

                const response = await makeApiRequest('/chat/save-message', {
                    method: 'POST',
                    body: JSON.stringify(dbData)
                });

                if (!response.success) {
                    throw new Error(response.message || 'Failed to save message');
                }

                return response;
            } catch (error) {
                console.error('Failed to save message:', error);
                throw error;
            }
        }

        async function loadChatHistoryFromDatabase() {
            try {
                const sessions = await loadChatSessionsFromDatabase();

                // Convert database format to local format
                chatHistoryData = [];
                sessions.forEach(session => {
                    if (session.messages && Array.isArray(session.messages)) {
                        session.messages.forEach(msg => {
                            chatHistoryData.push({
                                id: msg.id || generateId(),
                                type: msg.role,
                                message: msg.content,
                                citations: [],
                                timestamp: msg.timestamp || msg.message_timestamp,
                                isError: false,
                                sessionId: session.session_id
                            });
                        });
                    }
                });

                updateChatHistoryDisplay();
            } catch (error) {
                console.warn('Failed to load chat history from database:', error);
                chatHistoryData = [];
                updateChatHistoryDisplay();
            }
        }

        // API Functions
        async function getWelcomeMessage() {
            try {
                // Don't make API call if already has session ID and chat history
                if (currentSessionId && chatHistory.children.length > 1) {
                    messageInput.disabled = false;
                    sendButton.disabled = false;
                    messageInput.focus();
                    return;
                }

                const response = await makeApiRequest('/welcome', {
                    method: 'POST'
                });

                if (response.success) {
                    // Set session ID if not exists
                    if (!currentSessionId) {
                        currentSessionId = response.session_id;
                    }

                    // Remove loading indicator
                    const loadingWelcome = document.getElementById('loadingWelcome');
                    if (loadingWelcome) {
                        loadingWelcome.remove();
                    }

                    if (response.message) {
                        // Display welcome message without saving to history
                        addAIMessage(response.message, {
                            saveToHistory: false
                        });
                    }

                    // Enable input
                    messageInput.disabled = false;
                    sendButton.disabled = false;
                    messageInput.focus();
                } else {
                    throw new Error(response.message || 'Failed to get welcome message');
                }
            } catch (error) {
                const loadingWelcome = document.getElementById('loadingWelcome');
                if (loadingWelcome) loadingWelcome.remove();

                const friendlyError = getFriendlyErrorMessage(error);
                addAIMessage(friendlyError, {
                    isError: true,
                    saveToHistory: false
                });

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

                // Make new session if not exists
                if (!currentSessionId) {
                    currentSessionId = 'session_' + generateId();

                    // Save session ke database
                    try {
                        await saveChatSessionToDatabase({
                            session_id: currentSessionId,
                            title: message.substring(0, 50) + (message.length > 50 ? '...' : ''),
                        });
                    } catch (error) {
                        console.warn('Failed to save session to database:', error);
                    }
                }

                // Add user message dan simpan ke history
                addUserMessage(message);

                // Save user message to chat history and database
                const userMessageData = {
                    id: generateId(),
                    type: 'user',
                    message: message,
                    timestamp: new Date(),
                    sessionId: currentSessionId
                };

                addToChatHistory(userMessageData);

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

                    // Add AI response with saveToHistory option
                    addAIMessage(response.message, {
                        citations: [],
                        sessionId: currentSessionId,
                        saveToHistory: true
                    });
                } else {
                    throw new Error(response.message || 'Failed to send message');
                }

            } catch (error) {
                removeTypingIndicator();

                const friendlyError = getFriendlyErrorMessage(error);
                addAIMessage(friendlyError, {
                    isError: true,
                    retryable: true,
                    originalQuery: message,
                    saveToHistory: false
                });

            } finally {
                isSubmitting = false;
                sendButton.disabled = false;
                sendButton.querySelector('.button-text').textContent = originalButtonText;
                messageInput.focus();
            }
        }

        async function checkHealth() {
            try {
                const response = await makeApiRequest('/health');

                if (response.success) {
                    console.info('Layanan berjalan normal');
                    updateConnectionStatus('online');
                } else {
                    throw new Error(response.message || 'Health check failed');
                }
            } catch (error) {
                console.error('Layanan tidak dapat diakses: ' + error.message, 'error');
                updateConnectionStatus('offline');
            }
        }

        function startNewChat() {
            showConfirmationModal({
                title: 'Percakapan Baru',
                message: 'Apakah Anda yakin ingin memulai percakapan baru?',
                type: 'info',
                confirmText: 'Ya, Mulai Baru',
                cancelText: 'Batal',
                onConfirm: () => {
                    // Clear current chat
                    chatHistory.innerHTML = '';

                    // Reset session
                    currentSessionId = null;

                    // Update chat history display untuk menghilangkan highlight
                    updateChatHistoryDisplay();

                    // Show loading dan get welcome message
                    chatHistory.innerHTML = `
                        <div class="text-center py-4" id="loadingWelcome">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;

                    messageInput.disabled = true;
                    sendButton.disabled = true;

                    setTimeout(() => {
                        getWelcomeMessage();
                    }, 300);

                    showToast('Percakapan baru dimulai', 'info');
                },
                onCancel: () => {
                    console.log('New chat cancelled');
                }
            });
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

        chatHistory.addEventListener('click', function(e) {
            const retryButton = e.target.closest('.btn-retry');
            if (retryButton) {
                const originalQuery = retryButton.dataset.originalQuery;
                if (originalQuery && !isSubmitting) {
                    retryButton.closest('.chat-message.ai-message').remove();
                    sendMessage(originalQuery);
                }
            }
        });

        // Initialize
        (async () => {
            try {
                await loadChatHistoryFromDatabase();

                chatHistory.innerHTML = `
                    <div class="text-center py-4" id="loadingWelcome">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                messageInput.disabled = true;
                sendButton.disabled = true;

                await getWelcomeMessage();

            } catch (error) {
                console.error('Initialization error:', error);

                // Fallback jika ada error
                messageInput.disabled = false;
                sendButton.disabled = false;

                chatHistory.innerHTML = `
                    <div class="text-center py-4">
                        <div class="alert alert-warning">
                            <h5>Peringatan</h5>
                            <p>Gagal memuat riwayat percakapan. Anda masih bisa memulai percakapan baru.</p>
                        </div>
                    </div>
                `;
            }
        })();

        // Periodic health check
        setInterval(checkHealth, 30 * 60 * 1000);

        // console.log('RAG Chat initialized successfully');
    });
</script>
