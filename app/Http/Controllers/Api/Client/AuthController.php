<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
  
use App\Models\Client;
use App\Models\Token;
use App\Mail\ResetPassword;
 
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:clients',
            'mobile'     => 'required|string|max:15|regex:/[0-9]{10}/|digits:11|unique:clients',
            'region_id' => 'required',
            // 'city'   => 'required',
            'password'  => 'required|confirmed',
            'image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }
        
        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client -> api_token = str_random(60);
        
        if ($request->hasFile('image')) {

            $path = public_path();
            $destinationPath = $path . '/uploads/clientImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $client->image = 'uploads/clientImages/' . $name;
        }
        $client-> save();
        if($client)
        {
            return responseJson( 1 , 'تم الاضافه بنجاح ' , [
                'api_token' => $client -> api_token,
                'client' => $client,
            ]);
        }else 
        {
            return responseJson( 0 , 'حاول مره اخرى ');
        }
        
    }
    
    
    public function login(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'email'     => 'required|string|email|max:255',
            'password' => 'required'
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }

        $client = Client::where('email', $request->email)->first();
        if ($client) {
            if (Hash::check($request->password, $client->password)) {
                // check if not activated
                return responseJson( 1 , 'تم تسجيل الدخول بنجاح ' , [
                    'api_token ' => $client ->api_token ,
                    'client' => $client
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
        $validation = validator()->make($request->all(),[
            'email' => 'required|string|email|max:255'
        ]);
        
        if($validation->fails())
        {
            return responseJson( 0 , $validation->errors()->first() , $validation->errors());
        }
        
        $user = Client::where('email',$request->email)->first();
        if($user)
        {
            $code = rand(1111,9999);
            $update = $user->update(['pin_code' => $code]);
            if($update)
            {
                // send Email 
                Mail::to($user->email)
                ->bcc("lamiaagamal4295@gmail.com") // mail of manager
                ->send(new ResetPassword($user));
        
                return responseJson( 1 , 'تحقق من رسائل الايميل ', [
                    
                    'pin_code' =>$code,
                    'mail_fails' => Mail::failures(),
                    'email' => $user->email,
                    ]);
            
            }else{
                return responseJson( 0 , 'حاول مره اخرى');
            }   
        }else{
         return responseJson( 0 , 'خطأ فى الايميل');
        }
        
    }   
    
    
    //password function
    public function newPassword(Request $request)
    {
        $validation = validator()->make($request->all() , [
            'pin_code' => 'required' ,
            'password' => 'required|confirmed' ,
        ]);

        if($validation->fails())
        {
            $data = $validation->errors();
            return responseJson( 0 , $validation->errors()->first() , $data);
        }

        $user = Client::where('pin_code' , $request->pin_code)->where('pin_code' , '!=' ,0)
        ->first();

        if($user)
        {
            $user->password = bcrypt($request->password);
            $user->pin_code = null;

            if($user->save())
            {
                return responseJson( 1 , 'تم تغيير كلمة المرور بنجاح');
            } else {
                return responseJson( 0 , 'حدث خطأ ، حاول مره اخرى ');
            }

        } else{
            return responseJson( 0 , ' الكود خطأ ');
        }
    
    }


    public function profile(Request $request)
    {
        $validator = validator()->make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|string|max:15|regex:/[0-9]{10}/|digits:11',
            'region_id' => 'required',
            'city'   => 'required',
            'image' => 'image|mimes:png,jpeg',
        ]);

        if($validator->fails())
        {
           $data = $validator->errors();
           return responseJson( 0 , $validator->errors()->first() , $data);
        }

        $loginUser = $request->user();
        $loginUser->update($request->all());

        if ($request->hasFile('image')) {
            if (file_exists($loginUser->image))
                unlink($loginUser->image);
            $path = public_path();
            $destinationPath = $path . '/uploads/clientImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $loginUser->image = 'uploads/clientImages/' . $name;
        }
        
        $loginUser->save();

        $data = [
            'clients' => $request->user()->fresh()->load('region.city')
        ];
        return responseJson(1, 'تم تحديث البيانات', $data);
    }   

    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);
        return responseJson(1,'',$notifications);
    }

    public function registerToken(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'type'  => 'required|in:android,ios',
            'token' => 'required',
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
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