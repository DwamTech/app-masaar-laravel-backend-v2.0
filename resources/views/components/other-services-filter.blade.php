<!-- Other Services Filter Component -->
<div class="row">
        <div class="col-12">
            <div class="car-filter-card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        الخدمات الأخرى - Other Services Filter
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
                            <button class="btn btn-primary btn-sm add-emirate-btn" id="other-services-add-emirate-btn">
                                <i class="fas fa-plus me-1"></i>
                                إضافة إمارة
                            </button>
                        </div>
                        <div class="emirates-container" id="other-services-emirates-container">
                            <!-- Emirates will be dynamically added here -->
                        </div>
                        <div class="add-emirate-form" id="other-services-add-emirate-form" style="display: none;">
                            <div class="input-group mt-2">
                                <input type="text" class="form-control" placeholder="اسم الإمارة" id="other-services-new-emirate-input">
                                <button class="btn btn-success" type="button" id="other-services-save-emirate-btn">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-secondary" type="button" id="other-services-cancel-emirate-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Type Section -->
                    <div class="filter-section mb-3">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-layer-group me-2"></i>
                                نوع القسم - Section Type
                            </h5>
                        </div>
                        <div class="static-list-container">
                            <div class="static-list" id="other-services-section-types-container">
                                <!-- Section types will be dynamically added here -->
                            </div>
                            <div class="add-item-section fixed-footer">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="إضافة نوع قسم جديد" id="new-other-services-section-type-input">
                                    <button class="btn btn-success add-item-btn add-other-services-section-type-btn" type="button">
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
                        
                        <!-- Service Filter -->
                        <div class="sub-filter-section mb-2">
                            <h6 class="sub-section-title">
                                <i class="fas fa-concierge-bell me-2"></i>
                                الخدمة - Service
                            </h6>
                            <div class="static-list-container">
                                <div class="static-list" id="other-services-services-container">
                                    <!-- Services will be dynamically added here -->
                                </div>
                                <div class="add-item-section fixed-footer">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="إضافة خدمة جديدة" id="new-other-services-service-input">
                                        <button class="btn btn-success add-item-btn add-other-services-service-btn" type="button">
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
                        <div class="preview-container" id="other-services-preview-container">
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p>قم بإضافة الإمارات والأقسام لرؤية المعاينة</p>
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

