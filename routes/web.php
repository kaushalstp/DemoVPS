<?php

use Illuminate\Support\Facades\Route; 
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//use Illuminate\Cache\RateLimiting\Limit;
//use Illuminate\Support\Facades\RateLimiter;
//use Cookie;
//use Unicodeveloper\Identify\Facades\IdentifyFacade;
//use Identify;
//use DeviceDetector\DeviceDetector;
//use DeviceDetector\Parser\Device\AbstractDeviceParser;

//Use Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('takemydbbackup', function () {    
        
    
    
    //shell_exec("mysqldump --opt -h 173.248.158.227 -u client1mev -pweF1l2n@3G*f client1mev  > mydumpfile.sql");
    system("mysqldump --opt -h 173.248.158.227 -u client1mev -pweF1l2n@3G*f client1mev  > mydumpfile.sql");
    

});

Route::get('copyinlinefile', 'TestController@copyinlinefile');

Route::get('checkAffilliateCode', 'UserController@checkAffilliate');

Route::get('testoutput/', function (){ 

   //phpinfo();     

    $total_buys = 0;
    $totalPurchaseInfo = DB::table('invoices')
                ->select(DB::raw('SUM(credits) as total_buy'))                
                ->where('user_id',5998)
                ->first();

    //echo "<pre>";print_r($totalPurchaseInfo);exit;
    $total_buys = $totalPurchaseInfo->total_buy;
    echo "Total Buys: $total_buys";exit;

    foreach($totalPurchaseInfo as $buyinfo){
        //echo "<pre>";print_r($buyinfo);exit;
        if(!empty($buyinfo->total_buy)){
            $total_buys = $buyinfo->total_buy;
        }        
    }
    $total_buys = $totalPurchaseInfo->total_buy;            

    echo "Total buys: $total_buys";
});


Route::get('downloadreport/{filetype}/{fileid}', function ($filetype="",$fileid=0){ 

    $uploadsinfo = DB::table('user_upload')->where('upload_id',$fileid)->first();

    //echo "<pre>";print_r($uploadsinfo);exit;

    $csv_file_path = $uploadsinfo->file_path;
    $xls_file_path = $uploadsinfo->valid_file_path;
    $validcsv_file_path = $uploadsinfo->valid_file_path;

    $invalid_file_path = $uploadsinfo->invalid_file_path;
    $catchall_file_path = $uploadsinfo->catchall_file_path;
    $unknown_file_path = $uploadsinfo->unknown_file_path;

    
    if($filetype == "csv"){
        return response()->download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."{$csv_file_path}"));
    }

    if($filetype == "xls"){
        return response()->download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."{$xls_file_path}"));
    }

    if($filetype == "validcsv"){
        return response()->download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."{$validcsv_file_path}"));
    }    

    if($filetype == "invalidcsv"){
        return response()->download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."{$invalid_file_path}"));
    }    

    if($filetype == "unknowncsv"){
        return response()->download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."{$unknown_file_path}"));
    }    

    if($filetype == "catchallcsv"){
        return response()->download(storage_path("app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."{$catchall_file_path}"));
    }    

    
});


Route::get('refreshConfig', function () {
    Artisan::call('config:clear');    
    Artisan::call('cache:clear');    
});

Route::get('publishMyConfig', function () {
    Artisan::call('vendor:publish --tag="cors"');    
});

Route::get('clearCache', function () {    
    Artisan::call('cache:clear');
});

Route::get('artisanVendor', function () {    
    Artisan::call('vendor:publish');
});

Route::get('deviceinfo', function () { 
    
    //echo "<pre>";print_r($_SERVER);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $ipinfo_res = file_get_contents("http://ipinfo.io/{$ip_address}/geo");
    $ipdetails = json_decode($ipinfo_res, true);

    echo "<pre>";print_r($ipdetails);
  
});


Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
    $googleuser = Socialite::driver('google')->user();
    // $user->token    
    //echo "<pre>";print_r($user);exit;

    $user = DB::table('tbl_registration')->where(['email'=>$googleuser->email])->first();        
    $userdetailname = explode(" ",$googleuser->name);
    $firstname = $userdetailname[0];

    if(!$user){

    	
    	$firstname = $userdetailname[0];
    	$lastname = $userdetailname[1];

    	$created_at = date('Y-m-d H:i:s');
        $ip_address = '';
        $newkey = Str::random(16);

        $referal_cookie="";
        if(isset($_COOKIE['mev_source']) && $_COOKIE['mev_source'] != '' && $_COOKIE['mev_source'] != NULL ){
            $referal_cookie=$_COOKIE['mev_source'];
            $referal_cookie = substr($referal_cookie,2);
        }

    	$id = DB::table('tbl_registration')->insertGetId(
            [
                'password' => '','firstname' => $firstname,'lastname' => $lastname,
                'lastname' => $lastname,'email' => $googleuser->email,'access' => 1,'created_at'=>$created_at,
                'IP'=>$ip_address,'country'=>'0','credits'=>'100','blocked_credits'=>0,'email_activation_code'=>"",
                'login_type'=>"google",'apikey'=>$newkey,'source'=>$referal_cookie
            ]
        );

        $invoiceid = DB::table('invoices')->insertGetId(
                [                                                
                    'amount'=>0,
                    'credits'=>100,
                    'pending_credits'=>0,
                    'payment_status'=>'true',
                    'user_id'=>$id,
                    'paypal_email'=>'',
                    'paypal_email_verified'=>'y',
                    'payment_ref_number'=>'Free Credits',
                    'transaction_id'=>'',
                    'created_at'=>$created_at,                
                    'invoice_number'=>'Manual_'.date('Y-m-d'),
                    'payer_verify_otp'=>"",                
                    'inline_client_app'=>0,
                    'coupon_code'=>'',
                ]
            );

        $userid = $id;

        $users_file_dir = base_path("storage/app/public/uploads/$userid");   
        if(!file_exists($users_file_dir)){
            mkdir($users_file_dir,777);
        }

        $maildata=['useremail'=>$googleuser->email,'mevlogourl'=>url('public/img/mev-logo-2.png')];
        $admins_email=[                    
                'jpatadiya@gmail.com',
                'piyushpatdiya@gmail.com',
                'kaushal.accuwebhosting@gmail.com',                
        ];

        Mail::send('mails.adminalert_newuser', $maildata, function($message)use($admins_email)
        {
            $message->to($admins_email, 'MyEmailVerifier')
            ->subject('Internal: New User Registration');

            $message->from("support@myemailverifier.com",env('APP_NAME',' '));
        });


    }else{
    	
    	$userid = $user->reg_id; 
    }


    setcookie("mevlogin","y",time() + 3600,"/");      
    //echo "here v";exit;

    session(['userid' => $userid]);    
    session(['username' => $firstname]); 
    //session(['is_admin' => '1']); 
    session(['toastr_msg'=>'Logged In successfuilly!...']);    

    $userdashboard = url('Dashboard');

    return redirect($userdashboard);
});


