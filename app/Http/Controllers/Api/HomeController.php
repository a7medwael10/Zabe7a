<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Http\Resources\OfferResource;
use App\Models\Ad;
use App\Models\Offer;
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
            ->select('id', 'image_path', 'title', 'description', 'sort_order','offer_id')
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
            ->get()
             ->take(10);

        return $this->successResponse(
            AdResource::collection($bestSellingAds),
            'تم جلب الإعلانات الأكثر مبيعًا بنجاح'
        ,200);
    }

    public function showAdOrOffer(string $type, string $id)
    {


        if ($type === 'ad') {
            $ad = Ad::find($id);

            if (!$ad) {
                return $this->errorResponse('الإعلان غير موجود', 404);
            }

            return $this->successResponse(
                 new AdResource($ad), 'تمت العملية بنجاح');

        } elseif ($type === 'offer') {
            $offer = Offer::find($id);

            if (!$offer) {
                return $this->errorResponse('العرض غير موجود', 404);
            }

            return $this->successResponse(
                new OfferResource($offer)
            , 'تمت العملية بنجاح');
        }

        return $this->errorResponse('نوع غير صالح', 400);
    }


}
