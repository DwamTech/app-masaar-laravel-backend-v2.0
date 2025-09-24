<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecurityPermitSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
        'is_editable',
    ];

    protected $casts = [
        'is_editable' => 'boolean',
    ];

    /**
     * الحصول على قيمة الإعداد مع التحويل المناسب
     */
    public function getValue()
    {
        switch ($this->type) {
            case 'number':
                return (float) $this->value;
            case 'boolean':
                return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    /**
     * تعيين قيمة الإعداد مع التحويل المناسب
     */
    public function setValue($value)
    {
        switch ($this->type) {
            case 'json':
                $this->value = json_encode($value);
                break;
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            default:
                $this->value = (string) $value;
        }
    }

    /**
     * Scope للإعدادات القابلة للتعديل
     */
    public function scopeEditable($query)
    {
        return $query->where('is_editable', true);
    }

    /**
     * Scope للمجموعة
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * الحصول على إعداد بالمفتاح
     */
    public static function getSetting($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->getValue() : $default;
    }

    /**
     * تعيين إعداد بالمفتاح
     */
    public static function setSetting($key, $value, $type = 'string')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'type' => $type,
                'group' => 'general',
                'is_editable' => true,
            ]
        );
        
        $setting->setValue($value);
        $setting->save();
        
        return $setting;
    }
}