@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="page-title mb-1">
                                <i class="bi bi-person-x text-danger me-2"></i>
                                قائمة الحظر <small class="text-muted">- Blocked Users</small>
                            </h2>
                            <p class="text-muted mb-0">إدارة المستخدمين المحظورين في النظام <small>- Manage blocked users in the system</small></p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="bi bi-people-fill me-1"></i>
                                {{ $blockedUsers->count() }} مستخدم محظور
                            </span>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0 text-dark fw-semibold">
                                <i class="bi bi-table me-2 text-primary"></i>
                                قائمة المستخدمين المحظورين
                            </h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="input-group" style="max-width: 300px; margin-left: auto;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 bg-light" 
                                       placeholder="البحث في المستخدمين..." id="searchInput">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($blockedUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="usersTable">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="border-0 py-3 px-4 text-white fw-semibold">
                                            <i class="bi bi-person me-1"></i>
                                            المستخدم
                                        </th>
                                        <th class="border-0 py-3 px-4 text-white fw-semibold">
                                            <i class="bi bi-envelope me-1"></i>
                                            البريد الإلكتروني
                                        </th>
                                        <th class="border-0 py-3 px-4 text-white fw-semibold">
                                            <i class="bi bi-telephone me-1"></i>
                                            رقم الهاتف
                                        </th>
                                        <th class="border-0 py-3 px-4 text-white fw-semibold">
                                            <i class="bi bi-shield-exclamation me-1"></i>
                                            الحالة
                                        </th>
                                        <th class="border-0 py-3 px-4 text-white fw-semibold text-center">
                                            <i class="bi bi-gear me-1"></i>
                                            الإجراءات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blockedUsers as $user)
                                        <tr class="user-row">
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold text-dark user-name">{{ $user->name }}</h6>
                                                        <small class="text-muted">ID: #{{ $user->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                                                    <span class="user-email">{{ $user->email }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-phone-fill text-success me-2"></i>
                                                    <span class="user-phone">{{ $user->phone }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="status-badge badge bg-danger-subtle text-danger px-3 py-2 rounded-pill" data-user-id="{{ $user->id }}">
                                                    <i class="bi bi-x-circle-fill me-1"></i>
                                                    <span class="status-text">محظور</span>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <button type="button" 
                                                        class="toggle-status-btn btn btn-success btn-sm rounded-pill px-3 py-2 fw-semibold"
                                                        data-user-id="{{ $user->id }}"
                                                        data-current-status="blocked"
                                                        title="فك الحظر">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    <span class="btn-text">فك الحظر</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-2">لا توجد مستخدمين محظورين</h5>
                            <p class="text-muted mb-0">لم يتم حظر أي مستخدم في النظام حتى الآن</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table th {
        font-size: 0.875rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .user-row:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }
    
    .btn {
        transition: all 0.2s ease;
        border: none;
        font-weight: 600;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    
    .form-control {
        border-color: #e9ecef;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .alert {
        border: none;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .bg-danger-subtle {
        background-color: #f8d7da !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }
</style>

<!-- Search Functionality & Status Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('usersTable');
        const rows = table ? table.getElementsByClassName('user-row') : [];
        
        if (searchInput && rows.length > 0) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                Array.from(rows).forEach(function(row) {
                    const name = row.querySelector('.user-name').textContent.toLowerCase();
                    const email = row.querySelector('.user-email').textContent.toLowerCase();
                    const phone = row.querySelector('.user-phone').textContent.toLowerCase();
                    
                    const isMatch = name.includes(searchTerm) || 
                                   email.includes(searchTerm) || 
                                   phone.includes(searchTerm);
                    
                    row.style.display = isMatch ? '' : 'none';
                });
            });
        }
        
        // Status toggle functionality
        const toggleButtons = document.querySelectorAll('.toggle-status-btn');
        
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const currentStatus = this.getAttribute('data-current-status');
                const statusBadge = document.querySelector(`.status-badge[data-user-id="${userId}"]`);
                const statusText = statusBadge.querySelector('.status-text');
                const statusIcon = statusBadge.querySelector('i');
                const btnText = this.querySelector('.btn-text');
                const btnIcon = this.querySelector('i');
                
                if (currentStatus === 'blocked') {
                    // Change to active
                    statusBadge.className = 'status-badge badge bg-success-subtle text-success px-3 py-2 rounded-pill';
                    statusIcon.className = 'bi bi-check-circle-fill me-1';
                    statusText.textContent = 'نشط';
                    
                    this.className = 'toggle-status-btn btn btn-danger btn-sm rounded-pill px-3 py-2 fw-semibold';
                    this.setAttribute('data-current-status', 'active');
                    this.setAttribute('title', 'حظر المستخدم');
                    btnIcon.className = 'bi bi-x-circle me-1';
                    btnText.textContent = 'حظر';
                } else {
                    // Change to blocked
                    statusBadge.className = 'status-badge badge bg-danger-subtle text-danger px-3 py-2 rounded-pill';
                    statusIcon.className = 'bi bi-x-circle-fill me-1';
                    statusText.textContent = 'محظور';
                    
                    this.className = 'toggle-status-btn btn btn-success btn-sm rounded-pill px-3 py-2 fw-semibold';
                    this.setAttribute('data-current-status', 'blocked');
                    this.setAttribute('title', 'فك الحظر');
                    btnIcon.className = 'bi bi-check-circle me-1';
                    btnText.textContent = 'فك الحظر';
                }
                
                // Show success message
                const alertContainer = document.querySelector('.container-fluid');
                const existingAlert = alertContainer.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                const successMessage = currentStatus === 'blocked' ? 'تم فك حظر المستخدم بنجاح' : 'تم حظر المستخدم بنجاح';
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        ${successMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto-hide alert after 3 seconds
                setTimeout(function() {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 3000);
            });
        });
    });
</script>
@endsection