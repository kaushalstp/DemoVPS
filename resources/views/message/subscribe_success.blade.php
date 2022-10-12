<!DOCTYPE html>
<html>
    <head>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130160830-1"></script>
    </head>
    <body>
        
    	<div style="float:left;width:100%;text-align: center;padding: 10px 0;background-color: #000;" >
        	<img src="{{ $logo_url }}" />	
    	</div>

        <div 
        style="float:left;width:100%;text-align: center;background-color: green;color:#fff;padding: 10px 0;margin:20 0 0 0;" >
        	You have succeessfully subscribed.	<br/><br/>

            Redirecting in 5 seconds. Please wait.

        </div>


        <script>
        	
        	var return_url = '{{ $return_url }}';

             /* conversation  michanism */

            window.dataLayer = window.dataLayer || [];
            function gtag(){
                dataLayer.push(arguments);
                //console.log("code ran successfully... ");
            }
            gtag('js', new Date());
            gtag('config', 'UA-130160830-1');               

                /* conversation  michanism ends */
        	

        	setTimeout(function(){ 

        		window.location = return_url;        			
        	}, 8000);

        </script>


    </body>
</html>
