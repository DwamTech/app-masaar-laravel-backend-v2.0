@extends('layouts.dashboard')

@section('title', 'إرسال إشعار - Send Notification')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-primary mb-1">إرسال إشعار</h2>
                    <p class="text-muted mb-0">Send Notification to Users</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="#">إدارة التطبيق</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إرسال إشعار</li>
                    </ol>
                </nav>
            </div>
        </div>
</div>

<!-- Chat Bubble Cards Section -->
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <!-- Arabic Card -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="chat-bubble-card arabic-card">
                <div class="card-body p-4">
                    <!-- Dropdown -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">النوع</label>
                        <select class="form-select" id="arabicUserType">
                            <option value="admin">معلن</option>
                            <option value="visitor">زائر</option>
                        </select>
                    </div>
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان</label>
                        <input type="text" class="form-control" id="arabicTitle" placeholder="أدخل العنوان">
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">الوصف</label>
                        <textarea class="form-control" id="arabicDescription" rows="1" placeholder="أدخل الوصف"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- English Card -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="chat-bubble-card english-card">
                <div class="card-body p-4">
                    <!-- Dropdown -->
                    <div class="mb-3">
                         <label class="form-label fw-bold">Type</label>
                        <select class="form-select" id="englishUserType">
                            <option value="admin">Publisher</option>
                            <option value="visitor">Visitor</option>
                        </select>
                    </div>
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="englishTitle" placeholder="Enter title">
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="englishDescription" rows="1" placeholder="Enter description"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Target Section -->
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="target-section-card">
                <div class="card-body p-4">
                    <h4 class="section-title mb-4">
                        <i class="fas fa-bullseye me-2"></i>
                        الهدف - Target
                    </h4>
                    
                    <!-- Target Buttons -->
                    <div class="target-buttons mb-4">
                        <button type="button" class="btn btn-target" id="selectAllBtn">
                            <i class="fas fa-users me-2"></i>
                            تحديد الكل - Select All
                        </button>
                        <button type="button" class="btn btn-target-outline" id="selectBtn">
                            <i class="fas fa-user-check me-2"></i>
                            تحديد - Select
                        </button>
                    </div>
                    
                    <!-- Selected Users Display -->
                    <div class="selected-users-display" id="selectedUsersDisplay" style="display: none;">
                        <div class="selected-users-header">
                            <h6 class="mb-2">
                                <i class="fas fa-check-circle me-2"></i>
                                المستخدمون المحددون - Selected Users
                            </h6>
                            <span class="selected-count" id="selectedCount">0 مستخدم محدد - 0 users selected</span>
                        </div>
                        <div class="selected-users-list" id="selectedUsersList">
                            <!-- Selected users will be displayed here -->
                        </div>
                    </div>
                    
                    <!-- Send Button -->
                    <div class="send-section mt-4">
                        <button type="button" class="btn btn-send" id="sendNotificationBtn">
                            <i class="fas fa-paper-plane me-2"></i>
                            إرسال - Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Selection Modal -->
