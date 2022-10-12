<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Http;
//use MailchimpMarketing\ApiClient;



class AdminController extends Controller
{
    
    public function index(){
                                         
    } 

    public function rejectuserreward(Request $request){ 
        $postData = $request->input();        
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);


        DB::table('tbl_social_share')->where('id',$record_id)->update([
            'rewarded'=>'n',
            'rejected_reason'=>$rejected_reason,
        ]);
        
        $receiver_email = $request_by['useremail'];
        $maildata['username'] = $request_by['username'];
        $maildata['rejected_reason'] = $rejected_reason;
        $maildata['mevlogourl']= url('public/img/mev-logo-2.png');

        Mail::send("mails.notify_reward_rejection",$maildata,function($message)use($receiver_email)
        {
            $message->to($receiver_email, 'MyEmailVerifier')
            ->subject('Free Credits - Rejected');

            $message->from("support@myemailverifier.com","");
        });                    

        $response = ['status'=>'1','msg'=>'rejected successfully!... ','data'=>$data];                           
        return response()->json($response);
        exit;
    }

    public function approveSpecialDiscount(Request $request){
        $postData = $request->input();        
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        DB::table('tbl_special_discount_request')->where('id',$rec_id)->update([
            'amount'=>$amount,
            'approved'=>'y',
        ]);


        $text='Your special discount for '.$required_credits.' credits approved worth $ '.$amount;

        $id = DB::table('tbl_notifications')->insertGetId(
            [
                'subject' => 'Special Discount Approval',    
                'text' => $text,
                'users' => $reg_id,
                'read_by' => '0',            
                'created_at' => date('Y-m-d H:i:s'),             
            ]
        );

        $invcid = DB::table('invoices')->insertGetId(
                [ 
                    'credits'=>$required_credits,
                    'amount'=>$amount,
                    'payment_status'=>false,
                    'user_id'=>$reg_id,
                    'invoice_number'=>"Manual_".date("d-m-Y"),
                    'payment_ref_number'=>"direct",
                    'created_at'=>date("Y-m-d H:i:s"),
                    'pending_credits'=>$required_credits,
                    'special_discount'=>'y',
                ]
            ); 

        $maildata=[
                'contact_person'=>$contact_person,                
                'contact_email'=>$contact_email,                
                'required_credits'=>$required_credits,
                'amount'=>$amount,                
                'mevlogourl'=>url('public/img/mev-logo-2.png')
            ];

        Mail::send('mails.discount_approval',$maildata,function($message)use($maildata)
        {
            $message->to($maildata['contact_email'], 'MyEmailVerifier')
            ->subject('Spcial Discount Approval');

            $message->from("support@myemailverifier.com","");
        });

        $response = ['status'=>'1','msg'=>'Approved Successfully!...','data'=>$data]; 
        return response()->json($response);    
        exit;

    }

    public function getSpecialDiscountRequest(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
                    
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];

        $orderBy="id";
        $order="desc";

        $orWhereColumns = [];
        $orWhere = ['approved'=>'n'];

        if(count($filters) > 0){
            $filter_email=$filters[0]['text'];            
            $orWhere = [['email', 'like' ,"%".$filter_email."%"]];
        }

        
        if(count($postData['params']['queryParams']['sort']) > 0){
            
            $orderBy = $postData['params']['queryParams']['sort'][0]['name'];
            $order = $postData['params']['queryParams']['sort'][0]['order'];        
        }
        
        $offset = (($page - 1) * $limit);

        //$sql_query=DB::table('tbl_registration')->where($orWhere)->toSql();
         
        $total_posts = DB::table('tbl_special_discount_request')->where($orWhere)->count();        

        $posts = DB::table('tbl_special_discount_request as s')    
                ->leftJoin('tbl_registration as r','r.reg_id','=','s.userid')
                ->select(DB::raw("CONCAT(r.firstname,' ',r.lastname) as username"),'r.email','s.*')
                ->where($orWhere)                
                ->orderBy($orderBy, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
               
        for($i=0;$i<count($posts);$i++){            

            $posted_date = date('d-m-Y',strtotime($posts[$i]->created_at));
            $posts[$i]->created_at = $posted_date;                    
        }


        if(!$posts){
            //echo "not found";exit;
        }else{
           // echo "<pre>";print_r($logs);exit;
            $data = $posts;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
        //echo "<pre>";print_r($user);exit;

        $response['status']=1;
        $response['logs']=$posts;                    
        $response['total']=$total_posts;

        return response()->json($response);
    }

    public function releaseBlockedCredits(Request $request){
        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'1','msg'=>'Done','data'=>$data];

        $affected = DB::update("update  tbl_registration   
        SET blocked_credits =  0, credits =  credits + $blocked_credits
        WHERE reg_id = $reg_id "); 
        
        return response()->json($response);

    }

    public function saveAdminSettings(Request $request){

        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];   

        $adminSettings = DB::table('tbl_admin_settings')
                        ->where('id',1)
                        ->update([
                            'social_article_text'=>$social_article_text,
                            'social_share_interval'=>$social_share_interval,
                        ]);

        $response = ['status'=>'1','msg'=>'Saved...','data'=>$data];                           
        return response()->json($response);
        exit;
    }


    public function getAdminSettings(Request $request){

        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];   

        $adminSettings = DB::table('tbl_admin_settings')->where('id',1)->first();

        $data['admin_settings'] = $adminSettings;
        $response = ['status'=>'1','msg'=>'','data'=>$data];           
        return response()->json($response);
        exit;

        //echo "<pre>";print_r($adminSettings);exit;
        
    }

    public function addRewardCredits(Request $request){

        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $affected = DB::update("UPDATE tbl_registration set credits = (credits + $freecredits) 
            where reg_id = ?", [$reg_id]);

        
        $affected = DB::table('tbl_social_share')
            ->where('id', $recid)
            ->update(['rewarded' => 'y']);

                
        $affected = DB::table('tbl_social_share')
                    ->where('userid', '=', $reg_id)
                    ->where('id', '<', $recid)
                    ->where('social_media', '=',$media)
                    ->delete();

        $userinfo = DB::table('tbl_registration')->where('reg_id',$reg_id)->first();    
        $receiver_email=$userinfo->email;                

        $maildata=[                                
                'username'=>$userinfo->firstname.' '.$userinfo->lastname,                
                'freecredits'=>$freecredits,                
                'mevlogourl'=>url('public/img/mev-logo-2.png')
            ];


        Mail::send('mails.freecreditsapproved',$maildata,function($message)use($receiver_email)
        {
            $message->to($receiver_email, 'MyEmailVerifier')
            ->subject('Free Credits');

            $message->from("support@myemailverifier.com","");
        });                    

        $response = ['status'=>'1','msg'=>' Credit Added Successfully !... ','data'=>$data];  
             

        return response()->json($response);
    }


    public function getSharedPosts(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
                    
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];

        $orderBy="id";
        $order="desc";

        $orWhereColumns = [];
        $orWhere = ['rewarded'=>'p'];

        if(count($filters) > 0){
            $filter_email=$filters[0]['text'];            
            $orWhere = [['email', 'like' ,"%".$filter_email."%"]];
        }

        
        if(count($postData['params']['queryParams']['sort']) > 0){
            
            $orderBy = $postData['params']['queryParams']['sort'][0]['name'];
            $order = $postData['params']['queryParams']['sort'][0]['order'];        
        }
        
        $offset = (($page - 1) * $limit);

        //$sql_query=DB::table('tbl_registration')->where($orWhere)->toSql();
         
        $total_posts = DB::table('tbl_social_share')->where($orWhere)->count();        

        $posts = DB::table('tbl_social_share as s')    
                ->leftJoin('tbl_registration as r','r.reg_id','=','s.userid')
                ->select(DB::raw("CONCAT(r.firstname,' ',r.lastname) as username"),'r.email','s.*')
                ->where($orWhere)                
                ->orderBy($orderBy, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
               
        for($i=0;$i<count($posts);$i++){            

            $posted_date = date('d-m-Y',strtotime($posts[$i]->created_at));
            $posts[$i]->created_at = $posted_date;                    
        }


        if(!$posts){
            //echo "not found";exit;
        }else{
           // echo "<pre>";print_r($logs);exit;
            $data = $posts;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
        //echo "<pre>";print_r($user);exit;

        $response['status']=1;
        $response['logs']=$posts;                    
        $response['total']=$total_posts;

        return response()->json($response);
    }

    public function getReportsSubscribers(Request $request){

        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data,'errortype'=>''];
        extract($postData);

        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];


        $whrCond=" WHERE 1=1 ";
        $havingCond=" HAVING 1=1 ";


        if(isset($from_date) && $from_date != ''){

            if(isset($to_date) && $to_date != ''){

                if($from_date > $to_date){
                    $response = ['status'=>'0','msg'=>'','data'=>$data,'errortype'=>'invalid_date_range'];
                    return response()->json($response);    
                    exit;
                }

                $whrCond.=" AND (s.created_on BETWEEN '$from_date' AND '$to_date') ";
                $havingCond.=" AND (s.created_on BETWEEN '$from_date' AND '$to_date') ";
            }
        }

        
        $offset = (($page - 1) * $limit);     
        
        $reports_res = DB::select(" SELECT s.*,DATE(s.subscription_end_date) as subscription_end_date,CONCAT(r.firstname,' ',r.lastname) as username ,r.email FROM tbl_subscriptions s LEFT JOIN tbl_registration r ON r.reg_id = s.userid 
            $whrCond 
            ORDER BY s.id  DESC LIMIT $offset,$limit "); 

        
        $total_reports = DB::select(" SELECT count(s.id) as total FROM tbl_subscriptions s $whrCond ");


        //echo "<pre>";print_r($total_reports);exit;
        $total_recs = $total_reports[0]->total;

        if(empty($reports_res)){

            $data['allpaidusers'] = [];
            $data['total'] = 0;

            $response = ['status'=>'0','msg'=>' - No Record Found - ','data'=>$data,'errortype'=>''];
            return response()->json($response);
            exit;
        }

        $data['allpaidusers'] = $reports_res;
        $data['total'] = $total_recs;

        $response = ['status'=>'1','msg'=>' ','data'=>$data,'errortype'=>''];

        return response()->json($response);
        exit;    

    }

    public function getReportsHighestOrders(Request $request){

        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data,'errortype'=>''];
        extract($postData);

        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];


        $whrCond=" WHERE 1=1 ";
        $havingCond=" HAVING 1=1 ";


        if(isset($from_date) && $from_date != ''){

            if(isset($to_date) && $to_date != ''){

                if($from_date > $to_date){
                    $response = ['status'=>'0','msg'=>'','data'=>$data,'errortype'=>'invalid_date_range'];
                    return response()->json($response);    
                    exit;
                }

                $whrCond.=" AND (i.created_at BETWEEN '$from_date' AND '$to_date') ";
                $havingCond.=" AND (i.created_at BETWEEN '$from_date' AND '$to_date') ";
            }
        }

        
        $offset = (($page - 1) * $limit);     
        
        $reports_res = DB::select(" SELECT r.firstname,r.lastname,r.email,i.user_id,COUNT(i.invoice_id) AS highestorders FROM invoices i LEFT JOIN tbl_registration r ON r.reg_id = i.user_id 
            $whrCond 
            GROUP BY i.user_id ORDER BY COUNT(i.invoice_id)  DESC LIMIT $offset,$limit "); 


        

        $total_reports = DB::select(" SELECT count(DISTINCT(i.user_id)) as total FROM invoices i $whrCond ");


        //echo "<pre>";print_r($total_reports);exit;
        $total_recs = $total_reports[0]->total;

        if(empty($reports_res)){

            $data['allpaidusers'] = [];
            $data['total'] = 0;

            $response = ['status'=>'0','msg'=>' - No Record Found - ','data'=>$data,'errortype'=>''];
            return response()->json($response);
            exit;
        }

        $data['allpaidusers'] = $reports_res;
        $data['total'] = $total_recs;

        $response = ['status'=>'1','msg'=>' ','data'=>$data,'errortype'=>''];

        return response()->json($response);
        exit;
    }

    public function getAllOrders(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        
        $userid = session('userid');         
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];
        $sort=$postData['params']['queryParams']['sort'];
        //echo "<pre>";print_r($sort);exit;

        $whrRawCond=" 1=1 ";

        if(isset($from_date) && $from_date != ''){

            if(isset($to_date) && $to_date != ''){

                if($from_date > $to_date){
                    $response = ['status'=>'0','msg'=>'','data'=>$data,'errortype'=>'invalid_date_range'];
                    return response()->json($response);    
                    exit;
                }

                $whrRawCond.=" AND (invoices.created_at BETWEEN '$from_date' AND '$to_date') ";                
            }
        }
        
        

        $orderByColumn="invoice_id";
        $orderByAscDsc="desc";

        if(!empty($sort)){
            /*if(isset($sort[0]['name'])){
                $orderByColumn = $sort[0]['name'];                
            }*/
            
            foreach($sort as $loopsort){
                if(isset($loopsort['name']) && $loopsort['name'] != '' ){
                    $orderByColumn = $loopsort['name'];                       
                }

                if(isset($loopsort['order']) && $loopsort['order'] != '' ){
                    $orderByAscDsc = $loopsort['order'];
                }                
                
                
            }

            /*if(isset($sort[0]['order'])){
                $orderByAscDsc = $sort[0]['order'];
            }*/
        }

        $orderBy=$orderByColumn;
        $order=$orderByAscDsc;

        $orWhereColumns = [];
        $orWhere = [];



        if(count($filters) > 0){
            $filter_email='';            
            $filter_coupon_code='';            

            foreach($filters as $filterval){
                if($filterval['name'] == 'email'){
                       $filter_email=$filterval['text'];   
                       $orWhere[] =  ['tbl_registration.email', 'like' ,"%".$filter_email."%"];         
                }    

                if($filterval['name'] == 'coupon_code'){
                    $filter_coupon_code=$filterval['text'];             
                    $orWhere[] = ['invoices.coupon_code', 'like' ,"%".$filter_coupon_code."%"];
                }    
            }            
        }

        if(isset($global_search) && trim($global_search) != ''){

            $whrCond.= " AND (s.drname like '%$global_search%' 
            OR s.phone like '%$global_search%' 
            OR s.email like '%$global_search%' 
            OR s.patient_name like '%$global_search%' ) ";

            $global_search=$postData['params']['queryParams']['global_search'];
            $orWhereColumns = [                
                ['firstname',' like ',' "%$global_search%" '],
                ['lastname',' like ',' "%$global_search%" '],
                ['email',' like ',' "%$global_search%" '],
                ['phone',' like ',' "%$global_search%" '],                            
            ];                    
        }

        
        $offset = (($page - 1) * $limit);

        //$sql_query=DB::table('tbl_registration')->where($orWhere)->toSql();
         
        $total_users = DB::table('invoices')
                        ->leftJoin('tbl_registration', 'tbl_registration.reg_id', '=', 'invoices.user_id')
                        ->where($orWhere)->whereRaw(" $whrRawCond  ")->count();        

        $orders = DB::table('invoices')          
                ->leftJoin('tbl_registration', 'tbl_registration.reg_id', '=', 'invoices.user_id')      
                ->select("invoices.*","tbl_registration.email","tbl_registration.firstname"
                    ,"tbl_registration.lastname","tbl_registration.source")
                ->where($orWhere)          
                ->whereRaw(" $whrRawCond  ")      
                ->orderBy($orderBy, $order)
                ->offset($offset)
                ->limit($limit)
                ->get(); 




        for($i=0;$i<count($orders);$i++){            
            $orders[$i]->username = $orders[$i]->firstname." ".$orders[$i]->lastname;
            $orders[$i]->amount = "$ ".round($orders[$i]->amount,2);  
            $i_invoice_id = $orders[$i]->invoice_id;

            $invoice_download_link = url("downloadInvoice/$i_invoice_id");

            //$invoiceHistory[$i]->downloadlink = "<a href='$invoice_download_link' >Download</a>";
            $orders[$i]->downloadlink=$invoice_download_link; 

        }


        if(!$orders){
            //echo "not found";exit;
        }else{
           // echo "<pre>";print_r($logs);exit;
            $data = $orders;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
        //echo "<pre>";print_r($user);exit;

        $response['status']=1;
        $response['logs']=$orders;                    
        $response['total']=$total_users;

        return response()->json($response);
    }

    public function getRevenueBySource(Request $request){

        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];


        $whrCond=" WHERE 1=1 ";
        $whrCond2=" WHERE 1=1 ";
        $havingCond=" HAVING 1=1 ";


        if(isset($from_date) && $from_date != ''){
            if(isset($to_date) && $to_date != ''){
                $whrCond.=" AND (i.created_at BETWEEN '$from_date' AND '$to_date') ";
                $havingCond.=" AND (i.created_at BETWEEN '$from_date' AND '$to_date') ";
                $whrCond2.=" AND (i2.created_at BETWEEN '$from_date' AND '$to_date') ";
            }
        }

        $offset = (($page - 1) * $limit);     
        

        /*$reports_res = DB::select(" SELECT r.firstname,r.lastname,r.email,i.user_id, CONCAT('$',' ',ROUND(SUM(i.amount),2)) AS revenue FROM invoices i LEFT JOIN tbl_registration r ON r.reg_id = i.user_id 
            $whrCond
            GROUP BY i.user_id ORDER BY ROUND(SUM(i.amount),2)  DESC LIMIT $offset,$limit "); */

        $qry="SELECT r.source,count(r.reg_id) as users,(SELECT CONCAT('$',' ',ROUND(SUM(i.amount),2)) FROM invoices i $whrCond AND i.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source)) as source_revenue,(SELECT count(i2.invoice_id) FROM invoices i2 $whrCond2 AND i2.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source)) as orders FROM tbl_registration r GROUP BY r.source ORDER BY (SELECT SUM(i.amount) FROM invoices i $whrCond AND i.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source)) DESC LIMIT $offset,$limit ";    

        /*$qry="SELECT r.source,count(r.reg_id) as users,(SELECT CONCAT('$',' ',ROUND(SUM(i.amount),2)) FROM invoices i $whrCond AND i.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source)) as source_revenue,(SELECT count(i2.invoice_id) FROM invoices i2 $whrCond2 AND i2.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source)) as orders FROM tbl_registration r GROUP BY r.source HAVING (SELECT SUM(i.amount) FROM invoices i $whrCond AND i.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source)) > 0 ORDER BY (SELECT SUM(i.amount) FROM invoices i $whrCond AND i.user_id IN(SELECT reg_id FROM tbl_registration r2 WHERE r2.source = r.source))  DESC LIMIT $offset,$limit ";*/

        //echo $qry;exit; 

        $reports_res = DB::select($qry);


        $total_reports = DB::select(" SELECT count(DISTINCT(source)) as total FROM tbl_registration ");


        //echo "<pre>";print_r($total_reports);exit;
        $total_recs = $total_reports[0]->total;

        if(empty($reports_res)){

            $data['allpaidusers'] = [];
            $data['total'] = 0;

            $response = ['status'=>'0','msg'=>' - No Record Found - ','data'=>$data];
            return response()->json($response);
            exit;
        }

        $data['allpaidusers'] = $reports_res;
        $data['total'] = $total_recs;

        $response = ['status'=>'1','msg'=>' ','data'=>$data];

        return response()->json($response);
        exit;
    }

    public function getReportsPaidUsers(Request $request){

        $postData = $request->all();
        $current_date = date("Y-m-d H:i");
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];
        extract($postData);

        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];


        $whrCond=" WHERE 1=1 ";
        $havingCond=" HAVING 1=1 ";


        if(isset($from_date) && $from_date != ''){
            if(isset($to_date) && $to_date != ''){
                $whrCond.=" AND (i.created_at BETWEEN '$from_date' AND '$to_date') ";
                $havingCond.=" AND (i.created_at BETWEEN '$from_date' AND '$to_date') ";
            }
        }

        $offset = (($page - 1) * $limit);     
        

        $reports_res = DB::select(" SELECT r.firstname,r.lastname,r.email,i.user_id, CONCAT('$',' ',ROUND(SUM(i.amount),2)) AS revenue FROM invoices i LEFT JOIN tbl_registration r ON r.reg_id = i.user_id 
            $whrCond
            GROUP BY i.user_id ORDER BY ROUND(SUM(i.amount),2)  DESC LIMIT $offset,$limit "); 
    

        $total_reports = DB::select(" SELECT count(DISTINCT(i.user_id)) as total FROM invoices i $whrCond ");


        //echo "<pre>";print_r($total_reports);exit;
        $total_recs = $total_reports[0]->total;

        if(empty($reports_res)){

            $data['allpaidusers'] = [];
            $data['total'] = 0;

            $response = ['status'=>'0','msg'=>' - No Record Found - ','data'=>$data];
            return response()->json($response);
            exit;
        }

        $data['allpaidusers'] = $reports_res;
        $data['total'] = $total_recs;

        $response = ['status'=>'1','msg'=>' ','data'=>$data];

        return response()->json($response);
        exit;
    }

    public function getAlertLogs(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
        
        $userid = $uid;         
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];

        $orderBy="id";
        $order="desc";

        $orWhereColumns = [];
        $orWhere = [];

        if(count($filters) > 0){
            $filter_email=$filters[0]['text'];            
            $orWhere = [['email', 'like' ,"%".$filter_email."%"]];
        }

        if(isset($global_search) && trim($global_search) != ''){

            $whrCond.= " AND (s.drname like '%$global_search%' 
            OR s.phone like '%$global_search%' 
            OR s.email like '%$global_search%' 
            OR s.patient_name like '%$global_search%' ) ";

            $global_search=$postData['params']['queryParams']['global_search'];
            $orWhereColumns = [                
                ['firstname',' like ',' "%$global_search%" '],                                    
            ];                    
        }

        if(count($postData['params']['queryParams']['sort']) > 0){
            
            $orderBy = $postData['params']['queryParams']['sort'][0]['name'];
            $order = $postData['params']['queryParams']['sort'][0]['order'];        
        }
        
        $offset = (($page - 1) * $limit);

        
         
        $total_alerts = DB::table('tbl_notifications')->where($orWhere)->count();        

        $alerts = DB::table('tbl_notifications')                
                ->where($orWhere)                
                ->orderBy($orderBy, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
       
        for($i=0;$i<count($alerts);$i++){            

            $created_at = date('d-m-Y',strtotime($alerts[$i]->created_at));
            $alerts[$i]->created_at = $created_at;

            if($alerts[$i]->sent_to == 'selectedids'){
                $alerts[$i]->sent_to = "Selected Users";        
            }else{
                $alerts[$i]->sent_to = "All";
            }        
        }


        if(!$alerts){
           
        }else{
           
            $data = $alerts;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
       

        $response['status']=1;
        $response['logs']=$alerts;                    
        $response['total']=$total_alerts;

        return response()->json($response);
    }

    public function sendAlert(Request $request){

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data];
        //echo "<pre>";print_r($postData);exit;
        extract($postData['alertdata']);       
        $users_id_list = $userlist;
        
        if($send_to == 'all'){

            $user_list_res = DB::select('select GROUP_CONCAT(reg_id) as userlist FROM tbl_registration 
            WHERE is_admin = ? ', [0]);
            $users_id_list = $user_list_res[0]->userlist;
        }       

        

        $id = DB::table('tbl_notifications')->insertGetId(
            [
                'subject' => $subject,    
                'text' => $text,
                'users' => $users_id_list,
                'read_by' => '0',            
                'created_at' => date('Y-m-d H:i:s'),             
            ]
        );

        if($id > 0){
            $response = ['status'=>'1','msg'=>'Notifications Sent Successfully!.. ','data'=>$data];
        }else{
            $response = ['status'=>'0','msg'=>'Something Went Wrong !.. ','data'=>$data];
        }
        
        return response()->json($response);
    }    

    public function deleteCoupon($cid=0){

        $response = []; 
        $affected = DB::table('tbl_coupon_codes')->where('id',$cid)->delete();

        $response['status']=1;
        $response['message']="Coupon Deleted successfully ";

        return response()->json($response);        
    }



    public function submitCouponData(Request $request){

        $postData = $request->input();
        $response = [];
        //echo "<pre>";print_r($postData);exit;
        extract($postData);

        
        if($id > 0){
            $affected = DB::table('tbl_coupon_codes')
            ->where('id',$id)
            ->update([
                'coupon_code' => $coupon_code,    
                'percentage' => $percentage,
                'from_credits' => $from_credits,
                'to_credits' => $to_credits,                
            ]);                

            $response['status']=1;
            $response['message']="Coupon Code updated successfully";
        }else{

            $id = DB::table('tbl_coupon_codes')->insertGetId(
                [
                    'coupon_code' => $coupon_code,    
                    'percentage' => $percentage,
                    'from_credits' => $from_credits,
                    'to_credits' => $to_credits,                          
                ]
            );

            $response['status']=1;
            $response['message']="Coupon Code Added successfully";
        }
        
        return response()->json($response);
    }

    public function getCouponData($cid=0){        
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];

        $coupon = DB::table('tbl_coupon_codes')->where('id',$cid)->first();
        if(!$coupon){
            echo "not found";
        }

        $data = $coupon;
        $response = ['status'=>'1','msg'=>'','coupon'=>$data];
        //echo "<pre>";print_r($user);exit;


        return response()->json($response);
    }

    public function getAllCoupons(Request $request){

        $postData = $request->input();        

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];  

        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];

        $offset = (($page - 1) * $limit);        

        $coupons = DB::table('tbl_coupon_codes')->get(); 
        $data['coupons'] = $coupons;

        $response = ['status'=>'1','msg'=>'','data'=>$coupons];   

        $total_coupons = DB::table('tbl_coupon_codes')->count();   

        for($i=0;$i<count($coupons);$i++){                                

            if($coupons[$i]->status == 'y'){
                $coupons[$i]->status = true;
            }else{
                $coupons[$i]->status = false;
            }
        }

           
        $response['total']=$total_coupons;

        return response()->json($response);
    } 

    public function getSettings(Request $request){

        $postData = $request->input();        
        extract($postData); 
        $userid = session('userid');

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];   

        $today = date("Y-m-d"); 

        if(session('userid') <= 0 || session('userid') == NULL || session('userid') == '' ){ 
            $response = ['status'=>'0','msg'=>'','data'=>$data];               
            return response()->json($response);
            exit;
        }

        $credits_balance = DB::table('tbl_registration')        
        ->select('*')
        ->where('reg_id',$userid)->first();        
        
        $low_credits_notify = $credits_balance->low_credits_notify;
        $low_credits_limit = $credits_balance->low_credits_limit;

        $data['low_credits_notify']=$low_credits_notify;
        $data['low_credits_limit']=$low_credits_limit;
   
        $response = ['status'=>'1','msg'=>'','data'=>$data]; 
        return response()->json($response);
    }  

    public function getStats(Request $request){

        $postData = $request->input();
        extract($postData); 

        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];   

        $today = date("Y-m-d"); 
        $whrCond=" WHERE 1=1 ";

        $thismonth = date("n");
        $thisyear = date("Y");

        if($duro_filter == 'today'){
            $whrCond.=" AND DATE(created_at) = '$today' ";
        }

        if($duro_filter == 'yesterday'){
            $whrCond.=" AND DATE(created_at) = DATE_ADD('".$today."', INTERVAL -1 DAY) ";
        }

        if($duro_filter == '7days'){
            $whrCond.=" AND DATE(created_at) > DATE_ADD('".$today."', INTERVAL -7 DAY) ";
        }        

        if($duro_filter == '15days'){
            $whrCond.=" AND DATE(created_at) > DATE_ADD('".$today."', INTERVAL -15 DAY) ";
        }        

        if($duro_filter == 'thismon'){
            $whrCond.=" AND MONTH(created_at) = $thismonth AND YEAR(created_at) = $thisyear  ";
        }      

        if($duro_filter == 'lastmon'){
            
            $whrCond.=" AND YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ";
        }        

        if($duro_filter == '3mon'){
            
            $whrCond.=" AND (created_at) > DATE(LAST_DAY(CURRENT_DATE - INTERVAL 4 MONTH)) ";
            
        }        

        if($duro_filter == '6mon'){
            
            $whrCond.=" AND (created_at) > DATE(LAST_DAY(CURRENT_DATE - INTERVAL 7 MONTH)) ";
            
        }        

        if($duro_filter == '12mon'){
            
            $whrCond.=" AND (created_at) > DATE(LAST_DAY(CURRENT_DATE - INTERVAL 13 MONTH)) ";
        }        
        
       
        $data['recent_orders'] = [];

        if(session('userid') <= 0 || session('userid') == NULL || session('userid') == '' ){ 
            $response = ['status'=>'0','msg'=>'','data'=>$data];               
            return response()->json($response);
            exit;
        }

        $userid = session('userid');
        //$is_admin = session('is_admin');
        $is_admin = '1';

        //$is_admin=1; // temporary code
         /* Credits */ 
        
        $credits_balance = DB::table('tbl_registration')        
        ->select('*')
        ->where('reg_id',$userid)->first();        
        
        
        $earnings_res = DB::select("SELECT sum(amount) as total FROM invoices $whrCond ");            


        $earnings = $earnings_res[0]->total;
        $earnings = number_format($earnings,0);
        
        $data['earnings'] = $earnings;        
            
        /* ------- */


        /* Users */  
        
        $today_users_res = DB::select("SELECT count(reg_id) as total FROM tbl_registration 
                                    $whrCond "); 

        $today_users = $today_users_res[0]->total; 
        

               
        $active_users_res = DB::select("SELECT count(reg_id) as total FROM tbl_registration 
                                    WHERE access = '1' ");            
        $active_users = $active_users_res[0]->total;
        

        $inactive_users_res = DB::select("SELECT count(reg_id) as total FROM tbl_registration 
                                    WHERE access = '0' ");            
        $inactive_users = $inactive_users_res[0]->total;

        
        $data['users']['today'] = $today_users;
        $data['users']['active'] = $active_users;
        $data['users']['inactive'] = $inactive_users;

        
        /* ------- */


        /* Orders */ 
        
                
        if($is_admin == 1 || $is_admin == '1'){
            
        	//echo "SELECT count(invoice_id) as total FROM invoices $whrCond ";exit;
            $total_orders_res = DB::select("SELECT count(invoice_id) as total FROM invoices $whrCond "); 

            $total_orders = $total_orders_res[0]->total;                                
            
            $data['total_orders'] = $total_orders;     

            $data['orders']['today'] = 0;
            $data['orders']['thismonth'] = 0;         

        }else{

            $today_orders_res = DB::select("SELECT count(invoice_id) as total FROM invoices 
                                    WHERE DATE(created_at) = '$today' AND user_id = $userid ");

            $months_orders_res = DB::select("SELECT count(invoice_id) as total FROM invoices 
                                    WHERE MONTH(created_at) = '$thismonth'
                                    AND YEAR(created_at) = '$thisyear' 
                                    AND user_id = $userid  ");    
 
            $today_orders = $today_orders_res[0]->total;        
            $months_orders = $months_orders_res[0]->total;
            $data['total_orders'] = 0; 
            
            $data['orders']['today'] = $today_orders;
            $data['orders']['thismonth'] = $months_orders;  
        }        
        
        /* ------- */
         /* API Calls */ 
        
        if($is_admin == 1 || $is_admin == '1'){
            /*$total_log_res = DB::select("SELECT count(log_id) as total FROM api_log 
                                    WHERE DATE(created_at) = '$today'");                                     
            $total_log_res = DB::select("SELECT count(log_id) as total FROM api_log 
                                    $whrCond ");     */

            $data['total_logs'] =  0;
            $data['apicalls']['today'] = 0;
            $data['apicalls']['thismonth'] = 0;                                                        

        }else{
            $log_res = DB::select("SELECT count(log_id) as total FROM api_log 
                                    WHERE DATE(created_at) = '$today' AND user_id = $userid ");

            $months_api_res = DB::select("SELECT count(log_id) as total FROM api_log 
                                    WHERE MONTH(created_at) = '$thismonth' 
                                    AND YEAR(created_at) = '$thisyear' 
                                    AND user_id = $userid ");    

            $today_logs = $log_res[0]->total;        
            $months_logs = $months_api_res[0]->total;
            $data['apicalls']['today'] = $today_logs;
            $data['apicalls']['thismonth'] = $months_logs; 

           	$data['total_logs']=0;

        }
        
        //$today_earnings = DB::table('invoices')->where("created_at","=","$today")->count();  
 
        /* ------- */

         /* File Uploads */ 
        
        $data['uploads']['total'] = 0;
        $data['uploads']['inprogress'] = 0;
        $data['uploads']['completed'] = 0;     

        if(!$is_admin){

            $total_uploads_res = DB::select("SELECT count(upload_id) as total FROM user_upload 
                                    WHERE user_id = $userid ");         
            $total_uploads = $total_uploads_res[0]->total;        


            $total_inprogress_res = DB::select("SELECT count(upload_id) as total FROM user_upload 
                                        WHERE user_id = $userid AND ready_for_download = '0' ");            
            $total_inprogress = $total_inprogress_res[0]->total;



            $total_completed_res = DB::select("SELECT count(upload_id) as total FROM user_upload 
                                        WHERE user_id = $userid AND ready_for_download = '1' ");            
            $total_completed = $total_completed_res[0]->total;
            
            
            $data['uploads']['total'] = $total_uploads;
            $data['uploads']['inprogress'] = $total_inprogress;
            $data['uploads']['completed'] = $total_completed;            
        }
                        
        /* ------- */


        /* Get Recent Uploads */
        
        $data['recent_uploads'] = [];
        $recent_uploads=[];
        if(!$is_admin){
            $recent_uploads_res = DB::table("user_upload")
                                ->where(["user_id"=>$userid,'ready_for_download'=>1])
                                ->orderBy('upload_id','desc')
                                ->limit(5)
                                ->get();

            foreach($recent_uploads_res as $singleres){

                $csvfile_path = "";
                $csvfile_url = "";

                $xlsfile_path = "";
                $xlsfile_url = "";

                $csvfile_url = url('downloadreport/csv/'.$singleres->upload_id);
                $xlsfile_url = url('downloadreport/xls/'.$singleres->upload_id);

                $singleres->xlsfile_url=$xlsfile_url;
                $singleres->csvfile_url=$csvfile_url;

                $singleres->timeago=$this->humanTiming(strtotime($singleres->created_at));

                $filename_length = strlen(strlen($singleres->user_given_name));
                if($filename_length > 15){
                    $singleres->user_given_name = substr($singleres->user_given_name,0,14)."...";
                }

                $recent_uploads[] = $singleres;
            }                                

            //echo "<pre>";print_r($recent_uploads);exit;
            $data['recent_uploads'] = $recent_uploads; 



            /* Recent Orders */
            
            $recent_orders=[];
            if(!$is_admin){
                $recent_orders_res = DB::table("invoices")
                                    ->where(
                                        [
                                            "user_id"=>$userid,
                                            'paypal_email_verified'=>'y',
                                            'payment_status'=>'true'
                                        ]
                                    )
                                    ->orderBy('invoice_id','desc')
                                    ->limit(5)
                                    ->get();

                foreach($recent_orders_res as $singleres){

                    $singleres->amount = round($singleres->amount,2);
                   
                    $singleres->timeago=$this->humanTiming(strtotime($singleres->created_at));

                    $singleres->invoice_link = url("downloadInvoice/".$singleres->invoice_id);    

                    $recent_orders[] = $singleres; 
                }                                

                //echo "<pre>";print_r($recent_uploads);exit;
                $data['recent_orders'] = $recent_orders;
            }
        }    
        /* ------- */

        
        /*  popular coupons */  

        $data['popular_coupons'] = [];
        if($is_admin == 1){
            $popular_coupons = DB::select("select coupon_code,count(invoice_id) as total_orders,
                ROUND(sum(amount),2) as revenue FROM invoices GROUP BY coupon_code HAVING coupon_code != ''");

            $data['popular_coupons'] = $popular_coupons;
        }

        /* ----------------------- */

        /* Revenue Reports */
            $data['revenue_reports']=[];
            $data['revenue_reports_12_days']=[];

            if($is_admin == 1){

                /* Last 12 months */

                $revenue_reports = DB::select(" SELECT ROUND(SUM(i.amount),0) as total,YEAR(i.created_at) as iyear,MONTH(i.created_at),MONTHNAME(i.created_at) as monthname FROM invoices i GROUP BY YEAR(i.created_at),MONTH(i.created_at) ORDER BY YEAR(i.created_at) DESC ,MONTH(i.created_at) DESC limit 12   ");

                /* ------ */


                /* Last 12 weeks */

                /*$revenue_reports = DB::select(" SELECT ROUND(SUM(i.amount),0) as total,YEAR(i.created_at) as iyear,MONTH(i.created_at),MONTHNAME(i.created_at) as monthname FROM invoices i GROUP BY YEAR(i.created_at),MONTH(i.created_at) ORDER BY YEAR(i.created_at) DESC ,MONTH(i.created_at) DESC limit 12   ");*/

                /* ------ */

                /* Last 12 Days */

                $revenue_reports_12_days = DB::select(" select DATE(created_at) as idate, count(invoice_id) as total_orders, ROUND(sum(amount),0) as total from invoices WHERE created_at > now() - INTERVAL 11 day group by DATE(created_at) ORDER BY idate DESC  ");

                /* ------ */
                for($i=0;$i<count($revenue_reports);$i++){
                    $revenue_reports[$i]->revenuedate = substr($revenue_reports[$i]->monthname,0,3).' - '.$revenue_reports[$i]->iyear;
                    //$revenue_reports_12_days[$i]->revenuedate = date("jS M ",strtotime($revenue_reports_12_days[$i]->idate));
                }

                for($i=0;$i<count($revenue_reports_12_days);$i++){
                    //$revenue_reports[$i]->revenuedate = date("M-j",strtotime($revenue_reports[$i]->revenuedate));
                    $revenue_reports_12_days[$i]->revenuedate = date("jS M ",strtotime($revenue_reports_12_days[$i]->idate));
                }

                $data['revenue_reports_days'] = $revenue_reports_12_days;
                $data['revenue_reports'] = $revenue_reports;
                $data['revenue_reports_12_days'] = $revenue_reports_12_days;
            }
            

        /* ------- */

        $response = ['status'=>'0','msg'=>'','data'=>$data]; 
        return response()->json($response);
    }

    public function addCredits(Request $request){

        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $affected = DB::update("UPDATE tbl_registration set credits = (credits + $new_credits) 
            where reg_id = ?", [$reg_id]);

        $id = DB::table('invoices')->insertGetId(
                [ 
                    'credits'=>$new_credits,
                    'amount'=>$amount,
                    'payment_status'=>true,
                    'user_id'=>$reg_id,
                    'invoice_number'=>"Manual_".date("d-m-Y"),
                    'payment_ref_number'=>"direct",
                    'created_at'=>date("Y-m-d H:i:s"),
                    'pending_credits'=>0,
                ]
            ); 
     
        if($affected <= 0){
            $response = ['status'=>'0','msg'=>' Failed to Add Credit. Please try again!... ','data'=>$data];    
        }else{
            $response = ['status'=>'1','msg'=>' Credit Added Successfully !... ','data'=>$data];    
        }

        return response()->json($response);
    }

    public function updateUserAffiliate(Request $request){

        $postData = $request->input();
        extract($postData);   
       
        $response = [];
        $data = [];
        $response = ['status'=>'0','msg'=>'','data'=>$data];    

        $affected = DB::update("UPDATE tbl_registration 
            SET affiliate_url = '$affiliate_url'   
            WHERE reg_id = ?", [$reg_id]);
       
     
        if($affected <= 0){
            $response = ['status'=>'0','msg'=>' Failed to Update URL. Please try again!... ','data'=>$data];    
        }else{
            $response = ['status'=>'1','msg'=>' Affiliate Link updated successfully !... ','data'=>$data];    
        }

        return response()->json($response);
    }

    public function updateUserStatus(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        $access = $status == true ? '1' : '0';

        $affected = DB::table('tbl_registration')
              ->where('reg_id',$reg_id)
              ->update(
                        [
                            'access' => $access,
                        ]
                    );

        $response = ['status'=>'1','msg'=>'status changed Successfully','data'=>$data];

        return response()->json($response);
    }

    public function updateCouponStatus(Request $request){
        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);

        $status = $status == true ? 'y' : 'n';

        $affected = DB::table('tbl_coupon_codes')
              ->where('id',$id)
              ->update(
                        [
                            'status' => $status,
                        ]
                    );

        $response = ['status'=>'1','msg'=>'status changed Successfully','data'=>$data];

        return response()->json($response);
    }

    public function getAllUploads(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
        
        //$userid = $uid;      
        $userid = session('userid');

        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];

        $orderBy="upload_id";
        $order="desc";

        $orWhereColumns = [];
        $orWhere = [];

        if(count($filters) > 0){
            $filter_email=$filters[0]['text'];            
            $orWhere = [['tbl_registration.email', 'like' ,"%".$filter_email."%"]];
        }

        if(isset($global_search) && trim($global_search) != ''){

            $whrCond.= " AND (s.drname like '%$global_search%' 
            OR s.phone like '%$global_search%' 
            OR s.email like '%$global_search%' 
            OR s.patient_name like '%$global_search%' ) ";

            $global_search=$postData['params']['queryParams']['global_search'];
            $orWhereColumns = [                
                ['firstname',' like ',' "%$global_search%" '],
                ['lastname',' like ',' "%$global_search%" '],
                ['email',' like ',' "%$global_search%" '],
                ['phone',' like ',' "%$global_search%" '],                            
            ];                    
        }

        
        $offset = (($page - 1) * $limit);

        //$sql_query=DB::table('tbl_registration')->where($orWhere)->toSql();
         
        $total_users = DB::table('user_upload')
                        ->leftJoin('tbl_registration', 'tbl_registration.reg_id', '=', 'user_upload.user_id')
                        ->where($orWhere)->count();        

        $orders = DB::table('user_upload')          
                ->leftJoin('tbl_registration', 'tbl_registration.reg_id', '=', 'user_upload.user_id')      
                ->select("user_upload.*","tbl_registration.email","tbl_registration.firstname"
                    ,"tbl_registration.lastname")
                ->where($orWhere)                
                ->orderBy($orderBy, $order)
                ->offset($offset)
                ->limit($limit)
                ->get(); 

        


        if(!$orders){
            //echo "not found";exit;
        }else{
           // echo "<pre>";print_r($logs);exit;
            $data = $orders;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
        //echo "<pre>";print_r($user);exit;

        $response['status']=1;
        $response['logs']=$orders;                    
        $response['total']=$total_users;

        return response()->json($response);
    }


    public function getAllUsers(Request $request){        

        $postData = $request->input();
        $data = [];
        $response = ['status'=>'','msg'=>'','data'=>$data]; 
        extract($postData);
        
        $userid = $uid;         
        $paramsAr=[];        
        $limit=$postData['params']['queryParams']['per_page'];
        $page = $postData['params']['queryParams']['page'];
        $filters=$postData['params']['queryParams']['filters'];

        $orderBy="reg_id";
        $order="desc";

        $orWhereColumns = [];
        $orWhere = [];

        if(count($filters) > 0){
            $filter_email=$filters[0]['text'];            
            $orWhere = [['email', 'like' ,"%".$filter_email."%"]];
        }

        if(isset($global_search) && trim($global_search) != ''){

            $whrCond.= " AND (s.drname like '%$global_search%' 
            OR s.phone like '%$global_search%' 
            OR s.email like '%$global_search%' 
            OR s.patient_name like '%$global_search%' ) ";

            $global_search=$postData['params']['queryParams']['global_search'];
            $orWhereColumns = [                
                ['firstname',' like ',' "%$global_search%" '],
                ['lastname',' like ',' "%$global_search%" '],
                ['email',' like ',' "%$global_search%" '],
                ['phone',' like ',' "%$global_search%" '],                            
            ];                    
        }

        if(count($postData['params']['queryParams']['sort']) > 0){
            

            $orderBy = $postData['params']['queryParams']['sort'][0]['name'];
            $order = $postData['params']['queryParams']['sort'][0]['order'];        
        }
        
        $offset = (($page - 1) * $limit);


        //$sql_query=DB::table('tbl_registration')->where($orWhere)->toSql();
         
        $total_users = DB::table('tbl_registration')->where($orWhere)->count();        

        $users = DB::table('tbl_registration')                
                ->where($orWhere)                
                ->orderBy($orderBy, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
       
        for($i=0;$i<count($users);$i++){            

            $reg_date = date('d-m-Y h:i A',strtotime($users[$i]->created_at));
            $users[$i]->created_at = $reg_date;

            $users[$i]->email = $users[$i]->email;

            if($users[$i]->access == '0' || $users[$i]->access == 0 ){
                $users[$i]->active = false;
            }else{
                $users[$i]->active = true;
            }
        }


        if(!$users){
            //echo "not found";exit;
        }else{
           // echo "<pre>";print_r($logs);exit;
            $data = $users;
            $response = ['status'=>'1','msg'=>'','logs'=>$data];
        }
        
        //echo "<pre>";print_r($user);exit;

        $response['status']=1;
        $response['logs']=$users;                    
        $response['total']=$total_users;

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


}
