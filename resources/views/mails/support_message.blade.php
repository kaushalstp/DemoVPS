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
        
        <p>Hello Team</p>

        <p>You have received a Query from <b> {{ $username }} </b> </p>

        <p><b>User Email:</b>  {{ $email }} </p>

		<p><b>Subject:</b>  {{ $subject }} </p>

		<p><b>Message:</b>  {{ $messages }} </p>        
           		   

    </body>
</html>
