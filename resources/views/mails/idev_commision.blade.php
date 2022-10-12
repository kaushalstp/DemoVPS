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

        <p>Hello <b> Admin </b>,</p>

        <p> Following User got referal commission from below transaction. </p>

        <p>
            
            Affiliate User ID: {{ $affiliate_id }} <br/>
            Invoice No: {{ $invoice_no }}  <br/>
            Invoice Ref: {{ $invoice_ref }} <br/>
            Total Sales Amount: {{ $sales_amount }} <br/>

        </p>

    </body>
</html>
