<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompeleteProfileRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;


class UserProfileController extends Controller
{
    use ApiResponse;
    public function completeData(CompeleteProfileRequest $request)
    {
        $validated = $request->validated();

        try {
            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
            ];

            if ($request->hasFile('avatar')) {
                $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::where('email', $request->email)->first();

            $user->update([
                'first_name'=>$userData['first_name'],
                'last_name'=>$userData['last_name'],
                'gender'=>$userData['gender'],
            ]);

            $addressData = [
                'user_id'=>$user->id,
                'label' => $validated['label'] ?? null,
                'country' => $validated['country'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'street' => $validated['street'],
                'postal_code' => $validated['postal_code'],
                'building_description' => $validated['building_description'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'is_primary' => $validated['is_primary'] ?? false,
            ];

            $address = Address::create($addressData);

            return $this->successResponse(
                 null,
                'تم اكمال بياناتك بنجاح',
                201
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('فشل في اكمال البيانات', 500);
        }
    }
}
