<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendOtpJob;
use App\Models\User;
use App\Models\UserVerification;
use App\Models\Address;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
      {
          $validated = $request->validate([
              'phone_country_code' => 'required|string|max:5',
              'phone_number' => [
                  'required',
                  'string',
                  'regex:/^[0-9]\d{6,14}$/',
                  'unique:users,phone_number,NULL,id,phone_country_code,' . $request->phone_country_code,
              ],
              'email' => 'required|email|unique:users',
              'first_name' => 'required|string|max:50',
              'last_name' => 'required|string|max:50',
              'gender' => 'nullable|in:male,female',
              'password' => 'required|string|min:8|confirmed',
              'agree_terms' => 'required|accepted',
              'avatar' => 'nullable|image|max:2048',
          ], [
              'phone_country_code.required' => 'رمز البلد مطلوب',
              'phone_number.required' => 'رقم الهاتف مطلوب',
              'phone_number.regex' => 'صيغة رقم الهاتف غير صحيحة',
              'phone_number.unique' => 'رقم الهاتف مستخدم مسبقاً',
              'email.required' => 'البريد الإلكتروني مطلوب',
              'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
              'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
              'first_name.required' => 'الاسم الأول مطلوب',
              'last_name.required' => 'الاسم الأخير مطلوب',
              'password.required' => 'كلمة المرور مطلوبة',
              'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
              'password.confirmed' => 'كلمة المرور غير متطابقة',
              'agree_terms.required' => 'يجب الموافقة على الشروط والأحكام',
              'agree_terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
              'avatar.image' => 'الصورة يجب أن تكون ملف صورة صالح',
          ]);

          try {

              $phoneNumber = ltrim(preg_replace('/[^0-9]/', '', $validated['phone_number']), '0');

              $userData = [
                  'phone_country_code' => $validated['phone_country_code'],
                  'phone_number' => $phoneNumber,
                  'email' => $validated['email'],
                  'first_name' => $validated['first_name'],
                  'last_name' => $validated['last_name'],
                  'gender' => $validated['gender'],
                  'password' => Hash::make($validated['password']),
                  'agree_terms' => true,
              ];

              if ($request->hasFile('avatar')) {
                  $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
              }
              dd($request->all());
              $user = User::create([
                  'phone_country_code' => $userData['phone_country_code'],
                  'phone_number',
                  'email',
                  'first_name',
                  'last_name',
                  'gender',
                  'avatar',
                  'agree_terms',
                  'is_email_verified',
                  'email_verified_at',
                  'password'
              ]);

              $otp = rand(1000, 9999);
              UserVerification::create([
                  'user_id' => $user->id,
                  'type' => 'email',
                  'otp' => $otp,
                  'expires_at' => now()->addMinutes(10),
              ]);


              SendOtpJob::dispatch($user->email, $otp, 'email');

              $token = $user->createToken('auth_token')->plainTextToken;

              return $this->successResponse(
                  [
                      'user' => $user,
                      'token' => $token
                  ],
                  'تم التسجيل بنجاح. الرجاء تفعيل البريد الإلكتروني',
                  201
              );

          } catch (\Exception $e) {
              DB::rollBack();
              return $this->errorResponse('فشل في عملية التسجيل', 500);
          }
      }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone_country_code' => 'required|string|max:5',
            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]\d{6,14}$/',
            ],
            'password' => 'required|string',
        ], [
            'phone_country_code.required' => 'رمز البلد مطلوب',
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $phoneNumber = ltrim(preg_replace('/[^0-9]/', '', $validated['phone_number']), '0');

        $user = User::where('phone_country_code', $validated['phone_country_code'])
            ->where('phone_number', $phoneNumber)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->errorResponse('بيانات الدخول غير صحيحة', 401);
        }

        try {
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse(
                [
                    'user' => $user,
                    'token' => $token
                ],
                'تم تسجيل الدخول بنجاح'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في تسجيل الدخول', 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
            'type' => 'required|in:email,phone'
        ], [
            'otp.required' => 'رمز التحقق مطلوب',
            'otp.size' => 'رمز التحقق يجب أن يكون 6 أرقام',
            'type.required' => 'نوع التحقق مطلوب',
            'type.in' => 'نوع التحقق غير صحيح',
        ]);

        try {
            $verification = UserVerification::where('user_id', auth()->id())
                ->where('type', $request->type)
                ->where('otp', $request->otp)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$verification) {
                return $this->errorResponse('رمز التحقق غير صحيح أو منتهي الصلاحية', 400);
            }

            $user = auth()->user();

            if ($request->type === 'email') {
                $user->is_email_verified = true;
                $user->email_verified_at = now();
            } else {
                $user->is_phone_verified = true;
                $user->phone_verified_at = now();
            }

            $user->save();

            $verification->update([
                'is_used' => true,
                'used_at' => now()
            ]);

            $message = $request->type === 'email'
                ? 'تم تفعيل البريد الإلكتروني بنجاح'
                : 'تم تفعيل رقم الهاتف بنجاح';

            return $this->successResponse(null, $message);

        } catch (\Exception $e) {
            return $this->errorResponse('فشل في عملية التحقق', 500);
        }
    }

    public function resendOtp(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:email,phone'
        ], [
            'type.required' => 'نوع التحقق مطلوب',
            'type.in' => 'نوع التحقق غير صحيح',
        ]);

        try {
            $user = auth()->user();
            $otp = $this->generateOTP();

            UserVerification::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]);

            if ($request->type === 'email') {
                SendOtpJob::dispatch($user->email, $otp, 'email');
            }

            return $this->successResponse(
                null,
                'تم إرسال رمز التحقق بنجاح'
            );

        } catch (\Exception $e) {
            return $this->errorResponse('فشل في إرسال رمز التحقق', 500);
        }
    }
    public function refreshToken(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse(
                [
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                    ],
                    'token' => $token
                ],
                'تم تجديد رمز الدخول بنجاح'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في تجديد رمز الدخول', 500);
        }
    }


}
