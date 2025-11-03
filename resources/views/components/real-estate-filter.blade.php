<!-- Real Estate Filter Component -->
<div class="row">
        <div class="col-12">
            <div class="car-filter-card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-home me-2"></i>
                        العقارات - Real Estate Filter
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
                            <button class="btn btn-primary btn-sm add-emirate-btn" id="real-estate-add-emirate-btn">
                                <i class="fas fa-plus me-1"></i>
                                إضافة إمارة
                            </button>
                        </div>
                        <div class="emirates-container" id="real-estate-emirates-container">
                            <!-- Emirates will be dynamically added here -->
                        </div>
                        <div class="add-emirate-form" id="real-estate-add-emirate-form" style="display: none;">
                            <div class="input-group mt-2">
                                <input type="text" class="form-control" placeholder="اسم الإمارة" id="real-estate-new-emirate-input">
                                <button class="btn btn-success" type="button" id="real-estate-save-emirate-btn">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-secondary" type="button" id="real-estate-cancel-emirate-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Property Type Section -->
                    <div class="filter-section mb-3">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-home me-2"></i>
                                نوع العقار - Property Type
                            </h5>
                        </div>
                        <div class="static-list-container">
                            <div class="static-list" id="real-estate-property-types-container">
                                <!-- Property types will be dynamically added here -->
                            </div>
                            <div class="add-item-section fixed-footer">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="إضافة نوع عقار جديد" id="new-real-estate-property-type-input">
                                    <button class="btn btn-success add-item-btn add-real-estate-property-type-btn" type="button">
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
                        
                        <!-- Type Filter -->
                        <div class="sub-filter-section mb-2">
                            <h6 class="sub-section-title">
                                <i class="fas fa-home me-2"></i>
                                النوع - Type
                            </h6>
                            <div class="static-list-container">
                                <div class="static-list" id="real-estate-types-container">
                                    <!-- Types will be dynamically added here -->
                                </div>
                                <div class="add-item-section fixed-footer">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="إضافة نوع جديد" id="new-real-estate-type-input">
                                        <button class="btn btn-success add-item-btn add-real-estate-type-btn" type="button">
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
                        <div class="preview-container" id="real-estate-preview-container">
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

