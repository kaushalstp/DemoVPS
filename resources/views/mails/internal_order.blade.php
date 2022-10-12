<!DOCTYPE html>
<html>
    <head>
        
    </head>
    <body>

        <p>
            <div style="float:left;width:100%;text-align: center;" > 
                <img src="{{ $mevlogourl }}" />                         
            </div>
        </p>
        
        <p><b>Hello Admin</b>,</p>

        <p>{{ $username }} has placed an Order in MyEmailVerifier. Please see more detail as below.</p>

        <p>            
            User Email: {{ $useremail }} <br/>
        	Credits: {{ $credits }} <br/>
            Amount: {{ $amount }} <br/> 
            Date of Purchase: {{ $purchase_date }}  <br/>
        </p>

        
       		       

    </body>
</html>
