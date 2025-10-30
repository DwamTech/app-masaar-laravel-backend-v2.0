<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\User;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoritesController extends Controller
{
    /**
     * List user's favorites with minimal fields per type.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $favorites = Favorite::with('favoritable')
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        $items = $favorites->map(function (Favorite $fav) {
            $type = $fav->typeAlias();
            return $this->transformItem($fav->favoritable, $type);
        })->filter()->values();

        return response()->json(['status' => true, 'favorites' => $items]);
    }

    /**
     * Add a favorite for the current user (id + type).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:restaurant,property',
        ]);

        $user = $request->user();
        [$typeAlias, $modelClass, $id] = $this->resolveModelClassAndId($validated['type'], (int) $validated['id']);
        $model = $modelClass::findOrFail($id);

        if ($typeAlias === 'restaurant' && ($model instanceof User) && $model->user_type !== 'restaurant') {
            return response()->json(['status' => false, 'message' => 'المعرف ليس مطعم'], 422);
        }

        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'favoritable_type' => $modelClass, // store fully qualified model class
            'favoritable_id' => $model->getKey(),
        ]);

        return response()->json([
            'status' => true,
            'favorite' => $this->transformItem($model, $typeAlias),
        ]);
    }

    /**
     * Remove a favorite (id + type).
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:restaurant,property',
        ]);

        $user = $request->user();
        [$typeAlias, $modelClass, $id] = $this->resolveModelClassAndId($validated['type'], (int) $validated['id']);

        $deleted = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', $modelClass)
            ->where('favoritable_id', $id)
            ->delete();

        return response()->json(['status' => true, 'deleted' => (bool) $deleted]);
    }

    /**
     * Check if specific item is favorited.
     */
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:restaurant,property',
        ]);

        $user = $request->user();
        [$typeAlias, $modelClass, $id] = $this->resolveModelClassAndId($validated['type'], (int) $validated['id']);

        $exists = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', $modelClass)
            ->where('favoritable_id', $id)
            ->exists();

        return response()->json(['status' => true, 'is_favorite' => $exists]);
    }

    /**
     * Get minimal info for a specific item by id + type (no need to be favorited).
     */
    public function item(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:restaurant,property',
        ]);

        [$typeAlias, $modelClass, $id] = $this->resolveModelClassAndId($validated['type'], (int) $validated['id']);
        $model = $modelClass::findOrFail($id);
        if ($typeAlias === 'restaurant' && ($model instanceof User) && $model->user_type !== 'restaurant') {
            return response()->json(['status' => false, 'message' => 'غير موجود كمطعم'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $this->transformItem($model, $typeAlias),
        ]);
    }

    /**
     * Map type to model class and expected id.
     * For restaurants, the id is the User id for the restaurant account.
     */
    private function resolveModelClassAndId(string $type, int $id): array
    {
        if ($type === 'restaurant') {
            return ['restaurant', User::class, $id];
        }
        if ($type === 'property') {
            return ['property', Property::class, $id];
        }
        abort(422, 'نوع غير صالح');
    }

    /**
     * Transform an item to minimal fields based on type requirements.
     */
    private function transformItem($model, string $type): array
    {
        if ($type === 'restaurant' && $model instanceof User) {
            $detail = $model->restaurantDetail; // may be null
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

        return [];
    }
}