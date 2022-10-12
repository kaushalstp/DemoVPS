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

        <p>You have successfully registered on MyEmailVerifier.</p>

		<p>Please <a href="{{ $activation_link }}" >click here</a>  to activate your account</p>
		
    </body>
</html>
