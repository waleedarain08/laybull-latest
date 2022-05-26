<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\City;
use App\Country;
use App\Event;
use App\EventFavourite;
use App\EventGoing;
use App\EventLike;
use App\Follow;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CountryCollection;
use App\Http\Resources\EventFavouriteCollection;
use App\Http\Resources\EventGoingCollection;
use App\Http\Resources\EventLikeCollection;
use App\Product;
use App\Ratting;
use App\User;
use App\Http\Resources\User as ResourcesUser;
use App\Http\Resources\UserCollection;
use App\Setting;
use App\Venue;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::paginate(25);
        return new UserCollection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|max:255|unique:users',
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors()->first(), 403);
        }
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->address = $request->address;
        $user->dob = $request->dob;
        if ($request->is_seller == true){
            $user->bank_name = $request->bank_name;
            $user->iban = $request->iban;
            $user->card_number = $request->account_number;
            $user->account_name = $request->account_name;
        }
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $userImageName = 'laybull_user_' .Str::random(10). '.' . $file->getClientOriginalExtension();
            Storage::disk('public_user')->put($userImageName, File::get($file));
            $user->profile_picture = url('media/user/'.$userImageName);
        }
        $user->save();
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

        $token = Auth()->user()->createToken('Token')->accessToken;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'localhost/laybull2/public/api/email/resend',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        }


        return $this->formatResponse('success', 'user sign-up successfully');
    }
    public function resend(Request $request){
//        dd("here");
        if ($request->user()->hasVerifiedEmail()) {

            return response(['message'=>'Already verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        if ($request->wantsJson()) {
            return response(['message' => 'Email Sent']);
        }

        return back()->with('resent', true);
    }


    public function verify(Request $request)
    {
        auth()->loginUsingId($request->route('id'));

        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
//            return '<h1>Successfully Verified</h1>';

            return response(['message'=>'Already verified']);

            // return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
//        return '<h1>Successfully Verified</h1>';
        return response(['message'=>'Successfully verified']);

    }



    public function loginwithemail(Request $request)
    {
        if ($request->key != 'aaaabbbbcccc') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('email', $request->email)->orWhere('email', $request->userID)->first();
        if ($user == null) {
            $user = User::create($request->all());
        }
        Auth::login($user);

        if ($request->fcm_token) {
            User::where('id', auth()->user()->id)->update(['fcm_token' => $request->fcm_token]);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = auth()->user();
        if ($user->profile_picture != null) {
            $user->profile_picture = asset('images') . '/' . $user->profile_picture;
        }
        $user = new ResourcesUser(auth()->user());
        return response(['success' => true, 'data' => $user, 'access_token' => $accessToken]);
    }

    public function privacy()
    {
        $policy = `<h1>Privacy Policy for O'Bannons</h1>
        <p>At obannonbeer, accessible from obannonbeer.com, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by obannonbeer and how we use it.</p>
        <p>If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</p>
        <p>This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in obannonbeer. This policy is not applicable to any information collected offline or via channels other than this website. Our Privacy Policy was created with the help of the <a href="https://www.privacypolicygenerator.info">Free Privacy Policy Generator</a>.</p>
        <h2>Consent</h2>
        <p>By using our website, you hereby consent to our Privacy Policy and agree to its terms.</p>
        <h2>Information we collect</h2>
        <p>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</p>
        <p>If you contact us directly, we may receive additional information about you such as your name, email address, phone number, the contents of the message and/or attachments you may send us, and any other information you may choose to provide.</p>
        <p>When you register for an Account, we may ask for your contact information, including items such as name, company name, address, email address, and telephone number.</p>
        <h2>How we use your information</h2>
        <p>We use the information we collect in various ways, including to:</p>
        <ul>
        <li>Provide, operate, and maintain our website</li>
        <li>Improve, personalize, and expand our website</li>
        <li>Understand and analyze how you use our website</li>
        <li>Develop new products, services, features, and functionality</li>
        <li>Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes</li>
        <li>Send you emails</li>
        <li>Find and prevent fraud</li>
        </ul>
        <h2>Log Files</h2>
        <p>obannonbeer follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users' movement on the website, and gathering demographic information.</p>
        <h2>Advertising Partners Privacy Policies</h2>
        <P>You may consult this list to find the Privacy Policy for each of the advertising partners of obannonbeer.</p>
        <p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on obannonbeer, which are sent directly to users' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.</p>
        <p>Note that obannonbeer has no access to or control over these cookies that are used by third-party advertisers.</p>
        <h2>Third Party Privacy Policies</h2>
        <p>obannonbeer's Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options. </p>
        <p>You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers' respective websites.</p>
        <h2>CCPA Privacy Rights (Do Not Sell My Personal Information)</h2>
        <p>Under the CCPA, among other rights, California consumers have the right to:</p>
        <p>Request that a business that collects a consumer's personal data disclose the categories and specific pieces of personal data that a business has collected about consumers.</p>
        <p>Request that a business delete any personal data about the consumer that a business has collected.</p>
        <p>Request that a business that sells a consumer's personal data, not sell the consumer's personal data.</p>
        <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.</p>
        <h2>GDPR Data Protection Rights</h2>
        <p>We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:</p>
        <p>The right to access – You have the right to request copies of your personal data. We may charge you a small fee for this service.</p>
        <p>The right to rectification – You have the right to request that we correct any information you believe is inaccurate. You also have the right to request that we complete the information you believe is incomplete.</p>
        <p>The right to erasure – You have the right to request that we erase your personal data, under certain conditions.</p>
        <p>The right to restrict processing – You have the right to request that we restrict the processing of your personal data, under certain conditions.</p>
        <p>The right to object to processing – You have the right to object to our processing of your personal data, under certain conditions.</p>
        <p>The right to data portability – You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.</p>
        <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.</p>
        <h2>Children's Information</h2>
        <p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</p>
        <p>obannonbeer does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</p>`;
        return response()->json([
            'data' => $policy
        ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
            'fcm_token' => 'required'
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
//            if(!$user->email_verified_at)
//            {
//                auth()->logout();
//                return $this->formatResponse('error','Email not Verify',null,403);
//            }
            if ($request->fcm_token)
            {
                User::where('id', auth()->user()->id)->update(['fcm_token' => $request->fcm_token]);
            }
            $data['accessToken'] = auth()->user()->createToken('authToken')->accessToken;
//            $data['user'] = Auth::user();
            $data['user'] = User::where('id',Auth::id())->with('products')->first();
            $data['user']['followers'] =Follow::where('user_id', Auth::id())->count();
            $data['user']['followings'] =  Follow::where('follow_id', Auth::id())->count();


////            return($data['user']['id']);
//            $data['user']['selling_products'] = Product::where([
//            ['user_id','=',$data['user']['id']],
//                ['sold','=',1]
//            ])->get();
//            return $data;
//            $data['user']['order_product'] = Auth::user();
//            $data['user']['offer_product'] = Auth::user();
            return $this->formatResponse('success','user-login',$data);
        }
        else{
            return $this->formatResponse('error','Email or Password is incorrect',null,401);
        }
    }
    public function forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }
    public function passwordResetSuccessful(){
        return"<h1>Password Reset Successfully</h1>";
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show($id)
    {
        $user = User::with('products')->find($id);
        return new ResourcesUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors(), 403);
        }
        $user = User::find(Auth::id());
        $user->first_name = $request->first_name ;
        $user->last_name = $request->last_name ;
        $user->phone_number = $request->phone_number ;
        $user->country = $request->country ;
        $user->city = $request->city ;
        $user->address = $request->address ;
        $user->dob = $request->dob ;
        if ($request->is_seller == 1){
            $user->is_seller = 1;
            $user->bank_name = $request->bank_name ;
            $user->card_number = $request->card_number ;
            $user->iban = $request->iban ;
            $user->account_name = $request->account_name ;
        }
        $user->save();

        // $user->update($request->all());
        $user = User::find(Auth::id());
        return $this->formatResponse('success','user data updated',$user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function profilepicture(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $userImageName = 'laybull_user_' .Str::random(10). '.' . $file->getClientOriginalExtension();
            Storage::disk('public_user')->put($userImageName, File::get($file));
            $user->profile_picture = url('media/user/'.$userImageName);
            $user->save();
        }
        return $this->formatResponse('success','profile updated',User::find(Auth::id()));
    }

    public function changepassword(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->old_password, $user->password)) {
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            $message = "Password Changed Successfully";
        } else {
            $message = "Password Mismatch";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function about()
    {
        $about = Setting::where('name', 'about')->first();
        return response()->json([
            'about' => $about->text
        ]);
    }

    public function notificationtoggle(Request $request)
    {
        User::where('id', auth()->user()->id)->update(['notifications1' => $request->toggle_value]);
        $user = User::find(auth()->user()->id);
        return new ResourcesUser($user);

    }

    public function cities(Request $request)
    {
        $cities = City::where('country_code', $request->country_code)->get();
        return new CityCollection($cities);
    }

    public function countries()
    {
        $countries = Country::all();
        return new CountryCollection($countries);
    }
    public function post_seller_account_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'account_holder_name' => 'required',
             'account_number' => 'required',
             'account_phone_number' => 'required',
             'account_bank_name' => 'required',
             'account_iban' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        } else {
            try {
                $user = User::find(Auth::id());

                $user->update(['is_seller' => 1,
                                'account_name' => $request->account_holder_name,
                                'card_number' => $request->account_number,
                                'bank_name' => $request->account_bank_name,
                                'iban' => $request->account_iban,
                    ]);


                $status = 'True';
                $message = 'You Have Become a Seller';
                $user=User::find(Auth::id());

                return response()->json(compact('status', 'message', 'user'), 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        }
    }
    public function ratting(Request $request){
        $validator = Validator::make($request->all(), [
            'ratting_user_id' => 'required',
            'ratting' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors());
        }
        $check = Ratting::where('user_id',Auth::id())->where('ratting_user_id',$request->ratting_user_id)->first();
        if ($check){
            $check->ratting = $request->ratting;
            $check->save();
            return $this->formatResponse('success', 'ratting updated successfully');
        }
        $ratting = new Ratting();
        $ratting->user_id = Auth::id();
        $ratting->ratting_user_id = $request->ratting_user_id;
        $ratting->ratting = $request->ratting;
        $ratting->save();
        return $this->formatResponse('success', 'ratting added successfully');
    }

}