.emirate-item, .section-type-item, .service-item {
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

.emirate-item:hover, .section-type-item:hover, .service-item:hover {
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
    // Other Services Filter Data Storage
    let otherServicesData = {
        emirates: [],
        sectionTypes: [],
        services: []
    };

    // Add Emirate functionality
    const addEmirateBtn = document.getElementById('other-services-add-emirate-btn');
    const addEmirateForm = document.getElementById('other-services-add-emirate-form');
    const newEmirateInput = document.getElementById('other-services-new-emirate-input');
    const saveEmirateBtn = document.getElementById('other-services-save-emirate-btn');
    const cancelEmirateBtn = document.getElementById('other-services-cancel-emirate-btn');
    const emiratesContainer = document.getElementById('other-services-emirates-container');

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
        otherServicesData.emirates.push(emirate);
        renderEmirate(emirate);
        updatePreview();
    }

    function renderEmirate(emirate) {
        const emirateElement = document.createElement('div');
        emirateElement.className = 'emirate-item item-fade-in';
        emirateElement.innerHTML = `
            <span class="item-name">${emirate.name}</span>
            <button class="delete-btn" onclick="deleteOtherServicesEmirate(${emirate.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        emiratesContainer.appendChild(emirateElement);
    }

    window.deleteOtherServicesEmirate = function(id) {
        const emirateElement = emiratesContainer.querySelector(`[onclick="deleteOtherServicesEmirate(${id})"]`).parentElement;
        emirateElement.classList.add('item-fade-out');
        setTimeout(() => {
            otherServicesData.emirates = otherServicesData.emirates.filter(e => e.id !== id);
            emirateElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Section Type functionality
    const addSectionTypeBtn = document.querySelector('.add-other-services-section-type-btn');
    const newSectionTypeInput = document.getElementById('new-other-services-section-type-input');
    const sectionTypesContainer = document.getElementById('other-services-section-types-container');

    addSectionTypeBtn.addEventListener('click', function() {
        const sectionTypeName = newSectionTypeInput.value.trim();
        if (sectionTypeName) {
            addSectionType(sectionTypeName);
            newSectionTypeInput.value = '';
        }
    });

    newSectionTypeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addSectionTypeBtn.click();
        }
    });

    function addSectionType(name) {
        const sectionType = {
            id: Date.now(),
            name: name
        };
        otherServicesData.sectionTypes.push(sectionType);
        renderSectionType(sectionType);
        updatePreview();
    }

    function renderSectionType(sectionType) {
        const sectionTypeElement = document.createElement('div');
        sectionTypeElement.className = 'section-type-item item-fade-in';
        sectionTypeElement.innerHTML = `
            <span class="item-name">${sectionType.name}</span>
            <button class="delete-btn" onclick="deleteOtherServicesSectionType(${sectionType.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        sectionTypesContainer.appendChild(sectionTypeElement);
    }

    window.deleteOtherServicesSectionType = function(id) {
        const sectionTypeElement = sectionTypesContainer.querySelector(`[onclick="deleteOtherServicesSectionType(${id})"]`).parentElement;
        sectionTypeElement.classList.add('item-fade-out');
        setTimeout(() => {
            otherServicesData.sectionTypes = otherServicesData.sectionTypes.filter(st => st.id !== id);
            sectionTypeElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Service functionality
    const addServiceBtn = document.querySelector('.add-other-services-service-btn');
    const newServiceInput = document.getElementById('new-other-services-service-input');
    const servicesContainer = document.getElementById('other-services-services-container');

    addServiceBtn.addEventListener('click', function() {
        const serviceName = newServiceInput.value.trim();
        if (serviceName) {
            addService(serviceName);
            newServiceInput.value = '';
        }
    });

    newServiceInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addServiceBtn.click();
        }
    });

    function addService(name) {
        const service = {
            id: Date.now(),
            name: name
        };
        otherServicesData.services.push(service);
        renderService(service);
        updatePreview();
    }

    function renderService(service) {
        const serviceElement = document.createElement('div');
        serviceElement.className = 'service-item item-fade-in';
        serviceElement.innerHTML = `
            <span class="item-name">${service.name}</span>
            <button class="delete-btn" onclick="deleteOtherServicesService(${service.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        servicesContainer.appendChild(serviceElement);
    }

    window.deleteOtherServicesService = function(id) {
        const serviceElement = servicesContainer.querySelector(`[onclick="deleteOtherServicesService(${id})"]`).parentElement;
        serviceElement.classList.add('item-fade-out');
        setTimeout(() => {
            otherServicesData.services = otherServicesData.services.filter(s => s.id !== id);
            serviceElement.remove();
            updatePreview();
        }, 300);
    };

    // Update Preview
    function updatePreview() {
        const previewContainer = document.getElementById('other-services-preview-container');
        
        if (otherServicesData.emirates.length === 0 && otherServicesData.sectionTypes.length === 0 && 
            otherServicesData.services.length === 0) {
            previewContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>قم بإضافة الإمارات والأقسام لرؤية المعاينة</p>
                </div>
            `;
            return;
        }

        let previewHTML = '<div class="preview-data">';
        
        if (otherServicesData.emirates.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>الإمارات (${otherServicesData.emirates.length})</h6>
                    <div class="preview-items">
                        ${otherServicesData.emirates.map(e => `<span class="preview-tag">${e.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (otherServicesData.sectionTypes.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-layer-group me-2"></i>أنواع الأقسام (${otherServicesData.sectionTypes.length})</h6>
                    <div class="preview-items">
                        ${otherServicesData.sectionTypes.map(st => `<span class="preview-tag">${st.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (otherServicesData.services.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-concierge-bell me-2"></i>الخدمات (${otherServicesData.services.length})</h6>
                    <div class="preview-items">
                        ${otherServicesData.services.map(s => `<span class="preview-tag">${s.name}</span>`).join('')}
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