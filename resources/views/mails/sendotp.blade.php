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

        <p>Your one time password is:  <b>{{ $login_otp }}</b>  and it is valid for 10 minutes</p>

        <p>Kindly use this password for Log in to MyEmailVerifier </p>
    </body>
</html>
