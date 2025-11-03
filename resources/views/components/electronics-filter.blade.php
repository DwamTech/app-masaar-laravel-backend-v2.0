<!-- Electronics Filter Component -->
<div class="row">
        <div class="col-12">
            <div class="car-filter-card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-laptop me-2"></i>
                        الإلكترونيات - Electronics Filter
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Emirate Section -->
                    <div class="filter-section mb-2">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                الإمارة - Emirate
                            </h5>
                            <button class="btn btn-primary btn-sm add-emirate-btn" id="electronics-add-emirate-btn">
                                <i class="fas fa-plus me-1"></i>
                                إضافة إمارة
                            </button>
                        </div>
                        <div class="emirates-container" id="electronics-emirates-container">
                            <!-- Emirates will be dynamically added here -->
                        </div>
                        <div class="add-emirate-form" id="electronics-add-emirate-form" style="display: none;">
                            <div class="input-group mt-2">
                                <input type="text" class="form-control" placeholder="اسم الإمارة" id="electronics-new-emirate-input">
                                <button class="btn btn-success" type="button" id="electronics-save-emirate-btn">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-secondary" type="button" id="electronics-cancel-emirate-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section Type Section -->
                    <div class="filter-section mb-2">
                        <div class="section-header">
                            <h5 class="section-title">
                                <i class="fas fa-layer-group me-2"></i>
                                نوع القسم - Section Type
                            </h5>
                        </div>
                        <div class="static-list-container">
                            <div class="static-list" id="electronics-section-types-container">
                                <!-- Section types will be dynamically added here -->
                            </div>
                            <div class="add-item-section fixed-footer">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="إضافة نوع قسم جديد" id="new-electronics-section-type-input">
                                    <button class="btn btn-success add-item-btn add-electronics-section-type-btn" type="button">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="filter-section mb-2">
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
                                <div class="static-list" id="electronics-sections-container">
                                    <!-- Sections will be dynamically added here -->
                                </div>
                                <div class="add-item-section fixed-footer">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="إضافة قسم جديد" id="new-electronics-section-input">
                                        <button class="btn btn-success add-item-btn add-electronics-section-btn" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Filter -->
                        <div class="sub-filter-section mb-2">
                            <h6 class="sub-section-title">
                                <i class="fas fa-mobile-alt me-2"></i>
                                المنتج - Product
                            </h6>
                            <div class="static-list-container">
                                <div class="static-list" id="electronics-products-container">
                                    <!-- Products will be dynamically added here -->
                                </div>
                                <div class="add-item-section fixed-footer">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="إضافة منتج جديد" id="new-electronics-product-input">
                                        <button class="btn btn-success add-item-btn add-electronics-product-btn" type="button">
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
                        <div class="preview-container" id="electronics-preview-container">
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

