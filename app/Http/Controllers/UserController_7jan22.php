<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

use App\Post;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
Use Session; 
use Spatie\Browsershot\Browsershot;
//use Maatwebsite\Excel\Facades\Excel;
use Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use \yidas\phpSpreadsheet\Helper;


use PDF;
use Config;
use File;
use Cookie;

//use MailchimpMarketing\ApiClient;

class UserController extends Controller
{
    
    public function __construct(){
        session(['umsg'=>'']);
        Session::forget('umsg');
    }

    public function index(){
                                         
    } 
    

    public function cancelUserSubscription(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];            
        $used_coupons = [];            
        $cur_time = date('Y-m-d');
        $response = ['status'=>'0','msg'=>'','data'=>$data];        
        extract($postData);            


        $tokench = curl_init();

        curl_setopt($tokench, CURLOPT_URL, 'https://api-m.paypal.com/v1/oauth2/token');
        curl_setopt($tokench, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($tokench, CURLOPT_POST, 1);
        curl_setopt($tokench, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($tokench, CURLOPT_USERPWD,
            'AbDMPzSz5gKBdUMMoU7bNv0nYd9qoeIIizYsEGx8bCoQr2asBgH4b8VUXU36Fp7RrAugbhSZX5F7UaZL'.':'.
            'EGAH6NiskKdjd2M0XSjreuursOzUBOVXpdvBW1gUUsUauaitoKRgFyXzrMl3CRTW2aCaCU0CtMsZ7wLM');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($tokench, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($tokench);
        if (curl_errno($tokench)) {
            echo 'Error:' . curl_error($tokench);
        }
        curl_close($tokench);

        //echo $result;
        $resinfo = json_decode($result,true);
        //echo "<pre>";print_r($resinfo);exit;

        $paypal_auth_token = $resinfo['access_token'];
        //$subscrid = $subscrid;

        /*  CURL cancel subscription  */

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api-m.paypal.com/v1/billing/subscriptions/'.$subscrid.'/cancel');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"reason\": \"Not satisfied with the service\"\n}");

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$paypal_auth_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $newresult = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);   

        //echo $newresult;
        $cancelinfo = json_decode($newresult,true);
        //echo "<pre>";print_r($cancelinfo);exit;
        /*if(isset($cancelinfo['name']) && $cancelinfo['name'] == 'UNPROCESSABLE_ENTITY' ){

        }*/


        $affected = DB::table('tbl_subscriptions')->where('subscription_paypal_id',$subscrid)->update([
            'status'=>'expired',            
        ]);        

        /* ------------------------------ */

        $response = ['status'=>'1','msg'=>'Unsubscribed Successfully. ','data'=>$data];        

        return response()->json($response);
        exit;

    }

    public function checkAffilliate(Request $request){
        $postData = $request->input();

        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);


         /* Idev  Referal commission */
            //$mevsource = $userInfo->source;
            $mevsource = "103#myemailverifier.com - direct link";            
            
            if(trim($mevsource) != '' && $mevsource != NULL){
                $source_sec_hash = strripos($mevsource,"#");            
                $sourceid = substr($mevsource,1,($source_sec_hash-1));
                 
                if($sourceid != '' && $sourceid != NULL){
                    if(is_numeric($sourceid)){

                        $idev_ordernum = '8901';
                        $idev_saleamt = 114.00;
                        $affiliate_id =  $sourceid;                       

                        $idevch = curl_init();
                        curl_setopt($idevch, CURLOPT_URL, "https://myemailverifier.idevaffiliate.com/sale.php?idev_saleamt=".$idev_saleamt."&idev_ordernum=".$idev_ordernum."&affiliate_id=".$affiliate_id);
                        curl_setopt($idevch, CURLOPT_RETURNTRANSFER, true);
                        curl_exec($idevch);
                        curl_close($idevch);            

                        
                        $maildata = [                         
                            'affiliate_id'=>$affiliate_id,
                            'invoice_no'=>'12312345',
                            'mevlogourl'=>url('public/img/mev-logo-2.png'),
                            'invoice_ref'=>$idev_ordernum,
                            'sales_amount'=>$idev_saleamt
                        ];
                        
                        $admins_email=[                                                
                                'piyushpatdiya@gmail.com',
                                'kaushal.accuwebhosting@gmail.com',     
                                'jpatadiya@gmail.com'                           
                        ];

                        Mail::send('mails.idev_commision', $maildata, function($message)use($admins_email)
                        {
                            $message->to($admins_email, 'MyEmailVerifier')
                            ->subject('Internal: Affiliate Commission');

                             $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                        });
                    }
                }    
            }
  

        /* ---------------- */  

    }

    function cleanJunkChars($uploadedfname) {
       $uploadedfname = str_replace(' ', '-', $uploadedfname); // Replaces all spaces with hyphens.

       return preg_replace('/[^A-Za-z0-9\-.]/', '_', $uploadedfname); // Removes special chars.
    }
    
    public function processPendingPayment(Request $request){

        $postData = $request->input();

        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

            
        $created_at = date('Y-m-d H:i:s');
        $pending_credits=0;
        $otp = "";
        $otp_expires_on="";
        $paypal_email_verified="";
        //$invoice_number = strtoupper(Str::random(13));
        $userInfo = DB::table('tbl_registration')->where('reg_id',$userid)->first(); 

        if($userInfo->email == $payer_email){
            $paypal_email_verified = 'y';
            $pending_credits=0;
        }else{
            $paypal_email_verified = 'n';
            $pending_credits = $credits;
            
            $otp = rand(100001,999999);

            $mydate = date("Y-m-d H:i:s");
            $otp_last_date=date_create(date("Y-m-d H:i:s"));
            date_add($otp_last_date,date_interval_create_from_date_string("11 minutes"));
            $otp_expires_on = date_format($otp_last_date,"Y-m-d H:i:s");


            /* send paypal email comparision code */

                $maildata = [                         
                    'txnvc'=>$otp,       
                    'username'=>$userInfo->firstname.' '.$userInfo->lastname,
                    'mevlogourl'=>url('public/img/mev-logo-2.png')                                           
                ];

                $useremail = $payer_email;

                Mail::send('mails.payeremail', $maildata, function($message)use($useremail)
                {
                    $message->to($useremail, 'MyEmailVerifier')
                    ->subject('Transaction Verification Code');

                     $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                });

            /* --------- */


            /* Send Internal Email to Admin */
                $today = date("Y-m-d H:i:s");
                $internal_maildata=[
                    'credits'=>$credits,                    
                    'purchase_date'=>$today,  
                    'useremail'=>$userInfo->email,  
                    'username'=>$userInfo->firstname.' '.$userInfo->lastname,
                    'amount'=>$postData['paypal_response']['transactions'][0]['amount']['total'],  
                    'mevlogourl'=>url('public/img/mev-logo-2.png'),
                ];
                        
                $admins_email=[                    
                    'jpatadiya@gmail.com',
                    'piyushpatdiya@gmail.com',
                    'kaushal.accuwebhosting@gmail.com',                    
                ];

                Mail::send('mails.internal_order', $internal_maildata, function($message)use($admins_email)
                {
                    $message->to($admins_email, 'MyEmailVerifier')
                    ->subject('Internal: Order Received');

                     $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                });                                
        }

       

       $affected = DB::table('invoices')->where('invoice_id',$invoice_id)->update([
            'payment_status'=>'true',
            'paypal_email'=>$payer_email,
            'paypal_email_verified'=>$paypal_email_verified,
            'payment_ref_number'=>$postData['paypal_response']['id'],
            'created_at'=>$created_at,                            
            'invoice_number'=>$postData['paypal_response']['payer']['payer_info']['payer_id'],
            'payer_verify_otp'=>"$otp",
            'otp_expires_on'=>"$otp_expires_on",
            'otp_expires_on'=>"$otp_expires_on",
       ]);

            
        
        if($paypal_email_verified == 'y'){


            $affected = DB::update("update tbl_registration
                                 SET credits =  credits + $credits WHERE reg_id = $userid "); 

            $message = "Payment received successfully. Your credits are available now.";
            

        }else{
            
            $message = "<p>Since your PayPal email and Registered email is different, we need additional confirmation according to PayPal policy that you own your PayPal email address. We have sent a verification code to your PayPal email address. Please check inbox and confirm the Code. If it is not available in Inbox please check in spam folder and move it to inbox. </p><p> '.$useremail.' </p> ";
        }

        if($invoice_id > 0){

            $data['invoice_id']=$invoice_id;

            $response = [
                            'status'=>'1',
                            'msg'=>$message,
                            'data'=>$data,
                            'paypal_email_verified'=>$paypal_email_verified
                        ]; 
        }else{
            $response = [
                            'status'=>'0',
                            'msg'=>'payment failed. please try after some time!..',
                            'data'=>$data,
                            'paypal_email_verified'=>$paypal_email_verified
                        ]; 
        }

        return response()->json($response);
    }

    public function sendDiscountRequest(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];            
        $used_coupons = [];            
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        $curr_time = date('Y-m-d');
        extract($postData);            

        DB::table('tbl_special_discount_request')->insertGetId(
            [
                'userid'=>$userid,
                'organization'=>$organization,
                'contact_person'=>$contact_person,
                'contact_email'=>$contact_email,
                'detail'=>$detail,
                'credits_required'=>$credits_required,
                'paytype'=>$paytype,
                'created_at'=>$curr_time,
            ]
        );

        $userdata = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first(); 

        $admins_email=[                                
            'piyushpatdiya@gmail.com',
            'kaushal.accuwebhosting@gmail.com',                    
        ];

        $maildata=[
                'useremail'=>$userdata->email,
                'organization'=>$organization,
                'contact_person'=>$contact_person,
                'contact_email'=>$contact_email,
                'detail'=>$detail,
                'credits_required'=>$credits_required,
                'paytype'=>$paytype,                
                'mevlogourl'=>url('public/img/mev-logo-2.png')
        ];

        Mail::send('mails.special_discount_request_notify',$maildata,function($message)use($admins_email)
        {
            $message->to($admins_email, 'MyEmailVerifier')
            ->subject('Internal: Special Discount Request');

            $message->from("support@myemailverifier.com","");
        });


        $response = ['status'=>'1','msg'=>'We have received your request. We will be back to you soon.','data'=>$data];

        //return response('401 unauthorized',401);
        return response()->json($response);
        exit;

    }

