<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SecurityPermitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ],
            'travel_date' => $this->travel_date->format('Y-m-d'),
            'travel_date_formatted' => $this->travel_date->format('d/m/Y'),
            'nationality' => [
                'id' => $this->nationality_id,
                'name_ar' => $this->nationality?->name_ar,
                'name_en' => $this->nationality?->name_en,
                'code' => $this->nationality?->code,
            ],
            'country' => [
                'id' => $this->country_id,
                'name_ar' => $this->country?->name_ar,
                'name_en' => $this->country?->name_en,
                'code' => $this->country?->code,
            ],
            'people_count' => $this->people_count,
            'passport_image' => $this->passport_image,
            'residence_images' => $this->residence_images ?? [],
            'payment_method' => $this->payment_method,
            'payment_method_label' => $this->getPaymentMethodLabel(),
            'individual_fee' => $this->individual_fee,
            'total_amount' => $this->total_amount,
            'calculated_total' => $this->calculateTotalAmount(),
            'status' => $this->status,
            'status_label' => $this->status_label,
            'payment_status' => $this->payment_status,
            'payment_status_label' => $this->payment_status_label,
            'payment_reference' => $this->payment_reference,
            'notes' => $this->notes,
            'admin_notes' => $this->admin_notes,
            'processed_at' => $this->processed_at?->format('Y-m-d H:i:s'),
            'processed_at_formatted' => $this->processed_at?->format('d/m/Y H:i'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_formatted' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // معلومات إضافية
            'can_edit' => $this->canEdit(),
            'can_cancel' => $this->canCancel(),
            'days_since_created' => $this->created_at->diffInDays(now()),
        ];
    }

    /**
     * تسمية طريقة الدفع
     */
    private function getPaymentMethodLabel()
    {
        $labels = [
            'credit_card' => 'بطاقة ائتمان',
            'digital_wallet' => 'محفظة إلكترونية',
        ];

        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * التحقق من إمكانية التعديل
     */
    private function canEdit()
    {
        return in_array($this->status, ['new', 'pending']);
    }

    /**
     * التحقق من إمكانية الإلغاء
     */
    private function canCancel()
    {
        return $this->status === 'new';
    }
}