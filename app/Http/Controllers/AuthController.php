<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class AuthController extends Controller
{

    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'first_name' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json(['success'=>0, 'response'=>$validator->errors()->messages()]);
        }
        $check_user = User::where('email',$request->email)->first();
        if($check_user)
        {
            return response()->json(['success'=>0,'response'=>'User Already exist']);
        }
        else
        {
            $user = User::create([
                
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password'=> Hash::make($request->password),
            ]);
            $user['username'] = $user->Fullname;
            if($user)
            {
                $token = Auth::login($user);

                return response()->json(['success'=>1, 'user'=>$user]);
            }
            else
            {
                return response()->json(['success'=>0, 'response'=>'Something went wrong']);
            }
        }

    }

    public function login(Request $request)
    {
//     $password = 'Abc123!';
// $hashToStoreInDb = Hash::make($password);
// echo $hashToStoreInDb;
// $isPasswordCorrect = Hash::check($password, $request->password);
// echo "\n";
// dd(hash::make($request->password));
       $validator = Validator::make($request->all(),[
            'email'=> 'required|email',
            'password' => 'required',
       ]);

       if($validator->fails())
       {
            return response()->json(['success'=>0,'response'=>$validator->errors()->messages()]);
       }
       $cred['email'] = $request->email;
       $cred['password']= $request->password;
       
    //    $token = Auth::attempt($cred);
       if(!$token = JWTAuth::attempt($cred))
       {
           return response()->json(['sucsess'=>0,'response'=>'Wrong credentials!']);
       }
        return response()->json(['sucsess'=>1,'response'=>['message'=>'You are logged in successfully','token'=>$token]]);
    }

    public function saveToken(Request $request)
    {
        try {
            $get_user = User::find(11);
            if($get_user)
            {
                $get_user->update(['remember_token' => $request->notitoken]);
                return response()->json(['token saved successfully.']);
            }
            else
            {
                return response()->json(['token not successfully.']);
            }
        }
        catch(\Exception $e)
        {
            return 'Error updating token: ' . $e->getMessage();
        }
    }
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('remember_token')->pluck('remember_token')->all();
        $SERVER_API_KEY = 'AAAAHOZ10xE:APA91bHT3gCmoTc7ylbHXoDE7u3ubO3DW5EXvJXl8ex1G4Khir9s_MCRgJHquGCOObZUtm6a9h8OeD7G9tZM7pWTeam_ehhMArFCiu9UavSP5RC7jn096yFyHgrdRhg_z0Z2Ur0Kk6sW';
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $dataString = json_encode($data);   
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        print_r($headers);
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
        print_r($response);
        if($response == FALSE)
        {
            dd('curl failed');  
        }
    }
}
