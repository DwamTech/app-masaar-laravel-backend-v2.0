@extends('layouts.dashboard')

@section('title', 'تفاصيل المستخدم - User Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1 text-primary fw-bold">
                                <i class="bi bi-person-circle me-2"></i>
                                تفاصيل المستخدم
                                <small class="text-muted fs-6">User Details</small>
                            </h2>
                            <p class="text-muted mb-0">عرض تفاصيل المستخدم وإعلاناته</p>
                            <small class="text-muted">View user details and advertisements</small>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('users-management') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-1"></i>
                                العودة للقائمة
                                <br><small>Back to List</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill me-2"></i>
                        المعلومات الشخصية
                        <small class="d-block">Personal Information</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-item">
                                <label class="fw-bold text-primary">الاسم - Name:</label>
                                <p class="mb-0">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="fw-bold text-primary">الرقم - Phone Number:</label>
                                <p class="mb-0">{{ $user->phone ?? 'غير محدد - Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="fw-bold text-primary">الايميل - Email:</label>
                                <p class="mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="fw-bold text-primary">العنوان - Address:</label>
                                <p class="mb-0">
                                    @if($user->normalUser && $user->normalUser->address)
                                        {{ $user->normalUser->address }}
                                    @elseif($user->realEstate && $user->realEstate->officeDetail && $user->realEstate->officeDetail->address)
                                        {{ $user->realEstate->officeDetail->address }}
                                    @else
                                        غير محدد - Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <label class="fw-bold text-primary">الكود المسجل من خلاله - Registration Code:</label>
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    {{ $user->registration_code ?? 'REG' . $user->id }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        إحصائيات المستخدم
                        <small class="d-block">User Statistics</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card bg-primary text-white text-center p-3 rounded">
                                <i class="bi bi-megaphone display-6 mb-2"></i>
                                <h4 class="mb-1">{{ rand(0, 15) }}</h4>
                                <small>إعلانات - Ads</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card bg-success text-white text-center p-3 rounded">
                                <i class="bi bi-eye display-6 mb-2"></i>
                                <h4 class="mb-1">{{ rand(50, 500) }}</h4>
                                <small>مشاهدات - Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card bg-warning text-dark text-center p-3 rounded">
                                <i class="bi bi-calendar display-6 mb-2"></i>
                                <h4 class="mb-1">{{ $user->created_at->diffForHumans() }}</h4>
                                <small>تاريخ التسجيل - Registration</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card bg-secondary text-white text-center p-3 rounded">
                                <i class="bi bi-person-badge display-6 mb-2"></i>
                                <h4 class="mb-1">{{ $user->user_type === 'normal_user' ? 'زائر' : 'معلن' }}</h4>
                                <small>{{ $user->user_type === 'normal_user' ? 'Visitor' : 'Advertiser' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Ads Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-images me-2"></i>
                        إعلانات المستخدم
                        <small class="text-muted">User Advertisements</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @php
                            $sampleAds = [
                                ['title' => 'سيارة للبيع - Car for Sale', 'image' => 'https://via.placeholder.com/300x200/007bff/ffffff?text=Car+Ad', 'views' => rand(10, 100)],
                                ['title' => 'شقة للإيجار - Apartment for Rent', 'image' => 'https://via.placeholder.com/300x200/28a745/ffffff?text=Real+Estate', 'views' => rand(10, 100)],
                                ['title' => 'وظيفة متاحة - Job Available', 'image' => 'https://via.placeholder.com/300x200/ffc107/000000?text=Job+Ad', 'views' => rand(10, 100)],
                            ];
                        @endphp
                        
                        @forelse($sampleAds as $index => $ad)
                        <div class="col-lg-4 col-md-6">
                            <div class="ad-card card border-0 shadow-sm h-100">
                                <img src="{{ $ad['image'] }}" class="card-img-top" alt="Ad Image" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">{{ $ad['title'] }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info text-white">
                                            <i class="bi bi-eye me-1"></i>
                                            {{ $ad['views'] }} مشاهدة
                                            <br><small>Views</small>
                                        </span>
                                        <button class="btn btn-outline-primary btn-sm" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#adDetails{{ $index }}" 
                                                aria-expanded="false">
                                            <i class="bi bi-chevron-down me-1"></i>
                                            التفاصيل
                                            <br><small>Details</small>
                                        </button>
                                    </div>
                                    
                                    <!-- Ad Details Dropdown -->
                                    <div class="collapse mt-3" id="adDetails{{ $index }}">
                                        <div class="card card-body bg-light border-0">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <img src="{{ $ad['image'] }}" class="img-fluid rounded" alt="Ad Thumbnail">
                                                </div>
                                                <div class="col-8">
                                                    <h6 class="mb-2 text-primary">تفاصيل الإعلان - Ad Details</h6>
                                                    <p class="mb-1"><strong>العنوان - Title:</strong> {{ $ad['title'] }}</p>
                                                    <p class="mb-1"><strong>عدد الزوار - Visitors:</strong> {{ $ad['views'] }}</p>
                                                    <p class="mb-1"><strong>تاريخ النشر - Published:</strong> {{ now()->subDays(rand(1, 30))->format('Y-m-d') }}</p>
                                                    <p class="mb-0"><strong>الحالة - Status:</strong> 
                                                        <span class="badge bg-success">نشط - Active</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                <h5 class="text-muted">لا توجد إعلانات</h5>
                                <p class="text-muted">No advertisements found</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--primary-blue);
}

.info-item label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
}

.info-item p {
    font-size: 16px;
    color: #333;
}

.stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.ad-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.ad-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card {
    border-radius: 12px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    border-radius: 6px;
    font-weight: 500;
}

:root {
    --primary-blue: #3490dc;
}

.text-primary {
    color: var(--primary-blue) !important;
}

.bg-primary {
    background-color: var(--primary-blue) !important;
}

.btn-primary {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-primary {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-primary:hover {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}
</style>
@endsection