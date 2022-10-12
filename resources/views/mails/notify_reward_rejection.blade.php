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
    	
        Dear <b> {{ $username }} </b>,

        <p>Your recent request of free credits is rejected due to below reason.</p>     

        <p>
            <b>Reason:</b> {{ $rejected_reason }}
        </p>

        Thanks, <Br/>
        Matt

    </body>
</html>
