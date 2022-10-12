<?php 

    ini_set('auto_detect_line_endings',TRUE);
    ini_set('memory_limit', '1256M');

    error_reporting(E_ALL);

        

    /*  PHP Mailer lib */

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';    

    /* ---------------- */


    function robotErrorHandler($errno, $errstr, $errfile, $errline) {
        
        global $fileid,$file_path;

        $uploader_err='';
        $uploader_err.="<b>Custom error:</b> [$errno] $errstr<br/>";
        $uploader_err.=" Error on line $errline in $errfile<br/>";
        $uploader_err.="<br/><br/> File:  ($fileid)  $file_path <br/>";


        $email_body=$uploader_err;
        sendMail("piyushpatdiya@gmail.com","Packager Error ",$email_body);
        sendMail("kaushal.accuwebhosting@gmail.com","Uploader Error ",$email_body);
    }

    // Set user-defined error handler function
    set_error_handler("robotErrorHandler");


    /*  Packager code starts here */

    $root_path = '/home/kaushalproject/public_html/';
    $mev_path = '/home/kaushalproject/public_html/myadmin/';


    $site_url="https://client.myemailverifier.com/";
    $inline_site_url="https://myemailverifier.com/mev2/public/";

    //$storage_path = $mev_path.'storage/app/public/';
    