.emirate-item, .section-type-item, .section-item, .product-item {
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

.emirate-item:hover, .section-type-item:hover, .section-item:hover, .product-item:hover {
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
    // Electronics Filter Data Storage
    let electronicsData = {
        emirates: [],
        sectionTypes: [],
        sections: [],
        products: []
    };

    // Add Emirate functionality
    const addEmirateBtn = document.getElementById('electronics-add-emirate-btn');
    const addEmirateForm = document.getElementById('electronics-add-emirate-form');
    const newEmirateInput = document.getElementById('electronics-new-emirate-input');
    const saveEmirateBtn = document.getElementById('electronics-save-emirate-btn');
    const cancelEmirateBtn = document.getElementById('electronics-cancel-emirate-btn');
    const emiratesContainer = document.getElementById('electronics-emirates-container');

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
        electronicsData.emirates.push(emirate);
        renderEmirate(emirate);
        updatePreview();
    }

    function renderEmirate(emirate) {
        const emirateElement = document.createElement('div');
        emirateElement.className = 'emirate-item item-fade-in';
        emirateElement.innerHTML = `
            <span class="item-name">${emirate.name}</span>
            <button class="delete-btn" onclick="deleteElectronicsEmirate(${emirate.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        emiratesContainer.appendChild(emirateElement);
    }

    window.deleteElectronicsEmirate = function(id) {
        const emirateElement = emiratesContainer.querySelector(`[onclick="deleteElectronicsEmirate(${id})"]`).parentElement;
        emirateElement.classList.add('item-fade-out');
        setTimeout(() => {
            electronicsData.emirates = electronicsData.emirates.filter(e => e.id !== id);
            emirateElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Section Type functionality
    const addSectionTypeBtn = document.querySelector('.add-electronics-section-type-btn');
    const newSectionTypeInput = document.getElementById('new-electronics-section-type-input');
    const sectionTypesContainer = document.getElementById('electronics-section-types-container');

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
        electronicsData.sectionTypes.push(sectionType);
        renderSectionType(sectionType);
        updatePreview();
    }

    function renderSectionType(sectionType) {
        const sectionTypeElement = document.createElement('div');
        sectionTypeElement.className = 'section-type-item item-fade-in';
        sectionTypeElement.innerHTML = `
            <span class="item-name">${sectionType.name}</span>
            <button class="delete-btn" onclick="deleteElectronicsSectionType(${sectionType.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        sectionTypesContainer.appendChild(sectionTypeElement);
    }

    window.deleteElectronicsSectionType = function(id) {
        const sectionTypeElement = sectionTypesContainer.querySelector(`[onclick="deleteElectronicsSectionType(${id})"]`).parentElement;
        sectionTypeElement.classList.add('item-fade-out');
        setTimeout(() => {
            electronicsData.sectionTypes = electronicsData.sectionTypes.filter(st => st.id !== id);
            sectionTypeElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Section functionality
    const addSectionBtn = document.querySelector('.add-electronics-section-btn');
    const newSectionInput = document.getElementById('new-electronics-section-input');
    const sectionsContainer = document.getElementById('electronics-sections-container');

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
        electronicsData.sections.push(section);
        renderSection(section);
        updatePreview();
    }

    function renderSection(section) {
        const sectionElement = document.createElement('div');
        sectionElement.className = 'section-item item-fade-in';
        sectionElement.innerHTML = `
            <span class="item-name">${section.name}</span>
            <button class="delete-btn" onclick="deleteElectronicsSection(${section.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        sectionsContainer.appendChild(sectionElement);
    }

    window.deleteElectronicsSection = function(id) {
        const sectionElement = sectionsContainer.querySelector(`[onclick="deleteElectronicsSection(${id})"]`).parentElement;
        sectionElement.classList.add('item-fade-out');
        setTimeout(() => {
            electronicsData.sections = electronicsData.sections.filter(s => s.id !== id);
            sectionElement.remove();
            updatePreview();
        }, 300);
    };

    // Add Product functionality
    const addProductBtn = document.querySelector('.add-electronics-product-btn');
    const newProductInput = document.getElementById('new-electronics-product-input');
    const productsContainer = document.getElementById('electronics-products-container');

    addProductBtn.addEventListener('click', function() {
        const productName = newProductInput.value.trim();
        if (productName) {
            addProduct(productName);
            newProductInput.value = '';
        }
    });

    newProductInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            addProductBtn.click();
        }
    });

    function addProduct(name) {
        const product = {
            id: Date.now(),
            name: name
        };
        electronicsData.products.push(product);
        renderProduct(product);
        updatePreview();
    }

    function renderProduct(product) {
        const productElement = document.createElement('div');
        productElement.className = 'product-item item-fade-in';
        productElement.innerHTML = `
            <span class="item-name">${product.name}</span>
            <button class="delete-btn" onclick="deleteElectronicsProduct(${product.id})">
                <i class="fas fa-trash"></i>
            </button>
        `;
        productsContainer.appendChild(productElement);
    }

    window.deleteElectronicsProduct = function(id) {
        const productElement = productsContainer.querySelector(`[onclick="deleteElectronicsProduct(${id})"]`).parentElement;
        productElement.classList.add('item-fade-out');
        setTimeout(() => {
            electronicsData.products = electronicsData.products.filter(p => p.id !== id);
            productElement.remove();
            updatePreview();
        }, 300);
    };

    // Update Preview
    function updatePreview() {
        const previewContainer = document.getElementById('electronics-preview-container');
        
        if (electronicsData.emirates.length === 0 && electronicsData.sectionTypes.length === 0 && 
            electronicsData.sections.length === 0 && electronicsData.products.length === 0) {
            previewContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <p>قم بإضافة الإمارات والأقسام لرؤية المعاينة</p>
                </div>
            `;
            return;
        }

        let previewHTML = '<div class="preview-data">';
        
        if (electronicsData.emirates.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>الإمارات (${electronicsData.emirates.length})</h6>
                    <div class="preview-items">
                        ${electronicsData.emirates.map(e => `<span class="preview-tag">${e.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (electronicsData.sectionTypes.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-layer-group me-2"></i>أنواع الأقسام (${electronicsData.sectionTypes.length})</h6>
                    <div class="preview-items">
                        ${electronicsData.sectionTypes.map(st => `<span class="preview-tag">${st.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (electronicsData.sections.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-layer-group me-2"></i>الأقسام (${electronicsData.sections.length})</h6>
                    <div class="preview-items">
                        ${electronicsData.sections.map(s => `<span class="preview-tag">${s.name}</span>`).join('')}
                    </div>
                </div>
            `;
        }

        if (electronicsData.products.length > 0) {
            previewHTML += `
                <div class="preview-section-item">
                    <h6><i class="fas fa-mobile-alt me-2"></i>المنتجات (${electronicsData.products.length})</h6>
                    <div class="preview-items">
                        ${electronicsData.products.map(p => `<span class="preview-tag">${p.name}</span>`).join('')}
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