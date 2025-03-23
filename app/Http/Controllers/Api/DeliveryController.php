<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCompany;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    use ApiResponse;
    public function getCompanies()
    {
        $companies = DeliveryCompany::select('id', 'name', 'logo')->get();

        return $this->successResponse($companies, 'تم بنجاح',200);
    }
}