/*Route::get('/', function () {
    return view('admin');
}); 

Route::get('/', function () {
    return view('welcome');
}); */

//Route::get('getResponseAuthReturn', 'UserController@getResponseAPIAuthReturn');   
Route::get('hubspotAuthReturn',"UserController@hubspotAPIAuthReturn");  
Route::get('getResponseAuthReturn',"UserController@getResponseAPIAuthReturn");  
Route::get('contactContactAuthReturn',"UserController@contactContactAPIAuthReturn");  

Route::get('dopractice',"TrialController@getAllPractice");   


Route::get('checkcurl', 'UserController@checkCurl');  
Route::get('downloadInvoice/{invoiceno}', 'UserController@downloadInvoice'); 
Route::get('activateaccount/{activation_code}', 'UserController@activateaccount'); 

Route::get('paypalresponse', 'PaypalController@index'); 
Route::get('paypalnotify', 'PaypalController@paypalnotify'); 

Route::get('subscribe_success', 'PaypalController@subscribed');   

Route::post('notify_subscription', 'PaypalController@notifySubscription');  
Route::post('paypalnotify', 'PaypalController@paypalnotification');   



Route::get('verifier/validate_single/{email}/{apikey}', 'UserController@validate_single')
            ->middleware('throttle:20,1');   

Route::get('verifier/getcredits/{apikey}', 'UserController@getcredits');   

Route::get('verifier/file_info/{api_key}/{fileid}', 'UserController@getUploadedFileInfo');   
Route::post('verifier/upload_file', 'UserController@UploadFileFromAPI');   



Route::get('downloadmyfile/{userid}/{filename}', 'UserController@downloadCSVXLS');   

Route::get('hubspotAuthReturn',"UserController@hubspotAPIAuthReturn");  

Route::get('loginasclient/{userid}', function ($userid=0){ 
   

    session(['userid' => $userid]); 
    session(['username' => "Client"]); 
    session(['is_admin' => '0']);      

    return redirect(url('dashboard'));
    exit;

    //return Socialite::driver('google')->redirect();
});

Route::get('logintomev/{useridencoded}', function ($useridencoded='') { 
   
    $userid = base64_decode($useridencoded);
    session(['userid' => $userid]); 
    session(['username' => "Client"]); 
    session(['is_admin' => '0']);      

    return redirect(url('dashboard'));
    exit;

    //return Socialite::driver('google')->redirect();
});

Route::get('/', function () {
	
	//echo "route 1";exit;

    /*$requrl = $_SERVER['REQUEST_URI'];        
    echo $requrl;exit;*/
    /*$requrl = $_SERVER['REQUEST_URI'];      
    $req_segs = explode("/",$requrl);
    //echo $requrl;exit;
    echo "<pre>";print_r($req_segs);exit;    */

    

    // Home page vadu route

    

    if(session('userid') != '' && session('userid') > 0){    

        //return redirect(url('dashboard'));
    }

    return view('admin');
}); 


Route::get('{any}', function () { 

    // Dashboard vadu route
	//echo "route 2";exit;
    

	$allblank='';
	$requrl = $_SERVER['REQUEST_URI'];	    
	$req_segs = explode("/",$requrl);
	//echo $requrl;exit;
    //echo "<pre>";print_r($req_segs);exit;    


	//echo "<pre>";print_r($_COOKIE);exit;
	//echo "here: ".$_COOKIE['mevlogin'];
	
	


    if(session('userid') <= 0 || session('userid') == NULL || session('userid') == '' ){ 
        //header("Refresh:0");

        if(!in_array('notify_subscription',$req_segs) && !in_array('ResetPassword',$req_segs)){
			//return redirect(url(''));	 
		}

		

		//return redirect(url(''));	 		        
    }
	
    
    return view('admin');
})->where('any', '.*');
