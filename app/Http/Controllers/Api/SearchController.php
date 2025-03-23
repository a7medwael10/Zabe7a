<?php

namespace App\Http\Controllers\Api;

use App\Filament\Resources\AdResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Ad;

class SearchController extends Controller
{
    use ApiResponse;
    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = [];

        $offers = Offer::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();

        foreach ($offers as $offer) {
            $results[] = new OfferResource($offer);
        }


        $ads = Ad::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();

        foreach ($ads as $ad) {
            $results[] = new AdResource($ad);
        }

        return $this->successResponse($results, 'تم ارجاع النتائج بنجاح', 200);
    }
}
