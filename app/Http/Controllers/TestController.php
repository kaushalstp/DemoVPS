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

class TestController extends Controller
{
    
    public function __construct(){
        session(['umsg'=>'']);
        Session::forget('umsg');
    }

    public function index(){
                                         
    } 


    public function copyinlinefile(){

        $fileurl = "https://myemailverifier.com/mev2/public/downloadreport/csv/47542";
        $newfilename="mynewfile.csv";
        $newfilepath = "";

        $newpath = storage_path("app\public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."111".DIRECTORY_SEPARATOR."$newfilename");     

        if(file_exists($newpath)){
            echo " file exist <Br/> ";
        }else{
            echo " file doest not exist <Br/> ";
        }   

        copy($fileurl,$newpath);

        if(file_exists($newpath)){
            echo " file exist <Br/> ";
        }else{
            echo " file doest not exist <Br/> ";
        }   

        exit;

        /*$path = $request->file('filename')->storeAs("public".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."$userid".DIRECTORY_SEPARATOR,"$orgname");*/
    }

    public function testmethod(Request $request){
        $userid = session('userid');
        $postData = $request->all();        
        $data = [];            
        $used_coupons = [];            
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        $curr_time = date('Y-m-d');
        extract($postData);            
    }


    public function getpaypaltoken(){
        

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


        /*  CURL cancel subscription  */

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api-m.paypal.com/v1/billing/subscriptions/I-5M7J2NWR4A55/cancel');
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
        echo "<pre>";print_r($cancelinfo);exit;

        /* ------------------------------ */


        //return response()->json($result);
        exit;

    }

}


