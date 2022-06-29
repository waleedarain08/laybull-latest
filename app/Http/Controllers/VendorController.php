<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(){
        $vendors = User::where('is_seller',1)->get();
//        return  $vendors;
        return view('admin.vendor.vendor',get_defined_vars());
    }
    public function vendorVerified($id){
        $vendor = User::find($id);
        $vendor->verified_vendor = 1;
        $vendor->save();
        return redirect()->route('vendor');
    }
    public function vendorUnverified($id){
        $vendor = User::find($id);
        $vendor->verified_vendor = 0;
        $vendor->save();
        return redirect()->route('vendor');
    }
}
