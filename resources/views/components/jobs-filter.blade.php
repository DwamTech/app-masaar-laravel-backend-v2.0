<!-- Jobs Filter Component -->
<div class="row">
        <div class="col-12">
            <div class="car-filter-card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        الوظائف - Jobs Filter
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Emirate Section -->
                    <div class="filter-section mb-3">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                الإمارة - Emirate
                            </h5>
                            <button class="btn btn-primary btn-sm add-emirate-btn" id="jobs-add-emirate-btn">
                                <i class="fas fa-plus me-1"></i>
                                إضافة إمارة
                            </button>
                        </div>
                        <div class="emirates-container" id="jobs-emirates-container">
                            <!-- Emirates will be dynamically added here -->
                        </div>
                        <div class="add-emirate-form" id="jobs-add-emirate-form" style="display: none;">
                            <div class="input-group mt-2">
                                <input type="text" class="form-control" placeholder="اسم الإمارة" id="jobs-new-emirate-input">
                                <button class="btn btn-success" type="button" id="jobs-save-emirate-btn">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-secondary" type="button" id="jobs-cancel-emirate-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
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
                            <div class="static-list" id="jobs-category-types-container">
                                <!-- Category types will be dynamically added here -->
                            </div>
                            <div class="add-item-section fixed-footer">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="إضافة نوع فئة جديد" id="new-jobs-category-type-input">
                                    <button class="btn btn-success add-item-btn add-jobs-category-type-btn" type="button">
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
                        
                        <!-- Section Filter -->
                        <div class="sub-filter-section mb-2">
                            <h6 class="sub-section-title">
                                <i class="fas fa-layer-group me-2"></i>
                                القسم - Section
                            </h6>
                            <div class="static-list-container">
                                <div class="static-list" id="jobs-sections-container">
                                    <!-- Sections will be dynamically added here -->
                                </div>
                                <div class="add-item-section fixed-footer">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="إضافة قسم جديد" id="new-jobs-section-input">
                                        <button class="btn btn-success add-item-btn add-jobs-section-btn" type="button">
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
                        <div class="preview-container" id="jobs-preview-container">
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p>قم بإضافة الإمارات والفئات لرؤية المعاينة</p>
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

/* Animation styles */
.item-fade-in {
    animation: fadeInUp 0.3s ease-out;
}