$inline_app_path="C:\HostingSpaces\admin\myemailverifier.com\wwwroot\mev2\storage\app\public".DIRECTORY_SEPARATOR;
    $storage_path="C:\HostingSpaces\admin\client.myemailverifier.com\wwwroot\storage\app\public".DIRECTORY_SEPARATOR;

    //$conn = new mysqli('127.0.0.1','kaushalp_kaushal','AY)@WxbP?n9g', 'kaushalp_coreui');        
    $conn = new mysqli('127.0.0.1','root','my#ema@i@l@123', 'client1mev');        
        

    while(true){

        $jobmaster_query="SELECT * FROM job_master order by size ";  
        $jobmaster_result = $conn->query($jobmaster_query);            


        while($jmrow = $jobmaster_result->fetch_assoc()){
            
            
            $fileid =  $jmrow['file_id'];        
            $user_id =  $jmrow['user_id'];        
            $file_name = $jmrow['file_name'];                    
            $file_path = $jmrow['file_path'];
            $valid_file_name = '';        
            $valid_file_path = '';        
            $inline_client_app = $jmrow['inline_client_app'];       
            $blank_lines =  $jmrow['blank_lines'];
            $total_rows = $jmrow['size'];
        

            $fileinfo = pathinfo($file_name);    

        
            $total_unprocessed=0;

            $unprocessed_query="SELECT count(id) as total FROM list_in_process WHERE file_id = $fileid AND is_processed = 0 ";  
            $unprocessed_result = $conn->query($unprocessed_query);            
            $unprocessed_row = $unprocessed_result->fetch_assoc();

            $total_unprocessed = $unprocessed_row['total'];
            

            if($total_unprocessed >= 1){

                $get_fig_query="SELECT file_id,COUNT(CASE WHEN valid = 1 THEN 1 END) AS total_valid,
                                COUNT(CASE WHEN invalid = 1 THEN 1 END) AS total_invalid,
                                COUNT(CASE WHEN unknown = 1 THEN 1 END) AS total_unknown 
                                FROM list_in_process  where is_processed = 1 
                                GROUP BY file_id  having file_id = $fileid ";  
                

                $get_fig_res = $conn->query($get_fig_query);            
                $row_fig_arr = $get_fig_res->fetch_assoc();

                $total_valid = $row_fig_arr['total_valid'];
                $total_invalid = $row_fig_arr['total_invalid'];
                $total_unknown = $row_fig_arr['total_unknown'];


                $update_fig_query="UPDATE user_upload SET 
                    valid = $total_valid,invalid = $total_invalid,unknown = $total_unknown        
                    WHERE upload_id = $fileid ";  

                $update_fig_res = $conn->query($update_fig_query);            

                
                continue;                
            }

                        
            /*if($jmrow['inline_client_app'] == 1){
                $storage_path="C:\HostingSpaces\admin\myemailverifier.com\wwwroot\mev2\storage\app\public".DIRECTORY_SEPARATOR;
            }else{

                $storage_path="C:\HostingSpaces\admin\client.myemailverifier.com\wwwroot\storage\app\public".DIRECTORY_SEPARATOR;
            }        */
            

            if($fileinfo['extension'] == 'txt'){
                $csvfilepath = str_replace(".txt",".csv","$file_path");            
                $xlsfilepath = str_replace(".txt",".xlsx","$file_path");   
                $valid_file_name = str_replace(".txt","__VALID.csv","$file_name");                          
            }

            if($fileinfo['extension'] == 'csv'){
                $csvfilepath = $file_path;
                $xlsfilepath = str_replace(".csv",".xlsx","$file_path");
                $valid_file_name = str_replace(".csv","__VALID.csv","$file_name");                         
            }

            if($fileinfo['extension'] == 'xlsx'){
                $xlsfilepath = $file_path;
                $csvfilepath =  str_replace(".xlsx",".csv","$file_path");   
                $valid_file_name = str_replace(".xlsx","__VALID.csv","$file_name");                                  
            }

            if($fileinfo['extension'] == 'xls' ){
                $xlsfilepath = $file_path;
                $csvfilepath =  str_replace(".xls",".csv","$file_path");        
                $valid_file_name = str_replace(".xls","__VALID.csv","$file_name");                                  

            }
            

            if($inline_client_app == 1){

                $xlsfilepath=$inline_app_path.$xlsfilepath;
                $csvfilepath=$inline_app_path.$csvfilepath;                
            }else{
                $xlsfilepath=$storage_path.$xlsfilepath;
                $csvfilepath=$storage_path.$csvfilepath;

            }
            

            $ctr=0;
            $values_str="";
                        		

            $filerecords_query = "SELECT * FROM list_in_process WHERE file_id = $fileid  ORDER BY id ASC ";
            $file_records_res = $conn->query($filerecords_query); 
            if($file_records_res->num_rows <= 0){
                //echo "lip not found: $fileid";
                continue;
            }


            echo date("Y-m-d H:i:s")." Start packing File ($fileid) :  $csvfilepath  \n\n ";                                        
            $total_catchall = 0;
            $total_spamtrap=0;
            $total_toxic=0;


            $allLines=[];
            $validResults=[];
            while($row = $file_records_res->fetch_assoc()){
               

                $rowemail = $row['email']; 

                //echo "Row:   $ctr.  ".$row['id']." \n";
                $ctr++;

                $valid=$row['valid'];
                $invalid=$row['invalid'];
                $is_processed=1;
                $disposable_domain=$row['disposable_domain'] > 0 ? $row['disposable_domain'] : 0 ;
                $free_domain=$row['free_domain'] > 0 ? $row['free_domain'] : 0 ;
                $greylisted=$row['greylisted'];  
                $diagnosis=trim($row['diagnosis']);
                
                //$catch_all=$row['catch_all'] > 0 ? $row['catch_all'] : 0 ;

                /* check for catch-all */

                $diagnosis_lower = strtolower($diagnosis);
                $catchallpos =  stripos($diagnosis_lower,"catch all");  
                $catchallpos_two =  stripos($diagnosis_lower,"Catch All");  
                $catchallpos_three =  stripos($diagnosis_lower,"Catch-All");  
                $catchallpos_four =  stripos($diagnosis_lower,"catch-all");  
                $found_catchall=0;


                $spamtrap_pos = stripos($diagnosis_lower,"spam-trap");  
                $found_spamtrap=0;

                $toxic_pos = stripos($diagnosis_lower,"toxic domain");  
                $disposable_domain_pos = stripos($diagnosis_lower,"disposable"); 
                $found_toxicdomain=0; 

                if(is_int($toxic_pos) || is_int($disposable_domain_pos)){
                    $found_toxicdomain=1;
                    $total_toxic++;
                }

                if(is_int($spamtrap_pos)){
                    $found_spamtrap=1;
                    $total_spamtrap++;
                }


                if(is_int($catchallpos) || is_int($catchallpos_two) || is_int($catchallpos_three) || 
                    is_int($catchallpos_four)){
                    $found_catchall=1;
                    $total_catchall++;
                }

                /* ------------------- */

                $unknown=$row['unknown'];
                $catch_all=0;
                /*if(strpos($diagnosis,"Catch all") !== false){
                    $catch_all=1;
                }*/

                if($found_catchall==1){
                    $catch_all=1;   
                }

                $role_based=$row['role_based'] > 0 ? $row['role_based'] : 0 ;

                $free_domain_text = $free_domain > 0 ? 'Yes' : 'No';
                $role_based_text = $role_based > 0 ? 'Yes' : 'No';
                $status_text=$row['diagnosis'];

                if($valid >= 1){
                    $status_text="Valid";                
                }

                if($invalid >= 1){
                    $status_text="Invalid";                
                }            

                if($catch_all >= 1){
                    $status_text="Catch-all";
                    $diagnosis="There may be bounce";
                }

                if($greylisted >= 1 || $unknown >= 1){
                    $status_text="Unknown";
                }

                $unknown = $greylisted;
                
                 /* New CSV writer */

                $line_fields=[]; 
                $dbrowline = $row['line'];                
                $fields_match = array("{Result}", "{FreeDomain}","{RoleBased}", "{Diagnosis}");
                $fields_replace = array($status_text,$free_domain_text,$role_based_text, $diagnosis);                
                $line_fields = str_replace($fields_match,$fields_replace,$dbrowline);    

                $allLines[] = $line_fields;                   

                if($valid == 1 || $ctr == 1){
                   $validResults[]=$line_fields;   
                }             

                /* ------------------ */                        
            }


            /* Writing output to CSV */
            $csvfilehandle = fopen("$csvfilepath", "w");            
            foreach($allLines as $singleline){

                //$dbline = str_putcsv($singleline);
                fwrite($csvfilehandle,$singleline."\n");                 
            }
            fclose($csvfilehandle);                
            /* ------------------------------------- */


            /* Writing Valid Results output to CSV */
            $validcsvfilepath=str_replace(".csv","__VALID.csv",$csvfilepath);
            $validfilehandle = fopen("$validcsvfilepath", "w");            
            foreach($validResults as $singleline){

                //$dbline = str_putcsv($singleline);
                fwrite($validfilehandle,$singleline."\n");                 
            }
            fclose($validfilehandle);                
            /* ------------------------------------- */
               

            if($fileinfo['extension'] == 'txt' ){
                $xls_filename=str_replace(".txt",".xlsx",$file_name);
                $csv_filename=str_replace(".txt",".csv",$file_name);
            }

            if($fileinfo['extension'] == 'csv' ){
                $xls_filename=str_replace(".csv",".xlsx",$file_name);
                $csv_filename=$file_name;
            }

            if($fileinfo['extension'] == 'xlsx' ){
                $xls_filename=$file_name;
                $csv_filename=str_replace(".xlsx",".csv",$file_name);
            }

                
                $xls_file_path = "uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."$user_id".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$xls_filename;                
                

                $csv_file_path = "uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."$user_id".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$csv_filename;

                $valid_file_path = "uploads".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."$user_id".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$valid_file_name;
               

            /* Final figure update */            

            $get_fig_query="SELECT file_id,COUNT(CASE WHEN valid = 1 THEN 1 END) AS total_valid,
                                COUNT(CASE WHEN invalid = 1 THEN 1 END) AS total_invalid,
                                COUNT(CASE WHEN unknown = 1 THEN 1 END) AS total_unknown,
                                COUNT(CASE WHEN status_code = 1000 THEN 1 END) AS total_mev_robots   
                                FROM list_in_process  where is_processed = 1 
                                GROUP BY file_id  having file_id = $fileid ";  
                

            $get_fig_res = $conn->query($get_fig_query);            
            $row_fig_arr = $get_fig_res->fetch_assoc();

            $total_valid = ($row_fig_arr['total_valid'] - $total_catchall);
            $total_invalid = $row_fig_arr['total_invalid'];
            $total_unknown = $row_fig_arr['total_unknown'];
            $total_mev_robots = $row_fig_arr['total_mev_robots'];
            $mev_robots_percentage=0;

            if($total_mev_robots > 0){
                $mev_robots_percentage=round((($total_mev_robots / $total_rows)*100));
            }
           

            /* -------- */    

            $current_time=date("Y-m-d H:i:s");
            /* Final update of user_upload table */
            $update_download_query="UPDATE user_upload SET 
                        valid = $total_valid,
                        invalid = $total_invalid,
                        unknown = $total_unknown,
                        catchall = $total_catchall,
                        process_end_time = '$current_time',
                        credit_used = (total - $total_unknown - $blank_lines),
                        ready_for_download = 1, 
                        status = 'finished', 
                        file_path = '$csv_file_path',
                        valid_file_path= '$valid_file_path',
                        mev_status_ratio = $mev_robots_percentage,
                        xls_file_path = '$xls_file_path'  WHERE upload_id = $fileid ";  
                                
            $update_download_res = $conn->query($update_download_query);     


            $jobmaster_delete_query="DELETE FROM job_master WHERE file_id = $fileid ";  

            $lip_delete_query="DELETE FROM list_in_process WHERE file_id = $fileid ";  


            $conn->query($jobmaster_delete_query);                
            $conn->query($lip_delete_query);                
            /* --------- */

                


             /* Email Report */ 

            $getuser_query="SELECT u.*,r.reg_id,r.firstname,r.credits,r.email,r.lastname FROM user_upload u LEFT JOIN tbl_registration r ON r.reg_id = u.user_id WHERE u.upload_id = $fileid"; 
            
            //echo $getuser_query;exit;

            $userinfo_res = $conn->query($getuser_query);     
            $userInfo = $userinfo_res->fetch_assoc();
            //echo "<pre>";print_r($userInfo);exit;

            $userid = $userInfo['reg_id'];
            $useremail = $userInfo['email'];
            $username =  $userInfo['firstname'].' '.$userInfo['lastname'];   
            $file_name =  $userInfo['file_name'];

            $total_lines =  $userInfo['total'];            
            

            $total_invalid = $userInfo['invalid'];
            $total_valid = $userInfo['valid'];
            $total_unknown = $userInfo['unknown'];

            $credit_used =  ($total_lines - $total_unknown  - $blank_lines);
            $credit_balance = ($userInfo['credits'] + $total_unknown);            

            $valid_percentage=0;
            $invalid_percentage=0;
            $catchall_percentage=0;
            $unknown_percentage=0;
           

            if($total_valid > 0){
                $valid_percentage=round((($total_valid / $total_lines)*100));
            }            

            if($total_invalid > 0){
                $invalid_percentage=round((($total_invalid / $total_lines)*100));
            }    
            

            if($total_catchall > 0){
                $catchall_percentage=round((($total_catchall / $total_lines)*100));
            }            

            if($total_unknown > 0){
                $unknown_percentage=round((($total_unknown / $total_lines)*100));
            }

            $csv_download_link = $site_url."downloadreport/csv/$fileid";       
            $xls_download_link = $site_url."downloadreport/xls/$fileid"; 
            $validcsv_download_link = $site_url."downloadreport/validcsv/$fileid"; 
                

            if($inline_client_app == 1){
                $csv_download_link = $inline_site_url."downloadreport/csv/$fileid";       
                $xls_download_link = $inline_site_url."downloadreport/xls/$fileid"; 
                $validcsv_download_link = $inline_site_url."downloadreport/validcsv/$fileid"; 
            }
            

            $email_template = file_get_contents("VerifiedFile.html");   
            $email_body="";

            $search  = array(
                        "{username}",
                        "{filename}",
                        "{total}",
                        "{valid}",
                        "{invalid}",
                        "{unknown}",
                        "{catch-all}",
                        "{credit-used}",                        
                        "{remaining-credit}",
                        "{link-csv}",
                        "{link-xlsx}",
                        "{link-validcsv}",
                        

                        "{valid-p}",
                        "{invalid-p}",
                        "{unknown-p}",
                        "{catch-all-p}",
                        "{spam-trap}",
                        "{toxic}",
                    );


            $replace = array(
                $username,
                $file_name,
                $total_lines,
                $total_valid,
                $total_invalid,
                $total_unknown,
                $total_catchall,
                $credit_used,
                $credit_balance,
                $csv_download_link,
                $xls_download_link,
                $validcsv_download_link,

                $valid_percentage,
                $invalid_percentage,
                $unknown_percentage,    
                $catchall_percentage,                
                $total_spamtrap,
                $total_toxic,                            
            );

            //echo "<pre>";print_r($replace);exit;
            
            $email_body = str_replace($search, $replace,$email_template);
            $sample_email_body = "This is test mail from MEV";
                   

            $update_credits_query="UPDATE tbl_registration SET 
                    credits = (credits + $total_unknown + $blank_lines),blocked_credits = 0 WHERE reg_id = $userid ";  
            $update_credits_res = $conn->query($update_credits_query); 


            /* Update Credit History */

            $balance_query="SELECT credits FROM tbl_registration WHERE reg_id = $userid ";  
            $balance_result = $conn->query($balance_query);            
            $balance_row = $balance_result->fetch_assoc();

            $credit_balance = $balance_row['credits'];


            
            $history_insert_q="INSERT INTO tbl_credit_history 
                    (`user_id`,`detail`,`credit_change`,`balance`,`created_at`) 
                    VALUES ($userid,'File Validation $file_name ',- $credit_used,$credit_balance,'$current_time') ";

            $conn->query($history_insert_q);


            /* ------------- */

            echo date("Y-m-d H:i:s")." End packing File ($fileid) :  $file_path  
            \n -------------------------------------------------------- \n\n\n ";

            sendMail($useremail,"Your recent file validation (file: $fileid) ",$email_body);

            /* -------------------------------- */   
        }        
      
        sleep(60);    
    }        
    
    function sendMail($to='',$subject='Your recent file validation',$mailBody=''){

        $mail = new PHPMailer(true);

        Try
        {
            //Server settings
//            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP



            $mail->Host = 'mail.myemailverifier.com'; //Set the SMTP server to send through
//            $mail->Host = '173.248.158.227'; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = 'support@myemailverifier.com'; //SMTP username
            $mail->Password = 'w!ajzikhr%12@'; //SMTP password
  //          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = 25; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`



            //Recipients
            $mail->setFrom('support@myemailverifier.com', 'MyEmailVerifier');
            $mail->addAddress($to); //Add a recipient            

            //Attachments Optional
            

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $mailBody;
            $mail->AltBody = 'Please contact support@myemailverifier.com ';

            $mail->send();
            //echo 'Message has been sent';
        } 
        catch (Exception $e) 
        {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }


    function str_putcsv(array $fields)
    {
      $delimiter = ",";
      $enclosure = '"';
      $escs = [];
      foreach ($fields as $field) {
        $esc = (string) $field;
        if (
          false !== strpos($esc, $delimiter)
          || false !== strpos($esc, $enclosure)
        ) {
          $esc = $enclosure . strtr($esc, ["$enclosure" => "$enclosure$enclosure"]) . $enclosure;
        }
        $escs[] = $esc;
      }
      return implode($delimiter, $escs) . PHP_EOL;
    }

?>