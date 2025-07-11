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
        
        .editor-toolbar {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        
        .editor-toolbar button {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .editor-toolbar button:hover {
            background: rgba(74, 111, 165, 0.2);
        }
        
        .message-editor {
            min-height: 200px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.3);
        }
        
        .attachment-preview {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .attachment-item {
            background: rgba(255, 255, 255, 0.3);
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .attachment-item button {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
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
                
                <form class="compose-form">
                    <div class="form-group full-width">
                        <label>Recipients</label>
                        <select class="form-control" multiple id="recipients-select">
                            <option>All Members</option>
                            <option>Men's Fellowship</option>
                            <option>Women's Ministry</option>
                            <option>Youth Group</option>
                            <option>Church Leaders</option>
                        </select>
                        <div class="recipient-tags" id="recipient-tags"></div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Subject</label>
                        <input type="text" class="form-control" placeholder="Enter subject">
                    </div>
                    
                    <div class="form-group full-width">
                        <div class="editor-toolbar">
                            <button type="button" title="Bold"><i class="fas fa-bold"></i></button>
                            <button type="button" title="Italic"><i class="fas fa-italic"></i></button>
                            <button type="button" title="Underline"><i class="fas fa-underline"></i></button>
                            <button type="button" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                            <button type="button" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                            <button type="button" title="Link"><i class="fas fa-link"></i></button>
                            <button type="button" title="Image"><i class="fas fa-image"></i></button>
                        </div>
                        <div class="message-editor" contenteditable="true">
                            <p>Type your message here...</p>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Attachments</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="file" id="file-upload" style="display: none;">
                            <button type="button" class="btn btn-sm" id="upload-btn"><i class="fas fa-paperclip"></i> Add Attachment</button>
                            <span id="file-name" style="align-self: center; font-size: 0.9rem; color: #666;"></span>
                        </div>
                        <div class="attachment-preview" id="attachment-preview"></div>
                    </div>
                    
                    <div class="form-group full-width" style="text-align: right;">
                        <button type="button" class="btn"><i class="fas fa-save"></i> Save Draft</button>
                        <button type="submit" class="btn btn-accent"><i class="fas fa-paper-plane"></i> Send Message</button>
                    </div>
                </form>
            </div>
            
            <!-- Messages Section -->
            <div class="glass-card">
                <div class="message-tabs">
                    <div class="message-tab active">Inbox (12)</div>
                    <div class="message-tab">Sent</div>
                    <div class="message-tab">Drafts</div>
                    <div class="message-tab">Trash</div>
                </div>
                
                <div class="message-actions" style="margin-bottom: 15px;">
                    <button class="btn btn-sm"><i class="fas fa-inbox"></i> Mark as Read</button>
                    <button class="btn btn-sm"><i class="fas fa-trash"></i> Delete</button>
                    <button class="btn btn-sm"><i class="fas fa-tag"></i> Label</button>
                </div>
                
                <div class="message-list">
                    <!-- Message 1 -->
                    <div class="message-item unread">
                        <div class="message-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="message-sender">Pastor James Okello</div>
                        <div class="message-content">
                            <div class="message-subject">Weekly Bible Study Reminder</div>
                            <div class="message-preview">This is a reminder about our weekly Bible study tomorrow evening at 5:30 PM in the main hall. Please bring your...</div>
                        </div>
                        <div class="message-time">Today, 10:15 AM</div>
                        <div class="message-actions">
                            <button class="btn-icon" title="Mark as read"><i class="far fa-envelope-open"></i></button>
                            <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                        </div>
                    </div>
                    
                    <!-- Message 2 -->
                    <div class="message-item unread">
                        <div class="message-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="message-sender">Women's Ministry</div>
                        <div class="message-content">
                            <div class="message-subject">Prayer Meeting Update</div>
                            <div class="message-preview">Dear sisters, we're changing the location of this Friday's prayer meeting to Sister Sarah's home due to the...</div>
                        </div>
                        <div class="message-time">Yesterday, 2:30 PM</div>
                        <div class="message-actions">
                            <button class="btn-icon" title="Mark as read"><i class="far fa-envelope-open"></i></button>
                            <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                        </div>
                    </div>
                    
                    <!-- Message 3 -->
                    <div class="message-item">
                        <div class="message-checkbox">
                            <input type="checkbox">
                        </div>
                        <div class="message-sender">Youth Leader David</div>
                        <div class="message-content">
                            <div class="message-subject">Youth Camp Registration</div>
                            <div class="message-preview">The registration for our annual youth camp is now open! Please find attached the registration form and packing list...</div>
                        </div>
                        <div class="message-time">Mar 15</div>
                        <div class="message-actions">
                            <button class="btn-icon" title="Mark as unread"><i class="far fa-envelope"></i></button>
                            <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                        </div>
                    </div>
                    
                    <!-- More messages would be listed here -->
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div>Showing 1-10 of 42 messages</div>
                    <div>
                        <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                        <button class="btn btn-sm btn-accent">Next</button>
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
    
    <script src="js/scripts.js"></script>
    <script>
        // Communications specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle compose section
            const composeBtn = document.getElementById('compose-btn');
            const cancelComposeBtn = document.getElementById('cancel-compose');
            const composeSection = document.getElementById('compose-section');
            
            composeBtn.addEventListener('click', function() {
                composeSection.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            cancelComposeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                composeSection.style.display = 'none';
            });
            
            // Handle recipient selection
            const recipientsSelect = document.getElementById('recipients-select');
            const recipientTags = document.getElementById('recipient-tags');
            
            recipientsSelect.addEventListener('change', function() {
                recipientTags.innerHTML = '';
                const selectedOptions = Array.from(this.selectedOptions);
                
                selectedOptions.forEach(option => {
                    const tag = document.createElement('div');
                    tag.className = 'recipient-tag';
                    tag.innerHTML = `
                        ${option.text}
                        <button type="button"><i class="fas fa-times"></i></button>
                    `;
                    
                    tag.querySelector('button').addEventListener('click', function() {
                        option.selected = false;
                        tag.remove();
                    });
                    
                    recipientTags.appendChild(tag);
                });
            });
            
            // Handle file upload
            const uploadBtn = document.getElementById('upload-btn');
            const fileUpload = document.getElementById('file-upload');
            const fileName = document.getElementById('file-name');
            const attachmentPreview = document.getElementById('attachment-preview');
            
            uploadBtn.addEventListener('click', function() {
                fileUpload.click();
            });
            
            fileUpload.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileName.textContent = this.files[0].name;
                    
                    const attachmentItem = document.createElement('div');
                    attachmentItem.className = 'attachment-item';
                    attachmentItem.innerHTML = `
                        <i class="fas fa-paperclip"></i>
                        ${this.files[0].name}
                        <button type="button"><i class="fas fa-times"></i></button>
                    `;
                    
                    attachmentItem.querySelector('button').addEventListener('click', function() {
                        attachmentItem.remove();
                        fileUpload.value = '';
                        fileName.textContent = '';
                    });
                    
                    attachmentPreview.appendChild(attachmentItem);
                }
            });
            
            // Toggle message tabs
            const messageTabs = document.querySelectorAll('.message-tab');
            messageTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    messageTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    // In a real app, you would load the appropriate messages here
                });
            });
            
            // Toggle message read status
            const messageItems = document.querySelectorAll('.message-item');
            messageItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't toggle if clicking on action buttons or checkbox
                    if (e.target.tagName === 'INPUT' || e.target.closest('.message-actions')) {
                        return;
                    }
                    
                    this.classList.toggle('unread');
                    
                    const readIcon = this.querySelector('.fa-envelope-open, .fa-envelope');
                    if (readIcon) {
                        if (this.classList.contains('unread')) {
                            readIcon.classList.replace('fa-envelope-open', 'fa-envelope');
                        } else {
                            readIcon.classList.replace('fa-envelope', 'fa-envelope-open');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>