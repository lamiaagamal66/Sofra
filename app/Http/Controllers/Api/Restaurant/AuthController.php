<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Models\Restaurant;
use App\Models\Token;
use App\Mail\ResetPassword;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:restaurants',
            'mobile'          => 'required|string|max:15|regex:/[0-9]{10}/|digits:11|unique:restaurants',
            'region_id'       => 'required',
            // 'city'            => 'required',
            'password'        => 'required|confirmed',
            'categories'      => 'required|array',
            'minimum_cost'    => 'required|numeric',
            'delivery_cost'   => 'required|numeric',
            'whatsapp'        => 'required',
            'image'           => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'          => 'required'
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $request->merge(['password' => bcrypt($request->password)]);
        $user = Restaurant::create($request->all());
        $user -> api_token = str_random(60);

        if ($request->has('categories')) {
            $user->categories()->sync($request->categories);
        }

        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/restaurantImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $user->update(['image' => 'uploads/restaurantImages/' . $name]);
        }

        $user-> save();
        if ($user) 
        {
            return responseJson(1, 'تم الاضافه بنجاح ', [
                'api_token' => $user -> api_token,
                'user' => $user,
            ]);
        } else {
            return responseJson(0, 'حدث خطأ ، حاول مرة أخرى');
        }
    }

    public function login(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'email'    => 'required|string|email|max:255',
            'password' => 'required'
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $user = Restaurant::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
            
                return responseJson(1, 'تم تسجيل الدخول', [
                    'api_token' => $user->api_token,
                    'user'      => $user,
                ]);
            } else {
                return responseJson(0, 'بيانات الدخول غير صحيحة');
            }
        } else {
            return responseJson(0, 'بيانات الدخول غير صحيحة');
        }
    }


    public function resetPassword(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'email' => 'required|string|email|max:255'
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $user = Restaurant::where('email', $request->email)->first();
        if ($user) {
            $code = rand(111111, 999999);
            $update = $user->update(['pin_code' => $code]);
            if ($update) {
                // send email
                Mail::to($user->email)
                ->bcc("lamiaagamal4295@gmail.com") // mail of manager
                ->send(new ResetPassword($user));

                return responseJson( 1 , 'تحقق من رسائل الايميل ', [
                    'pin_code' =>$code,
                    'mail_fails' => Mail::failures(),
                    'email' => $user->email,
                    ]);
            } else{
                return responseJson( 0 , 'حاول مره اخرى');
            }   
        }else{
         return responseJson( 0 , 'خطأ فى الايميل');
        }
    }

    public function newPassword(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'pin_code' => 'required' ,
            'password' => 'required|confirmed' ,
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $user = Restaurant::where('pin_code', $request->pin_code)->where('pin_code', '!=', 0)->first();

        if ($user) {
            $user->password = bcrypt($request->password);
            $user->pin_code = null;

            if($user->save())
            {
                return responseJson(1, 'تم تغيير كلمة المرور بنجاح');
            } else {
                return responseJson(0, 'حدث خطأ ، حاول مرة أخرى');
            }
        } else {
            return responseJson(0, 'هذا الكود غير صالح');
        }
    }


    public function profile(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:restaurants',
            'mobile'          => 'required|string|max:15|regex:/[0-9]{10}/|digits:11|unique:restaurants',
            'region_id'       => 'required',
            // 'city'            => 'required',
            'password'        => 'required|confirmed',
            'categories'      => 'required|array',
            'minimum_cost'    => 'required|numeric',
            'delivery_cost'   => 'required|numeric',
            'whatsapp'        => 'required',
            'image'           => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'          => 'required'
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }
        
        if ($request->has('categories')) {
            $request->user()->categories()->sync($request->categories);
        }
        if ($request->hasFile('image')) {
            if (file_exists($request->user()->image)) {
                unlink($request->user()->image);
            }
            $path = public_path();
            $destinationPath = $path . '/uploads/restaurantImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $request->user()->image = 'uploads/clientImages/' . $name;
        }
        $request->user()->save();
        $data = [
            'restaurant' => $request->user()->load('region','categories')
        ];
        return responseJson(1, 'تم تحديث البيانات', $data);
    }

    public function notifications(Request $request)
    {
      $notifications = $request->user()->notifications()->latest()->paginate(10);
      return responseJson(1,'success ',$notifications);
    }

    public function registerToken(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'type'  => 'required|in:android,ios',
            'token' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0, $validation->errors()->first(), $data);
        }

        Token::where('token', $request->token)->delete();

        $request->user()->tokens()->create($request->all());
        return responseJson(1, 'تم التسجيل بنجاح');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function removeToken(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'token' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0, $validation->errors()->first(), $data);
        }

        Token::where('token', $request->token)->delete();
        return responseJson(1, 'تم الحذف بنجاح بنجاح');
    }


}