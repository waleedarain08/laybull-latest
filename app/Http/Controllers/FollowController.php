<?php

namespace App\Http\Controllers;

use App\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FollowController extends Controller
{
    public function follow(Request $request){
        $validator = Validator::make($request->all(),[
            'follow_id'=>'required',
        ]);

        if($validator->fails()){
            return $this->formatResponse('error','validation error',$validator->errors()->first(),401);
        }
        $check = Follow::where('follow_id', $request->follow_id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if($check)
            return  $this->formatResponse('error','You Already Allow This User');
        $follow = new Follow();
        $follow->user_id = Auth::id();
        $follow->follow_id = $request->follow_id;
        $follow->save();

        return  $this->formatResponse('success','You Follow This User Successfully');
    }
    public function unFollow(Request $request){
        $validator = Validator::make($request->all(),[
            'follow_id'=>'required',
        ]);

        if($validator->fails()){
            return $this->formatResponse('error','validation error',$validator->errors()->first(),401);
        }
        $follow = Follow::where('user_id', Auth::id())
            ->where('follow_id', $request->follow_id)
            ->delete();
        if($follow)
            return  $this->formatResponse('success','You SuccessFully UnFollow This User');
        else
            return  $this->formatResponse('error','You Already UnFollow This User');
    }
}