.item-fade-out {
    animation: fadeOutDown 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOutDown {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

.emirate-item, .category-item, .section-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 8px;
    display: flex;
    justify-content: between;
    align-items: center;
    transition: all 0.3s ease;
}

.emirate-item:hover, .category-item:hover, .section-item:hover {
    border-color: var(--primary-blue);
    box-shadow: 0 2px 8px rgba(52, 144, 220, 0.15);
}

.item-name {
    flex: 1;
    font-weight: 500;
    color: var(--dark-blue);
}

.delete-btn {
    background: #ef4444;
    border: none;
    color: white;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.delete-btn:hover {
    background: #dc2626;
    transform: scale(1.05);
}

.add-emirate-form {
    margin-top: 10px;
}

.static-list {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 5px;
}

.static-list::-webkit-scrollbar {
    width: 6px;
}

.static-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.static-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.static-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Jobs Filter Data Storage
    let jobsData = {
        emirates: [],
        categoryTypes: [],
        sections: []
    };

    // Add Emirate functionality
    const addEmirateBtn = document.getElementById('jobs-add-emirate-btn');
    const addEmirateForm = document.getElementById('jobs-add-emirate-form');
    const newEmirateInput = document.getElementById('jobs-new-emirate-input');
    const saveEmirateBtn = document.getElementById('jobs-save-emirate-btn');
    const cancelEmirateBtn = document.getElementById('jobs-cancel-emirate-btn');
    const emiratesContainer = document.getElementById('jobs-emirates-container');

    addEmirateBtn.addEventListener('click', function() {
        addEmirateForm.style.display = 'block';
        addEmirateBtn.style.display = 'none';
        newEmirateInput.focus();
    });

    cancelEmirateBtn.addEventListener('click', function() {
        addEmirateForm.style.display = 'none';
        addEmirateBtn.style.display = 'inline-block';
        newEmirateInput.value = '';
    });

    saveEmirateBtn.addEventListener('click', function() {
        const emirateName = newEmirateInput.value.trim();
        if (emirateName) {
            addEmirate(emirateName);
            newEmirateInput.value = '';
            addEmirateForm.style.display = 'none';
            addEmirateBtn.style.display = 'inline-block';
        }
    });

    newEmirateInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            saveEmirateBtn.click();
        } else if (e.key === 'Escape') {
            cancelEmirateBtn.click();
        }
    });

    function addEmirate(name) {
        const emirate = {
            id: Date.now(),
            name: name
        };
        jobsData.emirates.push(emirate);
        renderEmirate(emirate);
        updatePreview();
    }

    function renderEmirate(emirate) {
        const emirateElement = document.createElement('div');
        emirateElement.className = 'emirate-item item-fade-in';
        emirateElement.innerHTML = `
            <span class="item-name">${emirate.name}</span>
            <button class="delete-btn" onclick="deleteEmirate(${emirate.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        emiratesContainer.appendChild(emirateElement);
    }

    window.deleteEmirate = function(id) {
        const emirateElement = emiratesContainer.querySelector(`[onclick="deleteEmirate(${id})"]`).parentElement;
        emirateElement.classList.add('item-fade-out');
        setTimeout(() => {
            jobsData.emirates = jobsData.emirates.filter(e => e.id !== id);
            emirateElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Category Type functionality
    const addCategoryTypeBtn = document.querySelector('.add-jobs-category-type-btn');
    const newCategoryTypeInput = document.getElementById('new-jobs-category-type-input');
    const categoryTypesContainer = document.getElementById('jobs-category-types-container');

    addCategoryTypeBtn.addEventListener('click', function() {
        const categoryTypeName = newCategoryTypeInput.value.trim();
        if (categoryTypeName) {
            addCategoryType(categoryTypeName);
            newCategoryTypeInput.value = '';
        }
    });

    newCategoryTypeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addCategoryTypeBtn.click();
        }
    });

    function addCategoryType(name) {
        const categoryType = {
            id: Date.now(),
            name: name
        };
        jobsData.categoryTypes.push(categoryType);
        renderCategoryType(categoryType);
        updatePreview();
    }

    function renderCategoryType(categoryType) {
        const categoryElement = document.createElement('div');
        categoryElement.className = 'category-item item-fade-in';
        categoryElement.innerHTML = `
            <span class="item-name">${categoryType.name}</span>
            <button class="delete-btn" onclick="deleteCategoryType(${categoryType.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        categoryTypesContainer.appendChild(categoryElement);
    }

    window.deleteCategoryType = function(id) {
        const categoryElement = categoryTypesContainer.querySelector(`[onclick="deleteCategoryType(${id})"]`).parentElement;
        categoryElement.classList.add('item-fade-out');
        setTimeout(() => {
            jobsData.categoryTypes = jobsData.categoryTypes.filter(c => c.id !== id);
            categoryElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Section functionality
    const addSectionBtn = document.querySelector('.add-jobs-section-btn');
    const newSectionInput = document.getElementById('new-jobs-section-input');
    const sectionsContainer = document.getElementById('jobs-sections-container');

    addSectionBtn.addEventListener('click', function() {
        const sectionName = newSectionInput.value.trim();
        if (sectionName) {
            addSection(sectionName);
            newSectionInput.value = '';
        }
    });

    newSectionInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addSectionBtn.click();
        }
    });

    function addSection(name) {
        const section = {
            id: Date.now(),
            name: name
        };
        jobsData.sections.push(section);
        renderSection(section);
        updatePreview();
    }

    function renderSection(section) {
        const sectionElement = document.createElement('div');
        sectionElement.className = 'section-item item-fade-in';
        sectionElement.innerHTML = `
            <span class="item-name">${section.name}</span>
            <button class="delete-btn" onclick="deleteSection(${section.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        sectionsContainer.appendChild(sectionElement);
    }

    window.deleteSection = function(id) {
        const sectionElement = sectionsContainer.querySelector(`[onclick="deleteSection(${id})"]`).parentElement;
        sectionElement.classList.add('item-fade-out');
        setTimeout(() => {
            jobsData.sections = jobsData.sections.filter(s => s.id !== id);
            sectionElement.remove();
            updatePreview();
        }, 300);
    };

    // Update Preview
    function updatePreview() {
        const previewContainer = document.getElementById('jobs-preview-container');
        
        if (jobsData.emirates.length === 0 && jobsData.categoryTypes.length === 0 && jobsData.sections.length === 0) {
            previewContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>قم بإضافة الإمارات والفئات لرؤية المعاينة</p>
                </div>
            `;
            return;
        }

        let previewHTML = '<div class="preview-data">';
        
        if (jobsData.emirates.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>الإمارات (${jobsData.emirates.length})</h6>
                    <div class="preview-items">
                        ${jobsData.emirates.map(e => `<span class="preview-tag">${e.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (jobsData.categoryTypes.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-tags me-2"></i>أنواع الفئات (${jobsData.categoryTypes.length})</h6>
                    <div class="preview-items">
                        ${jobsData.categoryTypes.map(c => `<span class="preview-tag">${c.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (jobsData.sections.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-layer-group me-2"></i>الأقسام (${jobsData.sections.length})</h6>
                    <div class="preview-items">
                        ${jobsData.sections.map(s => `<span class="preview-tag">${s.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        previewHTML += '</div>';
        previewContainer.innerHTML = previewHTML;
    }
});
</script>

<style>
.preview-data {
    background: #f8fafc;
    border-radius: 8px;
    padding: 15px;
}

.preview-section-item {
    margin-bottom: 15px;
}

.preview-section-item:last-child {
    margin-bottom: 0;
}

.preview-section-item h6 {
    color: var(--dark-blue);
    font-weight: 600;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.preview-items {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.preview-tag {
    background: var(--primary-blue);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}
</style>