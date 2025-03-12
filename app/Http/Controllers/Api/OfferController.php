<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;
    public function index()
    {
        $offers = Offer::activeNow()
            ->orderBy('starts_at', 'desc')
            ->select(
                'id',
                'title',
                'slug',
                'thumbnail_path',
                'description',
                'original_price',
                'discount_percentage',
                'offer_price',
                'gift',
                'rating'
            )
            ->get();

        if($offers->isEmpty()){
            return $this->errorResponse('لا يوجد عروض متاحة حاليا',404);
        }

        return $this->successResponse(OfferResource::collection($offers),'تم استرجاع العروض بنجاح',200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $offer = Offer::ActiveNow()
            ->where('id', $id)
            ->first();

        if (!$offer) {
            return response()->json([
                'status' => false,
                'message' => 'العرض غير موجود أو غير متاح'
            ], 404);
        }

        return $this->successResponse(new OfferResource($offer),'تم استرجاع العروض بنجاح',200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