<div class="modal fade" id="userSelectionModal" tabindex="-1" aria-labelledby="userSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modern-modal">
            <div class="modal-header gradient-header">
                <h5 class="modal-title" id="userSelectionModalLabel">
                    <i class="fas fa-users-cog me-2 animated-icon"></i>
                    البحث عن المستخدمين - Search Users
                </h5>
                <button type="button" class="btn-close modern-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modern-modal-body">
                <!-- Search Box -->
                <div class="search-box-modern mb-4">
                    <div class="search-container">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" class="form-control modern-search" id="userSearchInput" 
                               placeholder="ابحث عن المستخدمين بالاسم أو البريد الإلكتروني... - Search users by name or email...">
                        <div class="search-loading" id="searchLoading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Search Results -->
                <div class="search-results-modern" id="searchResults">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h6 class="empty-title">ابدأ البحث للعثور على المستخدمين</h6>
                        <p class="empty-subtitle">Start searching to find users</p>
                    </div>
                </div>
                
                <!-- Selected in Modal -->
                <div class="modal-selected-users-modern mt-4" id="modalSelectedUsers" style="display: none;">
                    <div class="selected-header">
                        <h6 class="selected-title">
                            <i class="fas fa-check-circle me-2"></i>
                            محدد في هذه الجلسة - Selected in this session
                        </h6>
                        <span class="selected-badge" id="modalSelectedCount">0</span>
                    </div>
                    <div class="modal-selected-list-modern" id="modalSelectedList">
                        <!-- Temporarily selected users will be displayed here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer modern-footer">
                <button type="button" class="btn btn-cancel-modern" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    إلغاء - Cancel
                </button>
                <button type="button" class="btn btn-done-modern" id="doneBtn">
                    <i class="fas fa-check me-2"></i>
                    تم - Done
                    <span class="btn-shine"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// إظهار/إخفاء قسم المستخدمين المحددين
document.getElementById('specificUsers')?.addEventListener('change', function() {
    document.getElementById('specificUsersSection').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('allUsers')?.addEventListener('change', function() {
    document.getElementById('specificUsersSection').style.display = 'none';
});

// إظهار/إخفاء قسم الجدولة
document.getElementById('scheduleNotification')?.addEventListener('change', function() {
    document.getElementById('scheduleSection').style.display = this.checked ? 'block' : 'none';
});

// Target Section Functionality
let selectedUsers = [];
let tempSelectedUsers = [];

// Sample users data (replace with actual API call)
const sampleUsers = [
    { id: 1, name: 'أحمد محمد - Ahmed Mohamed', email: 'ahmed@example.com', type: 'admin' },
    { id: 2, name: 'فاطمة علي - Fatima Ali', email: 'fatima@example.com', type: 'visitor' },
    { id: 3, name: 'محمد حسن - Mohamed Hassan', email: 'mohamed@example.com', type: 'admin' },
    { id: 4, name: 'سارة أحمد - Sara Ahmed', email: 'sara@example.com', type: 'visitor' },
    { id: 5, name: 'علي محمود - Ali Mahmoud', email: 'ali@example.com', type: 'admin' },
    { id: 6, name: 'نور الدين - Nour Aldin', email: 'nour@example.com', type: 'visitor' },
    { id: 7, name: 'ليلى حسام - Layla Hossam', email: 'layla@example.com', type: 'admin' },
    { id: 8, name: 'يوسف عبدالله - Youssef Abdullah', email: 'youssef@example.com', type: 'visitor' }
];

// Select All Button
document.getElementById('selectAllBtn').addEventListener('click', function() {
    selectedUsers = [...sampleUsers];
    updateSelectedUsersDisplay();
    this.classList.add('active');
    document.getElementById('selectBtn').classList.remove('active');
});

// Select Button (Open Modal)
document.getElementById('selectBtn').addEventListener('click', function() {
    tempSelectedUsers = [...selectedUsers];
    updateModalSelectedUsers();
    const modal = new bootstrap.Modal(document.getElementById('userSelectionModal'));
    modal.show();
    this.classList.add('active');
    document.getElementById('selectAllBtn').classList.remove('active');
});

// Search functionality with loading animation
document.getElementById('userSearchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const loadingElement = document.getElementById('searchLoading');
    
    if (searchTerm.length === 0) {
        document.getElementById('searchResults').innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h6 class="empty-title">ابدأ البحث للعثور على المستخدمين</h6>
                <p class="empty-subtitle">Start searching to find users</p>
            </div>
        `;
        return;
    }
    
    // Show loading
    loadingElement.style.display = 'block';
    
    // Simulate API delay
    setTimeout(() => {
        const filteredUsers = sampleUsers.filter(user => 
            user.name.toLowerCase().includes(searchTerm) || 
            user.email.toLowerCase().includes(searchTerm)
        );
        displaySearchResults(filteredUsers);
        loadingElement.style.display = 'none';
    }, 300);
});

// Display search results with modern design
function displaySearchResults(users) {
    const resultsContainer = document.getElementById('searchResults');
    
    if (users.length === 0) {
        resultsContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon no-results">
                    <i class="fas fa-user-slash"></i>
                </div>
                <h6 class="empty-title">لا توجد نتائج</h6>
                <p class="empty-subtitle">No results found</p>
            </div>
        `;
        return;
    }
    
    resultsContainer.innerHTML = `
        <div class="results-header">
            <span class="results-count">${users.length} نتيجة - ${users.length} results</span>
        </div>
        <div class="results-list">
            ${users.map(user => `
                <div class="user-result-card ${tempSelectedUsers.find(u => u.id === user.id) ? 'selected' : ''}" data-user-id="${user.id}">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name">${user.name}</div>
                        <div class="user-email">${user.email}</div>
                        <span class="user-type-badge ${user.type}">${user.type === 'admin' ? 'إدارة - Admin' : 'زائر - Visitor'}</span>
                    </div>
                    <div class="user-actions">
                        <button type="button" class="btn select-user-btn-modern ${tempSelectedUsers.find(u => u.id === user.id) ? 'selected' : ''}" data-user-id="${user.id}">
                            <i class="fas fa-${tempSelectedUsers.find(u => u.id === user.id) ? 'check' : 'plus'}"></i>
                        </button>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
    
    // Add click events to select buttons and cards
    document.querySelectorAll('.select-user-btn-modern').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const userId = parseInt(this.dataset.userId);
            toggleUserSelection(userId);
        });
    });
    
    // Add click events to entire cards
    document.querySelectorAll('.user-result-card').forEach(card => {
        card.addEventListener('click', function() {
            const userId = parseInt(this.dataset.userId);
            toggleUserSelection(userId);
        });
    });
}

