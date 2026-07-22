<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Property $property): JsonResponse
    {
        $favorite = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->where('property_id', $property->id)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'favorited' => false,
                'message' => 'Property removed from favorites.',
            ]);
        }

        Favorite::query()->create([
            'user_id' => $request->user()->id,
            'property_id' => $property->id,
        ]);

        return response()->json([
            'favorited' => true,
            'message' => 'Property added to favorites.',
        ]);
    }
}
