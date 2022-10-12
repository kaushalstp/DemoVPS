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
    	
        Hello <b> {{ $username }} </b>,

        this is response from paypal.


        <p>Kindly use this password for Log in to MyEmailVerifier </p>


       	<p> {{ $paypal_res }} </p> 	


    </body>
</html>