// Toggle user selection
function toggleUserSelection(userId) {
    const userIndex = tempSelectedUsers.findIndex(u => u.id === userId);
    const user = sampleUsers.find(u => u.id === userId);
    
    if (userIndex > -1) {
        tempSelectedUsers.splice(userIndex, 1);
    } else {
        tempSelectedUsers.push(user);
    }
    
    updateModalSelectedUsers();
    // Refresh search results to update selection state
    const searchTerm = document.getElementById('userSearchInput').value.toLowerCase();
    const filteredUsers = sampleUsers.filter(user => 
        user.name.toLowerCase().includes(searchTerm) || 
        user.email.toLowerCase().includes(searchTerm)
    );
    displaySearchResults(filteredUsers);
}

// Update modal selected users with modern design
function updateModalSelectedUsers() {
    const modalSelectedContainer = document.getElementById('modalSelectedUsers');
    const modalSelectedList = document.getElementById('modalSelectedList');
    const modalSelectedCount = document.getElementById('modalSelectedCount');
    
    // Update counter
    modalSelectedCount.textContent = tempSelectedUsers.length;
    
    if (tempSelectedUsers.length === 0) {
        modalSelectedContainer.style.display = 'none';
        return;
    }
    
    modalSelectedContainer.style.display = 'block';
    modalSelectedList.innerHTML = tempSelectedUsers.map(user => `
        <div class="selected-user-tag-modern">
            <div class="tag-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="tag-info">
                <span class="tag-name">${user.name}</span>
                <span class="tag-type">${user.type === 'admin' ? 'إدارة' : 'زائر'}</span>
            </div>
            <button type="button" class="btn remove-user-btn-modern" data-user-id="${user.id}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
    
    // Add remove events
    document.querySelectorAll('.remove-user-btn-modern').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const userId = parseInt(this.dataset.userId);
            toggleUserSelection(userId);
        });
    });
}

// Done button in modal
document.getElementById('doneBtn').addEventListener('click', function() {
    selectedUsers = [...tempSelectedUsers];
    updateSelectedUsersDisplay();
    const modal = bootstrap.Modal.getInstance(document.getElementById('userSelectionModal'));
    modal.hide();
});

// Update selected users display
function updateSelectedUsersDisplay() {
    const displayContainer = document.getElementById('selectedUsersDisplay');
    const countElement = document.getElementById('selectedCount');
    const listElement = document.getElementById('selectedUsersList');
    
    if (selectedUsers.length === 0) {
        displayContainer.style.display = 'none';
        return;
    }
    
    displayContainer.style.display = 'block';
    countElement.textContent = `${selectedUsers.length} مستخدم محدد - ${selectedUsers.length} users selected`;
    
    listElement.innerHTML = selectedUsers.map(user => `
        <div class="selected-user-card">
            <div class="user-info">
                <div class="user-name">${user.name}</div>
                <div class="user-email">${user.email}</div>
                <span class="user-type-badge ${user.type}">${user.type === 'admin' ? 'إدارة - Admin' : 'زائر - Visitor'}</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-selected-btn" data-user-id="${user.id}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
    
    // Add remove events
    document.querySelectorAll('.remove-selected-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = parseInt(this.dataset.userId);
            selectedUsers = selectedUsers.filter(u => u.id !== userId);
            updateSelectedUsersDisplay();
        });
    });
}

