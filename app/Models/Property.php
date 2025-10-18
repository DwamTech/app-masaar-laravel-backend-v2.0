<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Property extends Model
{
    protected $fillable = [
        'title',
        'ownership_type',
        'property_price',
        'down_payment',
        'property_code',
        'view_count',
        'advertiser_type',
        'contact_info',
        'location',
        'description',
        'bedrooms',
        'bathrooms',
        'size_in_sqm',
        'finishing_level',
        'floor_number',
        'overlooking',
        'year_built',
        'price_per_square_meter',
        'payment_method',
        'property_status',
        'developer_name',
        'logo_url',
        'features',
        'amenities',
        'main_image',
        'gallery_image_urls',
        'property_type',
        'readiness_status',
        'currency',
        'is_featured',
        'address',
        'old_type',
        'type',
        'price',
        'image_url',
        'view',
        'area',
        'submitted_by',
        'submitted_price',
        'is_ready',
        'real_estate_id',
        'user_id'
    ];

    protected $casts = [
        'contact_info' => 'array',
        'location' => 'array',
        'features' => 'array',
        'amenities' => 'array',
        'gallery_image_urls' => 'array',
        'property_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'size_in_sqm' => 'decimal:2',
        'price_per_square_meter' => 'decimal:2',
        'view_count' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'floor_number' => 'integer',
        'year_built' => 'integer',
        'is_featured' => 'boolean',
        'is_ready' => 'boolean',
        'the_best' => 'boolean',
    ];

    // علاقة العقار بمقدم الخدمة
    public function realEstate()
    {
        return $this->belongsTo(RealEstate::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors & Mutators
    protected function contactInfo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_string($value) ? json_decode($value, true) : $value,
            set: fn ($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_string($value) ? json_decode($value, true) : $value,
            set: fn ($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('property_status', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeByAdvertiserType($query, $advertiserType)
    {
        return $query->where('advertiser_type', $advertiserType);
    }

    // Helper Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->property_price, 2) . ' ' . $this->currency;
    }

    public function getMainContactPhoneAttribute()
    {
        return $this->contact_info['phone'] ?? null;
    }

    public function getFormattedAddressAttribute()
    {
        return $this->location['formattedAddress'] ?? null;
    }

    public function getCoordinatesAttribute()
    {
        return [
            'latitude' => $this->location['latitude'] ?? null,
            'longitude' => $this->location['longitude'] ?? null,
        ];
    }

    // Boot method لتعيين القيم التلقائية
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            // تعيين property_code تلقائياً إذا لم يتم تحديده
            if (empty($property->property_code)) {
                $property->property_code = 'PROP-' . strtoupper(uniqid());
            }

            // تعيين advertiser_type بناءً على نوع المستخدم
            if ($property->user && $property->user->realEstate) {
                $realEstateType = $property->user->realEstate->type;
                $property->advertiser_type = $realEstateType === 'office' ? 'broker' : 'developer';
                
                // تعيين developer_name و logo_url
                if ($realEstateType === 'office' && $property->user->realEstate->officeDetail) {
                    $property->developer_name = $property->user->realEstate->officeDetail->office_name;
                    $property->logo_url = $property->user->realEstate->officeDetail->logo_image;
                } elseif ($realEstateType === 'individual' && $property->user->realEstate->individualDetail) {
                    $property->developer_name = $property->user->realEstate->individualDetail->agent_name;
                    $property->logo_url = $property->user->realEstate->individualDetail->profile_image;
                }
            }

            // حساب price_per_square_meter تلقائياً
            if ($property->property_price && $property->size_in_sqm) {
                $property->price_per_square_meter = $property->property_price / $property->size_in_sqm;
            }
        });

        static::updating(function ($property) {
            // إعادة حساب price_per_square_meter عند التحديث
            if ($property->isDirty(['property_price', 'size_in_sqm']) && $property->property_price && $property->size_in_sqm) {
                $property->price_per_square_meter = $property->property_price / $property->size_in_sqm;
            }
        });
    }
}
