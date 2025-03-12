<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendOtpJob;
use App\Models\User;
use App\Models\UserVerification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
      {
          $validated = $request->validated();

          try {

              $phoneNumber = ltrim(preg_replace('/[^0-9]/', '', $validated['phone_number']), '0');

              $userData = [
                  'phone_country_code' => '+966',
                  'phone_number' => $phoneNumber,
                  'email' => $validated['email'],
                  'agree_terms' => true,
              ];

              $user = User::create([
                  'phone_country_code' => $userData['phone_country_code'],
                  'phone_number'=>$userData['phone_number'],
                  'email'=>$userData['email'],
                  'agree_terms'=>$userData['agree_terms'],
                  'password'=> Hash::make($validated['password'])

              ]);

              $otp = rand(1000, 9999);
              UserVerification::create([
                  'user_id' => $user->id,
                  'type' => 'email',
                  'otp' => $otp,
                  'expires_at' => now()->addMinutes(10),
              ]);


              SendOtpJob::dispatch($user->email, $otp, 'email');


              return $this->successResponse(
                  [
                      'user' => $userData,
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
            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]\d{6,14}$/',
            ],
            'password' => 'required|string',
        ],
            [
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]
        );
        $phoneNumber = ltrim(preg_replace('/[^0-9]/', '', $validated['phone_number']), '0');


        if (!Auth::attempt(['phone_number' => $phoneNumber, 'password' => $validated['password']])) {
            return $this->errorResponse('بيانات الدخول غير صحيحة', 401);
        }

        $user = User::where('phone_number', $phoneNumber)
            ->first();

        if(!$user->is_email_verified){
            return $this->errorResponse('الرجاء تفعيل البريد الإلكتروني', 401);
        }

        try {
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse(
                [
                    'token' => $token
                ],
                'تم تسجيل الدخول بنجاح'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في تسجيل الدخول', 500);
        }
    }

//    public function verifyOtp(Request $request)
//    {
//
//        $validated = $request->validate([
//            'otp' => 'required|string|size:4',
//            'type' => 'required|in:email,phone'
//        ], [
//            'otp.required' => 'رمز التحقق مطلوب',
//            'otp.size' => 'رمز التحقق يجب أن يكون 4 أرقام',
//            'type.required' => 'نوع التحقق مطلوب',
//            'type.in' => 'نوع التحقق غير صحيح',
//        ]);
//        try {
//            $verification = UserVerification::where('user_id', auth()->id())
//                ->where('type', $request->type)
//                ->where('otp', $request->otp)
//                ->where('is_used', false)
//                ->where('expires_at', '>', now())
//                ->first();
//
//            if (!$verification) {
//                return $this->errorResponse('رمز التحقق غير صحيح أو منتهي الصلاحية', 400);
//            }
//
//            $user = auth()->user();
//
//            if ($request->type === 'email') {
//                $user->is_email_verified = true;
//                $user->email_verified_at = now();
//            } else {
//                $user->is_phone_verified = true;
//                $user->phone_verified_at = now();
//            }
//
//            $user->save();
//
//            $verification->update([
//                'is_used' => true,
//                'used_at' => now()
//            ]);
//
//            $message = $request->type === 'email'
//                ? 'تم تفعيل البريد الإلكتروني بنجاح'
//                : 'تم تفعيل رقم الهاتف بنجاح';
//
//            return $this->successResponse(null, $message);
//
//        } catch (\Exception $e) {
//            return $this->errorResponse('فشل في عملية التحقق', 500);
//        }
//    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|numeric|digits:4',
            'type' => 'required|in:email,reset_password'
        ], [
            'otp.required' => 'رمز التحقق مطلوب',
            'otp.digits' => 'رمز التحقق يجب أن يكون 4 أرقام',
            'type.required' => 'نوع التحقق مطلوب',
            'type.in' => 'نوع التحقق غير صحيح',
        ]);

        try {
            // Find OTP record
            $verification = UserVerification::where('type', $request->type)
                ->where('otp', $request->otp)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$verification) {
                return $this->errorResponse('رمز التحقق غير صحيح أو منتهي الصلاحية', 400);
            }

            $user = User::find($verification->user_id);
            if (!$user) {
                return $this->errorResponse('المستخدم غير موجود', 404);
            }

            $verification->update([
                'is_used' => true,
                'used_at' => now()
            ]);

            if ($request->type === 'email') {
                $user->is_email_verified = true;
                $user->email_verified_at = now();
                $user->save();
                $message = 'تم تفعيل البريد الإلكتروني بنجاح';
            } elseif ($request->type === 'reset_password') {
                $message = 'تم التحقق بنجاح، يمكنك الآن إعادة تعيين كلمة المرور';
            }

            return $this->successResponse(null, $message);
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في عملية التحقق', 500);
        }
    }


    public function resendOtp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => [
                'required',
                'exists:users,phone_number',
            ],
            'type' => 'required|in:email,reset_password'
        ], [
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.exists' => 'المستخدم غير موجود',
            'type.required' => 'نوع التحقق مطلوب',
            'type.in' => 'نوع التحقق غير صحيح',
        ]);
        try {
            $user = User::where('phone_number', $request->phone_number)->first();
            $otp = rand(1000, 9999);

            UserVerification::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]);

            if ($request->type === 'email') {
                SendOtpJob::dispatch($user->email, $otp, 'email');
            }
            if($request->type === 'reset_password'){
                SendOtpJob::dispatch($user->email, $otp, 'reset password');
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

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]\d{6,14}$/',
                'exists:users,phone_number',
            ],
            ], [
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.regex' => 'صيغة رقم الهاتف غير صحيحة',
            'phone_number.exists' => ' رقم الهاتف غير مسجل',
        ]);
        $phoneNumber = ltrim(preg_replace('/[^0-9]/', '', $validated['phone_number']), '0');

        try {
            $user = User::where('phone_number', $phoneNumber)->first();

            if (!$user) {
                return $this->errorResponse('المستخدم غير موجود', 404);
            }

            $otp = rand(1000, 9999);
            UserVerification::create([
                'user_id' => $user->id,
                'type' => 'reset_password',
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]);

            // Dispatch job to send the OTP via email
            SendOtpJob::dispatch($user->email, $otp, 'reset password');

            return $this->successResponse(null, 'تم إرسال رمز إعادة تعيين كلمة المرور بنجاح');

        } catch (\Exception $e) {
            return $this->errorResponse('فشل في إرسال رمز إعادة تعيين كلمة المرور', 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => [
                'required',
                'exists:users,phone_number',
            ],
            'password' => 'required|string|min:8|confirmed'
        ], [
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.exists' => 'المستخدم غير موجود',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.min' => 'يجب أن تتكون كلمة المرور الجديدة من 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق'
        ]);
        try {
            $user = User::where('phone_number', $validated['phone_number'])->first();

            if (!$user) {
                return $this->errorResponse('المستخدم غير موجود', 404);
            }

            $user->password = Hash::make($validated['password']);
            $user->save();

            return $this->successResponse(null, 'تم إعادة تعيين كلمة المرور بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في إعادة تعيين كلمة المرور', 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();   // لو عايز تفصل user العادي

        try {
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'تم تسجيل الخروج بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse('فشل في تسجيل الخروج', 500);
        }
    }

}
