<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AdController extends Controller
{
    use ApiResponse;
    public function getAdsByCategory(Request $request, $categoryId)
    {
        $adsQuery = Ad::where('category_id', $categoryId);

        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'best_selling':
                    $adsQuery->bestSelling();
                    break;
                case 'highest_rated':
                    $adsQuery->highestRated();
                    break;
                case 'price_high_to_low':
                    $adsQuery->priceHighToLow();
                    break;
                case 'price_low_to_high':
                    $adsQuery->priceLowToHigh();
                    break;
                case 'our_suggestions':
                    $adsQuery->ourSuggestions();
                    break;
                default:
                    $adsQuery->latest();
                    break;
            }
        } else {
            $adsQuery->latest();
        }

        $ads = $adsQuery->get();

        if ($ads->isEmpty()) {
            return $this->errorResponse('لا يوجد منتجات في هذا التصنيف داخل القسم', 404);
        }

        return $this->successResponse(
             AdResource::collection($ads),  'تمت العملية بنجاح');
    }



    public function suggestions()
    {
        $ads = Ad::highestRated()->limit(10)->get();

        if ($ads->isEmpty()) {
            return $this->errorResponse('لا يوجد منتجات', 404);
        }

        return $this->successResponse(AdResource::collection($ads), 'تمت العملية بنجاح');

    }
}
