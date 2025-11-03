<!-- Restaurant Filter Component -->
<div class="row">
        <div class="col-12">
            <div class="car-filter-card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-utensils me-2"></i>
                        المطاعم - Restaurant Filter
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Emirate Section with Districts -->
                    <div class="filter-section mb-3">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                الإمارة والمناطق - Emirates & Districts
                            </h5>
                            <button class="btn btn-primary btn-sm add-emirate-btn">
                                <i class="fas fa-plus me-1"></i>
                                إضافة إمارة
                            </button>
                        </div>
                        <div class="emirates-container" id="restaurant-emirates-container">
                            <!-- Emirates with their districts will be dynamically added here -->
                        </div>
                    </div>

                    <!-- Category Type Section -->
                    <div class="filter-section mb-3">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-tags me-2"></i>
                                نوع الفئة - Category Type
                            </h5>
                        </div>
                        <div class="static-list-container">
                            <div class="static-list" id="restaurant-category-types-container">
                                <!-- Category types will be dynamically added here -->
                            </div>
                            <div class="add-item-section fixed-footer">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="إضافة نوع فئة جديد" id="new-restaurant-category-type-input">
                                    <button class="btn btn-success add-item-btn add-restaurant-category-type-btn" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="filter-section mb-3">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-filter me-2"></i>
                                الفلاتر - Filters
                            </h5>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="sub-filter-section mb-2">
                            <h6 class="sub-section-title">
                                <i class="fas fa-list me-2"></i>
                                الفئة - Category
                            </h6>
                            <div class="static-list-container">
                                <div class="static-list" id="restaurant-categories-container">
                                    <!-- Categories will be dynamically added here -->
                                </div>
                                <div class="add-item-section fixed-footer">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="إضافة فئة جديدة" id="new-restaurant-category-input">
                                        <button class="btn btn-success add-item-btn add-restaurant-category-btn" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="preview-section">
                        <h5 class="section-title">
                            <i class="fas fa-eye me-2"></i>
                            معاينة البيانات - Data Preview
                        </h5>
                        <div class="preview-container" id="restaurant-preview-container">
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p>قم بإضافة الإمارات والمناطق لرؤية المعاينة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
.sub-filter-section {
    background: #f1f5f9;
    border-radius: 8px;
    padding: 12px;
    border: 1px solid #e2e8f0;
}

.sub-section-title {
    color: var(--dark-blue);
    font-weight: 600;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.sub-filter-section .static-list-container {
    max-height: 200px;
}

/* Emirate Card Styles */
.emirate-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.emirate-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.emirate-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 15px;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: between;
    align-items: center;
}

.emirate-name {
    font-weight: 600;
    margin: 0;
    flex-grow: 1;
}

.delete-emirate-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.delete-emirate-btn:hover {
    background: rgba(255,255,255,0.3);
}

.districts-container {
    padding: 15px;
}

.district-item, .category-item, .category-type-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 12px;
    margin: 5px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.district-item:hover, .category-item:hover, .category-type-item:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.item-text {
    font-size: 0.9rem;
    color: #374151;
}

.delete-item-btn {
    background: #ef4444;
    border: none;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: background 0.3s ease;
}

.delete-item-btn:hover {
    background: #dc2626;
}

.add-district-section {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
}

/* Twist Animation for Delete Effects */
@keyframes twistOut {
    0% {
        transform: rotate(0deg) scale(1);
        opacity: 1;
    }
    50% {
        transform: rotate(180deg) scale(0.8);
        opacity: 0.5;
    }
    100% {
        transform: rotate(360deg) scale(0);
        opacity: 0;
    }
}

.twist-out {
    animation: twistOut 0.6s ease-in-out forwards;
}

/* Delete Modal Styles */
.delete-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.delete-modal.show {
    display: flex;
}

.delete-modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 400px;
    width: 90%;
    text-align: center;
}

