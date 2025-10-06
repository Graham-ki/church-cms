<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communications - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Communications specific styles */
        .compose-section {
            margin-bottom: 30px;
        }
        
        .message-tabs {
            display: flex;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .message-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .message-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .message-tab:hover:not(.active) {
            border-bottom-color: rgba(74, 111, 165, 0.3);
        }
        
        .message-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .message-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .message-item:hover {
            background: rgba(74, 111, 165, 0.05);
        }
        
        .message-item.unread {
            background: rgba(74, 111, 165, 0.08);
            font-weight: 500;
        }
        
        .message-checkbox {
            margin-right: 15px;
        }
        
        .message-sender {
            width: 200px;
            font-weight: 500;
        }
        
        .message-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .message-subject {
            margin-bottom: 5px;
        }
        
        .message-preview {
            color: #666;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .message-time {
            width: 100px;
            text-align: right;
            font-size: 0.9rem;
            color: #666;
        }
        
        .message-actions {
            display: flex;
            gap: 10px;
            margin-left: 15px;
        }
        
        .compose-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        .recipient-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        
        .recipient-tag {
            background: var(--accent-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
        }
        
        .recipient-tag button {
            background: none;
            border: none;
            color: white;
            margin-left: 5px;
            cursor: pointer;
        }
        
        .message-editor {
            min-height: 200px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.3);
        }
        
        .btn-icon {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .btn-icon:hover {
            background: rgba(74, 111, 165, 0.1);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .message-count {
            background: var(--primary-color);
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ccc;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            color: var(--primary-color);
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #777;
        }
        
        .close-btn:hover {
            color: #333;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .message-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .message-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .message-sender-full {
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .message-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .message-subject-full {
            font-size: 1.3rem;
            margin: 10px 0;
            color: var(--dark-color);
        }
        
        .message-recipients {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .message-body {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .message-attachments {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .attachment-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }
        
        .attachment-item-full {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        
        .attachment-icon {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .attachment-info {
            flex: 1;
        }
        
        .attachment-name {
            font-weight: 500;
        }
        
        .attachment-size {
            font-size: 0.8rem;
            color: #666;
        }
        
        .attachment-download {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        
        .message-actions-full {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
        }
        
        .form-control {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #e67e22;
        }
        
        .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            background-color: #cbd5e0;
        }
        
        #loading-indicator {
            text-align: center;
            padding: 20px;
            display: none;
        }
        
        .message-actions-top {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="../public/images/logo.png" alt="Church Logo">
            <div class="logo-text">
                <h1>St. Stephen C.O.U</h1>
                <p>Church Management System</p>
            </div>
        </div>
        
        <div class="header-search">
            <input type="text" placeholder="Search communications...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="user-actions">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            
            <div class="user-profile">
                <div class="user-avatar" style="background-image: url('../public/images/user7.png');"></div>
                <span class="user-name">Admin User</span>
                <div class="user-dropdown">
                    <a href="#"><i class="fas fa-user"></i> My Profile</a>
                    <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    <a href="#" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="index" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="attendance" class="nav-link">
                        <i class="fas fa-user-check"></i>
                        <span class="nav-text">Attendance</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="communications" class="nav-link active">
                        <i class="fas fa-comments"></i>
                        <span class="nav-text">Communications</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="sacraments" class="nav-link">
                        <i class="fas fa-bible"></i>
                        <span class="nav-text">Sacraments</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="events" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Events</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="contributions" class="nav-link">
                        <i class="fas fa-donate"></i>
                        <span class="nav-text">Contributions</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="members" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Members</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reports" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="settings" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Page Content -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Communications</h1>
                <div class="actions">
                    <button class="btn btn-accent" id="compose-btn"><i class="fas fa-plus"></i> New Message</button>
                </div>
            </div>
            
            <!-- Compose Section (Initially Hidden) -->
            <div class="glass-card compose-section" id="compose-section" style="display: none;">
                <div class="card-header">
                    <h2 class="card-title">Compose Message</h2>
                    <button class="btn btn-sm" id="cancel-compose">Cancel</button>
                </div>
                
                <form class="compose-form" id="compose-form">
                    <div class="form-group full-width">
                        <label>Recipients</label>
                        <select class="form-control" id="recipients-select" name="audience">
                            <option value="All Members">All Members</option>
                            <option value="Men's Fellowship">Men's Fellowship</option>
                            <option value="Women's Ministry">Women's Ministry</option>
                            <option value="Youth Group">Youth Group</option>
                            <option value="Church Leaders">Church Leaders</option>
                        </select>
                        <div class="recipient-tags" id="recipient-tags"></div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Subject</label>
                        <input type="text" class="form-control" placeholder="Enter subject" id="message-subject" name="title">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Message</label>
                        <div class="message-editor" contenteditable="true" id="message-editor">
                            Type your message here...
                        </div>
                        <input type="hidden" id="message-content" name="message">
                    </div>
                    
                    <div class="form-group full-width" style="text-align: right;">
                        <button type="submit" class="btn btn-accent"><i class="fas fa-paper-plane"></i> Send Message</button>
                    </div>
                </form>
            </div>
            
            <!-- Messages Section -->
            <div class="glass-card">
                <div class="message-tabs">
                    <div class="message-tab active" data-tab="inbox">Inbox <span class="message-count" id="inbox-count">0</span></div>
                    <div class="message-tab" data-tab="sent">Sent <span class="message-count" id="sent-count">0</span></div>
                    <div class="message-tab" data-tab="drafts">Drafts <span class="message-count" id="drafts-count">0</span></div>
                    <div class="message-tab" data-tab="trash">Trash <span class="message-count" id="trash-count">0</span></div>
                </div>
                
                <div class="message-actions-top">
                    <button class="btn btn-sm" id="mark-read-btn"><i class="fas fa-inbox"></i> Mark as Read</button>
                    <button class="btn btn-sm" id="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                    <button class="btn btn-sm" id="label-btn"><i class="fas fa-tag"></i> Label</button>
                </div>
                
                <!-- Loading Indicator -->
                <div id="loading-indicator" style="text-align: center; padding: 20px; display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Loading messages...
                </div>
                
                <!-- Inbox Tab Content -->
                <div class="tab-content active" id="inbox-tab">
                    <div class="message-list" id="inbox-list">
                        <!-- Messages will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Sent Tab Content -->
                <div class="tab-content" id="sent-tab">
                    <div class="message-list" id="sent-list">
                        <!-- Messages will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Drafts Tab Content -->
                <div class="tab-content" id="drafts-tab">
                    <div class="message-list" id="drafts-list">
                        <!-- Messages will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Trash Tab Content -->
                <div class="tab-content" id="trash-tab">
                    <div class="message-list" id="trash-list">
                        <!-- Messages will be populated by JavaScript -->
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div id="message-count-display">Showing 0 of 0 messages</div>
                    <div>
                        <button class="btn btn-sm" id="prev-btn" style="margin-right: 5px;">Previous</button>
                        <button class="btn btn-sm btn-accent" id="next-btn">Next</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="#">About</a>
            <a href="#">Help</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Contact</a>
        </div>
        <div class="copyright">
            &copy; 2025 St. Stephen C.O.U Church Management System. All rights reserved.
        </div>
    </footer>
    
    <!-- Message View Modal -->
    <div id="message-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Message</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="message-header">
                    <div class="message-meta">
                        <div class="message-sender-full" id="modal-sender"></div>
                        <div class="message-date" id="modal-date"></div>
                    </div>
                    <div class="message-subject-full" id="modal-subject"></div>
                    <div class="message-recipients" id="modal-recipients"></div>
                </div>
                <div class="message-body" id="modal-body">
                    <!-- Message content will be populated by JavaScript -->
                </div>
                <div class="message-attachments" id="modal-attachments" style="display: none;">
                    <h3>Attachments</h3>
                    <div class="attachment-list" id="modal-attachment-list">
                        <!-- Attachments will be populated by JavaScript -->
                    </div>
                </div>
                <div class="message-actions-full">
                    <button class="btn btn-secondary" id="modal-reply-btn"><i class="fas fa-reply"></i> Reply</button>
                    <button class="btn btn-secondary" id="modal-forward-btn"><i class="fas fa-share"></i> Forward</button>
                    <button class="btn" id="modal-print-btn"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // DOM Elements
        const composeBtn = document.getElementById('compose-btn');
        const cancelComposeBtn = document.getElementById('cancel-compose');
        const composeSection = document.getElementById('compose-section');
        const composeForm = document.getElementById('compose-form');
        const messageTabs = document.querySelectorAll('.message-tab');
        const tabContents = document.querySelectorAll('.tab-content');
        const inboxList = document.getElementById('inbox-list');
        const sentList = document.getElementById('sent-list');
        const draftsList = document.getElementById('drafts-list');
        const trashList = document.getElementById('trash-list');
        const messageCountDisplay = document.getElementById('message-count-display');
        const inboxCount = document.getElementById('inbox-count');
        const sentCount = document.getElementById('sent-count');
        const draftsCount = document.getElementById('drafts-count');
        const trashCount = document.getElementById('trash-count');
        const markReadBtn = document.getElementById('mark-read-btn');
        const deleteBtn = document.getElementById('delete-btn');
        const labelBtn = document.getElementById('label-btn');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        // Modal elements
        const messageModal = document.getElementById('message-modal');
        const modalSender = document.getElementById('modal-sender');
        const modalDate = document.getElementById('modal-date');
        const modalSubject = document.getElementById('modal-subject');
        const modalRecipients = document.getElementById('modal-recipients');
        const modalBody = document.getElementById('modal-body');
        const modalAttachments = document.getElementById('modal-attachments');
        const modalAttachmentList = document.getElementById('modal-attachment-list');
        const modalCloseBtn = document.querySelector('#message-modal .close-btn');

        // Current state
        let currentTab = 'inbox';
        let selectedMessages = new Set();
        let messagesData = {
            inbox: [],
            sent: [],
            drafts: [],
            trash: []
        };

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Logout button functionality
            document.getElementById('logout-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to logout?')) {
                    window.location.href = 'login.html';
                }
            });

            // Load initial messages
            loadMessages('inbox');
            
            // Toggle compose section
            composeBtn.addEventListener('click', function() {
                composeSection.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            cancelComposeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                composeSection.style.display = 'none';
            });

            // Handle form submission
            composeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });

            // Handle recipient selection
            const recipientsSelect = document.getElementById('recipients-select');
            const recipientTags = document.getElementById('recipient-tags');
            
            recipientsSelect.addEventListener('change', function() {
                updateRecipientTags();
            });

            function updateRecipientTags() {
                recipientTags.innerHTML = '';
                const selectedOptions = Array.from(recipientsSelect.selectedOptions);
                
                selectedOptions.forEach(option => {
                    const tag = document.createElement('div');
                    tag.className = 'recipient-tag';
                    tag.innerHTML = `
                        ${option.text}
                        <button type="button"><i class="fas fa-times"></i></button>
                    `;
                    
                    tag.querySelector('button').addEventListener('click', function() {
                        option.selected = false;
                        updateRecipientTags();
                    });
                    
                    recipientTags.appendChild(tag);
                });
            }
            
            // Update the hidden message content field when the editor content changes
            const messageEditor = document.getElementById('message-editor');
            const messageContent = document.getElementById('message-content');
            
            messageEditor.addEventListener('input', function() {
                messageContent.value = messageEditor.innerHTML;
            });
            
            // Toggle message tabs
            messageTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });

            // Message actions
            markReadBtn.addEventListener('click', markSelectedAsRead);
            deleteBtn.addEventListener('click', deleteSelectedMessages);
            labelBtn.addEventListener('click', labelSelectedMessages);
            
            // Pagination
            prevBtn.addEventListener('click', goToPreviousPage);
            nextBtn.addEventListener('click', goToNextPage);
            
            // Modal functionality
            modalCloseBtn.addEventListener('click', function() {
                messageModal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            messageModal.addEventListener('click', function(e) {
                if (e.target === messageModal) {
                    messageModal.style.display = 'none';
                }
            });

            // Modal action buttons
            document.getElementById('modal-reply-btn').addEventListener('click', function() {
                alert('Reply functionality would be implemented here');
            });

            document.getElementById('modal-forward-btn').addEventListener('click', function() {
                alert('Forward functionality would be implemented here');
            });

            document.getElementById('modal-print-btn').addEventListener('click', function() {
                window.print();
            });
        });

        // Load messages from PHP backend
        function loadMessages(tabId) {
            showLoading(true);
            
            fetch(`communications_api.php?action=get_${tabId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messagesData[tabId] = data.messages;
                        renderMessages(tabId, data.messages);
                        updateMessageCounts();
                    } else {
                        console.error('Error loading messages:', data.error);
                        showError(`Failed to load ${tabId} messages`);
                    }
                    showLoading(false);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Failed to load messages');
                    showLoading(false);
                });
        }

        // Show/hide loading indicator
        function showLoading(show) {
            if (show) {
                loadingIndicator.style.display = 'block';
                document.getElementById(`${currentTab}-list`).innerHTML = '';
            } else {
                loadingIndicator.style.display = 'none';
            }
        }

        // Show error message
        function showError(message) {
            const container = document.getElementById(`${currentTab}-list`);
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error</h3>
                    <p>${message}</p>
                    <button class="btn btn-sm" onclick="loadMessages('${currentTab}')">Retry</button>
                </div>
            `;
        }

        // Switch between tabs
        function switchTab(tabId) {
            // Update active tab
            messageTabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('data-tab') === tabId) {
                    tab.classList.add('active');
                }
            });
            
            // Update active content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === `${tabId}-tab`) {
                    content.classList.add('active');
                }
            });
            
            // Update current tab
            currentTab = tabId;
            
            // Clear selected messages
            selectedMessages.clear();
            
            // Load messages for the selected tab
            loadMessages(tabId);
        }

        // Render messages for a specific tab
        function renderMessages(tabId, messages) {
            const container = document.getElementById(`${tabId}-list`);
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No messages</h3>
                        <p>There are no messages in your ${tabId}.</p>
                    </div>
                `;
                messageCountDisplay.textContent = `Showing 0 of 0 messages`;
                return;
            }
            
            let html = '';
            messages.forEach(message => {
                html += `
                    <div class="message-item ${message.read ? '' : 'unread'}" data-message-id="${message.id}">
                        <div class="message-checkbox">
                            <input type="checkbox" class="message-checkbox-input" data-message-id="${message.id}">
                        </div>
                        <div class="message-sender">${message.sender}</div>
                        <div class="message-content">
                            <div class="message-subject">${message.subject}</div>
                            <div class="message-preview">${message.preview}</div>
                        </div>
                        <div class="message-time">${message.time}</div>
                        <div class="message-actions">
                            <button class="btn-icon mark-read-single" title="${message.read ? 'Mark as unread' : 'Mark as read'}" data-message-id="${message.id}">
                                <i class="far ${message.read ? 'fa-envelope' : 'fa-envelope-open'}"></i>
                            </button>
                            <button class="btn-icon delete-single" title="Delete" data-message-id="${message.id}">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            
            // Add event listeners to the new elements
            container.querySelectorAll('.message-checkbox-input').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const messageId = this.getAttribute('data-message-id');
                    if (this.checked) {
                        selectedMessages.add(messageId);
                    } else {
                        selectedMessages.delete(messageId);
                    }
                    updateActionButtons();
                });
            });
            
            container.querySelectorAll('.mark-read-single').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const messageId = this.getAttribute('data-message-id');
                    toggleReadStatus(messageId);
                });
            });
            
            container.querySelectorAll('.delete-single').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const messageId = this.getAttribute('data-message-id');
                    moveToTrash(messageId);
                });
            });
            
            container.querySelectorAll('.message-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on checkbox or action buttons
                    if (e.target.tagName === 'INPUT' || e.target.closest('.message-actions')) {
                        return;
                    }
                    
                    const messageId = this.getAttribute('data-message-id');
                    const message = messagesData[currentTab].find(m => m.id == messageId);
                    
                    if (message) {
                        // Mark as read when opening if it's unread
                        if (!message.read) {
                            toggleReadStatus(messageId);
                        }
                        
                        // Show message in modal
                        showMessageModal(message);
                    }
                });
            });
            
            // Update message count display
            messageCountDisplay.textContent = `Showing 1-${messages.length} of ${messages.length} messages`;
            updateActionButtons();
        }

        // Update action buttons state
        function updateActionButtons() {
            const hasSelection = selectedMessages.size > 0;
            markReadBtn.disabled = !hasSelection;
            deleteBtn.disabled = !hasSelection;
            labelBtn.disabled = !hasSelection;
        }

        // Show message in modal
        function showMessageModal(message) {
            modalSender.textContent = message.sender;
            modalDate.textContent = message.time;
            modalSubject.textContent = message.subject;
            modalRecipients.textContent = `To: ${message.recipients}`;
            modalBody.innerHTML = message.fullBody;
            
            // Handle attachments
            if (message.attachments && message.attachmentList) {
                modalAttachments.style.display = 'block';
                modalAttachmentList.innerHTML = '';
                
                message.attachmentList.forEach(attachment => {
                    const attachmentItem = document.createElement('div');
                    attachmentItem.className = 'attachment-item-full';
                    attachmentItem.innerHTML = `
                        <div class="attachment-icon">
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div class="attachment-info">
                            <div class="attachment-name">${attachment.name}</div>
                            <div class="attachment-size">${attachment.size}</div>
                        </div>
                        <button class="attachment-download">Download</button>
                    `;
                    
                    modalAttachmentList.appendChild(attachmentItem);
                });
            } else {
                modalAttachments.style.display = 'none';
            }
            
            // Show modal
            messageModal.style.display = 'flex';
        }

        // Update message counts in tabs
        function updateMessageCounts() {
            inboxCount.textContent = messagesData.inbox.length;
            sentCount.textContent = messagesData.sent.length;
            draftsCount.textContent = messagesData.drafts.length;
            trashCount.textContent = messagesData.trash.length;
        }

        // Toggle read status of a message
        function toggleReadStatus(messageId) {
            const formData = new FormData();
            formData.append('message_id', messageId);

            fetch('communications_api.php?action=mark_read', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload current tab to reflect changes
                    loadMessages(currentTab);
                } else {
                    alert('Failed to mark message as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to mark message as read');
            });
        }

        // Move message to trash
        function moveToTrash(messageId) {
            if (!confirm('Are you sure you want to move this message to trash?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('message_id', messageId);
            
            fetch('communications_api.php?action=delete_message', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload both the current tab and trash tab
                    loadMessages(currentTab);
                    if (currentTab !== 'trash') {
                        loadMessages('trash');
                    }
                } else {
                    alert('Failed to delete message');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete message');
            });
        }

        // Mark selected messages as read
        function markSelectedAsRead() {
            if (selectedMessages.size === 0) {
                alert('Please select messages to mark as read');
                return;
            }
            
            const promises = Array.from(selectedMessages).map(messageId => {
                const formData = new FormData();
                formData.append('message_id', messageId);
                
                return fetch('communications_api.php?action=mark_read', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json());
            });
            
            Promise.all(promises)
                .then(results => {
                    const allSuccess = results.every(result => result.success);
                    if (allSuccess) {
                        // Reload current tab
                        loadMessages(currentTab);
                        selectedMessages.clear();
                    } else {
                        alert('Failed to mark some messages as read');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to mark messages as read');
                });
        }

        // Delete selected messages
        function deleteSelectedMessages() {
            if (selectedMessages.size === 0) {
                alert('Please select messages to delete');
                return;
            }
            
            if (!confirm(`Are you sure you want to move ${selectedMessages.size} message(s) to trash?`)) {
                return;
            }
            
            const promises = Array.from(selectedMessages).map(messageId => {
                const formData = new FormData();
                formData.append('message_id', messageId);
                
                return fetch('communications_api.php?action=delete_message', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json());
            });
            
            Promise.all(promises)
                .then(results => {
                    const allSuccess = results.every(result => result.success);
                    if (allSuccess) {
                        // Reload current tab and trash tab
                        loadMessages(currentTab);
                        if (currentTab !== 'trash') {
                            loadMessages('trash');
                        }
                        selectedMessages.clear();
                    } else {
                        alert('Failed to delete some messages');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete messages');
                });
        }

        // Label selected messages
        function labelSelectedMessages() {
            if (selectedMessages.size === 0) {
                alert('Please select messages to label');
                return;
            }
            
            const label = prompt('Enter label name:');
            if (label) {
                alert(`Label "${label}" would be applied to ${selectedMessages.size} message(s)`);
                // In a real implementation, you would send this to the server
            }
        }

        // Send a new message
        function sendMessage() {
            const title = document.getElementById('message-subject').value;
            const content = document.getElementById('message-content').value;
            const audience = document.getElementById('recipients-select').value;
            
            if (!title.trim()) {
                alert('Please enter a subject for your message');
                return;
            }
            
            if (content === 'Type your message here...' || content.trim() === '') {
                alert('Please enter a message');
                return;
            }
            
            const formData = new FormData(composeForm);
            
            fetch('communications_api.php?action=send_message', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Message sent successfully!');
                    
                    // Reset form and hide compose section
                    composeForm.reset();
                    document.getElementById('recipient-tags').innerHTML = '';
                    document.getElementById('message-editor').innerHTML = 'Type your message here...';
                    document.getElementById('message-content').value = '';
                    composeSection.style.display = 'none';
                    
                    // Reload sent messages
                    loadMessages('sent');
                } else {
                    alert('Failed to send message: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to send message');
            });
        }

        // Pagination functions
        function goToPreviousPage() {
            alert('Previous page - pagination would be implemented here');
        }

        function goToNextPage() {
            alert('Next page - pagination would be implemented here');
        }
    </script>
</body>
</html>