// Send notification button
document.getElementById('sendNotificationBtn').addEventListener('click', function() {
    if (selectedUsers.length === 0) {
        alert('يرجى تحديد المستخدمين أولاً - Please select users first');
        return;
    }
    
    const arabicTitle = document.getElementById('arabicTitle').value;
    const arabicDescription = document.getElementById('arabicDescription').value;
    const englishTitle = document.getElementById('englishTitle').value;
    const englishDescription = document.getElementById('englishDescription').value;
    
    if (!arabicTitle || !arabicDescription || !englishTitle || !englishDescription) {
        alert('يرجى ملء جميع الحقول - Please fill all fields');
        return;
    }
    
    const notificationData = {
        users: selectedUsers,
        arabic: {
            title: arabicTitle,
            description: arabicDescription
        },
        english: {
            title: englishTitle,
            description: englishDescription
        }
    };
    
    console.log('Sending notification:', notificationData);
    alert('تم إرسال الإشعار بنجاح - Notification sent successfully');
});

// Clear modal on close
document.getElementById('userSelectionModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('userSearchInput').value = '';
    document.getElementById('searchLoading').style.display = 'none';
    document.getElementById('searchResults').innerHTML = `
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h6 class="empty-title">ابدأ البحث للعثور على المستخدمين</h6>
            <p class="empty-subtitle">Start searching to find users</p>
        </div>
    `;
});
</script>

<style>
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1.25rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e0e6ed;
    padding: 0.75rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
}

.form-check-input:checked {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-outline-primary {
    border-color: var(--primary-blue);
    color: var(--primary-blue);
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.btn-outline-primary:hover {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-secondary {
    border-color: #6c757d;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

.stat-item {
    padding: 1rem;
    border-radius: 8px;
    background: rgba(52, 144, 220, 0.05);
}

.scheduled-notification {
    padding: 1rem;
    border-radius: 8px;
    background: rgba(108, 117, 125, 0.05);
    border-left: 4px solid var(--primary-blue);
}

.badge {
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
}

.breadcrumb {
    background: rgba(52, 144, 220, 0.1);
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: var(--primary-blue);
}

.breadcrumb-item a {
    color: var(--primary-blue);
    text-decoration: none;
}

.text-primary {
    color: var(--primary-blue) !important;
}

.bg-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)) !important;
}

.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
}

.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
}

.bg-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
}

/* Chat Bubble Cards Styles */
.chat-bubble-card {
    position: relative;
    background: linear-gradient(135deg, #3490dc, #2779bd);
    border-radius: 20px 20px 20px 5px;
    box-shadow: 0 8px 25px rgba(52, 144, 220, 0.3);
    border: none;
    overflow: visible;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
    height: 350px;
    padding: .5rem;
}

.chat-bubble-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(52, 144, 220, 0.4);
}

