<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertySearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // البحث متاح للجميع
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // البحث العام
            'search' => 'nullable|string|max:255',
            
            // الفلترة بالسعر
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'currency' => 'nullable|string|max:3',
            
            // الفلترة بالنوع
            'property_type' => 'nullable|in:apartment,villa,townhouse,office,shop',
            'ownership_type' => 'nullable|in:freehold,leasehold,usufruct',
            'advertiser_type' => 'nullable|in:owner,broker,developer',
            'property_status' => 'nullable|in:available,sold,rented',
            'readiness_status' => 'nullable|in:ready_to_move,under_construction,off_plan',
            'finishing_level' => 'nullable|in:fully_finished,semi_finished,core_and_shell',
            
            // الفلترة بالمساحة
            'min_size' => 'nullable|numeric|min:1',
            'max_size' => 'nullable|numeric|min:1|gte:min_size',
            
            // الفلترة بعدد الغرف
            'min_bedrooms' => 'nullable|integer|min:0|max:20',
            'max_bedrooms' => 'nullable|integer|min:0|max:20|gte:min_bedrooms',
            'min_bathrooms' => 'nullable|integer|min:0|max:20',
            'max_bathrooms' => 'nullable|integer|min:0|max:20|gte:min_bathrooms',
            
            // الفلترة بالموقع
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:100', // بالكيلومتر
            'location_search' => 'nullable|string|max:255',
            
            // الفلترة بالسنة
            'min_year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'max_year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 10) . '|gte:min_year_built',
            
            // الفلترة بالطابق
            'min_floor' => 'nullable|integer|min:0|max:200',
            'max_floor' => 'nullable|integer|min:0|max:200|gte:min_floor',
            
            // المميزات والخدمات
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            
            // خيارات العرض
            'is_featured' => 'nullable|boolean',
            'with_images_only' => 'nullable|boolean',
            
            // الترتيب والصفحات
            'sort_by' => 'nullable|in:price_asc,price_desc,size_asc,size_desc,date_asc,date_desc,views_desc',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'min_price.numeric' => 'الحد الأدنى للسعر يجب أن يكون رقماً',
            'max_price.numeric' => 'الحد الأقصى للسعر يجب أن يكون رقماً',
            'max_price.gte' => 'الحد الأقصى للسعر يجب أن يكون أكبر من أو يساوي الحد الأدنى',
            
            'property_type.in' => 'نوع العقار يجب أن يكون أحد القيم المحددة',
            'ownership_type.in' => 'نوع الملكية يجب أن يكون أحد القيم المحددة',
            'advertiser_type.in' => 'نوع المعلن يجب أن يكون أحد القيم المحددة',
            'property_status.in' => 'حالة العقار يجب أن تكون أحد القيم المحددة',
            'readiness_status.in' => 'حالة الجاهزية يجب أن تكون أحد القيم المحددة',
            'finishing_level.in' => 'مستوى التشطيب يجب أن يكون أحد القيم المحددة',
            
            'min_size.numeric' => 'الحد الأدنى للمساحة يجب أن يكون رقماً',
            'max_size.numeric' => 'الحد الأقصى للمساحة يجب أن يكون رقماً',
            'max_size.gte' => 'الحد الأقصى للمساحة يجب أن يكون أكبر من أو يساوي الحد الأدنى',
            
            'min_bedrooms.integer' => 'الحد الأدنى لعدد غرف النوم يجب أن يكون رقماً صحيحاً',
            'max_bedrooms.integer' => 'الحد الأقصى لعدد غرف النوم يجب أن يكون رقماً صحيحاً',
            'max_bedrooms.gte' => 'الحد الأقصى لعدد غرف النوم يجب أن يكون أكبر من أو يساوي الحد الأدنى',
            
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
            'radius.min' => 'نطاق البحث يجب أن يكون على الأقل 0.1 كيلومتر',
            'radius.max' => 'نطاق البحث يجب ألا يتجاوز 100 كيلومتر',
            
            'sort_by.in' => 'طريقة الترتيب يجب أن تكون أحد القيم المحددة',
            'per_page.max' => 'عدد النتائج في الصفحة يجب ألا يتجاوز 100',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // تحويل البيانات النصية إلى arrays إذا لزم الأمر
        if ($this->has('features') && is_string($this->features)) {
            $this->merge([
                'features' => json_decode($this->features, true) ?: explode(',', $this->features)
            ]);
        }

        if ($this->has('amenities') && is_string($this->amenities)) {
            $this->merge([
                'amenities' => json_decode($this->amenities, true) ?: explode(',', $this->amenities)
            ]);
        }

        // تعيين القيم الافتراضية
        $this->merge([
            'per_page' => $this->per_page ?? 20,
            'page' => $this->page ?? 1,
            'sort_by' => $this->sort_by ?? 'date_desc',
        ]);
    }

    /**
     * Get the validated data with proper types.
     */
    public function getSearchFilters(): array
    {
        $validated = $this->validated();
        
        // تنظيف البيانات وإزالة القيم الفارغة
        return array_filter($validated, function ($value) {
            return $value !== null && $value !== '' && $value !== [];
        });
    }
}