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

        <p>We got a request to reset your account password.</p>

		<p>Please click on the below link to reset your password</p>

		<p>
			<a href="{{ $pwd_reset_link }}" >Reset Password</a>
		</p>

    </body>
</html>