.emirate-item, .property-type-item, .type-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.emirate-item:hover, .property-type-item:hover, .type-item:hover {
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
    // Real Estate Filter Data Storage
    let realEstateData = {
        emirates: [],
        propertyTypes: [],
        types: []
    };

    // Add Emirate functionality
    const addEmirateBtn = document.getElementById('real-estate-add-emirate-btn');
    const addEmirateForm = document.getElementById('real-estate-add-emirate-form');
    const newEmirateInput = document.getElementById('real-estate-new-emirate-input');
    const saveEmirateBtn = document.getElementById('real-estate-save-emirate-btn');
    const cancelEmirateBtn = document.getElementById('real-estate-cancel-emirate-btn');
    const emiratesContainer = document.getElementById('real-estate-emirates-container');

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
        realEstateData.emirates.push(emirate);
        renderEmirate(emirate);
        updatePreview();
    }

    function renderEmirate(emirate) {
        const emirateElement = document.createElement('div');
        emirateElement.className = 'emirate-item item-fade-in';
        emirateElement.innerHTML = `
            <span class="item-name">${emirate.name}</span>
            <button class="delete-btn" onclick="deleteRealEstateEmirate(${emirate.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        emiratesContainer.appendChild(emirateElement);
    }

    window.deleteRealEstateEmirate = function(id) {
        const emirateElement = emiratesContainer.querySelector(`[onclick="deleteRealEstateEmirate(${id})"]`).parentElement;
        emirateElement.classList.add('item-fade-out');
        setTimeout(() => {
            realEstateData.emirates = realEstateData.emirates.filter(e => e.id !== id);
            emirateElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Property Type functionality
    const addPropertyTypeBtn = document.querySelector('.add-real-estate-property-type-btn');
    const newPropertyTypeInput = document.getElementById('new-real-estate-property-type-input');
    const propertyTypesContainer = document.getElementById('real-estate-property-types-container');

    addPropertyTypeBtn.addEventListener('click', function() {
        const propertyTypeName = newPropertyTypeInput.value.trim();
        if (propertyTypeName) {
            addPropertyType(propertyTypeName);
            newPropertyTypeInput.value = '';
        }
    });

    newPropertyTypeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addPropertyTypeBtn.click();
        }
    });

    function addPropertyType(name) {
        const propertyType = {
            id: Date.now(),
            name: name
        };
        realEstateData.propertyTypes.push(propertyType);
        renderPropertyType(propertyType);
        updatePreview();
    }

    function renderPropertyType(propertyType) {
        const propertyTypeElement = document.createElement('div');
        propertyTypeElement.className = 'property-type-item item-fade-in';
        propertyTypeElement.innerHTML = `
            <span class="item-name">${propertyType.name}</span>
            <button class="delete-btn" onclick="deleteRealEstatePropertyType(${propertyType.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        propertyTypesContainer.appendChild(propertyTypeElement);
    }

    window.deleteRealEstatePropertyType = function(id) {
        const propertyTypeElement = propertyTypesContainer.querySelector(`[onclick="deleteRealEstatePropertyType(${id})"]`).parentElement;
        propertyTypeElement.classList.add('item-fade-out');
        setTimeout(() => {
            realEstateData.propertyTypes = realEstateData.propertyTypes.filter(pt => pt.id !== id);
            propertyTypeElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Type functionality
    const addTypeBtn = document.querySelector('.add-real-estate-type-btn');
    const newTypeInput = document.getElementById('new-real-estate-type-input');
    const typesContainer = document.getElementById('real-estate-types-container');

    addTypeBtn.addEventListener('click', function() {
        const typeName = newTypeInput.value.trim();
        if (typeName) {
            addType(typeName);
            newTypeInput.value = '';
        }
    });

    newTypeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addTypeBtn.click();
        }
    });

    function addType(name) {
        const type = {
            id: Date.now(),
            name: name
        };
        realEstateData.types.push(type);
        renderType(type);
        updatePreview();
    }

    function renderType(type) {
        const typeElement = document.createElement('div');
        typeElement.className = 'type-item item-fade-in';
        typeElement.innerHTML = `
            <span class="item-name">${type.name}</span>
            <button class="delete-btn" onclick="deleteRealEstateType(${type.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        typesContainer.appendChild(typeElement);
    }

    window.deleteRealEstateType = function(id) {
        const typeElement = typesContainer.querySelector(`[onclick="deleteRealEstateType(${id})"]`).parentElement;
        typeElement.classList.add('item-fade-out');
        setTimeout(() => {
            realEstateData.types = realEstateData.types.filter(t => t.id !== id);
            typeElement.remove();
            updatePreview();
        }, 300);
    };

    // Update Preview
    function updatePreview() {
        const previewContainer = document.getElementById('real-estate-preview-container');
        
        if (realEstateData.emirates.length === 0 && realEstateData.propertyTypes.length === 0 && 
            realEstateData.types.length === 0) {
            previewContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>قم بإضافة الإمارات والمناطق لرؤية المعاينة</p>
                </div>
            `;
            return;
        }

        let previewHTML = '<div class="preview-data">';
        
        if (realEstateData.emirates.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>الإمارات (${realEstateData.emirates.length})</h6>
                    <div class="preview-items">
                        ${realEstateData.emirates.map(e => `<span class="preview-tag">${e.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (realEstateData.propertyTypes.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-home me-2"></i>أنواع العقارات (${realEstateData.propertyTypes.length})</h6>
                    <div class="preview-items">
                        ${realEstateData.propertyTypes.map(pt => `<span class="preview-tag">${pt.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (realEstateData.types.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-home me-2"></i>الأنواع (${realEstateData.types.length})</h6>
                    <div class="preview-items">
                        ${realEstateData.types.map(t => `<span class="preview-tag">${t.name}</span>`).join('')}
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