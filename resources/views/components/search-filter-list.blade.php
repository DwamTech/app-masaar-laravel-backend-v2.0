@php
    // Fallback for missing parameters to avoid breaking the page completely.
    $dataFile = $dataFile ?? '';
    $listType = $listType ?? '';
    $fieldName = $fieldName ?? '';
    $listName = $listName ?? 'Unnamed List';

    $items = [];
    if ($dataFile && $listType && $fieldName) {
        $path = resource_path('views/data/' . $dataFile);
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            $listKey = $listType . 'Settings';
            
            // Check if the expected keys exist
            if (isset($data[$listKey]) && isset($data[$listKey][$fieldName])) {
                $fieldData = $data[$listKey][$fieldName];

                // Handle old structure (array of strings under 'en' key)
                if (isset($fieldData['en']) && is_array($fieldData['en'])) {
                    $items = $fieldData['en'];
                } 
                // Handle new structure (array of objects with 'text' key)
                elseif (is_array($fieldData) && !empty($fieldData) && isset($fieldData[0]['text'])) {
                    $items = array_column($fieldData, 'text');
                }
            }
        }
    }
@endphp

<div class="list-section-wrapper mb-3">
    <label class="form-label fixed-header">{{ $listName }}</label>
    <div class="static-list-container english-list">
        <div class="static-list">
            @forelse($items as $item)
                <div class="list-item english-item" data-value="{{ $item }}">
                    <div class="item-actions">
                        <button class="btn btn-sm btn-warning edit-btn" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <span class="item-text">{{ $item }}</span>
                </div>
            @empty
                <div class="list-item">
                    <span class="item-text text-muted">No items found.</span>
                </div>
            @endforelse
        </div>
        <div class="add-item-section fixed-footer">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Add new item">
                <button class="btn btn-success add-item-btn" type="button">
                    <i class="fas fa-plus">+</i>
                </button>
            </div>
        </div>
    </div>
</div>