.chat-bubble-card::before {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 20px;
    width: 0;
    height: 0;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-top: 15px solid #3490dc;
    transform: rotate(135deg);
}

.english-card {
    border-radius: 20px 20px 5px 20px;
}

.english-card::before {
    left: auto;
    right: 20px;
    transform: rotate(-135deg);
}

.chat-bubble-card .card-body {
    background: white;
    border-radius: 15px;
    margin: 8px;
    position: relative;
    z-index: 2;
    height: 315px;
    
}

.chat-bubble-card .form-label {
    color: #2d3748;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.chat-bubble-card .form-control,
.chat-bubble-card .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.chat-bubble-card .form-control:focus,
.chat-bubble-card .form-select:focus {
    border-color: #3490dc;
    box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.1);
    outline: none;
}

.arabic-card .form-control,
.arabic-card .form-select,
.arabic-card .form-label {
    text-align: right;
    direction: rtl;
}

.english-card .form-control,
.english-card .form-select,
.english-card .form-label {
    text-align: left;
    direction: ltr;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chat-bubble-card {
        margin-bottom: 1.5rem;
    }
    
    .chat-bubble-card::before {
        display: none;
    }
}

/* Target Section Styles */
.target-section-card {
    background: linear-gradient(135deg, #2779bd 0%, #6c757d 100%);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
    border: none;
    overflow: hidden;
    position: relative;
}

.target-section-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    pointer-events: none;
}

.target-section-card .card-body {
    background: white;
    border-radius: 15px;
    margin: 8px;
    position: relative;
    z-index: 2;
}

