<div class="page-wrapper">
    <div class="container-fluid">
        <div class="page-header d-print-none" style="margin-top: 10px;">
            <div class="row align-items-center">
                <div class="col mb-2">
                    <div class="page-pretitle">RAG</div>
                    <h2 class="page-title">SAPA Anggaran</h2>
                </div>
                <!-- Mobile Toggle -->
                <div class="col-auto ms-auto d-md-none">
                    <button class="btn btn-icon" type="button" id="mobileHistoryBtn" title="Tampilkan Riwayat">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-history m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                            <path d="M9 4l0 16"></path>
                            <path d="M15 10l-2 2l2 2"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Layout -->
        <div class="chat-layout collapsed" id="chatLayout">
            <div class="offcanvas-backdrop"></div>

            <!-- Main Content -->
            <div class="chat-main-content">
                <div class="card" style="height: calc(100vh - 200px);">
                    <div class="card-body d-flex flex-column p-0 h-100">
                        <div class="chat-history flex-grow-1 overflow-auto p-3" id="chatHistory">
                            <div class="text-center py-4" id="loadingWelcome">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                            </div>
                        </div>
                        <!-- Chat Input -->
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

            <!-- Sidebar -->
            <div class="sidebar" id="chatSidebar">
                <div class="sidebar-content-wrapper">
                    <div class="sidebar-header">
                        <!-- Toggle -->
                        <button class="btn btn-icon ms-auto d-none d-md-flex" id="collapseBtn" title="Buka/Tutup Riwayat">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-2 m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                <path d="M9 4l0 16"></path>
                                <path d="M15 10l-2 2l2 2"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- New Chat -->
                    <div class="sidebar-action">
                        <button type="button" class="btn btn-ghost-orange btn-icon-text w-100" id="newChatBtn" title="Chat Baru">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit m-0">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                            <span class="btn-text">Chat Baru</span>
                        </button>
                    </div>
                    <!-- Chat History -->
                    <div class="sidebar-dynamic-content mt-3 mb-2">
                        <h3 class="card-title">Riwayat</h3>
                        <div class="sidebar-search">
                            <input type="text" id="historySearchInput" class="form-control" placeholder="Cari riwayat...">
                        </div>
                    </div>
                    <div class="chat-history-list flex-grow-1 overflow-auto" id="chatHistoryList">
                        <div class="text-muted text-center py-2">Belum ada riwayat percakapan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<style>
    /* === BASE LAYOUT (DESKTOP) === */
    .chat-layout {
        display: flex;
        gap: 1rem;
        position: relative;
    }

    .chat-main-content {
        flex-grow: 1;
        min-width: 0;
        transition: width 0.3s ease-in-out;
    }

    .sidebar {
        width: 25vw;
        flex-shrink: 0;
        transition: width 0.3s ease-in-out;
        background-color: var(--tblr-card-bg, #ffffff);
        border-radius: var(--tblr-card-border-radius, 4px);
        border: 1px solid var(--tblr-card-border-color, #e9ecef);
        display: flex;
        flex-direction: column;
    }

    .sidebar-content-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        padding: 0.5rem;
    }

    .sidebar-header {
        display: flex;
        flex-shrink: 0;
        margin-bottom: 0.5rem;
        min-height: 48px;
    }

    .sidebar-header .btn {
        background: transparent;
        border: none;
        padding: 1rem;
    }

    .sidebar-action {
        flex-shrink: 0;
        display: flex;
        justify-content: flex-start;
    }

    .btn-icon-text {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        overflow: hidden;
        justify-content: flex-start;
        transition: all 0.3s ease;
    }

    .btn-icon-text .btn-text {
        transition: opacity 0.2s 0.1s, max-width 0.3s 0.05s;
        opacity: 1;
        max-width: 100px;
        white-space: nowrap;
        font-weight: 600;
    }

    .sidebar-dynamic-content {
        transition: all 0.3s ease-out;
        opacity: 1;
        max-height: 500px;
        overflow: visible;
        padding: 0 1rem;
    }

    #collapseBtn svg {
        transition: transform 0.3s ease;
    }

    .offcanvas-backdrop {
        display: none;
    }

    /* --- DESKTOP COLLAPSED STATE --- */
    .chat-layout.collapsed .sidebar {
        width: 72px;
    }

    .chat-layout.collapsed .sidebar-action {
        justify-content: center;
    }

    .chat-layout.collapsed .sidebar-action .btn {
        width: 40px !important;
        height: 40px !important;
        padding: 0 !important;
        flex-shrink: 0;
    }

    .chat-layout.collapsed .btn-icon-text {
        gap: 0;
        justify-content: center;
    }

    .chat-layout.collapsed .btn-icon-text .btn-text,
    .chat-layout.collapsed .sidebar-dynamic-content,
    .chat-layout.collapsed .chat-history-list {
        opacity: 0;
        visibility: hidden;
        height: 0;
        overflow: hidden;
        padding: 0;
        margin: 0;
        pointer-events: none;
        max-width: 0;
    }

    .chat-layout.collapsed #collapseBtn svg {
        transform: rotate(180deg);
    }

    /* === MOBILE OFFCANVAS STYLES === */
    @media (max-width: 768px) {
        .chat-layout {
            display: block;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 280px !important;
            z-index: 1045;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            border-radius: 0;
        }

        .chat-layout.offcanvas-show .sidebar {
            transform: translateX(0);
        }

        /* Backdrop */
        .offcanvas-backdrop {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out;
        }

        /* State when backdrop terlihat */
        .chat-layout.offcanvas-show .offcanvas-backdrop {
            opacity: 1;
            visibility: visible;
        }

        .chat-layout.collapsed .sidebar {
            width: 280px !important;
        }

        .chat-layout.offcanvas-show .sidebar .sidebar-dynamic-content,
        .chat-layout.offcanvas-show .sidebar .chat-history-list,
        .chat-layout.offcanvas-show .sidebar .btn-text {
            opacity: 1 !important;
            visibility: visible !important;
            height: auto !important;
            max-width: none !important;
            overflow: visible !important;
            pointer-events: auto !important;
            margin: initial !important;
            padding: initial !important;
        }

        .chat-layout.offcanvas-show .sidebar .sidebar-action {
            justify-content: flex-start !important;
        }

        /* Mengembalikan ukuran penuh Tombol "Chat Baru" */
        .chat-layout.offcanvas-show .sidebar .sidebar-action .btn {
            width: 100% !important;
            height: auto !important;
            padding: 0.5rem 1rem !important;
        }

        /* Mengembalikan gap dan perataan ikon + teks di dalam tombol */
        .chat-layout.offcanvas-show .sidebar .btn-icon-text {
            justify-content: flex-start !important;
            gap: 0.75rem !important;
        }

        /* Mengembalikan style spesifik untuk area riwayat */
        .chat-layout.offcanvas-show .sidebar .sidebar-dynamic-content {
            padding: 0 1rem !important;
            margin-top: 0.75rem !important;
            margin-bottom: 0.5rem !important;
        }

        /* Mengembalikan kemampuan scroll untuk daftar riwayat */
        .chat-layout.offcanvas-show .sidebar .chat-history-list {
            overflow: auto !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }

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


    .ai-message .message-bubble.error-bubble {
        background-color: #f8d7da !important;
        border: 1px solid #f5c6cb;
        color: #721c24;
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
        padding: .5rem 1rem .5rem 1rem;
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

    /*
    ================================================
    DARK MODE STYLES
    ================================================
    */

    /* Base */
    body.theme-dark .card {
        background-color: var(--tblr-dark-card-bg, #1a202c);
        border-color: var(--tblr-dark-border-color, #2d3748);
    }

    body.theme-dark .text-muted {
        color: #94a3b8 !important;
    }

    body.theme-dark .form-control {
        background-color: #252e3a;
        border-color: #475569;
        color: #f1f5f9;
    }

    body.theme-dark .form-control::placeholder {
        color: #94a3b8;
    }

    /* Main Chat Area */
    body.theme-dark .chat-history {
        background-color: #1e293b;
    }

    body.theme-dark .ai-message .message-bubble {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #f1f5f9;
    }

    body.theme-dark .chat-input {
        border-top-color: #334155 !important;
    }

    /* Sidebar */
    body.theme-dark .sidebar {
        background-color: #161c24;
        border-color: #252e3a;
    }

    body.theme-dark .sidebar-header .btn {
        color: #cbd5e1;
    }

    body.theme-dark .sidebar-header .btn:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    body.theme-dark .btn-ghost-orange {
        color: #ff922b;
    }

    body.theme-dark .btn-ghost-orange:hover {
        background-color: rgba(255, 146, 43, 0.1);
        color: #ffa94d;
    }

    body.theme-dark .sidebar-dynamic-content .card-title {
        color: #e2e8f0;
    }

    /* Chat History List */
    body.theme-dark .chat-history-item {
        color: #cbd5e1;
    }

    body.theme-dark .chat-history-item:hover {
        background-color: #334155;
    }

    body.theme-dark .chat-history-item.active {
        background-color: #206bc4;
        color: white !important;
    }

    body.theme-dark .chat-history-item.active .delete-chat-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
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

<?php $this->load->view('telaah/script'); ?>
