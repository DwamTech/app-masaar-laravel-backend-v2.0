<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nationality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope للجنسيات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للترتيب
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    /**
     * علاقة مع التصاريح الأمنية
     */
    public function securityPermits()
    {
        return $this->hasMany(SecurityPermit::class);
    }

    /**
     * الحصول على الاسم حسب اللغة
     */
    public function getName($locale = 'ar')
    {
        return $locale === 'en' ? $this->name_en : $this->name_ar;
    }
}