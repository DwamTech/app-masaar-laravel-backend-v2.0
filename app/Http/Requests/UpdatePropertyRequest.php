<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $propertyId = $this->route('property') ?? $this->route('id');
        
        return [
            // الحقول الأساسية
            'title' => 'sometimes|required|string|max:255',
            'ownership_type' => 'sometimes|required|in:freehold,leasehold,usufruct',
            'property_price' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|max:3',
            'property_code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('properties', 'property_code')->ignore($propertyId)
            ],
            'advertiser_type' => 'sometimes|required|in:owner,broker,developer',
            'listing_purpose' => 'sometimes|required|in:sale,rent',
            
            // معلومات الاتصال
            'contact_info' => 'sometimes|required|array',
            'contact_info.phone' => 'required_with:contact_info|string|max:20',
            'contact_info.email' => 'nullable|email|max:255',
            'contact_info.whatsapp' => 'nullable|string|max:20',
            
            // الموقع
            'location' => 'sometimes|required|array',
            'location.latitude' => 'required_with:location|numeric|between:-90,90',
            'location.longitude' => 'required_with:location|numeric|between:-180,180',
            'location.formatted_address' => 'required_with:location|string|max:500',
            
            // تفاصيل العقار
            'bedrooms' => 'sometimes|required|integer|min:0|max:20',
            'bathrooms' => 'sometimes|required|integer|min:0|max:20',
            'size_in_sqm' => 'sometimes|required|numeric|min:1',
            'property_status' => 'sometimes|required|in:available,sold,rented',
            'property_type' => 'sometimes|required|in:apartment,villa,townhouse,office,shop',
            
            // الحقول الاختيارية
            'description' => 'nullable|string|max:2000',
            'down_payment' => 'nullable|numeric|min:0',
            'finishing_level' => 'nullable|in:fully_finished,semi_finished,core_and_shell',
            'floor_number' => 'nullable|integer|min:0|max:200',
            'overlooking' => 'nullable|string|max:255',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'payment_method' => 'nullable|string|max:255',
            'developer_name' => 'nullable|string|max:255',
            'logo_url' => 'nullable|url|max:500',
            'readiness_status' => 'nullable|in:ready_to_move,under_construction,off_plan',
            'is_featured' => 'nullable|boolean',
            
            // المميزات والخدمات
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            
            // الصور (اختيارية في التحديث)
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            
            // إزالة صور من المعرض
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'string|url',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان العقار مطلوب',
            'ownership_type.required' => 'نوع الملكية مطلوب',
            'ownership_type.in' => 'نوع الملكية يجب أن يكون أحد القيم المحددة',
            'property_price.required' => 'سعر العقار مطلوب',
            'property_price.numeric' => 'سعر العقار يجب أن يكون رقماً',
            'property_price.min' => 'سعر العقار يجب أن يكون أكبر من صفر',
            'currency.required' => 'العملة مطلوبة',
            'advertiser_type.required' => 'نوع المعلن مطلوب',
            'advertiser_type.in' => 'نوع المعلن يجب أن يكون أحد القيم المحددة',
            'listing_purpose.required' => 'الغرض من العرض (بيع/إيجار) مطلوب',
            'listing_purpose.in' => 'الغرض يجب أن يكون إما sale أو rent',
            
            'contact_info.required' => 'معلومات الاتصال مطلوبة',
            'contact_info.phone.required_with' => 'رقم الهاتف مطلوب',
            'contact_info.email.email' => 'البريد الإلكتروني غير صحيح',
            
            'location.required' => 'الموقع مطلوب',
            'location.latitude.required_with' => 'خط العرض مطلوب',
            'location.longitude.required_with' => 'خط الطول مطلوب',
            'location.formatted_address.required_with' => 'العنوان المنسق مطلوب',
            
            'bedrooms.required' => 'عدد غرف النوم مطلوب',
            'bedrooms.integer' => 'عدد غرف النوم يجب أن يكون رقماً صحيحاً',
            'bathrooms.required' => 'عدد دورات المياه مطلوب',
            'bathrooms.integer' => 'عدد دورات المياه يجب أن يكون رقماً صحيحاً',
            'size_in_sqm.required' => 'المساحة بالمتر المربع مطلوبة',
            'size_in_sqm.numeric' => 'المساحة يجب أن تكون رقماً',
            'property_status.required' => 'حالة العقار مطلوبة',
            'property_type.required' => 'نوع العقار مطلوب',
            
            'main_image.image' => 'الصورة الرئيسية يجب أن تكون صورة صحيحة',
            'main_image.mimes' => 'الصورة الرئيسية يجب أن تكون من نوع: jpeg, png, jpg, gif',
            'main_image.max' => 'حجم الصورة الرئيسية يجب ألا يتجاوز 5 ميجابايت',
            
            'gallery_images.*.image' => 'جميع صور المعرض يجب أن تكون صور صحيحة',
            'gallery_images.*.mimes' => 'صور المعرض يجب أن تكون من نوع: jpeg, png, jpg, gif',
            'gallery_images.*.max' => 'حجم كل صورة في المعرض يجب ألا يتجاوز 5 ميجابايت',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // تحويل البيانات النصية إلى arrays إذا لزم الأمر
        if ($this->has('contact_info') && is_string($this->contact_info)) {
            $this->merge([
                'contact_info' => json_decode($this->contact_info, true)
            ]);
        }

        if ($this->has('location') && is_string($this->location)) {
            $this->merge([
                'location' => json_decode($this->location, true)
            ]);
        }

        if ($this->has('features') && is_string($this->features)) {
            $this->merge([
                'features' => json_decode($this->features, true)
            ]);
        }

        if ($this->has('amenities') && is_string($this->amenities)) {
            $this->merge([
                'amenities' => json_decode($this->amenities, true)
            ]);
        }

        if ($this->has('remove_gallery_images') && is_string($this->remove_gallery_images)) {
            $this->merge([
                'remove_gallery_images' => json_decode($this->remove_gallery_images, true)
            ]);
        }
    }
}