    public function getMyAdditionalCoupons(Request $request){

        $userid = session('userid');
        $postData = $request->all();        
        $data = [];            
        $used_coupons = [];            
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $total_orders = DB::table('invoices')->where('user_id',$userid)->count();
        //echo "total orders: $total_orders";exit;

        $used_coupons_res = DB::select("select coupon_code,count(invoice_id) as total_orders FROM invoices WHERE user_id =  $userid  GROUP BY coupon_code HAVING coupon_code != '' ");


        foreach($used_coupons_res as $couponrec){

            $used_coupons[] = array(
                                    'coupon_code'=>$couponrec->coupon_code,
                                    'orders'=>$couponrec->total_orders
                                );

        }
        
        $data['orders_placed']=$total_orders;
        $data['used_coupons']=$used_coupons;        

        $response = ['status'=>'1','msg'=>'','data'=>$data]; 

        return response()->json($response);       
        exit;
    }

    
    public function UploadFileFromAPI(Request $request){

        $userid = 0;
        $postData = $request->all();        
        $data = [];        
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        /* first phase of process */

        $invalid_csv_values=false;
        $no_of_lines=0;        

        $userdata = DB::table('tbl_registration')->where('apikey', '=',"$api_key")->first(); 


        if(!$userdata){
            $response = ['status'=>false,'message'=>'Invalid API Key'];     
            return response()->json($response);
            exit;
        }


        try {
                  
            if(!$request->file('filename') || $request->file('filename') == ''){ 
                $response = ['status'=>'0','msg'=>'Please select a file','data'=>$data];
                return response()->json($response);  
                exit;    
            }

        }catch(Exception $e) { 
          
            $response = ['status'=>'0','msg'=>'Your file contains invalid format data','data'=>$data];
            return response()->json($response);  
            exit;    
        }    

        $userid = $userdata->reg_id;
        
        $extension = $request->file('filename')->getClientOriginalExtension();

        
        if($extension != 'csv' && $extension != 'txt' && $extension != 'xlsx' && $extension != 'xls'){
            $response = ['status'=>'0','msg'=>'Incorrect File uploaded. Allowed files are txt,csv,xls,xlsx','data'=>$data,'error_type'=>'invalid_file']; 
        }else{

            $newfilename = rand().time().'.'.$extension;            

            $orgname =  $request->file('filename')->getClientOriginalName();
            $filesize =  $request->file('filename')->getSize();

            $orgname = str_replace("'","",$orgname);
            $orgname = str_replace("#","",$orgname);
            $orgname = str_replace("@","",$orgname);

            $filename_repeat = DB::table('user_upload')
                ->where(
                        ['file_name'=>$orgname], 
                        ['userid'=>$userid]                        
                    )
                ->count();

            if($filename_repeat > 0){
                $file_path_info = pathinfo($orgname);
                $filename_without_ext = $file_path_info['filename'];
                $filename_without_ext.='_'.time();
                $orgname = $filename_without_ext.".".$extension;
            }

            $path = $request->file('filename')->storeAs("public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."$userid".DIRECTORY_SEPARATOR,"$orgname");

            //echo " csv path: ".$path;exit;

            $gudpath = str_replace("public".DIRECTORY_SEPARATOR,"",$path);
            $curdate = date("Y-m-d H:i:s");
            

            /*  csv new process demo  */
            $fullfilepath = base_path("$path");   
            $filestoragepath = storage_path("app/$path");
                                
            
            $line_of_text=[];
            $lewline_Ar=[];
                                                    
            if($extension == 'xls' || $extension == 'xlsx'){

                //$helper = \yidas\phpSpreadsheet\Helper::newSpreadsheet($filestoragepath);
                $mydata = \yidas\phpSpreadsheet\Helper::newSpreadsheet($filestoragepath)->getRows();
                $no_of_lines = count($mydata);                   
            }else{

                $csvfile = fopen($filestoragepath,"r");
                while(!feof($csvfile))
                {
                    //$singleline = fgets($csvfile);
                    $singlelineAr = fgetcsv($csvfile);
                    if(!is_array($singlelineAr)){
                        continue;
                    }
                    $no_of_lines = $no_of_lines + 1; 
                            
                }
                fclose($csvfile);                
            }            
            
            
            $userinfo = DB::table('tbl_registration')->where('reg_id',$userid)->first();

            if($no_of_lines > $userinfo->credits){

                $data['no_of_lines'] = $no_of_lines;           
                $data['available_credits'] = $userinfo->credits; 
                Storage::delete($path);

                $response = ['status'=>'false','msg'=>'You don\'t have sufficient credits to process this file. Your file contains '.$no_of_lines.' lines but '.$userinfo->credits.' credits are available in your account. Please purchase credits']; 
                return response()->json($response);
                exit;
            }


            if($invalid_csv_values){
                
                Storage::delete($path);
                $response = ['status'=>'false','msg'=>'File contains Invalid data format.each row should have only one email value ','data'=>$data]; 
                return response()->json($response);
                exit;
            }

            /* replace directory seperator */

                $new_gud_path = str_replace("/",DIRECTORY_SEPARATOR,$gudpath);      
                
            /* ----------- */

            $id = DB::table('user_dummy_uploads')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$orgname,
                    'user_given_name'=>$orgname,
                    'filesize'=>$filesize,
                    'credits_need'=>$no_of_lines,
                    'file_path'=>$new_gud_path, 
                    'xls_file_path'=>'',                                 
                    'user_id'=>$userid,                
                    'created_at'=>$curdate,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>$no_of_lines,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );

            /* ------- first phase ends here  ---- */            


            /*  second phase of prcess */

            $uploadsinfo = DB::table('user_dummy_uploads')->where('upload_id',$id)->first();
            $myuserinfo = DB::table('tbl_registration')->where('reg_id',$uploadsinfo->user_id)->first();

            $newfilename=$uploadsinfo->file_name;
            $listname=$uploadsinfo->user_given_name;
            $actual_file_path=$uploadsinfo->file_path;
            $userid=$uploadsinfo->user_id;
            $filesize=$uploadsinfo->filesize;

            $credits_need=$uploadsinfo->credits_need;

            $admins_email=[                                
                'piyushpatdiya@gmail.com',
                'kaushal.accuwebhosting@gmail.com',                    
            ];

            $maildata=[
                'username'=>$myuserinfo->firstname.' '.$myuserinfo->lastname,
                'useremail'=>$myuserinfo->email,
                'filename'=>$newfilename,
                'filesize'=>$filesize.' Bytes',
                'no_of_lines'=>$credits_need,
                'mevlogourl'=>url('public/img/mev-logo-2.png')
            ];

            Mail::send('mails.adminalert_newfile',$maildata,function($message)use($admins_email)
            {
                $message->to($admins_email, 'MyEmailVerifier')
                ->subject('Internal: New file uploaded');

                $message->from("support@myemailverifier.com","");
            });

            $current_date = date('Y-m-d');
            $myfileid = DB::table('user_upload')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$listname,
                    'user_given_name'=>$listname,
                    'file_path'=>$actual_file_path,       
                    'xls_file_path'=>'',         
                    'user_id'=>$userid,                
                    'created_at'=>$current_date,    
                    'credits_need'=>$credits_need,            
                    'ready_for_download'=>0,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>$uploadsinfo->credits_need,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );

            $affected = DB::table('user_dummy_uploads')
                    ->where('upload_id',$id)                    
                    ->delete();

            /* sendond phase ends here */


            /* final response here */
            $data['no_of_lines'] = $no_of_lines;           
            $data['available_credits'] = $userinfo->credits;           
            $response = [
                'status'=>'true',
                'msg'=>'File uploaded successfully',
                'file_name'=>$orgname,
                "file_id"=>$myfileid,
            ]; 
            /* ----  */
        }
              
        
        return response()->json($response);     

    }

    public function getUserSharedLinks(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];        
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);
        

        $sharedinfo = DB::table('tbl_social_share')->where('userid',$userid)->first();


        if(!empty($sharedinfo)){
            $data['sharedinfo']=$sharedinfo;        
            $response = ['status'=>'1','msg'=>'','data'=>$data];
        }else{
            $data['sharedinfo']=[];
            $response = ['status'=>'0','msg'=>'','data'=>$data];
        }

        
        return response()->json($response);
        exit;
    }

    public function saveMySocialLink(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];        
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $today = date('Y-m-d');

        $social_record = DB::table('tbl_social_share')
                        ->where('userid',$userid)
                        ->where('social_media',$social_media)
                        ->orderBy('id','desc')                        
                        ->first();


        $admin_settings = DB::table('tbl_admin_settings')->where('id',1)->first();                                  

        $datediff_days = 0;                
        if(empty($social_record)){
            
            DB::table('tbl_social_share')
            ->insertGetId(
                [
                    'userid' => $userid,
                    'post_link' => $post_link,
                    'credits' => $freecredits,
                    'social_media' => $social_media,
                    'created_at'=>$today
                ]            
            );
        }else{

            $last_created_at = $social_record->created_at;
        
            $thidate = strtotime($today);
            $your_date = strtotime($last_created_at);

            $datediff = $thidate - $your_date;

            $datediff_days = round($datediff / (60 * 60 * 24));   

            if($datediff_days > $admin_settings->social_share_interval){

                DB::table('tbl_social_share')        
                ->insertGetId(
                    [
                        'userid' => $userid,
                        'post_link' => $post_link,
                        'social_media' => $social_media,
                        'created_at'=>$today
                    ]            
                );
            }                
        }

        $userdata = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first(); 

        $admins_email=[                                
            'piyushpatdiya@gmail.com',
            'kaushal.accuwebhosting@gmail.com',                    
        ];

        $maildata=[
                'useremail'=>$userdata->email,                                               
                'credits'=>$freecredits,                
                'social_media'=>$social_media,                
                'post_link'=>$post_link,                
                'mevlogourl'=>url('public/img/mev-logo-2.png')
        ];

        Mail::send('mails.free_credits_request_notify',$maildata,function($message)use($admins_email)
        {
            $message->to($admins_email, 'MyEmailVerifier')
            ->subject('Internal: Free Credits Request');

            $message->from("support@myemailverifier.com","");
        });

        //$rec_exists = DB::table('tbl_social_share')->where('userid',$userid)->exists();
             
        $response = ['status'=>'1','msg'=>'Saved','data'=>$data];
        return response()->json($response);
        exit;        
    }

    public function changeSwitch(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];        
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $affected = DB::table('tbl_registration') 
        ->where('reg_id',$userid)            
        ->update(
            [
                'low_credits_notify'=>$low_credit_switch == true ? 'n' : 'y',
                //'low_credits_notify'=>$low_credit_switch == true ? 'y' : 'n',                
            ]
        ); 

        $response = ['status'=>'1','msg'=>'Saved...','data'=>$data];
        return response()->json($response);    
        exit;                    
    }

    public function changeCreditLimit(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];        
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $affected = DB::table('tbl_registration') 
        ->where('reg_id',$userid)            
        ->update(
            [                                
                'low_credits_limit'=>$low_credit_limit,
            ]
        ); 

        $response = ['status'=>'1','msg'=>'Saved...','data'=>$data];
        return response()->json($response);    
        exit;                    
    }

    public function getMyUserName(){
        $data=['username'=>session('username'),'userid'=>session('userid'),'is_admin'=>session('is_admin')];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        return response()->json($response);
    }

    public function saveLibAPIKey(Request $request){

        $userid = session('userid');
        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $batchInsert=[];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $library="getresponse";

        
        $list_exists = DB::table('tbl_integration')
                                ->where('userid', $userid)
                                ->where('library',$library)
                                ->exists();

        if($list_exists){
            
            $affected = DB::table('tbl_integration') 
            ->where('userid',$userid)
            ->where('library',$library)
            ->update(
                [
                    'userid'=>$userid,
                    'library'=>$library,
                    'apikey'=>$apikey,
                    'created_at'=>$current_date,                       
                ]
            ); 

        }else{
               

            $id = DB::table('tbl_integration')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'apikey'=>$apikey,
                    'userid'=>$userid,
                    'library'=>$library,
                    'created_at'=>$current_date,                                   
                ]
            );
        }     


        $liblists =  DB::table('tbl_contact_list')                    
                    ->where([
                        ['userid','=',$userid],
                        ['library','=',$library],                                        
                    ])->get();

        /*echo "<pre>";                            
        print_r($liblist);exit;*/
        

        if($library == 'mailchimp'){
            $webids=[];


            foreach($liblists as $listitem){
            
                $webids[]=$listitem->web_id;
            }


            $server_prefix = $apikey;
            $sp_pos = (stripos($apikey,"-")+1);
            $server_prefix = substr($apikey,$sp_pos);


            // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://$server_prefix.api.mailchimp.com/3.0/lists");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            curl_setopt($ch, CURLOPT_USERPWD, 'mevapi' . ':' . $apikey);

            $mailchimp_result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $mailchimp_res_info = json_decode($mailchimp_result);     
            //echo "<pre>";print_r($mailchimp_res_info);exit;
            foreach($mailchimp_res_info->lists as $mailchimp_list_item){    

                //echo "<pre>";print_r($mailchimp_list_item);exit;

                if(!in_array($mailchimp_list_item->web_id,$webids)){
                            
                    $batchInsert[]=array(
                        'listname'=>$mailchimp_list_item->name,
                        'userid'=>$userid,
                        'library'=>'mailchimp',
                        'api_id'=>$mailchimp_list_item->id,
                        'web_id'=>$mailchimp_list_item->web_id,
                        'created_at'=>$current_date,                        
                    );                        
                }
            }                    
        }


        if($library == 'getresponse'){
            $get_response = Http::withHeaders([
                    'X-Auth-Token' => "api-key $apikey",            
                    ])->get("https://api.getresponse.com/v3/contacts",[                    
                ]);  
            
                echo $get_response;exit;
                $get_response_arr = json_decode($get_response);
        }

        if(!empty($batchInsert)){
            DB::table('tbl_contact_list')->insert($batchInsert);    
        }





    }

    public function practice(){

        $date = date_create(date("Y-m-d"));

        date_add($date,date_interval_create_from_date_string("-30 days"));
        echo date_format($date,"Y-m-d");

        $query= " select i.created_at,sum(i.amount) as total from invoices i GROUP BY DATE(i.created_at)  ";

        $query= " select DATE(i.created_at) as revenuedate ,sum(i.amount) as total from invoices i WHERE i.created_at > (SELECT DATE_ADD(CURDATE(), INTERVAL -30 DAY)) GROUP BY DATE(i.created_at)  ";


        $q2 = " SELECT DATE_ADD(CURDATE(), INTERVAL -30 DAY)  ";

    }

    
    function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ?    FALSE : TRUE;
    }

    public function startVerifyProcess(Request $request){
		$postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $uploadsinfo = DB::table('user_dummy_uploads')->where('upload_id',$uploadid)->first();
        $myuserinfo = DB::table('tbl_registration')->where('reg_id',$uploadsinfo->user_id)->first();

        $newfilename=$uploadsinfo->file_name;
        $listname=$uploadsinfo->user_given_name;
        $actual_file_path=$uploadsinfo->file_path;
        $userid=$uploadsinfo->user_id;
        $filesize=$uploadsinfo->filesize;

        $credits_need=$uploadsinfo->credits_need;

        $admins_email=[                                
            'piyushpatdiya@gmail.com',
            'kaushal.accuwebhosting@gmail.com',                    
        ];

        $maildata=[
            'username'=>$myuserinfo->firstname.' '.$myuserinfo->lastname,
            'useremail'=>$myuserinfo->email,
            'filename'=>$newfilename,
            'filesize'=>$filesize.' Bytes',
            'no_of_lines'=>$credits_need,
            'mevlogourl'=>url('public/img/mev-logo-2.png')
        ];

        Mail::send('mails.adminalert_newfile',$maildata,function($message)use($admins_email)
        {
            $message->to($admins_email, 'MyEmailVerifier')
            ->subject('Internal: New file uploaded');

            $message->from("support@myemailverifier.com","");
        });


        $id = DB::table('user_upload')->insertGetId(
            [                      
                //'file_name'=>$newfilename,
                'file_name'=>$listname,
                'user_given_name'=>$listname,
                'file_path'=>$actual_file_path,       
                'xls_file_path'=>'',         
                'user_id'=>$userid,                
                'created_at'=>$current_date,    
                'credits_need'=>$credits_need,            
                'ready_for_download'=>-1,                
                'valid'=>0,                
                'invalid'=>0,                
                'total'=>$uploadsinfo->credits_need,                
                'unknown'=>0, 
                'spam_trap'=>0,               
                'toxic_domains'=>0,               
                'credit_used'=>0,                
                'eps'=>'',                
                'status'=>'',                
            ]
        );

        $affected = DB::table('user_dummy_uploads')
                    ->where('upload_id',$uploadid)                    
                    ->delete();

        if($id > 0){
        	$response = ['status'=>'1','msg'=>'','data'=>$data];                    
        }else{
        	$response = ['status'=>'0','msg'=>'Something went wrong. Please try again !...','data'=>$data];                    
        }            
		

        return response()->json($response);
    }

    public function stopVerifyProcess(Request $request){
        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $uploadinfo = DB::table('user_upload')->where('upload_id', '=',"$fileid")->first();            
        $uploaded_files_id = substr($uploadinfo->eps,3);

        $apiresponse = Http::get('https://bulkapi.millionverifier.com/bulkapi/stop/?api_key=Kai6IGOSveck7symwzmZhhG1B&file_id='.$uploaded_files_id);

        $apiresponsetext = $apiresponse->body(); 
        $apiResponseObj = json_decode($apiresponsetext);
                

        $response = ['status'=>'1','msg'=>'Please wait!  it will take around 3 min to show download buttons','data'=>$data];                    

        return response()->json($response);
    }

    public function confirmInvoice(Request $request){

        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        extract($postData);

        $otp = rand(100001,999999);
        $mydate = date("Y-m-d H:i:s");
        $otp_last_date=date_create(date("Y-m-d H:i:s"));
        date_add($otp_last_date,date_interval_create_from_date_string("11 minutes"));
        $otp_expires_on = date_format($otp_last_date,"Y-m-d H:i:s");


        $invoice = DB::table('invoices as i')
        ->join('tbl_registration as r', 'r.reg_id', '=', 'i.user_id')
        ->select('i.*', 'r.firstname','r.lastname', 'r.email')
        ->where('i.invoice_id', '=',"$invoice_id")->first();        
        
        
        $affected = DB::table('invoices') 
            ->where('invoice_id',$invoice_id)
            ->update(
                [
                    'payer_verify_otp' => $otp,
                    'otp_expires_on' => $otp_expires_on                        
                ]
            );    



        /* send paypal email comparision code */

            $maildata = [                         
                'txnvc'=>$otp,       
                'username'=>$invoice->firstname.' '.$invoice->lastname,
                'mevlogourl'=>url('public/img/mev-logo-2.png')

            ];

            $useremail = $invoice->paypal_email;
            


            Mail::send('mails.payeremail', $maildata, function($message)use($useremail)
            {
                $message->to("$useremail", 'MyEmailVerifier')
                ->subject('Transaction Verification Code');

                 $message->from('support@myemailverifier.com',env('APP_NAME',' '));
            });

            
        /* --------- */    

        $data['invoice_id']=$invoice_id;

            $response = ['status'=>'1','msg'=>'<p>Since your PayPal email and Registered email is different, we need additional confirmation according to PayPal policy that you own your PayPal email address. We have sent a verification code to your PayPal email address. Please check inbox and confirm the Code. If it is not available in Inbox please check in spam folder and move it to inbox.</p><p> '.$useremail.' </p> ','data'=>$data]; 

        return response()->json($response);
    }

    public function hubspotAPIAuthReturn(Request $request){
        //echo "<pre>";print_r($request->all());exit;
        $code = $request->input('code');

        $hubspot_client_id = config('app.hubspot_client_id');                            
        $hubspot_client_secret = config('app.hubspot_client_secret');                            
        $hubspot_auth_redirect_url = config('app.hubspot_auth_redirect_url'); 

        

        $aweber_response = Http::withHeaders([
        'Content-Type' => "application/x-www-form-urlencoded;charset=utf-8",            
        ])->post("https://api.hubapi.com/oauth/v1/token",[ 
            'code'=>$code,
            'grant_type'=>'authorization_code',
            'client_id'=>$hubspot_client_id,
            'client_secret'=>$hubspot_client_secret,
            'redirect_uri'=>"$hubspot_auth_redirect_url",
        ]);       


        /*$aweber_response = Http::withHeaders([
            'Content-Type' => "application/x-www-form-urlencoded;charset=utf-8",            
            ])->post("https://api.hubapi.com/oauth/v1/token?grant_type=authorization_code&code=$code&client_id=$$hubspot_client_id&client_secret=$hubspot_client_secret&redirect_uri=$hubspot_auth_redirect_url");       */
        

        echo $aweber_response;exit;
    }

    public function getCCMembers(Request $request){
        $data = [];
        $response = ['status'=>'0','msg'=>'something went wrong!..','data'=>$data];
        $userid = session('userid');
        $postData = $request->all();
        $current_date = date("Y-m-d H:i");


        extract($postData);
        
        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    
        $listdata = DB::table('tbl_contact_list')->where('id', '=',"$listid")->first();   

        $client_id = config('app.cc_client_id');
        $client_secret = config('app.cc_secret_key');

        $access_token = $user->cc_access_token;
        $refresh_token = $user->cc_refresh_token;
        $apilistid=$listdata->api_id;

        if($current_date > $user->cc_token_expires){

            $authheader = base64_encode($client_id.":".$client_secret);

            $refreshtoken_response = Http::withHeaders([
            'Authorization' => "Basic $authheader",            
            ])->post("https://idfed.constantcontact.com/as/token.oauth2?refresh_token=$refresh_token&grant_type=refresh_token",[]);       


            $token_data = json_decode($refreshtoken_response);
            $access_token = $token_data->access_token;
            $refresh_token = $token_data->refresh_token;

                
            $token_expires = date('Y-m-d H:i', strtotime($current_date." + 7200 seconds"));

            $affected = DB::table('tbl_registration') 
                ->where('reg_id',$userid)
                ->update(
                    [
                        'cc_token_expires' => $token_expires,
                        'cc_refresh_token' => $refresh_token,
                        'cc_access_token'=>"$access_token",                        
                    ]
                );
        }

        $members_response = Http::withHeaders([
                'Authorization' => "Bearer $access_token",            
                ])->get("https://api.cc.email/v3/contacts?lists=$apilistid",[]);     
                        
        $new_contacts=[];
        if(isset($members_response['contacts']) && count($members_response['contacts']) > 0 ){
            $allmembers = $members_response['contacts'];
            foreach ($allmembers as $mymember) {
                //echo "<pre>";print_r($mymember);exit;
                $new_contacts[] = $mymember['email_address']['address'];    
            }
            
            $this->saveToCSV($new_contacts,$listdata->listname);

            $response = array('status'=>'1','msg'=>'List imported successfully! You will be notified via email once it is processed.','data'=>$data); 
        } 


        return response()->json($response);
    }

     public function contactContactAPIAuthReturn(Request $request){
        //echo "<pre>";print_r($request->all());exit;        
        $userid = session('userid');
        $auth_code = $request->input('code');

        $client_id = config('app.cc_client_id');
        $client_secret = config('app.cc_secret_key');
        $redirect_uri = config('app.cc_auth_redirect_url');


        /*$client_id = '45f0749b-7a09-4d6f-a690-20a47e126b9a';
        $client_secret = 'Wvf0yTn1bW3M-OW7Zld8Cw';
        $redirect_uri = 'http://harrytest.tk/contactContactAuthReturn';*/

        $authheader = base64_encode($client_id.":".$client_secret);
        $current_date = date("Y-m-d H:i");
        //echo $authheader;exit;

        $newtoken_response = Http::withHeaders([
            'Authorization' => "Basic $authheader",            
            ])->post("https://idfed.constantcontact.com/as/token.oauth2?code=$auth_code&grant_type=authorization_code&redirect_uri=$redirect_uri",[]);  

        $token_data = json_decode($newtoken_response);
        $access_token = $token_data->access_token;
        $refresh_token = $token_data->refresh_token;

            
        $token_expires = date('Y-m-d H:i', strtotime($current_date." + 7200 seconds"));

        $affected = DB::table('tbl_registration') 
                ->where('reg_id',$userid)
                ->update(
                    [
                        'cc_token_expires' => $token_expires,
                        'cc_refresh_token' => $refresh_token,
                        'cc_access_token'=>"$access_token",                        
                    ]
                );   
                            
        $api_base_url="https://api.cc.email/v3/contact_lists";
        $list_response = Http::withHeaders([
            'Authorization' => "Bearer $access_token",            
            ])->get($api_base_url,[]);          

        $list_response_obj = json_decode($list_response,true);                                
        //echo "<pre>";print_r($list_response_obj);exit;


        if(isset($list_response_obj['lists']) && count($list_response_obj['lists']) > 0 ){
            foreach ($list_response_obj['lists'] as $listitem) {
                $list_exists = DB::table('tbl_contact_list')
                                ->where('api_id',$listitem['list_id'])
                                ->where('library','constantcontact')
                                ->exists();

                if(!$list_exists){
                    $current_date = date('Y-m-d H:i:s');
                    $listAr = [
                        'listname'=>$listitem['name'],
                        'userid'=>$userid,
                        'library'=>"constantcontact",
                        'api_id'=>$listitem['list_id'],
                        'web_id'=>'',                            
                        'created_at'=>$current_date
                    ];

                    $id = DB::table('tbl_contact_list')->insertGetId($listAr);    
                } 
            }    
        }
        
        
        //echo "<pre>";print_r($list_response_obj);exit;
        return redirect('integration');
                   
    }

    public function getGetResponseMembers(Request $request){
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        $contact_response_ar = [];
        $current_date = date('Y-m-d H:i');
        $userid = session('userid');

        $postData = $request->input();                      
        extract($postData);
        //echo $rescode;exit;

        $client_id = config('app.getresponse_client_id');                            
        $client_secret = config('app.getresponse_secret_key');              
        
        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    
        $listdata = DB::table('tbl_contact_list')->where('id', '=',"$listid")->first();    

        $list_aweber_id = $listdata->api_id;        
        $getresponse_access_token = $user->getresponse_access_token;  
       

        /*echo $current_date;
        echo "<br/>".$user->getresponse_token_expires;
        exit;*/

        if($current_date > $user->getresponse_token_expires){
            

            $old_refresh_token = $user->getresponse_refresh_token;

            $newtoken_response = Http::withBasicAuth($client_id,$client_secret)
            ->post('https://api.getresponse.com/v3/token',[                    
                'grant_type'=>'refresh_token',
                'refresh_token'=>$old_refresh_token,
            ]);

            $newtoken_response_ar = json_decode($newtoken_response,true);
            
            if(isset($newtoken_response_ar['code']) && $newtoken_response_ar['code'] == '1014' ){
                $response = ['status'=>'0','msg'=>'Something went wrong.'];                
            }else{

                $new_token = $newtoken_response_ar['access_token'];
                $new_refresh_token = $newtoken_response_ar['refresh_token'];

                $token_expires_seconds = $newtoken_response_ar['expires_in'];
                $token_expires = date('Y-m-d H:i', strtotime($current_date." + $token_expires_seconds seconds"));

                $affected = DB::table('tbl_registration') 
                ->where('reg_id',$userid)
                ->update(
                    [
                        'getresponse_token_expires' => $token_expires,
                        'getresponse_refresh_token' => $new_refresh_token,
                        'getresponse_access_token'=>"$new_token",                        
                    ]
                );   
                
                $contact_response = Http::withHeaders([
                    'Authorization' => "Bearer $new_token",            
                    ])->get("https://api.getresponse.com/v3/contacts",[                    
                ]);  
            
                //echo $apiresponsetext;exit;
                $contact_response_ar = json_decode($contact_response);                                            
            }

            //echo "<pre>";print_r($contact_response_ar);exit;            
        }else{

            $contact_response = Http::withHeaders([
                'Authorization' => "Bearer $getresponse_access_token",            
                ])->get("https://api.getresponse.com/v3/contacts",[                    
            ]); 

            $contact_response_ar = json_decode($contact_response);
            //echo "<pre>";print_r($contact_response_ar);exit;                  
        } 
       

        if(count($contact_response_ar) <= 0){
            //echo "<pre>";print_r($aweber_response_ar);exit;
            $response = ['status'=>'0','msg'=>'Something went wrong.'];                
        }else{
            $new_contacts=[];
        
            foreach($contact_response_ar as $newmember){    

                $new_contacts[] = $newmember->email;                        
            }

            $this->saveToCSV($new_contacts,$listdata->listname);
            $response = ['status'=>'1','msg'=>'List imported successfully! You will be notified via email once it is processed.'];    
        }

        return response()->json($response);
    }


    public function getResponseAPIAuthReturn(Request $request){
        
        //echo "<pre>";print_r($request->all());exit;
        $userid = session('userid');
        $res_code = $request->input('code');

        $client_id = config('app.getresponse_client_id');                            
        $client_secret = config('app.getresponse_secret_key');       

        $response = Http::withBasicAuth($client_id,$client_secret)
        ->post('https://api.getresponse.com/v3/token',[                    
            'grant_type'=>'authorization_code',
            'code'=>"$res_code",
        ]);

        $responseAr = json_decode($response,true);
        //echo "<pre>";print_r($responseAr);exit;
        $current_date = date("Y-m-d H:i");
        $access_token = $responseAr['access_token'];
        $refresh_token = $responseAr['refresh_token'];
        $token_expires_seconds = $responseAr['expires_in'];
        $token_expires = date('Y-m-d H:i', strtotime($current_date." + $token_expires_seconds seconds"));

        $affected = DB::table('tbl_registration') 
            ->where('reg_id',$userid)
            ->update(
                [
                    'getresponse_token_expires' => $token_expires,
                    'getresponse_refresh_token' => $refresh_token,
                    'getresponse_access_token'=>"$access_token",                        
                ]
            ); 
        $contact_response = Http::withHeaders([
            'Authorization' => "Bearer $access_token",            
            ])->get("https://api.getresponse.com/v3/campaigns",[                    
        ]);                


        $contact_response_ar = json_decode($contact_response,true);

        if(count($contact_response_ar) > 0){
            foreach ($contact_response_ar as $contactlist){

                //echo "<pre>";print_r($contactlist);exit;
                
                $list_exists = DB::table('tbl_contact_list')
                            ->where('api_id',$contactlist['campaignId'])
                            ->where('library','getresponse')
                            ->exists();

                if(!$list_exists){
                    $current_date = date('Y-m-d H:i:s');
                    $listAr = [
                        'listname'=>$contactlist['name'],
                        'userid'=>$userid,
                        'library'=>"getresponse",
                        'api_id'=>$contactlist['campaignId'],
                        'web_id'=>'',                            
                        'created_at'=>$current_date
                    ];

                    $id = DB::table('tbl_contact_list')->insertGetId($listAr);    
                }  
            }
        }

        return redirect('integration');
    }

    public function getUserCouponCodes(){
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];

        $coupon_code_list =  DB::table('tbl_coupon_codes')->get();  

        if(!$coupon_code_list){
            $response = ['status'=>'0','msg'=>'','data'=>[]];
        }else{
            $response = ['status'=>'1','msg'=>'','data'=>$coupon_code_list];    
        }
        
        return response()->json($response);
        //echo "<pre>";print_r($coupon_code_list);exit;
    }

    public function givePercentage($newval=0,$totalval=0){
        $percentage=0;
        if($newval > 0 && $totalval > 0){
            $percentage = (($newval/$totalval)*100);
        }        
        return round($percentage)."%";
    }

    public function getAweberMembers(Request $request){
		$data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        $current_date = date('Y-m-d H:i');
        $userid = session('userid');

        $postData = $request->input();                      
        extract($postData);
        //echo $rescode;exit;

        $client_id = config('app.aweber_client_id');                            
        $client_secret = config('app.aweber_client_secret');              
        
        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    
        $listdata = DB::table('tbl_contact_list')->where('id', '=',"$listid")->first();    

        $list_aweber_id = $listdata->api_id;
        $aweber_account_id = $user->aweber_account_id;
        $aweber_access_token = $user->aweber_access_token;  
        //$aweber_access_token = "3XlDoAWyICpjtdKaYnx4eRpoX9wE76Rh";
        //echo "$list_aweber_id || $aweber_account_id || $aweber_access_token";
        if($current_date > $user->aweber_token_expires){

            $aweber_response =  Http::post('https://auth.aweber.com/oauth2/token',[ 
                'refresh_token'=>$user->aweber_refresh_token,
                'redirect_uri'=>url('Integration'),
                'client_id'=>$client_id,
                'client_secret'=>$client_secret,
                'grant_type'=>'refresh_token',
                'scope'=>'account.read,list.read,subscriber.read,subscriber.read-extended'
            ]);


            $apiresponsetext = $aweber_response->body(); 
            //echo $apiresponsetext;exit;
            $apiResponseObj = json_decode($apiresponsetext);
            //echo "<pre>";print_r($apiResponseObj);exit;
            $aweber_access_token = $apiResponseObj->access_token;      
            $new_refresh_token = $apiResponseObj->access_token;      

            $token_expires_seconds = $apiResponseObj->expires_in;
            $token_expires = date('Y-m-d H:i', strtotime($current_date." + $token_expires_seconds seconds"));

            $affected = DB::table('tbl_registration') 
            ->where('reg_id',$userid)
            ->update(
                [
                    'aweber_token_expires' => $token_expires,
                    'aweber_refresh_token' => $new_refresh_token,
                    'aweber_access_token'=>"$aweber_access_token",                        
                ]
            ); 
        }


        $aweber_response = Http::withHeaders([
            'Authorization' => "Bearer $aweber_access_token",            
            ])->get("https://api.aweber.com/1.0/accounts/$aweber_account_id/lists/$list_aweber_id/subscribers",[                    
        ]);                

        $aweber_response_ar = json_decode($aweber_response,true);                            

        if(isset($aweber_response_ar['error'])){
            //echo "<pre>";print_r($aweber_response_ar);exit;
            $response = ['status'=>'0','msg'=>'Something went wrong.'];                
        }else{
            $new_contacts=[];
        
            foreach($aweber_response_ar['entries'] as $newmember){    

                $new_contacts[] = $newmember['email'];                        
            }

            $this->saveToCSV($new_contacts,$listdata->listname);
            $response = ['status'=>'1','msg'=>'List imported successfully! You will be notified via email once it is processed.'];    
        }
        

        return response()->json($response);
    }

    public function getAweberAccount(){

    	$data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        $current_date = date('Y-m-d H:i');
        $userid = session('userid');

   		$aweber_access_token = session('aweber_access_token'); 	
   		session(['aweber_access_token'=>'']);
   		Session::forget('aweber_access_token');
   		//$aweber_access_token = "jfjFzffl9sSflJ7zYq5mWCpbEmqWyhmh";
   		$aweber_response = Http::withHeaders([
            'Authorization' => "Bearer $aweber_access_token",            
        	])->get('https://api.aweber.com/1.0/accounts',[                    
        ]);

        //echo $aweber_response;exit;	
        $account_id = $aweber_response['entries'][0]['id'];


        $aweber_list_response = Http::withHeaders([
            'Authorization' => "Bearer $aweber_access_token",            
        	])->get("https://api.aweber.com/1.0/accounts/$account_id/lists",[                    
        ]);

        //echo $aweber_list_response;exit;            

       	$affected = DB::table('tbl_registration')
        ->where('reg_id',$userid)
        ->update(
            [
                'aweber_access_token' => "$aweber_access_token",                                    
                'aweber_account_id' => "$account_id",                                    
            ]
        );                        	

        $aweber_list_response_ar = json_decode($aweber_list_response,true);
        //echo "<pre>";print_r($aweber_list_response_ar);exit;
        foreach($aweber_list_response_ar['entries'] as $awelist){

        	$list_exists = DB::table('tbl_contact_list')
                            ->where('api_id',$awelist['id'])
                            ->where('library','aweber')
                            ->exists();

            if(!$list_exists){
                $current_date = date('Y-m-d H:i:s');
                $listAr = [
                    'listname'=>$awelist['name'],
                    'userid'=>$userid,
                    'library'=>"aweber",
                    'api_id'=>$awelist['id'],
                    'web_id'=>'',                            
                    'created_at'=>$current_date
                ];

                $id = DB::table('tbl_contact_list')->insertGetId($listAr);    
            }                    
        }

       	//echo "<pre>";print_r($aweber_list_response_ar);exit;
        //echo "Account id: $aweber_list_response";exit;
        

        if(isset($aweber_list_response_ar['total_size']) && $aweber_list_response_ar['total_size'] > 0 ){

            $aweber_integration_list =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','aweber'],                                        
                        ])->get();        
            $data['aweber_contact_list'] = $aweber_integration_list;

            $response = ['status'=>'1','msg'=>'','data'=>$data];            
        }else{
            $response = ['status'=>'0','msg'=>'','data'=>[]];            
        }		
		return response()->json($response);
    }

    public function getAweberToken(Request $request){
        //echo url('integration');exit;
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        $userid = session("userid");
        $postData = $request->input();  
        $current_date = date('Y-m-d H:i');
        extract($postData); 

        $client_id = config('app.aweber_client_id');                            
        $client_secret = config('app.aweber_client_secret'); 

        $aweber_response =  Http::post('https://auth.aweber.com/oauth2/token',[ 
                                'code'=>$code,
                                'redirect_uri'=>'http://client.myemailverifier.com/Integration',
                                'client_id'=>$client_id,
                                'client_secret'=>$client_secret,
                                'grant_type'=>'authorization_code',
                                'scope'=>'account.read,list.read,subscriber.read,subscriber.read-extended'
                            ]);

                              
        $apiresponsetext = $aweber_response->body(); 
        $apiResponseObj = json_decode($apiresponsetext);
        //echo "<pre>";print_r($apiResponseObj);exit;
        $data = $apiResponseObj;
        $aweber_access_token = $apiResponseObj->access_token;

        $token_expires_seconds = $apiResponseObj->expires_in;
        $token_expires = date('Y-m-d H:i', strtotime($current_date." + $token_expires_seconds seconds"));

        session(['aweber_access_token'=>$aweber_access_token]);
        $affected = DB::table('tbl_registration')
        ->where('reg_id',$userid)
        ->update(
            [
                'aweber_token_expires' => $token_expires,
                'aweber_refresh_token' => $apiResponseObj->refresh_token,
                'aweber_access_token'=>"$aweber_access_token",    
                'aweber_auth_code' => "$code",                                    
            ]
        ); 


        $response = ['status'=>'1','msg'=>'','data'=>$data];
        return response()->json($response);
    }

    public function disconnectAPI(Request $request){
        $apiname=$request->input('apiname');
        $userid = session('userid'); 
        //$apiname="mailchimp";
        $token_field="";
        $disconnect_fields=[];

        if($apiname == 'mailchimp'){            
            $disconnect_fields=[
                "mailchimp_token" => "",                                    
            ];
        }

        if($apiname == 'aweber'){
            $disconnect_fields=[
                "aweber_access_token" => "",                                    
                "aweber_refresh_token" => "",
                "aweber_auth_code" => "",     
                "aweber_account_id" => "",
                "aweber_token_expires" => "",                                    
            ];
        }

        if($apiname == 'getresponse'){
            $disconnect_fields=[
                "getresponse_access_token" => "",                                    
                "getresponse_refresh_token" => "",
                "getresponse_token_expires" => "",                     
            ];
        }

        if($apiname == 'constantcontact'){
            $disconnect_fields=[
                "cc_access_token" => "",                                    
                "cc_refresh_token" => "",
                "cc_token_expires" => "",                     
            ];
        }

        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];  

        if(!empty($disconnect_fields)){
            $affected = DB::table('tbl_contact_list')
                    ->where('userid',$userid)
                    ->where('library',$apiname)
                    ->delete();

            $affected = DB::table('tbl_registration')
            ->where('reg_id',$userid)
            ->update($disconnect_fields);                           
        }
        

        $response = ['status'=>'1','msg'=>'','data'=>$data]; 
        return response()->json($response);
    }

    public function saveToCSV($email_list_str='',$listname="")    
    {
        
        $userid = session('userid'); 
        $curdate =  date("Y-m-d H:i");
        $newfilename = rand().time().".csv";    
        $newfile_path = base_path("storage/app/public/uploads/$userid/$newfilename");   
        $newfile_dir = base_path("storage/app/public/uploads/$userid");   
        $actual_file_path = "uploads/$userid/$newfilename"; 

        if(!File::exists($newfile_dir)) {
            File::makeDirectory($newfile_dir, $mode = 0777, true, true);
        }
        //File::makeDirectory($newfile_dir, $mode = 0777, true, true);

        /*$myfile = fopen($newfile_path, 'w+');            
        //fputcsv($myfile,$email_list);        
        fwrite($myfile,$email_list_str);               
        fclose($myfile);            */

        file_put_contents($newfile_path, $email_list_str);

        $id = DB::table('user_upload')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$listname,
                    'user_given_name'=>$listname,
                    'file_path'=>$actual_file_path,       
                    'xls_file_path'=>'',         
                    'user_id'=>$userid,                
                    'created_at'=>$curdate,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>0,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );
    }

    public function getUserIntegrations(Request $request){
        $userid = session('userid');
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        $data['mailchimp_contact_list'] = '';

        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    

        $mailchimp_client_id = config('app.mailchimp_client_id');                            
        $mailchimp_client_secret = config('app.mailchimp_client_secret'); 
        $aweber_client_id = config('app.aweber_client_id');                            
        $aweber_client_secret = config('app.aweber_client_secret'); 
        $getresponse_client_id = config('app.getresponse_client_id');                            

        $cc_client_id = config('app.cc_client_id');                            
        $cc_auth_redirect_url = config('app.cc_auth_redirect_url');                             
        

        $hubspot_client_id = config('app.hubspot_client_id');                            
        $hubspot_auth_redirect_url = config('app.hubspot_auth_redirect_url');                            

        $oauth_callback = 'http://client.myemailverifier.com/Integration';

        $data['aweber_client_id'] = $aweber_client_id; 
        $data['aweber_client_secret'] = $aweber_client_secret;

        $data['mailchimp_auth_url']  = "https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=$mailchimp_client_id&redirect_uri=$oauth_callback";

        $data['aweber_auth_url']  = "https://auth.aweber.com/oauth2/authorize?response_type=code&client_id=$aweber_client_id&client_secret=$aweber_client_secret&redirect_uri=$oauth_callback&scope=account.read list.read subscriber.read subscriber.read-extended&state=asdfglkjhasdfg";

        $data['getresponse_auth_url']  = "https://app.getresponse.com/oauth2_authorize.html?response_type=code&client_id=$getresponse_client_id&state=xyz";    

        $data['cc_auth_url']  = "https://api.cc.email/v3/idfed?client_id=$cc_client_id&redirect_uri=$cc_auth_redirect_url&response_type=code&scope=account_read+contact_data+campaign_data";    

        $data['hubspot_auth_url']  = "https://app.hubspot.com/oauth/authorize?client_id=".$hubspot_client_id."&redirect_uri=".$hubspot_auth_redirect_url."&scope=contacts%20oauth";    




        if($user->mailchimp_token != ''){
            $data['mailchimp_connected'] = 'y';

            $integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],
                            ['library','=','mailchimp'],                                        
                        ])->get();
            $data['mailchimp_contact_list'] = $integration;
        }else{
            $data['mailchimp_connected'] = 'n';
        }

        if(trim($user->aweber_access_token) != ''){
            $data['aweber_connected'] = 'y';

            $aweber_integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','aweber'],                                        
                        ])->get();
            $data['aweber_contact_list'] = $aweber_integration;
        }else{
            $data['aweber_connected'] = 'n';
        }

        if(trim($user->getresponse_access_token) != ''){
            $data['getresponse_connected'] = 'y';

            $getresponse_integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','getresponse'],                                        
                        ])->get();
            $data['getresponse_contact_list'] = $getresponse_integration;
        }else{
            $data['getresponse_connected'] = 'n';
        }

        if(trim($user->cc_access_token) != ''){
            $data['cc_connected'] = 'y';

            $cc_integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','constantcontact'],                                        
                        ])->get();
            $data['cc_contact_list'] = $cc_integration;
        }else{
            $data['cc_connected'] = 'n';
        }

        $response = ['status'=>'1','msg'=>'','data'=>$data]; 

        return response()->json($response);

        //echo "<pre>";print_r($integration);exit;
    }

    public function getUserIntegrations_old(Request $request){
        $userid = session('userid');
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        $data['mailchimp_contact_list'] = '';

        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    

        $mailchimp_client_id = config('app.mailchimp_client_id');                            
        $mailchimp_client_secret = config('app.mailchimp_client_secret'); 
        $aweber_client_id = config('app.aweber_client_id');                            
        $aweber_client_secret = config('app.aweber_client_secret'); 
        $getresponse_client_id = config('app.getresponse_client_id');                            

        $cc_client_id = config('app.cc_client_id');                            
        $cc_auth_redirect_url = config('app.cc_auth_redirect_url');                            
        

        $hubspot_client_id = config('app.hubspot_client_id');                            
        $hubspot_auth_redirect_url = config('app.hubspot_auth_redirect_url');                            

        $oauth_callback = url('Integration');

        $data['aweber_client_id'] = $aweber_client_id;
        $data['aweber_client_secret'] = $aweber_client_secret;

        $data['mailchimp_auth_url']  = "https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=$mailchimp_client_id&redirect_uri=$oauth_callback";

        $data['aweber_auth_url']  = "https://auth.aweber.com/oauth2/authorize?response_type=code&client_id=$aweber_client_id&client_secret=$aweber_client_secret&redirect_uri=$oauth_callback&scope=account.read list.read subscriber.read subscriber.read-extended&state=asdfglkjhasdfg";

        $data['getresponse_auth_url']  = "https://app.getresponse.com/oauth2_authorize.html?response_type=code&client_id=$getresponse_client_id&state=xyz";    

        $data['cc_auth_url']  = "https://api.cc.email/v3/idfed?client_id=$cc_client_id&redirect_uri=$cc_auth_redirect_url&response_type=code&scope=account_read+contact_data+campaign_data";    

        $data['hubspot_auth_url']  = "https://app.hubspot.com/oauth/authorize?client_id=".$hubspot_client_id."&redirect_uri=".$hubspot_auth_redirect_url."&scope=contacts%20oauth";    




        if($user->mailchimp_token != ''){
            $data['mailchimp_connected'] = 'y';

            $integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],
                            ['library','=','mailchimp'],                                        
                        ])->get();
            $data['mailchimp_contact_list'] = $integration;
        }else{
            $data['mailchimp_connected'] = 'n';
        }

        if(trim($user->aweber_access_token) != ''){
            $data['aweber_connected'] = 'y';

            $aweber_integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','aweber'],                                        
                        ])->get();
            $data['aweber_contact_list'] = $aweber_integration;
        }else{
            $data['aweber_connected'] = 'n';
        }

        if(trim($user->getresponse_access_token) != ''){
            $data['getresponse_connected'] = 'y';

            $getresponse_integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','getresponse'],                                        
                        ])->get();
            $data['getresponse_contact_list'] = $getresponse_integration;
        }else{
            $data['getresponse_connected'] = 'n';
        }

        if(trim($user->cc_access_token) != ''){
            $data['cc_connected'] = 'y';

            $cc_integration =  DB::table('tbl_contact_list')
                        ->select('id','listname as name')
                        ->where([
                            ['userid','=',$userid],            
                            ['library','=','constantcontact'],                                        
                        ])->get();
            $data['cc_contact_list'] = $cc_integration;
        }else{
            $data['cc_connected'] = 'n';
        }

        $response = ['status'=>'1','msg'=>'','data'=>$data]; 

        return response()->json($response);

        //echo "<pre>";print_r($integration);exit;
    }

    public function getMailchimpListInfo(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        $current_date = date('Y-m-d H:i:s');       
        extract($postData);
        //echo $rescode;exit;
        $userid = session('userid');
        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    
        $listdata = DB::table('tbl_contact_list')->where('id', '=',"$listid")->first();    
        $list_mailchimp_id = $listdata->api_id;


        $access_token = $user->mailchimp_token;
        $em_api = "mailchimp";    
        if($em_api == "mailchimp"){

            /*$mailchimp_client_id = "643485622935";                            
            $mailchimp_client_secret = "78f2f505b9cc4891bfab1635609883d982a596fb5b9a0185a4";              
            $oauth_callback = "http://kaushalproject.com/myadmin/Integration";               */

            $mailchimp_client_id = config('app.mailchimp_client_id');                            
            $mailchimp_client_secret = config('app.mailchimp_client_secret');              
            //$oauth_callback = url('Integration');
            $oauth_callback='https://client.myemailverifier.com/Integration';
            

            $url = 'https://login.mailchimp.com/oauth2/metadata';
            $context = stream_context_create([
            'http' => [
              'header' => "Authorization: OAuth $access_token",
                ],
            ]);
            $result = file_get_contents($url, false, $context);
            $decoded = json_decode($result);
            $dc = $decoded->dc;

              
            /* Curl example */
                          

             /* get list members  */
            // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch_member = curl_init();


            //curl_setopt($ch_member,CURLOPT_URL,"https://us10.api.mailchimp.com/3.0/lists/$listid/members?offset=150&count=10");
            curl_setopt($ch_member,CURLOPT_URL,"https://$dc.api.mailchimp.com/3.0/lists/$list_mailchimp_id/members");
            curl_setopt($ch_member, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch_member, CURLOPT_CUSTOMREQUEST, 'GET');


            $headers = array();
            $headers[] = 'Authorization: OAuth '.$access_token;
            curl_setopt($ch_member, CURLOPT_HTTPHEADER, $headers);

            $member_result = curl_exec($ch_member);            
            if (curl_errno($ch_member)) {
                echo 'Error:' . curl_error($ch_member);
            }
            curl_close($ch_member);
            $member_list_info = json_decode($member_result);     

            $new_contacts_str="";
            foreach($member_list_info->members as $newmember){    

                $tmpele = array($newmember->email_address);
                //$new_contacts[] = $newmember->email_address;            
                $new_contacts_str.= strip_tags($newmember->email_address)." \n ";                            
            }

            $this->saveToCSV($new_contacts_str,$listdata->listname);

            $response = ['status'=>'1','msg'=>'List imported successfully! You will be notified via email once it is processed.'];

            return response()->json($response);
            exit;

                  /* ------------- */            
        }
    }

    public function sendInquiryMessage(Request $request){
        $userid = session('userid'); 
        $data = [];
        $response = ['status'=>'1','msg'=>''];    

        $subject = $request->input('subject');
        $newmessage = $request->input('message');

        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    



        /* Send Email */
            $supportmaildata = array(                         
                'username'=>$user->firstname." ".$user->lastname,
                'email'=>$user->email,
                'subject'=>htmlentities($subject),
                'messages'=>htmlentities($newmessage),
                'mevlogourl'=>url('public/img/mev-logo-2.png'),
            );
 
            //echo "<pre>";print_r($supportmaildata);exit;
            $useremail = $user->email;  
            $newvar  = view('mails.support_message',$supportmaildata)->render();
            /*echo $newvar;exit;*/


            $admins_email=[                    
                    'jpatadiya@gmail.com',
                    'piyushpatdiya@gmail.com',
                    'kaushal.accuwebhosting@gmail.com',                    
            ];

            $mailusers=[
                        'useremail'=>$useremail,
                        'admins_email'=>$admins_email,
                    ];

            Mail::send('mails.support_message',$supportmaildata,function($message)use($mailusers)
            {
                $message->to('support@myemailverifier.com', 'MyEmailVerifier')
                ->subject('User Inquiry');

                $message->from($mailusers['useremail'],"");
            });

            Mail::send('mails.support_message',$supportmaildata,function($message)use($mailusers)
            {
                $message->to($mailusers['admins_email'], 'MyEmailVerifier')
                ->subject('Internal: User Inquiry');

                $message->from('support@myemailverifier.com',"");
            });

           

            $response = ['status'=>'1','msg'=>'Your message has been sent successfully!...'
            ,'data'=>$data];

        /* ---------------------------- */


        return response()->json($response);

    }

    public function requestAfflAccount(){
        $userid = session('userid');    
        $data = [];
        $response = ['status'=>'1','msg'=>'']; 
        $user = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();    

        $affected = DB::table('tbl_registration')
        ->where('reg_id',$userid)
        ->update(
            [
                'affiliate_request' => "y",                    
                'affiliate_url' => '',                    
            ]
        );   

        /* Send Email */
            $maildata = [                         
                'username'=>$user->firstname." ".$user->lastname,
                'email'=>$user->email,
                'mevlogourl'=>url('public/img/mev-logo-2.png'),
            ];

            //$useremail = $user->email;
            $senderemail = $user->email;
            $supportemail = "support@myemailverifier.com";
            Mail::send('mails.affiliate_request', $maildata, function($message)use($supportemail,$senderemail)
            {
                $message->to($supportemail, 'MyEmailVerifier')
                ->subject('Affilliate Request');

                 $message->from($senderemail,env('APP_NAME',' '));
            });

            $response = ['status'=>'1','msg'=>'Password reset link sent on your email address. !...'
            ,'data'=>$data];

        /* ---------------------------- */


        return response()->json($response); 

    }

    public function checkMyAffiliateAccount(){
        $userid = session('userid');    
        $userdata = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();
        $data = [];
        
        //$newuser  = (array)$userdata;
        $data = array(
            'affiliate_request'=>$userdata->affiliate_request,
            'affiliate_url'=>$userdata->affiliate_url
        );

        $response = ['status'=>'1','msg'=>'','data'=>$data];                           

        return response()->json($response);
    }

    public function createAfflAccount(){
        $userid = session('userid');
        $userdata = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();
        $newuser  = (array)$userdata;
        //echo "<pre>";print_r($newuser);exit;

        /*$response = Http::post('https://myemailverifier.idevaffiliate.com/API/scripts/new_affiliate.php', [
                        'secret' => config('app.affil_api_secret'),
                        'email' => $userdata->email,
                        'password' => $userdata->password,
                        'username'=>$userdata->firstname.$userdata->lastname,
                        'first_name'=>$userdata->firstname,
                        'last_name'=>$userdata->lastname
                    ]);*/
    }

    public function checksessionmessage(){
        $data = [];
        $response = ['status'=>'1','sessMsg'=>session('toastr_msg')]; 
        session(['toastr_msg'=>'']);
        Session::forget('toastr_msg');

        return response()->json($response);

        //return session('toastr_msg');

        exit;
    }

    public function checkusersession(){
        
        $data = [];
        $response = ['status'=>'']; 

        if(session('userid') <= 0 || session('userid') == NULL || session('userid') == '' ){ 
            //header("Refresh:0");
            $response = ['status'=>'0','msg'=>'','data'=>$data];  

        }else{                    

            $userid = session('userid');

            $user = DB::table('tbl_registration')->where('reg_id',$userid)->first(); 

            $last_login = $user->last_active;
            $current_time = date("Y-m-d H:i:s");
            

            $last_login_obj=date_create("$last_login");
            $current_time_obj=date_create("$current_time");
            $diff=date_diff($last_login_obj,$current_time_obj);
            

            $interval_days = $diff->format("%a");
            $interval_hours = $diff->format("%h");

            //echo "Diff: $interval_days  Hours: $interval_hours ";exit;

            /*if($interval_hours >=1 || $interval_days >= 1){
                $response = ['status'=>'0','msg'=>'','data'=>$data];                           
                return response()->json($response);
                exit;

            }*/


            $data = array('userid'=>session('userid'),'is_admin'=>session('is_admin'),'sessMsg'=>session('toastr_msg'));
            $response = ['status'=>'1','msg'=>'','data'=>$data];                       
        }

        
        //return response('',401);
        return response()->json($response);
        exit;
    }

    public function getUploadedFileInfo($api_key="",$fileid=0){

        $userdata = DB::table('tbl_registration')->where('apikey', '=',"$api_key")->first();
        if(!$userdata){
            $response = ['status'=>'false','msg'=>'Invalid API Key']; 
            return response()->json($response); 
            exit; 
        }

        $filedata = DB::table('user_upload')->where(
            [
                ['upload_id', '=',"$fileid"],
                ['user_id', '=',$userdata->reg_id]            
            ]    
        )->first();


        if(!$filedata){
            $response = ['status'=>'false','msg'=>'Invalid Request']; 
            return response()->json($response); 
            exit; 
        }

       
        $csvfile_url = url('downloadreport/csv/'.$filedata->upload_id);
        $xlsfile_url = url('downloadreport/xls/'.$filedata->upload_id);

        $newfiledata = array(
            'upload_id'=>$filedata->upload_id,
            'filename'=>$filedata->file_name,
            'file_path'=>$csvfile_url,
            'xls_file_path'=>$xlsfile_url,
            'user_id'=>$filedata->user_id,
            'created_at'=>$filedata->created_at,
            'ready_for_download'=>$filedata->ready_for_download,
            'valid'=>$filedata->valid,
            'invalid'=>$filedata->invalid,
            'unknown'=>$filedata->unknown,
            'credit_used'=>$filedata->credit_used,
            'total'=>$filedata->total,
            'status'=>$filedata->status,
        );

        //echo json_encode($newfiledata,JSON_UNESCAPED_SLASHES);exit;
        
        return response()->json($newfiledata,200,[],JSON_UNESCAPED_SLASHES); 

    }

    public function uploadmyfile(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'false']; 
        extract($postData);

        if(!$request->hasFile('filename')){
            $response = ['status'=>'false','msg'=>'Please upload a file']; 
            return response()->json($response); 
            exit; 
        }

        $userdata = DB::table('tbl_registration')->where('apikey', '=',"$api_key")->first();
        if(!$userdata){
            $response = ['status'=>'false','msg'=>'Invalid API Key']; 
            return response()->json($response); 
            exit; 
        }
        $userid = $userdata->reg_id;

        $extension = $request->file('filename')->extension();

        if($extension == 'csv' || $extension == 'txt'){
            
            $newfilename = rand().time().'.'.$extension;

            $orgname =  $request->file('filename')->getClientOriginalName();
            /*$orgname = str_replace("'","",$orgname);
            $orgname = str_replace("#","",$orgname);
            $orgname = str_replace("@","",$orgname);*/

            $path = $request->file('filename')->storeAs("uploads/$userid","$orgname");
            $curdate = date("Y-m-d H:i:s");

            $id = DB::table('user_upload')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$orgname,
                    'user_given_name'=>$orgname,
                    'file_path'=>$path,       
                    'xls_file_path'=>'',         
                    'user_id'=>$userid,                
                    'created_at'=>$curdate,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>0,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );

            if($id > 0){
                $response = [
                    'status'=>'true',
                    'filename'=>$orgname,
                    'file_id'=>$id,
                    "msg"=>"File Uploaded Successfully"
                ];             
            }else{
                $response = [
                    'status'=>'false',                
                    "msg"=>"Failed to Upload the File"
                ];             
            }
        }else{
            $response = ['status'=>'false','msg'=>'Please upload only csv file']; 
        }
                    
        return response()->json($response);        

    }    


    public function getcredits($apikey=""){

        $userdata = DB::table('tbl_registration')->where('apikey', '=',"$apikey")->first();                         
        if(!$userdata){
            $response = ['status'=>false,'message'=>'Invalid API Key'];     
        }else{
            $usercredits = $userdata->credits;
            $response = ['credits'=>"$usercredits"];     
        }

        return response()->json($response);        
    }

    public function validate_single($email='',$apikey=''){
        $data = [];

        $userdata = DB::table('tbl_registration')->where('apikey', '=',"$apikey")->first(); 


        if(!$userdata){
            $response = ['status'=>false,'message'=>'Invalid API Key'];     
            return response()->json($response);
            exit;
        }

        $userid = $userdata->reg_id;

        /* new code */

        //$userdata = DB::table('tbl_registration')->where('email', '=',"$email")->first();     
        if($userdata->credits < 1){
            $response = ['status'=>'0','msg'=>'You do not have enough credits.'];
            return response()->json($response);
            exit;
        }

        $apiresponse = Http::get('http://23.239.201.239/api/validate_inside.php?apikey=rapid_solo&email='.
            $email);

        $apiresponsetext = $apiresponse->body(); 
        $apiResponseObj = json_decode($apiresponsetext);
        //echo "<pre>";print_r($apiResponseObj);exit;

        $affected = DB::update("update tbl_registration
                                SET credits =  credits - 1 WHERE reg_id = $userid "); 

        $emailusername = explode("@",$email);
        //echo "<pre>";print_r($emailusername);exit;
        $email_user = $emailusername[0];
        $email_domain = $emailusername[1];

        /* ------ */

        //$useremail = $userdata->email;
        
        $created_at = date("Y-m-d H:i:s");

        $id = DB::table('api_log')->insertGetId(
            [   
                'user_id'=>$userdata->reg_id,                   
                'apikey'=>$apikey,
                'email'=>$email,
                'status'=>'true',
                'result'=>$apiresponsetext,
                'created_at'=>$created_at,
            ]
        );

        header('Access-Control-Allow-Origin: *');
        //return $apiresponsetext;
        return response($apiresponsetext);
    }

    public function setfilemode(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);

        $uploadsinfo = DB::table('user_upload')->where('upload_id',$fileid)->first();
        $credits_need = $uploadsinfo->credits_need;
        $user_id = $uploadsinfo->user_id;



        if($mymode == "rapid"){
            $ready_for_download = "5";
        }

        if($mymode == "relax"){
            $ready_for_download = "0";
        }

        $affected = DB::table('user_upload')
        ->where(
            [
                'upload_id'=>$fileid,                
            ]
        )
        ->update(
            [
                'ready_for_download'=>$ready_for_download,
            ]
        ); 


        $affected2 = DB::update("update  tbl_registration   
        SET blocked_credits = blocked_credits + $credits_need, credits =  credits - $credits_need 
        WHERE reg_id = $user_id "); 
         

        $response = ['status'=>'1','msg'=>'','data'=>$data]; 

        return response()->json($response);
    }

    public function checkUploadResults($fileid=0){

        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 

        if($fileid <= 0){
            return response()->json($response);
            exit;
        }


        $filedata = DB::table('user_upload')->where('upload_id', '=',"$fileid")->first();

        if(!$filedata){
            $response = ['status'=>'0','msg'=>'','data'=>$filedata];              
        }else{
            

            $csvfile_path = "";
            $csvfile_url = "";

            $xlsfile_path = "";
            $xlsfile_url = "";
 
            $downloadable=false;
            if($filedata->ready_for_download > 0){
                $downloadable=true;
            }

            $csvfile_path = base_path('public/storage/'.$filedata->file_path);
            if(trim($filedata->file_path) != '' && file_exists($csvfile_path)){
                $csvfile_url = asset("public/storage/".$filedata->file_path);
                //$downloadable=true;
            }

            $xlsfile_path = base_path('public/storage/'.$filedata->xls_file_path);
            if(trim($filedata->xls_file_path) != '' && file_exists($xlsfile_path)){
                $xlsfile_url = asset("public/storage/".$$filedata->xls_file_path);
                //$downloadable=true;
            }

            $filedata->file_path = $csvfile_url;
            $filedata->xls_file_path = $xlsfile_url;
            $filedata->downloadable = $downloadable;

            $response = ['status'=>'1','msg'=>'','data'=>$filedata];         
        }
        
                
        return response()->json($response);    
    }

    public function deletethisfile($fileid=0){

        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 


        $filedata = DB::table('user_upload')->where('upload_id', '=',"$fileid")->first();
        $affected = DB::table('user_upload')->where('upload_id',$fileid)->delete();

        if($affected > 0){
            
            //echo "<pre>";print_r($filedata);exit;

            Storage::delete($filedata->file_path);
            $response = ['status'=>'1','msg'=>'File Deleted Successfully!...','data'=>$data];
        }

        return response()->json($response);
    }

    public function getcsvinfo(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);

        $invalid_csv_values=false;
        $no_of_lines=0;        

        try {
                  

            if(!$request->file('csvfile') || $request->file('csvfile') == ''){ 
                $response = ['status'=>'0','msg'=>'Please select a file','data'=>$data];
                return response()->json($response);  
                exit;    
            }
        }catch(Exception $e) {
          
            $response = ['status'=>'0','msg'=>'Your file contains invalid format data','data'=>$data];
            return response()->json($response);  
            exit;    
        }    

        $userid = session('userid');        
        $extension = $request->file('csvfile')->getClientOriginalExtension();
        $originalfilename = $request->file('csvfile')->getClientOriginalName();

        if(strlen($originalfilename) > 95){
            $response = ['status'=>'0','msg'=>'filename is too long.','data'=>$data,'error_type'=>'invalid_file']; 
            return response()->json($response);
            exit;
        }

        
        if($extension != 'csv' && $extension != 'txt' && $extension != 'xlsx' && $extension != 'xls'){
            $response = ['status'=>'0','msg'=>'Incorrect File uploaded. Allowed files are txt,csv,xls,xlsx','data'=>$data,'error_type'=>'invalid_file']; 
        }else{

            $newfilename = rand().time().'.'.$extension;            

            $orgname =  $request->file('csvfile')->getClientOriginalName();
            $orgname = $this->cleanJunkChars($orgname);
            $filesize =  $request->file('csvfile')->getSize();

            $orgname = str_replace("'","",$orgname);
            $orgname = str_replace("#","",$orgname);
            $orgname = str_replace("@","",$orgname);

            $filename_repeat = DB::table('user_upload')
                ->where(
                        ['file_name'=>$orgname], 
                        ['userid'=>$userid]                        
                    )
                ->count();

            if($filename_repeat > 0){
                $file_path_info = pathinfo($orgname);
                $filename_without_ext = $file_path_info['filename'];
                $filename_without_ext.='_'.time();
                $orgname = $filename_without_ext.".".$extension;
            }

            $path = $request->file('csvfile')->storeAs("public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."$userid".DIRECTORY_SEPARATOR,"$orgname");

            //echo " csv path: ".$path;exit;

            $gudpath = str_replace("public".DIRECTORY_SEPARATOR,"",$path);
            $curdate = date("Y-m-d H:i:s");
            

            /*  csv new process demo  */
            $fullfilepath = base_path("$path");   
            $filestoragepath = storage_path("app/$path");
                                
            
            $line_of_text=[];
            $lewline_Ar=[];
                                                    
            if($extension == 'xls' || $extension == 'xlsx'){

            	//$helper = \yidas\phpSpreadsheet\Helper::newSpreadsheet($filestoragepath);
                $mydata = \yidas\phpSpreadsheet\Helper::newSpreadsheet($filestoragepath)->getRows();
                $no_of_lines = count($mydata);

				/*while ($xlsrow = $helper->getRow()) {
					
					$email_cols = 0;
	                foreach($xlsrow as $key=>$val){
	                    
	                    if($email_cols > 1){
	                        $invalid_csv_values=true;
	                        break;
	                    }                    

	                    $single_new_Ar[] = $val;                    
	                    if($this->valid_email($val)){
	                        
	                        $email_cols = $email_cols + 1;
	                        
	                    }                    
	                }
	                

	                if($email_cols >= 1){
	                    $no_of_lines = $no_of_lines + 1;     
	                }

	                $lewline_Ar[] = $single_new_Ar; 		               
				}    */        	
            }else{

            	$csvfile = fopen($filestoragepath,"r");
            	while(!feof($csvfile))
	            {
	                //$singleline = fgets($csvfile);
                    $singlelineAr = fgetcsv($csvfile);
                    if(!is_array($singlelineAr)){
                        continue;
                    }
                    $no_of_lines = $no_of_lines + 1; 
	               
	               
	                

	                /*if($email_cols >= 1){
	                    $no_of_lines = $no_of_lines + 1;     
	                }

	                $lewline_Ar[] = $single_new_Ar;  */              
	            }
	            fclose($csvfile);

	            /*$writefile = fopen($filestoragepath,"w");
	            foreach ($lewline_Ar as $line) {
	              fputcsv($writefile, $line,',',' ');
	            }
	            fclose($writefile);        */
            }            
            
            

            $userinfo = DB::table('tbl_registration')->where('reg_id',$userid)->first();

            if($no_of_lines > $userinfo->credits){

            	$data['no_of_lines'] = $no_of_lines;           
            	$data['available_credits'] = $userinfo->credits; 
            	Storage::delete($path);

            	$response = ['status'=>'0','msg'=>'You don\'t have sufficient credits to process this file. Please purchase credits','data'=>$data,'error_type'=>'credit_limit']; 
                return response()->json($response);
                exit;
            }


            if($invalid_csv_values){
                
                Storage::delete($path);
                $response = ['status'=>'0','msg'=>'File contains Invalid data format.each row should have only one email value ','data'=>$data,'error_type'=>'invalid_format']; 
                return response()->json($response);
                exit;
            }

            /* replace directory seperator */

                $new_gud_path = str_replace("/",DIRECTORY_SEPARATOR,$gudpath);      
                

            /* ----------- */

            $id = DB::table('user_dummy_uploads')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$orgname,
                    'user_given_name'=>$orgname,
                    'filesize'=>$filesize,
                    'credits_need'=>$no_of_lines,
                    'file_path'=>$new_gud_path, 
                    'xls_file_path'=>'',                                 
                    'user_id'=>$userid,                
                    'created_at'=>$curdate,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>$no_of_lines,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );

            
            //echo "<pre>";print_r($lewline_Ar);exit;

            
            /*$batchInsert=[];
            foreach ($line_of_text[0] as $key => $value){

                $batchInsert[]=array(
                    'upload_id'=>$id,
                    'email'=>$value,
                    'is_processed'=>'n',
                    'status'=>'',
                    'free_domain'=>'',
                    'catch_all'=>'',
                    'updated_at'=>$curdate,
                    'created_at'=>$curdate,
                );
            }
            if(!empty($batchInsert)){
                DB::table('tbl_upload_progress')->insert($batchInsert);    
            }*/
            
                        
            /* ------ */                
            //echo "<pre>";print_r($userinfo);exit;        

            $data = ['filename'=>$orgname,'created_on'=>date('d-m-Y'),"fileid"=>$id];
            $data['no_of_lines'] = $no_of_lines;           
            $data['available_credits'] = $userinfo->credits;           
            $response = ['status'=>'1','msg'=>'File uploaded successfully','data'=>$data]; 
        }
              
		
        return response()->json($response);
    }

    public function processemailist(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);

        if(!$request->file('csvfile') || $request->file('csvfile') == ''){ 
            $response = ['status'=>'0','msg'=>'Please select a file','data'=>$data];
            return response()->json($response);  
            exit;    
        }

        $userid = session('userid');
        $csvFileObj = $request->file('csvfile');        
        $extension = $request->file('csvfile')->getClientOriginalExtension();
        

        if($extension != 'csv' && $extension != 'txt' && $extension != 'xls' && $extension != 'xlsx'){
            $response = ['status'=>'0','msg'=>'Allow files are txt,csv,xlx,xlxs','data'=>$data]; 
        }else{
            $newfilename = rand().time();
            $filenameithoutextnsn=$newfilename;
            $newfilename = $newfilename.'.'.$extension;            
            $orgname =  $request->file('csvfile')->getClientOriginalName();


            $path = $request->file('csvfile')->storeAs("public/uploads/$userid","$orgname");

            $gudpath = str_replace("public/","",$path);
            $curdate = date("Y-m-d H:i:s");

            $id = DB::table('user_upload')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$orgname,
                    'user_given_name'=>$orgname,
                    'file_path'=>$gudpath, 
                    'xls_file_path'=>'',                                 
                    'user_id'=>$userid,                
                    'created_at'=>$curdate,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>0,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );
            exit;
            /*  csv new process demo  */
            $fullfilepath = base_path("$path");   
            $filestoragepath = storage_path("app/$path");

            $newxlspath = storage_path("app/public/uploads/$userid/$filenameithoutextnsn");            

            //echo $filestoragepath;exit;            
            $lewline_Ar=[];    

            if($extension == 'xls' || $extension == 'xlsx' ){

                $xlshelper = \yidas\phpSpreadsheet\Helper::newSpreadsheet($filestoragepath);
                while ($xlsrow = $xlshelper->getRow()) {
                    
                    $email_cols = 0;
                    $single_new_Ar=[];

                    foreach($xlsrow as $key=>$val){
                        
                        if($email_cols > 1){
                            $invalid_csv_values=true;
                            break;
                        }                    

                        $single_new_Ar[] = $val;                    
                        if($this->valid_email($val)){
                            
                            $email_cols = $email_cols + 1;
                            $single_new_Ar[] = "Is Processed";    
                            $single_new_Ar[] = "Status";    
                            $single_new_Ar[] = "Free Domain";    
                            $single_new_Ar[] = "Catch All";
                        }                    
                    }
                    
                    
                    $lewline_Ar[] = $single_new_Ar;     

                    /* write new xls file */
                    /*$newxlsname = rand().time().'.'.$extension;    
                    $newxlsfullpath = storage_path("app/public/uploads/$userid");               
                    //echo $newxlsfullpath;exit;
                    $newxlsdata = \yidas\phpSpreadsheet\Helper::newSpreadsheet($filestoragepath)
                    ->addRows($lewline_Ar);*/

                    /* ----- */
                }  
               

                $newxls = \yidas\phpSpreadsheet\Helper::newSpreadsheet()
                ->addRows($lewline_Ar)
                ->save($newxlspath);

                $verynewfilename = $newxlspath.="xlsx";

            }

            if($extension == 'csv' || $extension == 'txt' ){
             
                $csvfile = fopen($filestoragepath,"r");
                $line_of_text=[];
                $lewline_Ar=[];
                while(!feof($csvfile))
                {
                    $singleline = fgets($csvfile);
                    $singlelineAr = explode(",",$singleline);                
                    $single_new_Ar=[];

                    foreach($singlelineAr as $key=>$val){
                        $single_new_Ar[] = $val;
                        if(filter_var($val, FILTER_VALIDATE_EMAIL)){
                            $single_new_Ar[] = "Is Processed";    
                            $single_new_Ar[] = "Status";    
                            $single_new_Ar[] = "Free Domain";    
                            $single_new_Ar[] = "Catch All";    
                        }
                    }
                    $lewline_Ar[] = $single_new_Ar;
                    //echo $singleline."<br />";
                }
                fclose($csvfile);

                $writefile = fopen($filestoragepath,"w");
                foreach ($lewline_Ar as $line) {
                  fputcsv($writefile, $line,',',' ');
                }

                fclose($writefile);
            }
            


            exit;
            //echo "<pre>";print_r($line_of_text);exit;

            $batchInsert=[];
            foreach ($line_of_text[0] as $key => $value){

                $batchInsert[]=array(
                    'upload_id'=>$id,
                    'email'=>$value,
                    'is_processed'=>'n',
                    'status'=>'',
                    'free_domain'=>'',
                    'catch_all'=>'',
                    'updated_at'=>$curdate,
                    'created_at'=>$curdate,
                );
            }
            if(!empty($batchInsert)){
                DB::table('tbl_upload_progress')->insert($batchInsert);    
            }
            
                        
            /* ------ */    
                        
            $data = ['filename'=>$orgname,'created_on'=>date('d-m-Y'),"fileid"=>$id];
            $response = ['status'=>'1','msg'=>'File uploaded successfully','data'=>$data]; 
        }
                
        return response()->json($response);
    }

    public function submitemailist(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);

        if(!$request->file('csvfile') || $request->file('csvfile') == ''){ 
            $response = ['status'=>'0','msg'=>'Please select a file','data'=>$data];
            return response()->json($response);  
            exit;   
        }

        $userid = session('userid');
        $extension = $request->file('csvfile')->getClientOriginalExtension();
                

        if($extension != 'csv'){
            $response = ['status'=>'0','msg'=>'Please upload only csv file','data'=>$data]; 
        }else{
            $newfilename = rand().time().'.'.$extension;
            $orgname =  $request->file('csvfile')->getClientOriginalName();

            $path = $request->file('csvfile')->storeAs("public/uploads/$userid","$orgname");
            $gudpath = str_replace("public/","",$path);

            $curdate = date("Y-m-d H:i:s");

            $id = DB::table('user_upload')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$orgname,
                    'user_given_name'=>$orgname,
                    'file_path'=>$gudpath, 
                    'xls_file_path'=>'',                                 
                    'user_id'=>$userid,                
                    'created_at'=>$curdate,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>0,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );
                        
            $data = ['filename'=>$orgname,'created_on'=>date('d-m-Y'),"fileid"=>$id];
            $response = ['status'=>'1','msg'=>'File uploaded successfully','data'=>$data]; 
        }
                
        return response()->json($response);
    }

    public function getSubscriptionDetails(){
        $data['planinfo'] = array();
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 


        $userid = session('userid');
        /*if(session('userid') != NULL && session('userid') > 0 ){

        }*/


        $subscriptionHistory = DB::table('tbl_subscriptions')
        ->where([
            ['userid','=',$userid],
            ['status','=','active']
        ])->get();

        if(count($subscriptionHistory) > 0){
            //echo "<pre>";print_r($subscriptionHistory);exit; 

            foreach($subscriptionHistory as $historyitem){

                if($historyitem->paypal_ref == 'mevsub1'){
                    $data['planinfo']['is_subscribe_test_plan'] = true;                    
                    //echo "in if";exit;
                }

                if($historyitem->paypal_ref == 'mev5k'){
                    $data['planinfo']['is_subscribe_mev5k_plan'] = true;
                    $data['planinfo']['mev5k_plan_subscription_id'] = $historyitem->subscription_paypal_id;
                }

                if($historyitem->paypal_ref == 'mev10k'){
                    $data['planinfo']['is_subscribe_mev10k_plan'] = true;
                }

                if($historyitem->paypal_ref == 'mev50k'){
                    $data['planinfo']['is_subscribe_mev50k_plan'] = true;
                }

                if($historyitem->paypal_ref == 'mev100k'){
                    $data['planinfo']['is_subscribe_mev100k_plan'] = true;
                }
            }

            $response = ['status'=>'1','msg'=>'','data'=>$data];  

        }

        return response()->json($response);

    }

    public function unsubscriberMyPlan(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);    
    }

    public function unsubscribePlan(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);

        $userid = session('userid');

        $affected = DB::table('tbl_subscriptions')
        ->where(
            [
                'userid'=>$userid,
                'paypal_ref'=>$planref,
            ]
        )
        ->update(
            [
                'status' => "expired",                    
                'subscription_end_date' => date("Y-m-d H:i:s"),                    
            ]
        );    

        if($affected > 0){
            $response = ['status'=>'1','msg'=>'Subscription cancelled successfully','data'=>$data];
        }

        return response()->json($response);
    }

    public function completePendingPayment(Request $request){
        $postData = $request->input();

        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        $userid = session('userid');
        $created_at = date('Y-m-d H:i:s');
        $pending_credits=0;        
    }

    public function creditsPayment(Request $request){

        $postData = $request->input();

        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        $userid = session('userid');
        $created_at = date('Y-m-d H:i:s');
        $pending_credits=0;
        $otp = "";
        $otp_expires_on="";
        $paypal_email_verified="";
        //$invoice_number = strtoupper(Str::random(13));
        $userInfo = DB::table('tbl_registration')->where('reg_id',$userid)->first(); 

        if($userInfo->email == $payer_email){
            $paypal_email_verified = 'y';
            $pending_credits=0;
        }else{
            $paypal_email_verified = 'n';
            $pending_credits = $credits;
            

            $otp = rand(100001,999999);

            $mydate = date("Y-m-d H:i:s");
            $otp_last_date=date_create(date("Y-m-d H:i:s"));
            date_add($otp_last_date,date_interval_create_from_date_string("11 minutes"));
            $otp_expires_on = date_format($otp_last_date,"Y-m-d H:i:s");


            /* send paypal email comparision code */

                $maildata = [                         
                    'txnvc'=>$otp,       
                    'username'=>$userInfo->firstname.' '.$userInfo->lastname,
                    'mevlogourl'=>url('public/img/mev-logo-2.png')                                           
                ];

                $useremail = $payer_email;

                Mail::send('mails.payeremail', $maildata, function($message)use($useremail)
                {
                    $message->to($useremail, 'MyEmailVerifier')
                    ->subject('Transaction Verification Code');

                     $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                });
            /* --------- */


            /* Send Internal Email to Admin */
                $today = date("Y-m-d H:i:s");
                $internal_maildata=[
                    'credits'=>$credits,                    
                    'purchase_date'=>$today,  
                    'useremail'=>$userInfo->email,  
                    'username'=>$userInfo->firstname.' '.$userInfo->lastname,
                    'amount'=>$postData['paypal_response']['transactions'][0]['amount']['total'],  
                    'mevlogourl'=>url('public/img/mev-logo-2.png'),
                ];
                        
                $admins_email=[                    
                    'jpatadiya@gmail.com',
                    'piyushpatdiya@gmail.com',
                    'kaushal.accuwebhosting@gmail.com',                    
                ];

                Mail::send('mails.internal_order', $internal_maildata, function($message)use($admins_email)
                {
                    $message->to($admins_email, 'MyEmailVerifier')
                    ->subject('Internal: Order Received');

                     $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                });

                /* ----------------------- */

                $notification_id = DB::table('tbl_notifications')->insertGetId(
                    [                                                
                        'type'=>'unconfirmed_order',
                        'subject'=>'Unconfirm Order',
                        'text'=>'You have one unconfirmed order. Please goto Invoice and click on <b>Confirm Order</b>',
                        'users'=>$userid,
                        'status'=>'n',
                        'created_at'=>date("Y-m-d H:i:s"),                
                    ]
                );
        }

       
        $id = DB::table('invoices')->insertGetId(
            [                                                
                'amount'=>$postData['paypal_response']['transactions'][0]['amount']['total'],
                'credits'=>$credits,
                'pending_credits'=>$pending_credits,
                'payment_status'=>'true',
                'user_id'=>$userid,
                'paypal_email'=>$payer_email,
                'paypal_email_verified'=>$paypal_email_verified,
                'payment_ref_number'=>$postData['paypal_response']['id'],
                'created_at'=>$created_at,                
                'invoice_number'=>$postData['paypal_response']['payer']['payer_info']['payer_id'],
                'payer_verify_otp'=>"$otp",
                'otp_expires_on'=>"$otp_expires_on",
                'otp_expires_on'=>"$otp_expires_on",
                'coupon_code'=>isset($applied_code) ? $applied_code : '',
            ]
        );


        /* Idev  Referal commission */
            $mevsource = $userInfo->source;
            //$mevsource = "#103#myemailverifier.com - direct link";            
            
            if(trim($mevsource) != '' && $mevsource != NULL){
                $source_sec_hash = strripos($mevsource,"#");            
                $sourceid = substr($mevsource,1,($source_sec_hash-1));
                 
                if($sourceid != '' && $sourceid != NULL){
                    if(is_numeric($sourceid)){

                        $idev_ordernum = $id;
                        $idev_saleamt = $postData['paypal_response']['transactions'][0]['amount']['total'];
                        $affiliate_id =  $sourceid;                       

                        $idevch = curl_init();
                        curl_setopt($idevch, CURLOPT_URL, "https://myemailverifier.idevaffiliate.com/sale.php?idev_saleamt=".$idev_saleamt."&idev_ordernum=".$idev_ordernum."&affiliate_id=".$affiliate_id);
                        curl_setopt($idevch, CURLOPT_RETURNTRANSFER, true);
                        curl_exec($idevch);
                        curl_close($idevch);            

                        
                        $maildata = [                         
                            'affiliate_id'=>$affiliate_id,
                            'invoice_no'=>$postData['paypal_response']['payer']['payer_info']['payer_id'],
                            'mevlogourl'=>url('public/img/mev-logo-2.png'),
                            'invoice_ref'=>$idev_ordernum,
                            'sales_amount'=>$idev_saleamt
                        ];

                        $useremail = $userInfo->email;
                        $admins_email=[                                                
                                'piyushpatdiya@gmail.com',
                                'kaushal.accuwebhosting@gmail.com',                                
                        ];

                        Mail::send('mails.idev_commision', $maildata, function($message)use($admins_email)
                        {
                            $message->to($admins_email, 'MyEmailVerifier')
                            ->subject('Affiliate Commission');

                             $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                        });
                    }
                }    
            }
  

        /* ---------------- */    
       
        
        if($paypal_email_verified == 'y'){


            $affected = DB::update("update tbl_registration
                                 SET credits =  credits + $credits WHERE reg_id = $userid "); 

            $message = "Payment received successfully. Your credits are available now.";            
        }else{
            
            $message = "<p>Since your PayPal email and Registered email is different, we need additional confirmation according to PayPal policy that you own your PayPal email address. We have sent a verification code to your PayPal email address. Please check inbox and confirm the Code. If it is not available in Inbox please check in spam folder and move it to inbox. </p><p> '.$useremail.' </p> ";
        }

        if($id > 0){

            $data['invoice_id']=$id;

            $response = [
                            'status'=>'1',
                            'msg'=>$message,
                            'data'=>$data,
                            'paypal_email_verified'=>$paypal_email_verified
                        ]; 
        }else{
            $response = [
                            'status'=>'0',
                            'msg'=>'payment failed. please try after some time!..',
                            'data'=>$data,
                            'paypal_email_verified'=>$paypal_email_verified
                        ]; 
        }

        return response()->json($response);
    }
   
    public function markasread(){

       $userid = session('userid');

       $affected = DB::update("update tbl_notifications 
        SET read_by =  concat(read_by,',$userid')
       WHERE FIND_IN_SET('$userid',users) "); 
    }

    public function getToken(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        $userid = session('userid'); 
        extract($postData);
        //echo $rescode;exit;

        if($em_api == "mailchimp"){

            $mailchimp_client_id = config('app.mailchimp_client_id');                            
            $mailchimp_client_secret = config('app.mailchimp_client_secret');              
            //$oauth_callback = url('Integration');
            $oauth_callback = url('http://client.myemailverifier.com/Integration');
            

                /*$url = 'https://login.mailchimp.com/oauth2/token';
                $context = stream_context_create([
                'http' => [
                  'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                  'method' => 'POST',
                  'content' => http_build_query([
                    'grant_type' => "authorization_code",
                    'client_id' => $mailchimp_client_id,
                    'client_secret' => $mailchimp_client_secret,
                    'redirect_uri' => $oauth_callback,
                    'code' => $rescode,
                  ]),
                ],
              ]);
              $result = file_get_contents($url, false,$context);
              $decoded = json_decode($result);

              $access_token = $decoded->access_token;*/


            /* Curl example */
            $postFeilds = array(    
                'grant_type'=>'authorization_code',
                'client_id'=>$mailchimp_client_id,
                'client_secret' => $mailchimp_client_secret,
                'redirect_uri' => $oauth_callback,
                'code' => $rescode,
            );


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://login.mailchimp.com/oauth2/token"); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

            curl_setopt($ch, CURLOPT_POSTFIELDS,$postFeilds); 

            $output = curl_exec($ch);              
            $output = json_decode($output);
            //echo "<pre>";print_r($output);exit;

            $access_token = $output->access_token;
            
            curl_close($ch);

            //echo $output;exit;
            /* ---------- */

                $url = 'https://login.mailchimp.com/oauth2/metadata';
                $context = stream_context_create([
                    'http' => [
                    'header' => "Authorization: OAuth $access_token",
                    ],
                ]);

                $result = file_get_contents($url, false, $context);
                $decoded = json_decode($result);
                $dc = $decoded->dc;
                //echo "<pre>";print_r($decoded);exit;

              /* fetch users contact list  */
            
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://$dc.api.mailchimp.com/3.0/lists");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


                $headers = array();
                $headers[] = 'Authorization: OAuth '.$access_token;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    //echo 'Error:' . curl_error($ch);
                    $response = ['status'=>'0','msg'=>'something went wrong!.. ','data'=>$data]; 
                }else{

                    /* save mailchimp token to our database */
                            
                        $affected = DB::table('tbl_registration')
                        ->where('reg_id',$userid)
                        ->update(
                            [
                                'mailchimp_token' => "$access_token",                    
                                
                            ]
                        );
                    /* ------------------ */

                    $contact_list = json_decode($result);
                    //echo "<pre>";print_r($contact_list);exit;
                    foreach($contact_list->lists As $mylist){

                        $list_exists = DB::table('tbl_contact_list')
                                        ->where('api_id',$mylist->id)
                                        ->where('library','mailchimp')
                                        ->exists();


                        if(!$list_exists){
                            $current_date = date('Y-m-d H:i:s');
                            $listAr = [
                                'listname'=>$mylist->name,
                                'userid'=>$userid,
                                'library'=>"mailchimp",
                                'api_id'=>$mylist->id,
                                'web_id'=>$mylist->web_id,                            
                                'created_at'=>$current_date
                            ];

                            $id = DB::table('tbl_contact_list')->insertGetId($listAr);    
                        }                    
                    }

                    $response = ['status'=>'1','msg'=>'','contactlist'=>$contact_list]; 
                }
                curl_close($ch);     

                return response()->json($response);
                exit;

              /* ------------- */            
        }
    }
    
    public function activateaccount($activation_code = ""){

        //echo $activation_code;exit;

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $affected = DB::table('tbl_registration')
            ->where('email_activation_code',$activation_code)
            ->update(
                [
                    'email_verified' => "y",                    
                    'access' => 1,                    
                ]
            );
     
        if($affected <= 0){
            $response = ['status'=>'0','msg'=>' Something went wrong. Please try again!... ','data'=>$data];

        }else{
            session(['umsg' => 'Account activated successfully. Please Log In !...']); 
            $response = ['status'=>'1','msg'=>' Account activated successfully !... ','data'=>$data];    
        }        

        return redirect('Login');
    }



    public function downloadInvoice($invoiceid=''){
        
        //echo "here ";exit;

        //$invoiceno = '3HN3ZFMBQEQBJ';
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $userid = session('userid');

        //$invoice = DB::table('invoices')->where('invoice_number', '=',"$invoiceno")->first();        
        

        $invoice = DB::table('invoices as i')
        ->join('tbl_registration as r', 'r.reg_id', '=', 'i.user_id')
        ->select('i.*', 'r.firstname','r.lastname', 'r.email')
        ->where('i.invoice_id', '=',"$invoiceid")->first();        

        if(!$invoice){
            return redirect(url(''));        
        }

        
        if($userid != $invoice->user_id){

            if(session('is_admin') != '1'){
                return redirect(url(''));
                exit;
            }                
        }

        //echo "<pre>";print_r($invoice);exit;    
        $invoice->created_at = date("d-m-Y",strtotime($invoice->created_at));
        $invoice->logoimg = url('public/img/mev-logo-2.svg');
        $invoice->amount = round($invoice->amount,2);
         
        $data[] = $invoice;        
        //$invoice_data = view('pdf.invoice',(array)$invoice)->render();
        //$invoice_data = view('pdf.invoice_three', ['invoice'=>$invoice])->render();
        $invoice_data = view('pdf.invoice_three', ['invoice'=>(array)$invoice])->render();

        //echo $invoice_data;exit;



        /* Download Library */ 
        //echo $invoice_data;exit;
        //Browsershot::html($invoice_data)->save('example.pdf');

        /*$pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($invoice_data);
        return $pdf->stream();*/
        

        //$new_invoice = (array)$invoice;

        $pdf = PDF::loadView('pdf.invoice', ['invoice'=>$invoice]); 
        return $pdf->stream('myinvoice.pdf');

        /* --------- */

        $response = ['status'=>'1','msg'=>'','data'=>$data];    

        //return response()->json($response);
    }

    public function downloadInvoice_original($invoiceid=''){

        //echo "here ";exit;

        //$invoiceno = '3HN3ZFMBQEQBJ';
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $userid = session('userid');

        //$invoice = DB::table('invoices')->where('invoice_number', '=',"$invoiceno")->first();        
        

        $invoice = DB::table('invoices as i')
        ->join('tbl_registration as r', 'r.reg_id', '=', 'i.user_id')
        ->select('i.*', 'r.firstname','r.lastname', 'r.email')
        ->where('i.invoice_id', '=',"$invoiceid")->first();        

        if(!$invoice){
            return redirect(url(''));        
        }

        
        if($userid != $invoice->user_id){

            if(session('is_admin') != '1'){
                return redirect(url(''));
                exit;
            }                
        }

        //echo "<pre>";print_r($invoice);exit;    
        $invoice->created_at = date("d-m-Y",strtotime($invoice->created_at));
        $invoice->logoimg = url('public/img/mev-logo-2.svg');
        $invoice->amount = round($invoice->amount,2);
         
        $data[] = $invoice;        
        //$invoice_data = view('pdf.invoice',(array)$invoice)->render();

        /* Download Library */
        //echo $invoice_data;exit;
        //Browsershot::html($invoice_data)->save('example.pdf');

        /*$pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($invoice_data);
        return $pdf->stream();*/
        $data = [
            'foo' => 'bar'
        ]; 

        //$new_invoice = (array)$invoice;

        $pdf = PDF::loadView('pdf.invoice', ['invoice'=>$invoice]); 
        return $pdf->stream('document.pdf');

        /* --------- */

        $response = ['status'=>'1','msg'=>'','data'=>$data];    

        //return response()->json($response);
    }

    public function invoiceHistory(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
        
        $userid = session('userid');         
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        
        $offset = (($page - 1) * $limit); 

        $total_logs = DB::table('invoices')->where('user_id', '=', $userid)->count();        
        $cur_sign = Config::get('constants.CURRENCY.CUR_SIGN');

        $notifaffected = DB::table('tbl_notifications')
                    ->where('users',$userid)
                    ->where('type','unconfirmed_order')
                    ->delete();

        $invoiceHistory = DB::table('invoices')
                ->where('user_id', '=', $userid)
                ->offset($offset)
                ->orderBy('invoice_id', 'desc')                
                ->limit($limit)->get();



        // echo "here <pre>"; print_r($invoiceHistory);exit; 

        for($i=0;$i<count($invoiceHistory);$i++){
            $i_invoice_id = $invoiceHistory[$i]->invoice_id;
            $invoiceHistory[$i]->invoice_real_id = $i_invoice_id;
            $invoiceHistory[$i]->invoice_id = $invoiceHistory[$i]->invoice_number;            
            //$invoiceHistory[$i]->downloadlink=url("downloadInvoice/$i_invoice_id");
            $invoiceHistory[$i]->allowdownload=true; 
            $invoice_download_link = url("downloadInvoice/$i_invoice_id");

            if($invoiceHistory[$i]->is_subscription == 'n'){
                if($invoiceHistory[$i]->paypal_email_verified == 'n'){
                    $invoiceHistory[$i]->allowdownload=false; 
                    $invoice_download_link = "";
                }    
            }


            if($invoiceHistory[$i]->special_discount == 'y'){
                if($invoiceHistory[$i]->payment_status == 'false'){
                    $invoiceHistory[$i]->allowdownload=false; 
                    $invoice_download_link = "";
                }    
            }
            

            //$invoiceHistory[$i]->downloadlink = "<a href='$invoice_download_link' >Download</a>";
            $invoiceHistory[$i]->downloadlink=$invoice_download_link; 

            $invoiceHistory[$i]->created_at = 
            date("Y-m-d  H:i",(strtotime($invoiceHistory[$i]->created_at)));
            if($invoiceHistory[$i]->is_subscription == 'y'){
                $invoiceHistory[$i]->credits = $invoiceHistory[$i]->credits." Credits Monthly Subscription";
            }else{
                $invoiceHistory[$i]->credits = $invoiceHistory[$i]->credits." Credits";
            }            
            //$invoiceHistory[$i]->status = "True";
            $invoiceHistory[$i]->integeramount = round($invoiceHistory[$i]->amount,2);
            $invoiceHistory[$i]->amount = $cur_sign.' '.round($invoiceHistory[$i]->amount,2);            
        }

        if(!$invoiceHistory){
            $response = ['status'=>'0','msg'=>'No records found','data'=>$data];                
        }else{
            $response = ['status'=>'1','msg'=>'','invoiceHistory'=>$invoiceHistory];                
        }

        $response['status']=1;
        $response['invoiceHistory']=$invoiceHistory;                    
        $response['total']=$total_logs;

        return response()->json($response);
    }

    public function ResetUserPassword(Request $request){
        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $affected = DB::table('tbl_registration')
            ->where('password_reset_token',$reset_token)
            ->update(
                [
                    'password' => "$new_password",                                        
                    'access' => "1",
                    'email_verified' => "y",                    
                ]
            );
     
        if($affected <= 0){
            $response = ['status'=>'0','msg'=>' Failed to reset passoword. Please try again!... ','data'=>$data];    
        }else{
            $response = ['status'=>'1','msg'=>' Password Reset Successfully !... ','data'=>$data];    
        }

        return response()->json($response);
    }

    public function ForgotPassword(Request $request){
        $postData = $request->input();
        extract($postData);
        //echo "<pre>";print_r($postData);exit;
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    
        
        $email_exists = DB::table('tbl_registration')->where('email',"$email")->exists();

        if(!$email_exists){

            $response = ['status'=>'0','msg'=>'This Email address is not registered !...'
            ,'data'=>$data];

            return response()->json($response);
        }

        $user = DB::table('tbl_registration')->where(['email'=>"$email"])->first();        

        $reset_token = Str::random(20);
        $pwd_reset_link = url("resetpassword/$reset_token");

        $affected = DB::table('tbl_registration')
            ->where('email',$email)
            ->update(
                [
                    'password_reset_token' => "$reset_token",                    
                ]
            );

          
        /* Send Email */
            $maildata = [                         
                'username'=>$user->firstname." ".$user->lastname,
                'pwd_reset_link'=>$pwd_reset_link,
                'mevlogourl'=>url('public/img/mev-logo-2.png'),
            ];

            $useremail = $user->email;
            Mail::send('mails.forgotpassword', $maildata, function($message)use($email)
            {
                $message->to("$email", 'MyEmailVerifier')
                ->subject('Password Reset Link');

                 $message->from('support@myemailverifier.com',env('APP_NAME',' '));
            });

            $response = ['status'=>'1','msg'=>'Password reset link sent on your email address. !...'
            ,'data'=>$data];


            /* ---------------------------- */

            return response()->json($response);  
    }

    public function verify_paypal_payer(Request $request){
        $postData = $request->input();
        extract($postData);
        $userid = session('userid');
        //echo "<pre>";print_r($postData);exit;

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    
        $invoice = DB::table('invoices')->where(['invoice_id'=>$invoice_id])->first();        

        if($invoice->payer_verify_otp != $new_otp){            

            $response = ['status'=>'0','msg'=>'Incorrect Code entered','data'=>""];    
            return response()->json($response);            
        }
        
        
        $new_credits = $invoice->pending_credits;
        $affected = DB::update("update invoices 
                                 SET paypal_email_verified = 'y' WHERE invoice_id = $invoice_id "); 

        $affected = DB::update("update tbl_registration
                                 SET credits =  credits + $new_credits WHERE reg_id = $userid "); 

        $affected = DB::table('invoices')
        ->where('invoice_id',$invoice_id)
        ->update(
            [
                'pending_credits' => 0,                                
            ]
        );   

        $response = ['status'=>'1','msg'=>'Your order is successfully verified and your credits are available now.','data'=>""];    

        
        return response()->json($response);
    }

    public function verifyotp(Request $request){
        $postData = $request->input();
        extract($postData);
        //echo "<pre>";print_r($postData);exit;

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $user = DB::table('tbl_registration')->where(['email'=>"$user_email"])->first();        
        

        if(!$user){
            $response = ['status'=>'0','msg'=>'<span style="color:red;" >Something went wrong !...</span>'
            ,'data'=>$data];
        }
 

        $cur_time = date('Y-m-d H:i:s');
        if($cur_time > $user->otp_expires_on){
            $response = ['status'=>'0','msg'=>'<span style="color:red;" >OTP expired. Please try again !...</span>'
            ,'data'=>$data];   
        }

        if($user->login_otp == $new_otp){

            session(['userid' => $user->reg_id]);
            session(['username' => $user->firstname]);
            session(['is_admin' => $user->is_admin]);
            session(['toastr_msg'=>'Successfully Logged In !...']);    

            $response = ['status'=>'1','msg'=>'','data'=>$data];   
        }else{
            $response = ['status'=>'0','msg'=>'<span style="color:red;" >Wrong OTP !...</span>'
            ,'data'=>$data];   
        }

        return response()->json($response);
    }    

    public function sendotp(Request $request){



        $postData = $request->input();
        extract($postData);
        //echo "<pre>";print_r($postData);exit;

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        $user = DB::table('tbl_registration')->where(['email'=>"$new_email"])->first();        

        if(!$user){
           
            $created_at = date("Y-m-d H:i");
            $newkey = Str::random(16);
            $newkey =  strtoupper($newkey); 
            $ip_address = $request->ip();

            $new_password = Str::random(10);

            $reg_id = DB::table('tbl_registration')->insertGetId(
                [
                    'password' => '',
                    'firstname' => '',                    
                    'lastname' => '',
                    'email' => $new_email,
                    'password'=>$new_password,
                    'access' => 1,
                    'created_at'=>$created_at,
                    'IP'=>$ip_address,
                    'country'=>'0',
                    'credits'=>'100',
                    'blocked_credits'=>0,
                    'email_activation_code'=>"",
                    'email_verified'=>"",
                    'apikey'=>$newkey,
                ]
            );
            $useremail = $new_email;
            $first_name="User";
            $last_name="";

            /* set randome password for first time */

            $maildata = [         
                'new_password'=>$new_password,  
                'mevlogourl'=>url('public/img/mev-logo-2.png')
            ];


            Mail::send('mails.newpassword', $maildata, function($message)use($new_email)
            {
                $message->to("$new_email", 'MyEmailVerifier')
                ->subject('New Password - MyEmailVerifier');

                 $message->from('support@myemailverifier.com',env('APP_NAME',' '));
            });

            /* ---------------- */

        }else{
            $reg_id = $user->reg_id;
            $first_name = $user->firstname;
            $last_name = $user->lastname;
            $useremail = $user->email;
        }

        //echo "<pre>";print_r($user);exit;
        /* send password in email */

        $otp = rand(100001,999999);


        $mydate = date("Y-m-d H:i:s");
        $otp_last_date=date_create(date("Y-m-d H:i:s"));
        date_add($otp_last_date,date_interval_create_from_date_string("11 minutes"));
        $otp_expires_on = date_format($otp_last_date,"Y-m-d H:i:s");


        $affected = DB::table('tbl_registration')
        ->where('reg_id',$reg_id)
        ->update(
            [
                'login_otp' => $otp,
                'otp_expires_on' => $otp_expires_on,
            ]
        );

        /* Send Email */
        $maildata = [         
            'login_otp'=>$otp,
            'username'=>$first_name." ".$last_name, 
            'otp_expires_on'=>$mydate,
            'mevlogourl'=>url('public/img/mev-logo-2.png'),
        ];
        


        Mail::send('mails.sendotp', $maildata, function($message)use($useremail)
        {
            $message->to("$useremail", 'MyEmailVerifier')
            ->subject('Login OTP');

             $message->from('support@myemailverifier.com',env('APP_NAME',' '));
        });

        

        /* ---------------------------- */

        $response = ['status'=>'1','msg'=>' <span style="color:green;" > OTP sent successfully on your Email Address. valid for 10 minutes !..  </span> ','data'=>$data];
          

        return response()->json($response);                  
    }

    public function signOut(Request $request){
        //session()->flush();        
        session(['userid'=>'']);
        $response = ['status'=>'1','msg'=>'Logged Out successfully!.. ','data'=>[]];
        $request->session()->flush();


        return response()->json($response);
    }

    public function signIn(Request $request){

            
        $postData = $request->input();
        extract($postData);
        $recaptcha_secret_key = config('app.recaptcha_secret_key');        

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',            
        ])->post('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret_key.'&response='.$recaptcha,[                    
        ]);

        $apiresponsetext = $response->body(); 
        $apiResponseObj = json_decode($apiresponsetext);
        //echo "<pre>";print_r($apiResponseObj);exit;
                        
        if($apiResponseObj->success == '1' || $apiResponseObj->success == 1){            
        //if(1){                

            /*$user = DB::table('tbl_registration')
                    ->where(['email'=>"$email"],['password'=>"$password"])->first();*/

            $user = DB::table('tbl_registration')
                    ->where('email','=',$email)
                    ->where('password','=',$password)->first();                    

            if(!$user){
                $response = ['status'=>'0','msg'=>'Incorrect Email or Password','data'=>$data];
            }else{

                $users_file_dir = base_path("storage/app/public/uploads/".$user->reg_id);   
                if(!file_exists($users_file_dir)){
                    mkdir($users_file_dir,777);
                }

                if($user->email_verified == 'n'){
                    $response = ['status'=>'0','msg'=>'Your account is not activated yet!.. ','data'=>$user];
                }else if($user->access == 0 || $user->access == '0'){

                    /* Send Activation link in Email */  

                    $activation_code = Str::random(20);         

                    DB::table('tbl_registration')
                    ->where('reg_id',$user->reg_id)
                    ->update(
                        [
                            'email_activation_code' => "$activation_code",
                        ]
                    );

                    $activation_link = url("activateaccount/$activation_code");
                    $maildata = [                         
                        'username'=>$user->firstname." ".$user->lastname,
                        'activation_link'=>$activation_link,
                        'mevlogourl'=>url('public/img/mev-logo-2.png'),
                    ];

                    $useremail = $user->email;

                    Mail::send('mails.activateaccount', $maildata, function($message)use($useremail)
                    {
                        $message->to("$useremail", 'MyEmailVerifier')
                        ->subject('Account Activation Link');

                        $message->from("support@myemailverifier.com",env('APP_NAME',' '));
                    });

                    /* ----------  */

                    $response = ['status'=>'0','msg'=>'Your account has been removed!.. To Activate your account again, Please check your email and click on verification link.','data'=>$user];
                }else{
                    

                    $ip_address = $_SERVER['REMOTE_ADDR'];
                    $ipinfo_res = file_get_contents("http://ipinfo.io/{$ip_address}/geo");
                    $ipdetails = json_decode($ipinfo_res, true);

                    $logincountry = $ipdetails['country'];

                    if($logincountry != $user->last_login_country){

                        $useremail = $user->email; 
                        $countryname = $this->getCountryName($ipdetails['country']);

                        $maildata = [                         
                            'username'=>$user->firstname." ".$user->lastname,      
                            'countryname'=>$countryname, 
                            'mevlogourl'=>url('public/img/mev-logo-2.png'),                     
                        ];
                        
                        /*Mail::send('mails.loginalert', $maildata, function($message)use($useremail)
                        {
                            $message->to("$useremail", 'MyEmailVerifier')
                            ->subject('Login Alert');

                            $message->from("support@myemailverifier.com",env('APP_NAME',' '));
                        });*/
                    }

                    $affected = DB::table('tbl_registration')
                                ->where('reg_id',$user->reg_id)
                                ->update(
                                    [
                                        'last_login_country' => $logincountry,
                                        'last_active' => date('Y-m-d H:i:s'),
                                    ]
                                );
                    
                    //Cookie::queue("mevlogin","y");   
                    setcookie("mevlogin","y",time() + 3600,"/");             

                    session(['userid' => $user->reg_id]); 
                    session(['username' => $user->firstname]); 
                    session(['is_admin' => $user->is_admin]); 
                    

                    //session(['toastr_msg'=>'Successfully Logged In !...']);   


                    $response = ['status'=>'1','msg'=>'Logged In successfully!.. ','data'=>$user];
                }                
            }             
        }else{

            $response = ['status'=>'0','msg'=>'please confirm that you are a human','data'=>''];
        }
                                
        return response()->json($response);
    }


    public function signIn_old(Request $request){

        $postData = $request->input();
        extract($postData);
        $recaptcha_secret_key = "6LfCFP4ZAAAAAHkkc25nL1qWxXoFTGOHjEZxz1KJ";

        /*echo config('services.recaptcha.secret');exit;
        echo "<pre>";print_r($postData);exit;*/

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        //echo $recaptcha;exit;
        /*$validatedData = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'recaptcha' => ['required', $recaptcha],
        ]);*/



        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',            
        ])->post('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret_key.'&response='.$recaptcha,[                    
        ]);

        $apiresponsetext = $response->body(); 
        $apiResponseObj = json_decode($apiresponsetext);

        //echo "<pre>";print_r($apiResponseObj);exit;
        //echo $apiResponseObj->success;exit;
        if($apiResponseObj->success == '1' || $apiResponseObj->success == 1){            

            $user = DB::table('tbl_registration')
                    ->where(['email'=>"$email"],['password'=>"$password"])->first();

            if(!$user){
                $response = ['status'=>'0','msg'=>'Incorrect Email or Password','data'=>$data];
            }else{

                if($user->access == 0 || $user->access == '0'){
                    $response = ['status'=>'0','msg'=>'Your account has been removed!.. ','data'=>$user];
                }else{
                    $response = ['status'=>'1','msg'=>'Logged In successfully!.. ','data'=>$user];
                }                
            }             
        }else{
            $response = ['status'=>'0','msg'=>'please verify your self by secure captcha','data'=>''];
        }
                        
        //echo "<pre>";print_r($user);exit;

        return response()->json($response);
    }

    public function deleteAccount(){
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        $userid = session('userid');

        //DB::table('users')->where('reg_id', '=', $userid)->delete();
        $affected = DB::table('tbl_registration')
        ->where('reg_id',$userid)
        ->update(
            [
                'access' => 0,
            ]
        );

        session(['userid'=>'']);
        Session::forget('userid');

        $response = ['status'=>'1','msg'=>'Account Deleted Successfully','data'=>$data];              
        return response()->json($response);
    }

    public function generateAPIKey(){
        $newkey = Str::random(16);
        $newkey =  strtoupper($newkey); 
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        $userid = session('userid');

        $affected = DB::table('tbl_registration')
              ->where('reg_id',$userid)
              ->update(
                        [
                            'apikey' => $newkey,
                        ]
                    );

        $data['newapikey'] = $newkey;
        $response = ['status'=>'1','msg'=>'','data'=>$data];              

        return response()->json($response);
    }

    public function getAPIKey(){        
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        $userid = session('userid');

        $user = DB::table('tbl_registration')->where('reg_id',$userid)->first();
        if(!$user){
            echo "not found";
        }

        $data = $user;
        $response = ['status'=>'1','msg'=>'','user'=>$data];
        //echo "<pre>";print_r($user);exit;


        return response()->json($response);
    }

    public function applyCouponCode(Request $request){
        $postData = $request->input();
        extract($postData);

        $response = [];
        $data=[];
        $data['special_discount']='n';        
        $special_coupon = 'MEVSPL1MLN';
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 


        if($coupon_code == $special_coupon){

            if($no_of_credits == '1000000'){
                $data['special_discount']='y';        

                $data['offper'] = 0;
                $response['status']="1";
                $response['data']=$data;
                $response['msg']="Congrats! special discount applied successfully ";                            
            }else{
                $data['special_discount']='n';        
            }

            return response($response);
            exit;
        }

        $copons = array(
            array('couponcode'=>'MEV40F2019','offper'=>40),
            array('couponcode'=>'MEV50F2019','offper'=>50),
            array('couponcode'=>'MEV60F2019','offper'=>60),            

            array('couponcode'=>'MEVUSER65F2020','offper'=>65),
            array('couponcode'=>'MEVUSER70F2020','offper'=>70),
            array('couponcode'=>'MEVUSER75F2020','offper'=>75),            
        );

        foreach($copons as $code){
            if($coupon_code == $code['couponcode']){

                if($coupon_code == 'MEV50F2019'){
                    if($no_of_credits < 50000){
                        $data['special_discount']='n';
                        return response($response);
                        exit;
                    }    
                }

                if($coupon_code == 'MEV60F2019'){
                    if($no_of_credits < 500000){
                        $data['special_discount']='n';
                        return response($response);
                        exit;
                    }    
                }

                $data['offper'] = $code['offper'];
                $response['status']="1";
                $response['data']=$data;
                $response['msg']="Congrats! ".$data['offper']."% off coupon applied successfully ";

                continue;
            }
        }

        return response()->json($response);        
    }

    public function getValidationRecords(Request $request){
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        $postData = $request->all();                
        extract($postData);

        $userid = session('userid');

        $user = DB::table('tbl_registration')->where('reg_id',$userid)->first();
        $searchkey="";
        //echo "<pre>";print_r($user);exit;
        $available_credits = $user->credits; 
        if(isset($search_word) && trim($search_word) != ''){
            $searchkey = $search_word;        
        }
              

        /*$user_uploads = DB::table('user_upload')
                        ->where('user_id', '=', $userid)
                        ->where('file_name','like',"%$searchkey%")
                        ->orderByDesc('upload_id')->get();            */
        

        /*$user_uploads = DB::table('user_upload')
                ->where('user_id','=', $userid)
                ->where(function ($query) {
                    global $searchkey;
                    $query->where('file_name', 'like', "%$searchkey%")
                    ->orWhere('upload_id', '=', $searchkey);
                })->toSql();  */

        /*$user_uploads = DB::table('user_upload')
                ->selectRaw('user_id = ? and (file_name like "%?%" or upload_id = ? ) ',
                    [$searchkey,$searchkey,$searchkey])
                ->get();*/

        /*$user_uploads = DB::raw("SELECT * FROM user_upload  WHERE user_id = $userid and (upload_id = $searchkey or file_name like '%$searchkey%' ) ")->select();*/

        
        $user_uploads = DB::select(DB::raw("SELECT * FROM user_upload  WHERE user_id = $userid and 
            (upload_id like '$searchkey%' or file_name like '%$searchkey%')  ORDER BY upload_id DESC "));

        //echo "<pre>"; print_r($user_uploads);exit;              

        /*echo $user_uploads;        
        dd(DB::getQueryLog());                 
        exit;    */

        if(!$user_uploads){

        }else{

            for($i=0;$i<count($user_uploads);$i++){

                $integration='';
                $csvfile_path = "";
                $csvfile_url = "";

                $xlsfile_path = "";
                $xlsfile_url = "";

                $downloadable=false;                        
                $allowstop=true;
                $progress_percent='0';
                $showmygraph=false;
                $deletable = true;
                $show_start_modes = true;


                $valid = $user_uploads[$i]->valid;
                $invalid = $user_uploads[$i]->invalid;
                $unknown = $user_uploads[$i]->unknown;
                $spam_trap = $user_uploads[$i]->spam_trap;
                $toxic_domains = $user_uploads[$i]->toxic_domains;
                $total_emails = $user_uploads[$i]->total;                
                $callout_class="callout callout-danger ";
                if($user_uploads[$i]->integration != ''){
                    $integration = " - ".$user_uploads[$i]->integration;
                }                

                $modesbtndisabled=0;

                if($user_uploads[$i]->ready_for_download < 0){
                    $allowstop=false;
                }

                if($user_uploads[$i]->ready_for_download == 1){    
                    $progress_percent = 100;
                    $allowstop=false;
                    $modesbtndisabled=1;
                    $downloadable = 1;
                    $showmygraph=true;
                    $show_start_modes=false;
                    $callout_class="callout callout-success ";
                }

                if($user_uploads[$i]->ready_for_download >= 1){
                        $deletable = false;
                }

                if($user_uploads[$i]->ready_for_download > 1){

                    

                    $processed_res = ($valid + $invalid + $unknown + $spam_trap + $toxic_domains);
                    if($processed_res > 0 && $total_emails > 0){
                        $progress_percent = (($processed_res/$total_emails) * 100);    
                    }
                    
                    $modesbtndisabled=1;
                    $showmygraph=true;
                    $show_start_modes=false;

                    $callout_class="callout callout-warning ";                    
                }

                if($user_uploads[$i]->ready_for_download == 0){

                    $processed_res = ($valid + $invalid + $unknown + $spam_trap + $toxic_domains);
                    if($processed_res > 0 && $total_emails > 0){
                        $progress_percent = (($processed_res/$total_emails) * 100);    
                    }
                    $modesbtndisabled=1;
                    $showmygraph=true;
                    $show_start_modes=false;
                    $callout_class="callout callout-warning ";
                    $deletable = false;
                
                }
                

                $progress_percent = $progress_percent."%";

                $csvfile_path = base_path('public/storage/'.$user_uploads[$i]->file_path);
                //echo $csvfile_path;exit;
                

                

                if(trim($user_uploads[$i]->file_path) != ''){
                    $csvfile_url = asset("public/storage/".$user_uploads[$i]->file_path);
                    //$downloadable=true;
                }

                $xlsfile_path = base_path('public/storage/'.$user_uploads[$i]->xls_file_path);
                if(trim($user_uploads[$i]->xls_file_path) != ''){
                    $xlsfile_url = asset("public/storage/".$user_uploads[$i]->xls_file_path);
                    //$downloadable=true;
                }

                if($user_uploads[$i]->ready_for_download > 0){
                    $showmygraph=true;
                }else{
                    $showmygraph=false;
                }

                if($user_uploads[$i]->ready_for_download == 0){
                    $showmygraph=true;
                    $downloadable = false;
                }

                $data[] = array(
                    'fileid'=>$user_uploads[$i]->upload_id,    
                    'filename'=>$user_uploads[$i]->user_given_name,
                    'integration'=>$integration,
                    'showmygraph'=>$showmygraph,
                    'total'=>$user_uploads[$i]->total,
                    'valid'=>$user_uploads[$i]->valid,
                    'invalid'=>$user_uploads[$i]->invalid,
                    'unknown'=>$user_uploads[$i]->unknown,
                    'creditused'=>$user_uploads[$i]->credit_used,
                    'allowstop'=>$allowstop,
                    //'file_path'=>asset("storage/app/".$user_uploads[$i]->file_path),
                    //'file_path'=>$csvfile_url,
                    //'xls_file_path'=>$xlsfile_url,
                    'file_path'=>url('downloadreport/csv/'.$user_uploads[$i]->upload_id),
                    'xls_file_path'=>url('downloadreport/xls/'.$user_uploads[$i]->upload_id),
                    'status'=>$user_uploads[$i]->status,
                    'downloadable'=>$downloadable,
                    'progress'=>'completed',
                    'spam_trap'=>$user_uploads[$i]->spam_trap,
                    'toxic_domains'=>$user_uploads[$i]->toxic_domains,
                    'progress_percent'=>$progress_percent,
                    'modesbtndisabled'=>$modesbtndisabled,
                    'show_start_modes'=>$show_start_modes,
                    'deletable'=>$deletable,
                    'callout_class'=>$callout_class,
                    'created_on'=>date("d-m-Y",strtotime($user_uploads[$i]->created_at)),                    
                    
                    'percent_valid'=>$this->givePercentage($user_uploads[$i]->valid,$user_uploads[$i]->total),
                    'percent_invalid'=>$this->givePercentage($user_uploads[$i]->invalid,$user_uploads[$i]->total),
                    'percent_unknown'=>$this->givePercentage($user_uploads[$i]->unknown,$user_uploads[$i]->total),
                    'percent_spam_trap'=>$this->givePercentage($user_uploads[$i]->spam_trap,$user_uploads[$i]->total),
                    'percent_toxic_domains'=>$this->givePercentage($user_uploads[$i]->toxic_domains,$user_uploads[$i]->total),

                );
            }        
        }

        
        /*$data = array(
            array('filename'=>"newfile.xls","created_on"=>"13-09-2020","total"=>"100","valid"=>"60","invalid"=>"17","unknown"=>"3","creditused"=>"100","status"=>"verified","progress"=>"completed"
                ,"spam_trap"=>"17","toxic_domains"=>"3"),

            array('filename'=>"septfile.xls","created_on"=>"14-09-2020","total"=>"100","valid"=>"70","invalid"=>"17","unknown"=>"3","creditused"=>"100","status"=>"verified","progress"=>"completed"
                ,"spam_trap"=>"7","toxic_domains"=>"3"),

            array('filename'=>"augfile.csv","created_on"=>"14-08-2020","total"=>"100","valid"=>"70","invalid"=>"17","unknown"=>"3","creditused"=>"100","status"=>"verified","progress"=>"completed"
                ,"spam_trap"=>"7","toxic_domains"=>"3")
        );*/
        

        $response = ['status'=>"1",'msg'=>'','data'=>$data,'credits'=>$available_credits];

        return response()->json($response);
    }



    public function getLogs(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
        

        $userid = session('userid');         
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        
        $offset = (($page - 1) * $limit);

        $total_logs = DB::table('api_log')->where('user_id', '=', $userid)->count();        

        $logs = DB::table('api_log')->where('user_id', '=', $userid)
                ->offset($offset)
                ->limit($limit)
                ->get();

        

        for($i=0;$i<count($logs);$i++){
            $resdata = json_decode($logs[$i]->result,true);
            $mynewdata = json_decode($logs[$i]->result);
            
            //echo "<pre>";print_r($resdata);exit;
            
            $logstatus = isset($resdata['Status']) ? $resdata['Status'] : '';
            $logs[$i]->logstatus = $logstatus;
            $logs[$i]->status = $logstatus;
            

            $logdate = date('d-m-Y h:i A',strtotime($logs[$i]->created_at));
            $logs[$i]->logdate = $logdate;
        }


        if(!$logs){
            //echo "not found";exit;
        }else{
           // echo "<pre>";print_r($logs);exit;
            $data = $logs;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
        //echo "<pre>";print_r($user);exit;

        $response['status']=1;
        $response['logs']=$logs;                    
        $response['total']=$total_logs;

        return response()->json($response);
    }

    
    public function changePassword(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        extract($postData);



        $userid = session('userid');

        $user = DB::table('tbl_registration')->where('reg_id',$userid)->first();
        //echo "<pre>";print_r($user);exit;
        if(!$user){
            echo "not found";
        }

        if($old_password != $user->password){
            $response = ['status'=>'0','msg'=>'Incorrect Old password','data'=>$data];                        
        }else{

            $affected = DB::table('tbl_registration')
              ->where('reg_id',$userid)
              ->update(
                        [
                            'password' => $new_password,
                        ]
                    );

            $response = ['status'=>'1','msg'=>'password changed Successfully','data'=>$data];
        }

        return response()->json($response);      
    }

    public function createUser(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        //echo "<pre>";print_r($postData);exit;
        extract($postData);

        $referal_cookie="";
        if(isset($_COOKIE['mev_source']) && $_COOKIE['mev_source'] != '' && $_COOKIE['mev_source'] != NULL ){
            $referal_cookie=$_COOKIE['mev_source'];            
        }

        $emailExists = DB::table('tbl_registration')->where('email',$email)->exists();    

        if($emailExists){
            $response = ['status'=>'0','msg'=>'Email Already Exists','data'=>$data];        
        }else{

            $created_at = date('Y-m-d H:i:s');
            $ip_address = $request->ip();
            $newkey = Str::random(16);

            $activation_code = Str::random(20);
            $id = DB::table('tbl_registration')->insertGetId(
                [
                    'password' => $password,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'access' => 0,
                    'created_at'=>$created_at,
                    'IP'=>$ip_address,
                    'country'=>'0',
                    'credits'=>'100',
                    'blocked_credits'=>0,
                    'email_activation_code'=>"$activation_code",
                    'apikey'=>$newkey,
                    'source'=>$referal_cookie
                ]
            );

            if($id > 1){

                /* Send Activation link in Email */   

                    $activation_link = url("activateaccount/$activation_code");
                    $useremail = $email;
                    $maildata = [                         
                        'username'=>$firstname." ".$lastname,
                        'activation_link'=>$activation_link,
                        'mevlogourl'=>url('public/img/mev-logo-2.png'),
                        'useremail'=>$useremail,
                    ];
                    

                    Mail::send('mails.activateaccount', $maildata, function($message)use($useremail)
                    {
                        $message->to("$useremail", 'MyEmailVerifier')
                        ->subject('Account Activation Link');

                        $message->from("support@myemailverifier.com",env('APP_NAME',' '));
                    });

                    $maildata=['useremail'=>$useremail,'mevlogourl'=>url('public/img/mev-logo-2.png')];
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

                    /* ----------  */
                    
                    
                    $response = ['status'=>'1','msg'=>'Registered Successfully. Please check your email',
                    'data'=>$data];  

            }else{

                $response = ['status'=>'0','msg'=>'Failed to Registered. Please try again.','data'=>[]];  
            }
              
        }
        
        return response()->json($response);
            
    }

    public function saveUserProfile(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        //echo "<pre>";print_r($postData);exit;
        extract($postData['userdata']);

        $userid = session('userid');
        $is_digits=true;

        if(trim($phone) != ''){
            $is_digits = ctype_digit($phone);
        }
        

        if(!$is_digits){
            $response = ['status'=>'0','msg'=>'please enter a valid phone number','data'=>$data];            
        }else{
            $affected = DB::table('tbl_registration')
            ->where('reg_id',$userid)
            ->update(
                [
                    'title'=>$title,
                    'firstname'=>$first_name,
                    'lastname'=>$last_name,
                    'phone'=>$phone,
                    'address'=>$address,
                    'company_website'=>$website,
                ]
            );
            $response = ['status'=>'1','msg'=>'Profile Updated Successfully','data'=>$data];    
        }
        

        return response()->json($response);
    }

    public function getUserProfile($userid=0){     




        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        //echo "<pre>";print_r($_SESSION);exit;
        //dd(session()->all());        
        if(session('userid') != NULL && session('userid') > 0 ){

            $userid = session('userid');


            $user = DB::table('tbl_registration')->where('reg_id',$userid)->first(); 
            $new_alerts=0;
            

            

            if(!$user){
                echo "not found";
            }

            $notifications = DB::table('tbl_notifications')
                    ->whereRaw(" FIND_IN_SET('$userid',users) ")
                    ->orderBy("id","desc") 
                    ->get();

            //echo "<pre>";print_r($notifications);exit;
            for($i=0;$i<count($notifications);$i++){
                $notifications[$i]->time_elapsed = $this->humanTiming(strtotime($notifications[$i]->created_at))." ago";
                $readby = explode(',',$notifications[$i]->read_by);
                if(in_array($userid,$readby)){ 
                    $notifications[$i]->is_read = true;                    
                    $notifications[$i]->alert_class="";
                }else{
                    $new_alerts++;
                    $notifications[$i]->is_read = false;    
                    $notifications[$i]->alert_class=" unreadalert ";
                }
            }

            $current_time = date('Y-m-d H:i:s');
            $affected = DB::update("update tbl_registration
                                 SET last_active =  '$current_time' WHERE reg_id = $userid "); 

            $data = $user;
            $response = [
                'status'=>'1','msg'=>'','user'=>$data,'notifications'=>$notifications,
                'new_alerts'=>$new_alerts == 0 ? '' : $new_alerts
            ];
        }else{
            $response = ['status'=>'0','msg'=>'Invalid Request. Please login again ','data'=>$data];
        }

        
        //echo "<pre>";print_r($user);exit;
         


        return response()->json($response);
    }

    public function validateEmail(Request $request){

    	$postData = $request->input();
        $response = [];
        $data = [];
        //echo "<pre>";print_r($postData);exit;
        extract($postData);

        $userid = session('userid');
                            
        $userdata = DB::table('tbl_registration')->where('reg_id', '=',"$userid")->first();     
        if($userdata->credits < 1){
            $response = ['status'=>'0','msg'=>'You do not have enough credits.','data'=>$data];
            return response()->json($response);
            exit;
        }

        /*$response = Http::post('http://23.239.201.239/api/validate_inside.php', [
            'apikey' => 'rapid_solo',
            'email' => 'kaushal.accuwebhosting@gmail.com',
        ]);*/
        $apiresponse = Http::get('http://23.239.201.239/api/validate_inside.php?apikey=rapid_solo&email='.
            $email);

        $apiresponsetext = $apiresponse->body(); 
        $apiResponseObj = json_decode($apiresponsetext);
        //echo "<pre>";print_r($apiResponseObj);exit;

        $affected = DB::update("update tbl_registration
                                 SET credits =  credits - 1 WHERE reg_id = $userid "); 

        $emailusername = explode("@",$email);
        //echo "<pre>";print_r($emailusername);exit;
        $email_user = $emailusername[0];
        $email_domain = $emailusername[1];

        if($apiResponseObj->Status == "Valid"){
            $vldinvld = "<span class='restext resvld' >Valid</span>";
        }else{
            $vldinvld = "<span class='restext resinvld' >Invalid</span>";
        }

        $data = array(            
            "Result"=>$vldinvld,
            "User"=>$email_user,
            "Domain"=>$email_domain,
            "more_info"=>$apiResponseObj->Diagnosis,
            "SMTP_Status_Code"=>'2.1.5',
            "Disposable"=>$apiResponseObj->Disposable_Domain == 0 ? "False" : "True",
            "Free"=>$apiResponseObj->Free_Domain == 0 ? "False" : "True",
            "Greylisted"=>$apiResponseObj->Greylisted == 0 ? "False" : "True",
        );

    	$response = ['status'=>'1','msg'=>'','data'=>$data];
    	return response()->json($response);
    }


    function humanTiming($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'min',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }


    function getCountryName($country_code=''){

        $code = strtoupper($country_code);

            $countryList = array(
                'AF' => 'Afghanistan',
                'AX' => 'Aland Islands',
                'AL' => 'Albania',
                'DZ' => 'Algeria',
                'AS' => 'American Samoa',
                'AD' => 'Andorra',
                'AO' => 'Angola',
                'AI' => 'Anguilla',
                'AQ' => 'Antarctica',
                'AG' => 'Antigua and Barbuda',
                'AR' => 'Argentina',
                'AM' => 'Armenia',
                'AW' => 'Aruba',
                'AU' => 'Australia',
                'AT' => 'Austria',
                'AZ' => 'Azerbaijan',
                'BS' => 'Bahamas the',
                'BH' => 'Bahrain',
                'BD' => 'Bangladesh',
                'BB' => 'Barbados',
                'BY' => 'Belarus',
                'BE' => 'Belgium',
                'BZ' => 'Belize',
                'BJ' => 'Benin',
                'BM' => 'Bermuda',
                'BT' => 'Bhutan',
                'BO' => 'Bolivia',
                'BA' => 'Bosnia and Herzegovina',
                'BW' => 'Botswana',
                'BV' => 'Bouvet Island (Bouvetoya)',
                'BR' => 'Brazil',
                'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
                'VG' => 'British Virgin Islands',
                'BN' => 'Brunei Darussalam',
                'BG' => 'Bulgaria',
                'BF' => 'Burkina Faso',
                'BI' => 'Burundi',
                'KH' => 'Cambodia',
                'CM' => 'Cameroon',
                'CA' => 'Canada',
                'CV' => 'Cape Verde',
                'KY' => 'Cayman Islands',
                'CF' => 'Central African Republic',
                'TD' => 'Chad',
                'CL' => 'Chile',
                'CN' => 'China',
                'CX' => 'Christmas Island',
                'CC' => 'Cocos (Keeling) Islands',
                'CO' => 'Colombia',
                'KM' => 'Comoros the',
                'CD' => 'Congo',
                'CG' => 'Congo the',
                'CK' => 'Cook Islands',
                'CR' => 'Costa Rica',
                'CI' => 'Cote d\'Ivoire',
                'HR' => 'Croatia',
                'CU' => 'Cuba',
                'CY' => 'Cyprus',
                'CZ' => 'Czech Republic',
                'DK' => 'Denmark',
                'DJ' => 'Djibouti',
                'DM' => 'Dominica',
                'DO' => 'Dominican Republic',
                'EC' => 'Ecuador',
                'EG' => 'Egypt',
                'SV' => 'El Salvador',
                'GQ' => 'Equatorial Guinea',
                'ER' => 'Eritrea',
                'EE' => 'Estonia',
                'ET' => 'Ethiopia',
                'FO' => 'Faroe Islands',
                'FK' => 'Falkland Islands (Malvinas)',
                'FJ' => 'Fiji the Fiji Islands',
                'FI' => 'Finland',
                'FR' => 'France, French Republic',
                'GF' => 'French Guiana',
                'PF' => 'French Polynesia',
                'TF' => 'French Southern Territories',
                'GA' => 'Gabon',
                'GM' => 'Gambia the',
                'GE' => 'Georgia',
                'DE' => 'Germany',
                'GH' => 'Ghana',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'GL' => 'Greenland',
                'GD' => 'Grenada',
                'GP' => 'Guadeloupe',
                'GU' => 'Guam',
                'GT' => 'Guatemala',
                'GG' => 'Guernsey',
                'GN' => 'Guinea',
                'GW' => 'Guinea-Bissau',
                'GY' => 'Guyana',
                'HT' => 'Haiti',
                'HM' => 'Heard Island and McDonald Islands',
                'VA' => 'Holy See (Vatican City State)',
                'HN' => 'Honduras',
                'HK' => 'Hong Kong',
                'HU' => 'Hungary',
                'IS' => 'Iceland',
                'IN' => 'India',
                'ID' => 'Indonesia',
                'IR' => 'Iran',
                'IQ' => 'Iraq',
                'IE' => 'Ireland',
                'IM' => 'Isle of Man',
                'IL' => 'Israel',
                'IT' => 'Italy',
                'JM' => 'Jamaica',
                'JP' => 'Japan',
                'JE' => 'Jersey',
                'JO' => 'Jordan',
                'KZ' => 'Kazakhstan',
                'KE' => 'Kenya',
                'KI' => 'Kiribati',
                'KP' => 'Korea',
                'KR' => 'Korea',
                'KW' => 'Kuwait',
                'KG' => 'Kyrgyz Republic',
                'LA' => 'Lao',
                'LV' => 'Latvia',
                'LB' => 'Lebanon',
                'LS' => 'Lesotho',
                'LR' => 'Liberia',
                'LY' => 'Libyan Arab Jamahiriya',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'MO' => 'Macao',
                'MK' => 'Macedonia',
                'MG' => 'Madagascar',
                'MW' => 'Malawi',
                'MY' => 'Malaysia',
                'MV' => 'Maldives',
                'ML' => 'Mali',
                'MT' => 'Malta',
                'MH' => 'Marshall Islands',
                'MQ' => 'Martinique',
                'MR' => 'Mauritania',
                'MU' => 'Mauritius',
                'YT' => 'Mayotte',
                'MX' => 'Mexico',
                'FM' => 'Micronesia',
                'MD' => 'Moldova',
                'MC' => 'Monaco',
                'MN' => 'Mongolia',
                'ME' => 'Montenegro',
                'MS' => 'Montserrat',
                'MA' => 'Morocco',
                'MZ' => 'Mozambique',
                'MM' => 'Myanmar',
                'NA' => 'Namibia',
                'NR' => 'Nauru',
                'NP' => 'Nepal',
                'AN' => 'Netherlands Antilles',
                'NL' => 'Netherlands the',
                'NC' => 'New Caledonia',
                'NZ' => 'New Zealand',
                'NI' => 'Nicaragua',
                'NE' => 'Niger',
                'NG' => 'Nigeria',
                'NU' => 'Niue',
                'NF' => 'Norfolk Island',
                'MP' => 'Northern Mariana Islands',
                'NO' => 'Norway',
                'OM' => 'Oman',
                'PK' => 'Pakistan',
                'PW' => 'Palau',
                'PS' => 'Palestinian Territory',
                'PA' => 'Panama',
                'PG' => 'Papua New Guinea',
                'PY' => 'Paraguay',
                'PE' => 'Peru',
                'PH' => 'Philippines',
                'PN' => 'Pitcairn Islands',
                'PL' => 'Poland',
                'PT' => 'Portugal, Portuguese Republic',
                'PR' => 'Puerto Rico',
                'QA' => 'Qatar',
                'RE' => 'Reunion',
                'RO' => 'Romania',
                'RU' => 'Russian Federation',
                'RW' => 'Rwanda',
                'BL' => 'Saint Barthelemy',
                'SH' => 'Saint Helena',
                'KN' => 'Saint Kitts and Nevis',
                'LC' => 'Saint Lucia',
                'MF' => 'Saint Martin',
                'PM' => 'Saint Pierre and Miquelon',
                'VC' => 'Saint Vincent and the Grenadines',
                'WS' => 'Samoa',
                'SM' => 'San Marino',
                'ST' => 'Sao Tome and Principe',
                'SA' => 'Saudi Arabia',
                'SN' => 'Senegal',
                'RS' => 'Serbia',
                'SC' => 'Seychelles',
                'SL' => 'Sierra Leone',
                'SG' => 'Singapore',
                'SK' => 'Slovakia (Slovak Republic)',
                'SI' => 'Slovenia',
                'SB' => 'Solomon Islands',
                'SO' => 'Somalia, Somali Republic',
                'ZA' => 'South Africa',
                'GS' => 'South Georgia and the South Sandwich Islands',
                'ES' => 'Spain',
                'LK' => 'Sri Lanka',
                'SD' => 'Sudan',
                'SR' => 'Suriname',
                'SJ' => 'Svalbard & Jan Mayen Islands',
                'SZ' => 'Swaziland',
                'SE' => 'Sweden',
                'CH' => 'Switzerland, Swiss Confederation',
                'SY' => 'Syrian Arab Republic',
                'TW' => 'Taiwan',
                'TJ' => 'Tajikistan',
                'TZ' => 'Tanzania',
                'TH' => 'Thailand',
                'TL' => 'Timor-Leste',
                'TG' => 'Togo',
                'TK' => 'Tokelau',
                'TO' => 'Tonga',
                'TT' => 'Trinidad and Tobago',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
                'TM' => 'Turkmenistan',
                'TC' => 'Turks and Caicos Islands',
                'TV' => 'Tuvalu',
                'UG' => 'Uganda',
                'UA' => 'Ukraine',
                'AE' => 'United Arab Emirates',
                'GB' => 'United Kingdom',
                'US' => 'United States of America',
                'UM' => 'United States Minor Outlying Islands',
                'VI' => 'United States Virgin Islands',
                'UY' => 'Uruguay, Eastern Republic of',
                'UZ' => 'Uzbekistan',
                'VU' => 'Vanuatu',
                'VE' => 'Venezuela',
                'VN' => 'Vietnam',
                'WF' => 'Wallis and Futuna',
                'EH' => 'Western Sahara',
                'YE' => 'Yemen',
                'ZM' => 'Zambia',
                'ZW' => 'Zimbabwe'
            );

        if( !$countryList[$code] ){ 
            return $code;            
        }else{
            return $countryList[$code];  
        } 

    }

    

}


