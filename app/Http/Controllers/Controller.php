<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function formatResponse($status,$message=null,$data=[],$code=200)
    {
        return [
            'status'=>$status,
            'message'=>$message,
            'data'=>$data,
            'code'=>$code
        ];
    }
    public function firebaseNotification($user_id,$title,$body)
    {
        $firebaseToken = User::where('id',$user_id)
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token')
        ->first();
        $SERVER_API_KEY = 'AAAAsEk1_sw:APA91bHNgUU0Uxt840Q7njFptRu-iEnxhyJuENhr_kR1yDJU7vGxIVmxikewxtLEmnWU2LSUmXv0wjp04q8mmg26HV_rfzwEO5y8UFQecLG1EXv5FPd_LjT6QWJQIU84LP2jUxbzn2vs';

        $data = [
            "registration_ids" => [$firebaseToken],
            "notification" => [
                "title" => $title,
                "body" => $body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $reponse = curl_exec($ch);
        // dd($reponse);
    }
}
