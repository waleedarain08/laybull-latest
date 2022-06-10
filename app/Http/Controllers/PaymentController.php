<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'IBAN_Number'=>'required',
            // 'card_number'=>'required',
            // 'expiry'=>'required',
            // 'account_name' => 'required',
            // 'cvv' => 'required',
            //  'email'=>'required',
            // 'first_name'=>'required',
            // 'last_name' => 'required',
            // 'address1' => 'required',
            //  'city'=>'required',
            // 'country_code'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        // $apikey = "NGNjMmJkNmEtOTBjNC00MWUzLTgzODktZmY4MmVmYWI2NTRhOjY0NDk4OTBkLTMxYzUtNDY5Ny1hNTg4LTM0ZDhmZTM0Yjc5OA==";     // enter your API key here

        // live
        $apikey = "NjU4ZGY3MjQtYjgxNy00ZDljLTg5ZDQtOTNkZGY0MTFlN2JkOmE0NWE3Y2RiLWNkOTgtNDAzMi05ODgxLThiMDI5OWRjMDgzZQ==";     // enter your API key here
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-gateway.ngenius-payments.com/identity/auth/access-token");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "accept: application/vnd.ni-identity.v1+json",
            "authorization: Basic " . $apikey,
            "content-type: application/vnd.ni-identity.v1+json"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  "{\"realmName\":\"NetworkInternational\"}");
        // curl_setopt($ch, CURLOPT_POSTFIELDS,  "{\"realmName\":\"ni\"}");
        $output = json_decode(curl_exec($ch));
        // dd($output);
        $access_token = $output->access_token;
        $postData = new StdClass();
        $postData->order = new StdClass();

        $postData->action = "SALE";
        $postData->amount = new StdClass();

        $postData->amount->currencyCode = "AED";
        $postData->amount->value = $request->amount * 100;
        $postData->emailAddress = $request->email;
        $postData->billingAddress = new StdClass();
        $postData->billingAddress->firstName = $request->first_name;
        $postData->billingAddress->lastName = $request->last_name;
        $postData->billingAddress->address1 = $request->address1;
        $postData->billingAddress->city = $request->city;
        $postData->billingAddress->countryCode = $request->country_code;
        $postData->payment = new StdClass();
        $postData->payment->pan = $request->card_number;
        $postData->payment->expiry = $request->expiry;
        $postData->payment->cvv = $request->cvv;
        $postData->payment->cardholderName = $request->account_name;
        // $postData->is3dsRequired = false;


        // $outlet = "f198652b-ec86-4345-b1e5-a17b68ef0716";
        //live
        $outlet = "b4a48f24-6d4f-4d72-8048-fc01aabcfb37";
        $token = $access_token;
        // dd($token);
        $json = json_encode($postData);
        // return $json; exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-gateway.ngenius-payments.com/transactions/outlets/" . $outlet . "/orders");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $token,
            "Content-Type: application/vnd.ni-payment.v2+json",
            "Accept: application/vnd.ni-payment.v2+json"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $output = json_decode(curl_exec($ch));
        // dd($output);
        curl_close($ch);

        if (isset($output->code) || isset($output->error)) {
            return response()->json(['status' => 'False', 'payment_link' => $output]);
        }

        $product = Product::find($request->product_id);
        // dd($product);
//        if($product->available==0){
//            return response()->json([
//                'success'=>false
//            ],500);
//        }


        $state = $output;
        $str = "Hello world. It's a beautiful day.";
        $order_id = explode(":", $output->_id);
        // dd($order_id[2]);
        $order_status_link = 'https://api-gateway.ngenius-payments.com/transactions/outlets/' . $outlet . '/orders/' . $order_id[2];
        return response()->json(['access_token' => $token, 'link' => $output->_links->payment->href, 'order_status_link' => $order_status_link]);
        $output1 = "";
        if ($state == "AWAIT_3DS") {
            $cnp3ds_url = $output->_links->{'cnp:3ds'}->href;
            $acsurl = $output->{'3ds'}->acsUrl;
            $acspareq = $output->{'3ds'}->acsPaReq;
            $acsmd = $output->{'3ds'}->acsMd;
            // $acsterm = "https://[your-post-3ds-script]";

            $result = [
                'cnp3ds_url' => $cnp3ds_url,
                'acsurl' => $acsurl,
                'acspareq' => $acspareq,
                'acsmd' => $acsmd,
            ];
            $data_string = [
                "PaRes" => "Y"
            ];
            $dataString = json_encode($data_string);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $cnp3ds_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . $access_token,
                "Content-Type: application/vnd.ni-payment.v2+json"
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $output1 = json_decode(curl_exec($ch));
            curl_close($ch);
        }


        if ($state == 'FAILED') {
            return response()->json(['state' => $state, 'status' => 'False', 'output' => $output]);
        } else {

            // dd($output);
            $update = Product::where('id', $request->product_id)
                ->update(
                    [
                        'available' => "0",
                    ]
                );
            $product->update(['available' => 0]);
            return response()->json(['state' => $state, 'status' => 'True', 'output' => $output, 'output' => $output1]);
        }
    }
}
