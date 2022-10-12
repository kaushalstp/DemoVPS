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

        <p>Special Discount Request recieved.</p>


        <p>            
            User Email: {{  $useremail }} <br/>
            Credits Required: {{  $credits_required }} &nbsp;&nbsp;&nbsp; - {{ $paytype }} <br/>
            Organization: {{  $organization }} <br/>                 
            Contact Person: {{  $contact_person }}  <br/>                       
            Contact Email: {{  $contact_email }}  <br/>           
        </p>

        <p>&nbsp;</p>

        <p>
            <b>Detail:</b> <br/>
            {{ $detail }}
        </p>
        
                   

    </body>
</html>
