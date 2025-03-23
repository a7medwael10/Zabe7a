<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Favourite;
use App\Models\Offer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class favouriteController extends Controller
{
    use ApiResponse;
    public function toggleFavourite(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:ad,offer',
        ]);

        $modelType = $request->type === 'ad' ? Ad::class : Offer::class;

        $favourite = Favourite::where('user_id', $user->id)
            ->where('favouriteable_id', $request->id)
            ->where('favouriteable_type', $modelType)
            ->first();

        if ($favourite) {
            $favourite->delete();

            return $this->successResponse(null, 'تم الحذف من المفضلة بنجاح');
        } else {
            Favourite::create([
                'user_id' => $user->id,
                'favouriteable_id' => $request->id,
                'favouriteable_type' => $modelType,
            ]);

            return $this->successResponse(null, 'تمت الإضافة إلى المفضلة بنجاح');
        }
    }

    public function userFavourite()
    {
        $user = auth()->user();

        $favourites = Favourite::where('user_id', $user->id)
            ->with('favouriteable')
            ->get()
            ->map(function ($favourite) {
               if ($favourite->favouriteable_type === Ad::class) {
                   return [
                       'id' => $favourite->favouriteable->id,
                       'type' => 'ad',
                       'title' => $favourite->favouriteable->title,
                       'thumbnail_path' => $favourite->favouriteable->thumbnail_path,
                       'price' => $favourite->favouriteable->price,
                       'weight' => $favourite->favouriteable->weight,
                       'rate' => $favourite->favouriteable->rating,
                   ];
               }
               else{
                   return [
                          'id' => $favourite->favouriteable->id,
                          'type' => 'offer',
                          'title' => $favourite->favouriteable->title,
                          'thumbnail_path' => $favourite->favouriteable->thumbnail_path,
                          'price_before' => $favourite->favouriteable->original_price,
                          'discount' => $favourite->favouriteable->discount_percentage.'%',
                          'price_after' => $favourite->favouriteable->offer_price,
                          'weight' => $favourite->favouriteable->weight,
                          'rate' => $favourite->favouriteable->rating,
                   ];
               }
            });

        return $this->successResponse($favourites);
    }

}
