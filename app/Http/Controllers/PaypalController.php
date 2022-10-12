<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Http;


class PaypalController extends Controller 
{    
    public function index(){
        
    }       

    
    public function paypalnotification(Request $request){
        
    }

     public function notifySubscription(Request $request){

        $data = [];
        $maildata = [];
        $postData = $request->all();
        //echo $postData;exit;
        //$postData = $_REQUEST;
        extract($postData);                    
        $custom_data = explode("|",$postData['custom']); 

        if(isset($postData['payment_status']) && $postData['payment_status'] != '' ){
            if(strtolower($postData['payment_status']) == 'completed'){
                    
            
                    $userid = $custom_data[0];
                    $planref = $custom_data[1];

                    $today = date('Y-m-d H:i:s');
                    $sub_start_date = strtotime($today);        

                    //$payres = print_r($_REQUEST,true); 

                    $userinfo = DB::table('tbl_registration')->where('reg_id', $userid)->first();


                    $maildata['username'] = $first_name." ".$last_name;
                    $maildata['plan_name'] = $item_name;
                    $maildata['end_date'] =  date("Y-m-d", strtotime("+1 month", $sub_start_date));    
                    $maildata['mevlogourl'] =  url('public/img/mev-logo-2.png');    
                    

                    if($planref == 'mev5k'){
                        $maildata['credits'] = "5000";
                    }

                    if($planref == 'mevweekly'){
                        $maildata['credits'] = "5000";
                    }

                    if($planref == 'mev10k'){
                        $maildata['credits'] = "10000";
                    }

                    if($planref == 'mev50k'){
                        $maildata['credits'] = "50000";
                    }

                    if($planref == 'mev100k'){
                        $maildata['credits'] = "100000";
                    }

                    if($planref == 'mevsub1'){
                        $maildata['credits'] = "100";
                    }

                    $new_credits = $maildata['credits'];               
                    $useremail = $userinfo->email;
                    //$useremail = "kaushal.accuwebhosting@gmail.com";

                    Mail::send('mails.subscription_notify', $maildata, function($message)use($useremail)
                    {
                        $message->to("$useremail", 'MyEmailVerifier')
                        ->subject('Subscription');

                         $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                    });

                    /* Send Internal Email to Admin */

                    $internal_maildata = [
                        'credits'=>$maildata['credits'],
                        'plan_name'=>$item_name, 
                        'subscribed_date'=>$today,  
                        'subscription_date'=>$today,  
                        'useremail'=>$userinfo->email,  
                        'username'=>$userinfo->firstname.' '.$userinfo->lastname,
                        'amount'=>$mc_gross,  
                        'mevlogourl'=>url('public/img/mev-logo-2.png'),
                                                  
                    ];
                            
                    $admins_email=[                    
                        'jpatadiya@gmail.com',
                        'piyushpatdiya@gmail.com',
                        'kaushal.accuwebhosting@gmail.com',                    
                    ];

                    Mail::send('mails.internal_order_subscription', $internal_maildata, function($message)use($admins_email)
                    {
                        $message->to($admins_email, 'MyEmailVerifier')
                        ->subject('Internal: Subscription Order Received');

                         $message->from('support@myemailverifier.com',env('APP_NAME',' '));
                    });

                    /* ----------------------- */


                    $id = DB::table('tbl_subscriptions')->insertGetId(
                        [                                                
                            'userid'=>$userid,                
                            'plan_name'=>$item_name,                
                            'plan_type'=>'monthly',                
                            'credits'=>$new_credits,                
                            'amount'=>$mc_gross,                
                            'paypal_ref'=>$planref,                
                            'paypal_btn_id'=>'',                
                            'subscribed_date'=>$today,  
                            'subscription_end_date'=>$maildata['end_date'],                              
                            'txn_id'=>$txn_id,                
                            'subscription_paypal_id'=>$subscr_id,                
                            'status'=>'active',                
                            'created_on'=>$today,                
                        ]
                    );       

                          
                    $invoiceid = DB::table('invoices')->insertGetId(
                        [                                                
                            'user_id'=>$userid,                                
                            'is_subscription'=>'y',
                            'credits'=>$new_credits,                
                            'amount'=>$mc_gross,                                
                            'subscription_date'=>$today,  
                            'subscription_end_date'=>$maildata['end_date'],                              
                            'payment_ref_number'=>$txn_id,                                                
                            'paypal_email_verified'=>'y',
                            'paypal_email'=>$payer_email,                                                
                            'invoice_number'=>$payer_id,               
                            'payment_status'=>'true',               
                            'pending_credits'=>'0',    
                            'created_at'=>$today,            
                        ]
                    );           

                    if($id > 0){

                        $affected = DB::update("update tbl_registration
                                             SET credits =  credits + $new_credits WHERE reg_id = $userid "); 
                    }        
            }
        }
    }

    public function notifySubscription_old(Request $request){
        
        $data = [];
        $maildata = [];
        $postData = $request->all();
        //echo $postData;exit;
        //$postData = $_REQUEST;
        extract($postData);                    
        $custom_data = explode("|",$postData['custom']); 

        //echo "here 234"; exit;

        $userid = $custom_data[0];
        $planref = $custom_data[1];

        $today = date('Y-m-d H:i:s');
        $sub_start_date = strtotime($today);        

        //$payres = print_r($_REQUEST,true); 

        $userinfo = DB::table('tbl_registration')->where('reg_id', $userid)->first();


        $maildata['username'] = $first_name." ".$last_name;
        $maildata['plan_name'] = $item_name;
        $maildata['end_date'] =  date("Y-m-d", strtotime("+1 month", $sub_start_date));    
        $maildata['mevlogourl'] =  url('public/img/mev-logo-2.png');    
        

        if($planref == 'mev5k'){
            $maildata['credits'] = "5000";
        }

        if($planref == 'mevweekly'){
            $maildata['credits'] = "5000";
        }

        if($planref == 'mev10k'){
            $maildata['credits'] = "10000";
        }

        if($planref == 'mev50k'){
            $maildata['credits'] = "50000";
        }

        if($planref == 'mev100k'){
            $maildata['credits'] = "100000";
        }

        if($planref == 'mevsub1'){
            $maildata['credits'] = "100";
        }

        $new_credits = $maildata['credits'];               
        $useremail = $userinfo->email;
        //$useremail = "kaushal.accuwebhosting@gmail.com";

        Mail::send('mails.subscription_notify', $maildata, function($message)use($useremail)
        {
            $message->to("$useremail", 'MyEmailVerifier')
            ->subject('Subscription');

             $message->from('support@myemailverifier.com',env('APP_NAME',' '));
        });

        /* Send Internal Email to Admin */

        $internal_maildata = [
            'credits'=>$maildata['credits'],
            'plan_name'=>$item_name, 
            'subscribed_date'=>$today,  
            'subscription_date'=>$today,  
            'useremail'=>$userinfo->email,  
            'username'=>$userinfo->firstname.' '.$userinfo->lastname,
            'amount'=>$mc_gross,  
            'mevlogourl'=>url('public/img/mev-logo-2.png'),
                                      
        ];
                
        $admins_email=[                    
            'jpatadiya@gmail.com',
            'piyushpatdiya@gmail.com',
            'kaushal.accuwebhosting@gmail.com',                    
        ];

        Mail::send('mails.internal_order_subscription', $internal_maildata, function($message)use($admins_email)
        {
            $message->to($admins_email, 'MyEmailVerifier')
            ->subject('Internal: Subscription Order Received');

             $message->from('support@myemailverifier.com',env('APP_NAME',' '));
        });

        /* ----------------------- */


        $id = DB::table('tbl_subscriptions')->insertGetId(
            [                                                
                'userid'=>$userid,                
                'plan_name'=>$item_name,                
                'plan_type'=>'monthly',                
                'credits'=>$new_credits,                
                'amount'=>$mc_gross,                
                'paypal_ref'=>$planref,                
                'paypal_btn_id'=>'',                
                'subscribed_date'=>$today,  
                'subscription_end_date'=>$maildata['end_date'],                              
                'txn_id'=>$txn_id,                
                'status'=>'active',                
                'created_on'=>$today,                
            ]
        );       

        $invoiceid = DB::table('invoices')->insertGetId(
            [                                                
                'user_id'=>$userid,                                
                'is_subscription'=>'y',
                'credits'=>$new_credits,                
                'amount'=>$mc_gross,                                
                'subscription_date'=>$today,  
                'subscription_end_date'=>$maildata['end_date'],                              
                'payment_ref_number'=>$txn_id,                                                
                'paypal_email_verified'=>'y',
                'paypal_email'=>$payer_email,                                                
                'invoice_number'=>$payer_id,               
                'payment_status'=>'true',               
                'pending_credits'=>'0',    
                'created_at'=>$today,            
            ]
        );        

        if($id > 0){

            $affected = DB::update("update tbl_registration
                                 SET credits =  credits + $new_credits WHERE reg_id = $userid "); 
        }
    }

    public function subscribed(){

        /* Send Email */
            $data = [];
            $msgdata = [];

            //$payres = print_r($_REQUEST,true);

            /*$maildata = [                         
                'username'=>'Kaushal Kapadiya',                
                'username'=>'Kaushal Kapadiya',                
                'paypal_res'=>$payres,
            ];
            
           
            Mail::send('mails.paypal_notify', $maildata, function($message)
            {
                $message->to("kaushal.accuwebhosting@gmail.com", 'MyEmailVerifier')
                ->subject('Subscription Success');

                 $message->from('xyz@gmail.com',env('APP_NAME',' '));
            });

            $response = ['status'=>'1','msg'=>'Password reset link sent on your email address. !...'
            ,'data'=>$data];*/


            /* ---------------------------- */

            $msgdata['logo_url'] = url('public/img/mev-logo.svg');
            $msgdata['return_url'] = url('Invoice');

            return view("message.subscribe_success",$msgdata);            
    }

    public function paypalnotify(){

         /* Send Email */

            mail("kaushal.accuwebhosting@gmail.com","Credits Purchased"," 1000 credits purchased ");         

            /*$data = [];
            $maildata = [                         
                'username'=>'Kaushal Kapadiya',                
            ];
           
            Mail::send('mails.paypal_notify', $maildata, function($message)
            {
                $message->to("kaushal.accuwebhosting@gmail.com", 'MyEmailVerifier')
                ->subject('Paypal Notify Test');

                 $message->from('xyz@gmail.com',env('APP_NAME',' '));
            });

            $response = ['status'=>'1','msg'=>'Password reset link sent on your email address. !...'
            ,'data'=>$data];*/


            /* ---------------------------- */


        echo "<pre>";
        print_r($_REQUEST);
        exit;        
    }

}
