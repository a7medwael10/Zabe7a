<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    use ApiResponse;
    public function getOffers()
    {
        $offers = Offer::activeNow()
            ->orderBy('starts_at', 'desc')
            ->get();

        if($offers->isEmpty()){
            return $this->errorResponse('لا يوجد عروض متاحة حاليا',404);
        }

        return $this->successResponse(OfferResource::collection($offers),'تم استرجاع العروض بنجاح',200);
    }





}
