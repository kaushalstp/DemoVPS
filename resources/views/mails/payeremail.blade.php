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

        <p>Your MyEmailVerifier Transaction Verification code is:  <b>{{ $txnvc }}</b>.</p>

        <p>Kindly use this password for Log in to MyEmailVerifier </p>

    </body>
</html>
