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
        
        <p>Hello Admin</p>

        <p>New file has been uploaded, find the details below.</p>
        

        <p>
            <b>User name:</b> {{ $username }} <br/>
            <b>User email:</b> {{ $useremail }} <br/>
            <b>File name:</b> {{ $filename }} <br/>
            <b>File size:</b> {{ $filesize }} <br/>
            <b>No of lines in file:</b> {{ $no_of_lines }} <br/>
        </p>
           		   

    </body>
</html>
