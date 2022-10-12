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


use PDF;
use Config;
use File;

//use MailchimpMarketing\ApiClient;

class TrialController extends Controller 
{
    
    public function index(){
                                         
    } 

    public function getAllPractice(){


        /*  Get all records */

            /*$users = DB::table("tbl_registration")->get();
            echo "<pre>";print_r($users);exit;
            foreach($users as $user){
                echo $user->firstname;
                echo "<br/><hr/><br/>";
            }*/

        /* ------- */


        /*  Where condition record */

            /*$users = DB::table("tbl_registration")->where('reg_id',26)->get();
            echo "<pre>";print_r($users);exit;
            foreach($users as $user){
                echo $user->firstname;
                echo "<br/><hr/><br/>";
            }*/

        /* ------- */



        /*  Where condition record  (no records) */

            $users = DB::table("tbl_registration")->where('reg_id',27)->get();
            //echo "<pre>";print_r($users);exit;
            echo count($users);
            if(!$users){
                echo "no records found";
            }else{

                 foreach($users as $user){
                    echo $user->firstname;
                    echo "<br/><hr/><br/>";
                }   

            }

            

        /* ------- */


    }




}