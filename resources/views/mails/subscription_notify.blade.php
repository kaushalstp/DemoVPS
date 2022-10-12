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
        
        <p>Hello <b> {{ $username }} </b>,</p>

        <p>You have Successfully subscribed to {{ $plan_name }} for 1 Month.</p>


        <p>
        	{{ $credits }} credits added to your wallet.
        </p>


        <p> Your Current Subscription ends on {{ $end_date }} </p>
       		       

    </body>
</html>