.delete-modal-buttons {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: center;
}
</style>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="delete-modal">
    <div class="delete-modal-content">
        <h5>تأكيد الحذف</h5>
        <p id="deleteMessage"></p>
        <div class="delete-modal-buttons">
            <button id="confirmDelete" class="btn btn-danger">حذف</button>
            <button id="cancelDelete" class="btn btn-secondary">إلغاء</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Restaurant Filter JavaScript Functionality
    
    // Global variables
    let deleteCallback = null;
    let emirateCounter = 0;
    
    // Initialize event listeners
    initializeEventListeners();
    
    function initializeEventListeners() {
        // Add Emirate button
        document.querySelector('.add-emirate-btn').addEventListener('click', addEmirate);
        
        // Add Category Type button
        document.querySelector('.add-restaurant-category-type-btn').addEventListener('click', addCategoryType);
        
        // Add Category button
        document.querySelector('.add-restaurant-category-btn').addEventListener('click', addCategory);
        
        // Enter key listeners for inputs
        document.getElementById('new-restaurant-category-type-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') addCategoryType();
        });
        
        document.getElementById('new-restaurant-category-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') addCategory();
        });
        
        // Delete modal event listeners
        document.getElementById('confirmDelete').addEventListener('click', () => {
            if (deleteCallback) {
                deleteCallback();
            }
            hideDeleteModal();
        });
        
        document.getElementById('cancelDelete').addEventListener('click', hideDeleteModal);
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') {
                hideDeleteModal();
            }
        });
    }
    
    // Add Emirate Function
    function addEmirate() {
        const emirateName = prompt('أدخل اسم الإمارة:');
        if (!emirateName || emirateName.trim() === '') return;
        
        emirateCounter++;
        const emirateId = `emirate-${emirateCounter}`;
        
        const emirateCard = document.createElement('div');
        emirateCard.className = 'emirate-card';
        emirateCard.id = emirateId;
        
        emirateCard.innerHTML = `
            <div class="emirate-header">
                <h6 class="emirate-name">${emirateName.trim()}</h6>
                <button class="delete-emirate-btn" onclick="deleteEmirate('${emirateId}', '${emirateName.trim()}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="districts-container">
                <div class="districts-list" id="${emirateId}-districts">
                    <!-- Districts will be added here -->
                </div>
                <div class="add-district-section">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="إضافة منطقة" id="${emirateId}-district-input">
                        <button class="btn btn-success" onclick="addDistrict('${emirateId}')" type="button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add enter key listener for district input
        setTimeout(() => {
            document.getElementById(`${emirateId}-district-input`).addEventListener('keypress', function(e) {
                if (e.key === 'Enter') addDistrict(emirateId);
            });
        }, 100);
        
        document.getElementById('restaurant-emirates-container').appendChild(emirateCard);
        updatePreview();
        showAlert('تم إضافة الإمارة بنجاح', 'success');
    }
    
    // Add District Function
    window.addDistrict = function(emirateId) {
        const input = document.getElementById(`${emirateId}-district-input`);
        const districtName = input.value.trim();
        
        if (!districtName) {
            showAlert('يرجى إدخال اسم المنطقة', 'warning');
            return;
        }
        
        const districtItem = document.createElement('span');
        districtItem.className = 'district-item';
        districtItem.innerHTML = `
            <span class="item-text">${districtName}</span>
            <button class="delete-item-btn" onclick="deleteItem(this, '${districtName}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.getElementById(`${emirateId}-districts`).appendChild(districtItem);
        input.value = '';
        updatePreview();
        showAlert('تم إضافة المنطقة بنجاح', 'success');
    }
    
    // Delete Emirate Function
    window.deleteEmirate = function(emirateId, emirateName) {
        showDeleteModal(
            `هل تريد حذف إمارة "${emirateName}" وجميع مناطقها؟`,
            () => {
                const emirateCard = document.getElementById(emirateId);
                removeItemWithTwist(emirateCard, () => {
                    updatePreview();
                    showAlert('تم حذف الإمارة بنجاح', 'success');
                });
            }
        );
    }
    
    // Add Category Type Function
    function addCategoryType() {
        const input = document.getElementById('new-restaurant-category-type-input');
        const categoryType = input.value.trim();
        
        if (!categoryType) {
            showAlert('يرجى إدخال نوع الفئة', 'warning');
            return;
        }
        
        const categoryTypeItem = document.createElement('span');
        categoryTypeItem.className = 'category-type-item';
        categoryTypeItem.innerHTML = `
            <span class="item-text">${categoryType}</span>
            <button class="delete-item-btn" onclick="deleteItem(this, '${categoryType}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.getElementById('restaurant-category-types-container').appendChild(categoryTypeItem);
        input.value = '';
        showAlert('تم إضافة نوع الفئة بنجاح', 'success');
    }
    
    // Add Category Function
    function addCategory() {
        const input = document.getElementById('new-restaurant-category-input');
        const category = input.value.trim();
        
        if (!category) {
            showAlert('يرجى إدخال اسم الفئة', 'warning');
            return;
        }
        
        const categoryItem = document.createElement('span');
        categoryItem.className = 'category-item';
        categoryItem.innerHTML = `
            <span class="item-text">${category}</span>
            <button class="delete-item-btn" onclick="deleteItem(this, '${category}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.getElementById('restaurant-categories-container').appendChild(categoryItem);
        input.value = '';
        showAlert('تم إضافة الفئة بنجاح', 'success');
    }
    
    // Delete Item Function (for districts, categories, category types)
    window.deleteItem = function(button, itemName) {
        showDeleteModal(
            `هل تريد حذف "${itemName}"؟`,
            () => {
                const item = button.closest('.district-item, .category-item, .category-type-item');
                removeItemWithTwist(item, () => {
                    updatePreview();
                    showAlert('تم الحذف بنجاح', 'success');
                });
            }
        );
    }
    
    // Delete Modal Functions
    function showDeleteModal(message, callback) {
        const modal = document.getElementById('deleteModal');
        const messageEl = document.getElementById('deleteMessage');
        
        messageEl.textContent = message;
        deleteCallback = callback;
        
        modal.classList.add('show');
    }
    
    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('show');
        deleteCallback = null;
    }
    
    // Remove Item with Twist Animation
    function removeItemWithTwist(element, callback) {
        element.classList.add('twist-out');
        
        setTimeout(() => {
            if (callback) callback();
            element.remove();
        }, 600);
    }
    
    // Update Preview Function
    function updatePreview() {
        const previewContainer = document.getElementById('restaurant-preview-container');
        const emirates = document.querySelectorAll('.emirate-card');
        const categoryTypes = document.querySelectorAll('.category-type-item');
        const categories = document.querySelectorAll('.category-item');
        
        if (emirates.length === 0 && categoryTypes.length === 0 && categories.length === 0) {
            previewContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>قم بإضافة الإمارات والفئات لرؤية المعاينة</p>
                </div>
            `;
            return;
        }
        
        let previewHTML = '<div class="preview-content">';
        
        // Emirates Preview
        if (emirates.length > 0) {
            previewHTML += '<h6><i class="fas fa-map-marker-alt me-2"></i>الإمارات والمناطق:</h6>';
            emirates.forEach(emirate => {
                const emirateName = emirate.querySelector('.emirate-name').textContent;
                const districts = emirate.querySelectorAll('.district-item .item-text');
                previewHTML += `<p><strong>${emirateName}:</strong> `;
                if (districts.length > 0) {
                    const districtNames = Array.from(districts).map(d => d.textContent).join(', ');
                    previewHTML += districtNames;
                } else {
                    previewHTML += 'لا توجد مناطق';
                }
                previewHTML += '</p>';
            });
        }
        
        // Category Types Preview
        if (categoryTypes.length > 0) {
            previewHTML += '<h6><i class="fas fa-tags me-2"></i>أنواع الفئات:</h6>';
            const typeNames = Array.from(categoryTypes).map(ct => ct.querySelector('.item-text').textContent).join(', ');
            previewHTML += `<p>${typeNames}</p>`;
        }
        
        // Categories Preview
        if (categories.length > 0) {
            previewHTML += '<h6><i class="fas fa-list me-2"></i>الفئات:</h6>';
            const categoryNames = Array.from(categories).map(c => c.querySelector('.item-text').textContent).join(', ');
            previewHTML += `<p>${categoryNames}</p>`;
        }
        
        previewHTML += '</div>';
        previewContainer.innerHTML = previewHTML;
    }
    
    // Show Alert Function
    function showAlert(message, type = 'info') {
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }
});
</script>