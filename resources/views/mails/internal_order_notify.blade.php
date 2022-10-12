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

        <p>{{ $username }} has subscribed to {{ $plan_name }} for 1 Month. Please see more detail as below.</p>

        <p>
            Plan: {{ $plan_name }} <br/>
            Email: {{ $useremail }} <br/>
        	Credits: {{ $credits }} <br/>
            Amount: {{ $amount }} <br/> 
            Date of Subscribed: {{ $subscription_date }}  <br/>
        </p>

        
       		       

    </body>
</html>
