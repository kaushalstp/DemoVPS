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

class InlineclientappController extends Controller
{
    
    public function __construct(){
        session(['umsg'=>'']);
        Session::forget('umsg');
    }

    public function completeMyPayment(Request $request){
        $postData = $request->input();

        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        //echo "<pre>";print_r($paymentDetail);exit;
        $payment_status = $paymentDetail['status'];

        if(strtolower($payment_status) != 'completed'){
            $response = [
                            'status'=>'0',
                            'msg'=>'payment failed. please try after some time!..',
                            'data'=>$data,                            
                        ]; 

            return response()->json($response);            
            exit;
        }
        
        $created_at = date('Y-m-d H:i:s');
        $pending_credits=0;
        $otp = "";
        $otp_expires_on="";
        $paypal_email_verified="";
        //$invoice_number = strtoupper(Str::random(13));
        

        $uploadsinfo = DB::table('inlineclientarea_dummy_uploads')->where('id',$dummy_file_id)->first();
        $userid = $reg_id;
       
        $current_date = date('Y-m-d');  
        $actual_file_path = "uploads/$userid/".$uploadsinfo->file_name; 
        $usercredits = $purchased_crdits;
        $credits_need = $usercredits;
        

        $affected2 = DB::update("update  tbl_registration   
        SET credits =  credits + $purchased_crdits WHERE reg_id = $userid ");                                        

          

            $myfileid = DB::table('user_upload')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$uploadsinfo->file_name,
                    'user_given_name'=>$uploadsinfo->file_name,
                    'file_path'=>$actual_file_path,       
                    'xls_file_path'=>$uploadsinfo->file_path,         
                    'user_id'=>$reg_id,                
                    'created_at'=>$current_date,    
                    'credits_need'=>$uploadsinfo->credits_need,            
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


            $userstorage_old_path = storage_path("app/public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."inline_client_area/".$uploadsinfo->file_name);         

            $userstorage_new_path = storage_path("app/public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."$reg_id/".$uploadsinfo->file_name);         

            copy($userstorage_old_path,$userstorage_new_path);
            unlink($userstorage_old_path);

            $affected = DB::table('inlineclientarea_dummy_uploads')
                    ->where('id',$dummy_file_id)                    
                    ->delete();


            $txn_amount = $paymentDetail['purchase_units'][0]['amount']['value'];
            $txn_payment_ref_number=$paymentDetail['purchase_units'][0]['reference_id'];
            $txn_invoice_number=$paymentDetail['payer']['payer_id'];
            $transaction_id = $paymentDetail['id'];

        $id = DB::table('invoices')->insertGetId(
            [                                                
                'amount'=>$txn_amount,
                'credits'=>$uploadsinfo->credits_need,
                'pending_credits'=>0,
                'payment_status'=>'true',
                'user_id'=>$userid,
                'paypal_email'=>$paymentDetail['payer']['email_address'],
                'paypal_email_verified'=>'y',
                'payment_ref_number'=>$txn_payment_ref_number,
                'transaction_id'=>$transaction_id,
                'created_at'=>$created_at,                
                'invoice_number'=>$txn_invoice_number,
                'payer_verify_otp'=>"",                
                'coupon_code'=>'',
            ]
        );
        
        $userInfo = DB::table('tbl_registration')->where('reg_id',$reg_id)->first(); 
        $available_credits = $userInfo->credits;
        
    
        if($id > 0){

            $data['newfileid']=$myfileid;
            $data['newfilename']=$uploadsinfo->file_name;
            $data['available_credits']=$available_credits;
            $message="";



            $response = [
                            'status'=>'1',
                            'msg'=>$message,
                            'data'=>$data,                            
                        ]; 
        }else{
            $response = [
                            'status'=>'0',
                            'msg'=>'payment failed. please try after some time!..',
                            'data'=>$data,                            
                        ]; 
        }

        return response()->json($response);
    }

    public function startMyProcess(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);    


        $mvfileid='';
        $lip_state='';
        $credits_need = 0;
        $ready_for_download=0;

        $uploadsinfo = DB::table('user_upload')->where('upload_id',$fileid)->first();
        $current_file_path = storage_path("app/public/uploads/$userid/".$uploadsinfo->file_name);  
        

        //$credits_need = 807;
        $mev_robot_active = DB::table('tbl_admin_settings')->where('id', '1')->value('mev_robot_active');
        echo "here: $mev_robot_active";exit;

        if($mev_robot_active == '1'){

            $lip_state=1;
        }

        if($mev_robot_active == '0' || $mev_robot_active == 0 ){
             
            $mv_response = Http::attach(
                'file_contents',file_get_contents($current_file_path),$uploadsinfo->file_name
            )->post('https://bulkapi.millionverifier.com/bulkapi/v2/upload',['key'=>'Kai6IGOSveck7symwzmZhhG1B']);

            $mv_response_obj = json_decode($mv_response);

            $mvfileid = 'MV_'.$mv_response_obj->file_id;

            /*echo "MV res: <pre>";
            print_r($mv_response_obj);            
            exit;*/
            $ready_for_download=3;
        }

        $affected2 = DB::update("update  tbl_registration   
        SET blocked_credits = blocked_credits + $credits_need, credits =  credits - $credits_need 
        WHERE reg_id = $userid "); 

        $userInfo = DB::table('tbl_registration')->where('reg_id',$userid)->first(); 
        $data['users_available_credits'] = ($userInfo->credits + $userInfo->blocked_credits);


        $affected = DB::table('user_upload')
                    ->where('upload_id',$fileid)
                    ->update(['ready_for_download'=>$ready_for_download]);          



        $response = ['status'=>'1','msg'=>'','data'=>$data];                     
        return response()->json($response);
        exit;
    }

    public function startMyProcess_old(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);    


        //$credits_need = 807;
        $affected2 = DB::update("update  tbl_registration   
        SET blocked_credits = blocked_credits + $credits_need, credits =  credits - $credits_need 
        WHERE reg_id = $userid "); 

        $userInfo = DB::table('tbl_registration')->where('reg_id',$userid)->first(); 
        $data['users_available_credits'] = ($userInfo->credits + $userInfo->blocked_credits);


        $affected = DB::table('user_upload')
                    ->where('upload_id',$fileid)
                    ->update(['ready_for_download'=>$ready_for_download,'eps'=>$mvfileid]);          



        $response = ['status'=>'1','msg'=>'','data'=>$data];                     
        return response()->json($response);
        exit;
    }

    public function getFileProgress(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);    

        $file_info = DB::table('user_upload')->where('upload_id',$fileid)->first();  

        $used_credits = 0;
        $available_credits = 0;

        $valid = $file_info->valid;
        $invalid = $file_info->invalid;
        $unknown = $file_info->unknown;
        $spam_trap = $file_info->spam_trap;
        $toxic_domains = $file_info->toxic_domains;
        $total = $file_info->total;


        $csv_link = url('downloadreport/csv/'.$file_info->upload_id);
        $xls_link = url('downloadreport/xls/'.$file_info->upload_id);


        $progress =  ((($valid+$invalid+$unknown+$spam_trap+$toxic_domains)/$total)*100);       


        $data['myfileinfo'] = $file_info; 
        $data['myfileinfo']->csv_link = $csv_link; 
        $data['myfileinfo']->xls_link = $xls_link; 
        $data['myfileinfo'] = $file_info; 

        $data['progress'] = ceil($progress);   

        if($data['progress'] > 100){
            $data['progress'] = 100;
        }

        if($file_info->ready_for_download == 1){
            
            $user_info = DB::table('tbl_registration')->where('reg_id',$file_info->user_id)->first();  

            $data['progress'] = 100;
            $available_credits = ($user_info->credits + $user_info->blocked_credits);
            $used_credits = $file_info->credit_used;
        }else{
            if($data['progress'] >= 100){
                $data['progress'] = 99;
            }
        }

        $data['used_credits'] = $used_credits; 
        $data['available_credits'] = $available_credits; 

        $response = ['status'=>'1','msg'=>'','data'=>$data]; 
        return response()->json($response);
        exit;  
    }

    public function verifyMyFileOTP(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);        

        $dummy_info = DB::table('inlineclientarea_dummy_uploads')->find($newrecid);             

        if(!$dummy_info){

            $response = ['status'=>'0','msg'=>'Something went wrong.','data'=>$data]; 
            return response()->json($response);
            exit;
        }            

        $actual_file_path = "uploads/$new_users_id/".$dummy_info->file_name; 

        if($dummy_info->otp == $myotp){

            //$dummy_info = DB::table('inlineclientarea_dummy_uploads')->find($newrecid);
            $user = DB::table('tbl_registration')
                    ->where(['reg_id'=>"$new_users_id"])->first();           
            
            if($user->credits < $dummy_info->credits_need){
                
                $data['payment_need'] = 'y';                                           
                $payment_required = 'y';

                $total_emails = $dummy_info->credits_need;
                $available_credits = $user->credits;

                $payment_for = ($dummy_info->credits_need - $user->credits);
                $netamount = $this->calculate_amount($payment_for);
                $data['payment_for_caption'] = "$payment_for credits ($total_emails - $available_credits available) ";
                $data['purchased_crdits'] = $payment_for;

                $response = [
                                'status'=>'1',
                                'msg'=>'',
                                'data'=>$data,
                                'error_type'=>'payment_required',
                                'netamount'=>round($netamount,2)
                            ]; 
                return response()->json($response);
                exit;
            }        

            

            $id = DB::table('user_upload')->insertGetId(
                [                      
                    'file_name'=>$dummy_info->file_name,                    
                    'user_given_name'=>$dummy_info->user_given_name,                    
                    'credits_need'=>$dummy_info->credits_need,
                    'file_path'=>$actual_file_path, 
                    'xls_file_path'=>'',                                 
                    'user_id'=>$dummy_info->user_id,                
                    'created_at'=>$dummy_info->created_at,                
                    'ready_for_download'=>-1,                
                    'valid'=>0,                
                    'invalid'=>0,                
                    'total'=>$dummy_info->total,                
                    'unknown'=>0, 
                    'spam_trap'=>0,               
                    'toxic_domains'=>0,               
                    'credit_used'=>0,                
                    'eps'=>'',                
                    'status'=>'',                
                ]
            );

            $data['fileid']=$id;
            $data['file_name']=$dummy_info->file_name;
            $data['available_credits']=$user->credits;


            $userstorage_old_path = storage_path("app/public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."inline_client_area/".$dummy_info->file_name);         

            $userstorage_new_path = storage_path("app/public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."$new_users_id/".$dummy_info->file_name);         

            copy($userstorage_old_path,$userstorage_new_path);
            unlink($userstorage_old_path);
            //Storage::delete($userstorage_old_path);

            DB::table('inlineclientarea_dummy_uploads')->where('id',$newrecid)->delete();
                        

            $response = ['status'=>'1','msg'=>'<span style="color:green;" >
                                    OTP Verified
                                </span>','data'=>$data];             
        }else{
            $response = ['status'=>'0','msg'=>'<span style="color:red;" >Incorrect OTP</span>','data'=>$data];                 
        }

        return response()->json($response);
        exit;        
    }

    public function registeruseremail(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);
        
        $created_at = date('Y-m-d H:i');
        $otp = rand(100001,999999);

        $rec_exists = DB::table('tbl_registration')->where('email',$email)->exists();           
        if($rec_exists){

            $uinfo = DB::table('tbl_registration')->where('email',$email)->first();

            $dummy_affected = DB::table('inlineclientarea_dummy_uploads')
                            ->where('id',$dummy_file_id)
                            ->update(['user_id'=>$uinfo->reg_id,'otp'=>$otp]);


            $useremail = $email;
            $first_name=$uinfo->firstname;
            $last_name=$uinfo->lastname;
            $mydate = date("Y-m-d H:i:s");

            /* Send OTP  */
            $maildata = [         
                'login_otp'=>$otp,
                'username'=>$first_name.' '.$last_name, 
                'otp_expires_on'=>$mydate,
                'mevlogourl'=>url('public/img/mev-logo-2.png'),
            ];


            Mail::send('mails.sendotp', $maildata, function($message)use($useremail)
            {
                $message->to("$useremail", 'MyEmailVerifier')
                ->subject('Login OTP');

                 $message->from('support@myemailverifier.com',env('APP_NAME',' '));
            });

            /* ----------------------------- */                

            $response = [
                        'status'=>'1',
                        'msg'=>'<span style="color:green;" >OTP sent to your email address.</span>',
                        'new_user'=>'0',
                        'new_users_id'=>$uinfo->reg_id,
                        'useridencoded'=>base64_encode($uinfo->reg_id),
                        'data'=>$data
                    ];                            
        }else{

            $newkey = Str::random(16);
            $newkey =  strtoupper($newkey); 
            $ip_address = $request->ip();

            $new_password = Str::random(10);
            $otp = rand(100001,999999);
            $mydate = date("Y-m-d H:i:s");
            $otp_last_date=date_create(date("Y-m-d H:i:s"));
            date_add($otp_last_date,date_interval_create_from_date_string("11 minutes"));
            $otp_expires_on = date_format($otp_last_date,"Y-m-d H:i:s");

            $reg_id = DB::table('tbl_registration')->insertGetId(
                [
                    'password' => '',
                    'firstname' => '',                    
                    'lastname' => '',
                    'email' => $email,
                    'password'=>$new_password,
                    'credits'=>500, 
                    'access' => 1,
                    'created_at'=>$created_at,
                    'IP'=>$ip_address,
                    'country'=>'0',                    
                    'email_activation_code'=>"",
                    'email_verified'=>"",
                    'apikey'=>$newkey,
                    'login_otp' => $otp,
                    'otp_expires_on' => $otp_expires_on,
                ]
            );

            $userstoragepath = storage_path("app/public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."$reg_id");

            if(!file_exists($userstoragepath)){             
                mkdir($userstoragepath,0777);                
            }


            $dummy_affected = DB::table('inlineclientarea_dummy_uploads')
                            ->where('id',$dummy_file_id)
                            ->update(['user_id'=>$reg_id,'otp'=>$otp]);
            

            $useremail = $email;
            $first_name="User";
            $last_name="";

            /* Send OTP  */
            $maildata = [         
                'login_otp'=>$otp,
                'username'=>'User', 
                'otp_expires_on'=>$mydate,
                'mevlogourl'=>url('public/img/mev-logo-2.png'),
            ];


            Mail::send('mails.sendotp', $maildata, function($message)use($useremail)
            {
                $message->to("$useremail", 'MyEmailVerifier')
                ->subject('Login OTP');

                 $message->from('support@myemailverifier.com',env('APP_NAME',' '));
            });

            /* ----------------------------- */

            

            /* set randome password for first time */

            $pwd_maildata = [         
                'new_password'=>$new_password,  
                'mevlogourl'=>url('public/img/mev-logo-2.png')
            ];


            Mail::send('mails.newpassword', $pwd_maildata, function($message)use($useremail)
            {
                $message->to("$useremail", 'MyEmailVerifier')
                ->subject('New Password - MyEmailVerifier');

                 $message->from('support@myemailverifier.com',env('APP_NAME',' '));
            });

            /* ---------------- */

            $response = [
                            'status'=>'1',
                            'msg'=>'<span style="color:green;" >OTP sent to your email address.</span>',
                            'new_user'=>'1',
                            'new_users_id'=>$reg_id,
                            'useridencoded'=>base64_encode($reg_id),
                            'data'=>$data
                        ];
        }

        return response()->json($response);                                 
        exit;
    }

    public function uploaduserfile(Request $request){
        //sleep(3);
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        extract($postData);

        if(!$request->file('mevinlinefile') || $request->file('mevinlinefile') == ''){ 
            $response = ['status'=>'0','msg'=>'Please select a file','data'=>$data];
            return response()->json($response);  
            exit;    
        }

        $no_of_lines = 0;
        $new_gud_path = '';

        
        $csvFileObj = $request->file('mevinlinefile');        
        $extension = $request->file('mevinlinefile')->getClientOriginalExtension();

        if($extension != 'csv' && $extension != 'txt' && $extension != 'xls' && $extension != 'xlsx'){
            $response = ['status'=>'0','msg'=>'Invalid File uploaded. ','data'=>$data]; 
            return response()->json($response);
            exit;
        }else{

            $newfilename = rand().time().'.'.$extension;            

            $orgname =  $request->file('mevinlinefile')->getClientOriginalName();
            $filesize =  $request->file('mevinlinefile')->getSize();

            $orgname = str_replace("'","",$orgname);
            $orgname = str_replace("#","",$orgname);
            $orgname = str_replace("@","",$orgname);

            $filename_repeat = DB::table('user_upload')
                ->where(
                        ['file_name'=>$orgname]                         
                    )
                ->count();

            if($filename_repeat > 0){
                $file_path_info = pathinfo($orgname);
                $filename_without_ext = $file_path_info['filename'];
                $filename_without_ext.='_'.time();
                $orgname = $filename_without_ext.".".$extension;
            }

            $path = $request->file('mevinlinefile')->storeAs("public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."inline_client_area".DIRECTORY_SEPARATOR,"$orgname");

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
        }


        $payment_required = 'n';
        $netamount = 0;
        //$no_of_lines = 670;
        /*if($no_of_lines > 500){

            $data['no_of_lines'] = $no_of_lines;            
            $data['payment_need'] = 'y';                                           
            $payment_required = 'y';
            $payment_for = ($no_of_lines - 500);
            $netamount = $this->calculate_amount($payment_for);
            $data['payment_for_caption'] = "$payment_for credits ($no_of_lines - 500 free credits) ";
            $data['purchased_crdits'] = $payment_for;

            $response = ['status'=>'1','msg'=>'','data'=>$data,'error_type'=>'payment_required','netamount'=>$netamount]; 
            return response()->json($response);
            exit;
        }*/


        $id = DB::table('inlineclientarea_dummy_uploads')->insertGetId(
                [                      
                    //'file_name'=>$newfilename,
                    'file_name'=>$orgname,
                    'user_given_name'=>$orgname,
                    'filesize'=>$filesize,
                    'credits_need'=>$no_of_lines,
                    'payment_required'=>$payment_required,
                    'file_path'=>$new_gud_path, 
                    'xls_file_path'=>'',                                 
                    'user_id'=>0,                
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

        //$data['payment_need'] = $payment_required; 
        $data['dummyid'] = $id;
        $data['no_of_lines'] = $no_of_lines;           
        
        
        $response = ['status'=>'1','msg'=>'','data'=>$data,'netamount'=>round($netamount,2)];  
        //$response = array('status'=>'1','msg'=>'','data'=>$data,'error_type'=>'');

        return response()->json($response);
        exit;
    } 
    

    public function calculate_amount($credits=0){

        $netamount = 0;

        if($credits < 2000){
            $price_per_credit = 0.0048;
        }

        if($credits >= 2000){       
            $price_per_credit = 0.0042;
        }

        if($credits >= 10000){
            $price_per_credit = 0.0036;
        }

        if($credits >= 50000){
            $price_per_credit = 0.0024;
        }

        if($credits >= 500000){
            $price_per_credit = 0.0020;
        }

        if($credits >= 1000000){
            $price_per_credit = 0.0015;
        }

        if($credits >= 2000000){
            $price_per_credit = 0.001125;
        }

        if($credits >= 5000000){
            $price_per_credit = 0.0009;
        }

        $netamount = ($credits * $price_per_credit);

        return $netamount;
    }    

}


