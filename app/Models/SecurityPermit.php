<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Notifier;
use Illuminate\Support\Facades\Log;

class SecurityPermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_date',
        'nationality',
        'nationality_id',
        'people_count',
        'coming_from',
        'country_id',
        'passport_image',
        'other_document_image',
        'residence_images',
        'payment_method',
        'individual_fee',
        'total_amount',
        'payment_status',
        'payment_reference',
        'status',
        'notes',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'residence_images' => 'array',
        'individual_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'people_count' => 'integer',
    ];

    protected $appends = [
        'status_label',
        'payment_status_label',
    ];

    /**
     * علاقة التصريح بالمستخدم (صاحب الطلب)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة مع الدولة
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * علاقة مع الجنسية
     */
    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    /**
     * تسميات الحالات
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'new' => 'جديد',
            'pending' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            'expired' => 'منتهي الصلاحية',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * تسميات حالة الدفع
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'في انتظار الدفع',
            'paid' => 'تم الدفع',
            'failed' => 'فشل الدفع',
            'refunded' => 'تم الاسترداد',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Scope للحالة
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope للمستخدم
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * حساب المبلغ الإجمالي
     */
    public function calculateTotalAmount()
    {
        if ($this->individual_fee && $this->people_count) {
            return $this->individual_fee * $this->people_count;
        }
        return 0;
    }

    /**
     * تحديث حالة الطلب مع إرسال إشعار
     */
    public function updateStatus($newStatus, $adminNotes = null)
    {
        $oldStatus = $this->status;
        
        $this->update([
            'status' => $newStatus,
            'admin_notes' => $adminNotes,
            'processed_at' => now(),
        ]);

        // إرسال إشعار للمستخدم عند تغيير الحالة
        $this->sendStatusNotification($oldStatus, $newStatus);

        return $this;
    }

    /**
     * إرسال إشعار تغيير الحالة
     */
    protected function sendStatusNotification($oldStatus, $newStatus)
    {
        // إذا لم يكن هناك مستخدم مرتبط (حالة بيانات قديمة أو حذف المستخدم)، نتخطى الإشعار لتجنب الأخطاء
        if (!$this->user) {
            // تسجيل تحذير للمراجعة دون إيقاف العملية
            if (function_exists('logger')) {
                logger()->warning('SecurityPermit: Skipping notification, user relation missing', [
                    'permit_id' => $this->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]);
            } else {
                Log::warning('SecurityPermit: Skipping notification, user relation missing', [
                    'permit_id' => $this->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]);
            }
            return;
        }

        $messages = [
            'pending' => [
                'title' => 'طلب التصريح الأمني قيد المراجعة',
                'body' => 'طلب التصريح الأمني الخاص بك رقم #' . $this->id . ' قيد المراجعة من قبل الإدارة.',
            ],
            'approved' => [
                'title' => 'تم قبول طلب التصريح الأمني',
                'body' => 'تم قبول طلب التصريح الأمني الخاص بك رقم #' . $this->id . '. يمكنك الآن المتابعة.',
            ],
            'rejected' => [
                'title' => 'تم رفض طلب التصريح الأمني',
                'body' => 'تم رفض طلب التصريح الأمني الخاص بك رقم #' . $this->id . '. يرجى مراجعة البيانات وإعادة التقديم.',
            ],
            'expired' => [
                'title' => 'انتهت صلاحية التصريح الأمني',
                'body' => 'انتهت صلاحية التصريح الأمني رقم #' . $this->id . '.',
            ],
        ];

        if (isset($messages[$newStatus])) {
            Notifier::send(
                $this->user,
                'security_permit_status_' . $newStatus,
                $messages[$newStatus]['title'],
                $messages[$newStatus]['body']
            );
        }
    }

    /**
     * إرسال إشعار للإدارة عند إنشاء طلب جديد
     */
    public function notifyAdminOfNewRequest()
    {
        // البحث عن المدراء
        $admins = User::where('user_type', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notifier::send(
                $admin,
                'new_security_permit_request',
                'طلب تصريح أمني جديد',
                'تم تقديم طلب تصريح أمني جديد رقم #' . $this->id . ' من المستخدم ' . $this->user->name
            );
        }
    }
}
