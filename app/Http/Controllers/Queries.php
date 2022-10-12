<?php 

    $users = DB::table('users')
                ->select('id','listname as name')
                ->where([
                    ['userid','=',$userid],            
                    ['library','=','aweber'],                                        
                ])->get();        


    $invoice = DB::table('invoices as i')
        ->join('tbl_registration as r', 'r.reg_id', '=', 'i.user_id')
        ->select('i.*', 'r.firstname','r.lastname', 'r.email')
        ->where('i.invoice_id', '=',"$invoiceid")->first();                        

    




?>