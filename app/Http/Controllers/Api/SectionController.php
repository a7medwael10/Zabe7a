<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Section;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function getCategoriesOfSection(string $id)
    {
        $section = Section::select('id', 'name', 'slug')->find($id);

        if (!$section) {
            return $this->errorResponse('القسم غير موجود', 404);
        }

      $categories = $section->categories()
            ->select('id', 'name', 'slug')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($categories->isEmpty()) {
            return $this->errorResponse('لا يوجد أصناف مرتبطة بهذا القسم', 404);
        }

        return $this->successResponse([
            'section' => $section,
            'categories' => $categories
        ], 'تمت العملية بنجاح');

    }

    public function getAdsOfSection(Request $request, string $sectionId)
    {
        $section = Section::find($sectionId);

        if (!$section) {
            return $this->errorResponse('القسم غير موجود', 404);
        }

        $adsQuery = Ad::whereHas('category', function ($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
        });

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

        return $this->successResponse([
            'section' => $section->only(['name']),
            'ads' => AdResource::collection($ads),
        ], 'تمت العملية بنجاح');
    }


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

        return $this->successResponse([
            'ads' => AdResource::collection($ads),
        ], 'تمت العملية بنجاح');
    }

    public function showAd(string $id)
    {
        $ad = Ad::find($id);

        if (!$ad) {
            return $this->errorResponse('المنتج غير موجود', 404);
        }

        return $this->successResponse([
            'ad' => new ADResource($ad),
        ], 'تمت العملية بنجاح');
    }

}
