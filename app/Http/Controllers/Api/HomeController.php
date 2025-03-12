<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Section;
use App\Models\Slider;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponse;


    public function slider()
    {
        $sliders = Slider::where('is_active', true)
            ->select('id', 'image_path', 'title', 'description', 'sort_order')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($slider) {
                $slider->image_path = asset('storage/' . $slider->image_path);
                return $slider;
            });

        if($sliders->isEmpty()){
            return $this->errorResponse('لا يوجد سلايدر', 404);
        }
        return $this->successResponse(
            $sliders,
            'تم جلب السلايدر بنجاح'
        );
    }

    public function getSections()
    {
        $sections = Section::select('id','name','slug','icon')->get()
            ->map(function ($slider) {
                $slider->icon = asset('storage/' . $slider->icon);
                return $slider;
            });
        ;
        if ($sections->isEmpty()) {
            return $this->errorResponse('لا يوجد اقسام', 404);
        }

        return $this->successResponse($sections, 'تمت العملية بنجاح');
    }

    public function bestSelling()
    {
        $bestSellingAds = Ad::bestSelling()
            ->select('id', 'title', 'slug', 'thumbnail_path', 'price', 'quantity_sold', 'rating')
            ->get()
            ->map(function ($ad) {
                $ad->thumbnail_path = asset('storage/'.$ad->thumbnail_path);
                return $ad;
            })
             ->take(10);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الإعلانات الأكثر مبيعًا بنجاح',
            'data' => $bestSellingAds,
        ]);
    }

}
