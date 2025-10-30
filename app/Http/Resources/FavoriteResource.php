<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Property;
use App\Models\MenuItem;

class FavoriteResource extends JsonResource
{
    /**
     * @param Favorite $resource
     */
    public function toArray($request)
    {
        /** @var Favorite $fav */
        $fav = $this->resource;
        $type = $fav->typeAlias();
        $model = $fav->favoritable;

        if ($type === 'restaurant' && $model instanceof User) {
            $detail = $model->restaurantDetail;
            $name = $detail?->restaurant_name ?? $model->name;
            $image = $detail?->logo_image ?? $detail?->profile_image ?? $model->avatar ?? null;
            $mealsCount = $detail ? MenuItem::where('restaurant_id', $detail->id)->count() : 0;

            return [
                'type' => 'restaurant',
                'id' => $model->id,
                'name' => $name,
                'image' => $image,
                'meals_count' => $mealsCount,
            ];
        }

        if ($type === 'property' && $model instanceof Property) {
            $image = $model->image_url
                ?? $model->main_image
                ?? (is_array($model->gallery_image_urls) && count($model->gallery_image_urls) ? $model->gallery_image_urls[0] : null);

            $location = [
                'latitude' => $model->location['latitude'] ?? null,
                'longitude' => $model->location['longitude'] ?? null,
                'formatted_address' => $model->formatted_address
                    ?? ($model->location['formatted_address'] ?? ($model->location['formattedAddress'] ?? null)),
            ];

            return [
                'type' => 'property',
                'id' => $model->id,
                'views' => $model->view_count ?? 0,
                'address' => $model->address ?? $location['formatted_address'],
                'image' => $image,
                'location' => $location,
            ];
        }

        return [
            'type' => $fav->favoritable_type,
            'id' => $fav->favoritable_id,
        ];
    }
}