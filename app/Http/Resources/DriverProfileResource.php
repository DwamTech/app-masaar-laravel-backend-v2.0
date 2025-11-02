<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DriverProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $primaryCar = $this->driverCars->first();
        $detail = $this->carRental?->driverDetail;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_image' => $this->profile_image,
            'profile_image_url' => $this->profile_image ? Storage::url($this->profile_image) : null,
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
            'phone' => $this->phone,
            'car_info' => $primaryCar ? [
                'car_type' => $primaryCar->car_type,
                'car_model' => $primaryCar->car_model,
                'car_color' => $primaryCar->car_color,
                'license_plate' => $primaryCar->car_plate_number,
            ] : null,
            'driver_details' => $detail ? [
                'cost_per_km' => $detail->cost_per_km,
                'daily_driver_cost' => $detail->daily_driver_cost,
                'max_km_per_day' => $detail->max_km_per_day,
                'payment_methods' => $detail->payment_methods,
                'rental_options' => $detail->rental_options,
            ] : null,
        ];
    }
}