.section-title {
    color: #2d3748;
    font-weight: 600;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.target-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-target {
    background: linear-gradient(135deg, #2779bd, #5a6268);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-target:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-target.active {
    background: linear-gradient(135deg, #4c63d2, #5a67d8);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(76, 99, 210, 0.5);
}

.btn-target-outline {
    background: transparent;
    border: 2px solid #0055d3;
    color: #667eea;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-target-outline:hover {
    background: linear-gradient(135deg, #2779bd, #5a6268);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-target-outline.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-send {
    background: linear-gradient(135deg, #48bb78, #38a169);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(72, 187, 120, 0.3);
}

.btn-send:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(72, 187, 120, 0.4);
    color: white;
}

/* Selected Users Display */
.selected-users-display {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-radius: 15px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    margin-top: 1rem;
}

.selected-users-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.selected-users-header h6 {
    color: #2d3748;
    font-weight: 600;
    margin: 0;
}

.selected-count {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.selected-users-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.selected-user-card {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.selected-user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.selected-user-card .user-info {
    flex: 1;
}

.selected-user-card .user-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.selected-user-card .user-email {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.user-type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.user-type-badge.admin {
    background: linear-gradient(135deg, #ed8936, #dd6b20);
    color: white;
}

.user-type-badge.visitor {
    background: linear-gradient(135deg, #4299e1, #3182ce);
    color: white;
}

/* Modern Modal Styles */
.modern-modal {
    border-radius: 25px;
    border: none;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #0055d3 50%, #5a6268 100%);
    color: white;
    border-radius: 25px 25px 0 0;
    border-bottom: none;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.gradient-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.modal-title {
    font-weight: 700;
    margin: 0;
    font-size: 1.4rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.animated-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.modern-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
    transform: scale(1.2);
}

.modern-close:hover {
    opacity: 1;
    transform: scale(1.3) rotate(90deg);
}

.modern-modal-body {
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

/* Modern Search Box */
.search-box-modern {
    position: relative;
}

.search-container {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
    border: 2px solid transparent;
    transition: all 0.3s ease;
    overflow: hidden;
}

.search-container:hover {
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.3);
}

.search-container:focus-within {
    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.25);
    border-color: #667eea;
    transform: translateY(-2px);
}

.search-icon {
    padding: 1rem 1.5rem;
    color: linear-gradient(135deg, #667eea, #764ba2);
    /* color: white; */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.modern-search {
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    background: transparent;
    flex: 1;
    outline: none;
    color: #2d3748;
}

.modern-search::placeholder {
    color: #a0aec0;
    font-style: italic;
}

.search-loading {
    padding: 1rem;
    color: #667eea;
    font-size: 1.1rem;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Modern Search Results */
.search-results-modern {
    max-height: 500px;
    overflow-y: auto;
    border-radius: 20px;
    background: white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #a0aec0;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #718096;
    animation: float 3s ease-in-out infinite;
}

.empty-icon.no-results {
    background: linear-gradient(135deg, #fed7d7, #feb2b2);
    color: #e53e3e;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.empty-title {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.empty-subtitle {
    color: #a0aec0;
    margin: 0;
    font-size: 0.9rem;
}

.results-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-bottom: 1px solid #e2e8f0;
    border-radius: 20px 20px 0 0;
}

.results-count {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
}

.results-list {
    padding: 0.5rem;
}

.user-result-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
    background: white;
}

.user-result-card:hover {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.user-result-card.selected {
    background: linear-gradient(135deg, #e6fffa, #b2f5ea);
    border-color: #38b2ac;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 178, 172, 0.2);
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-result-card .user-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.user-result-card .user-email {
    color: #718096;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    word-break: break-all;
}

.select-user-btn-modern {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid #e2e8f0;
    background: white;
    color: #a0aec0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1.1rem;
    margin-left: 1rem;
    flex-shrink: 0;
}

.select-user-btn-modern:hover {
    transform: scale(1.1);
    border-color: #667eea;
    color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.select-user-btn-modern.selected {
    background: linear-gradient(135deg, #48bb78, #38a169);
    border-color: #48bb78;
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

/* Modern Modal Selected Users */
.modal-selected-users-modern {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-radius: 20px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.selected-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.selected-title {
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    font-size: 1rem;
}

.selected-badge {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    min-width: 30px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.modal-selected-list-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.selected-user-tag-modern {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.selected-user-tag-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.tag-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.tag-info {
    flex: 1;
    min-width: 0;
}

.tag-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.tag-type {
    font-size: 0.8rem;
    color: #718096;
    background: #f7fafc;
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    display: inline-block;
}

/* Modern Remove Buttons */
.remove-user-btn-modern,
.remove-selected-btn {
    border-radius: 4px;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
    color: #e53e3e;
    font-size: 1rem;
    font-weight: bold;
}

.remove-user-btn-modern:hover,
.remove-selected-btn:hover {
    transform: scale(1.2);
    background: #e53e3e;
    color: white;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(229, 62, 62, 0.3);
}

/* Modern Footer */
.modern-footer {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-top: 1px solid #e2e8f0;
    padding: 1.5rem 2rem;
    border-radius: 0 0 25px 25px;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-cancel-modern {
    background: white;
    border: 2px solid #e2e8f0;
    color: #718096;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-cancel-modern:hover {
    border-color: #cbd5e0;
    color: #4a5568;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.btn-done-modern {
    background: linear-gradient(135deg, #48bb78, #38a169);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 15px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(72, 187, 120, 0.3);
}

.btn-done-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(72, 187, 120, 0.4);
    color: white;
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.btn-done-modern:hover .btn-shine {
    left: 100%;
}

/* Responsive Design */
@media (max-width: 768px) {
    .target-buttons {
        flex-direction: column;
    }
    
    .selected-users-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .selected-users-list {
        grid-template-columns: 1fr;
    }
    
    .user-result-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .user-result-item .user-actions {
        align-self: flex-end;
    }
    
    .selected-user-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .selected-user-card .remove-selected-btn {
        align-self: flex-end;
    }
}
</style>
@endsection