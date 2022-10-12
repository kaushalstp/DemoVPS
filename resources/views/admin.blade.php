
<?php 
    //phpinfo();exit;
?>

<!DOCTYPE html>
<html>
    <head>
        <base href="/" >
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">


        <!-- <meta name="twitter:card" content="summary_large_image">        
        <meta name="twitter:creator" content="@KaushalKapadiy3">
        <meta name="twitter:title" content="MyEmailVerifier">
        <meta name="twitter:description" content="MEV - Verify your email list. The guest list and parade of limousines with celebrities emerging from them seemed more suited to a red carpet event in Hollywood or New York than than a gritty stretch of Sussex Avenue near the former site of the James M. Baxter Terrace public housing project here.">
        <meta name="twitter:image" content="https://myemailverifier.com/images/email-verification.png"> -->


        <title>MyEmailVerifier</title>
      <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:url"           content="https://myemailverifier.com/" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="MyEmailVerifier" />
        <meta property="og:description"   content="Verify your Email List" />
        <meta property="og:image"        content="https://myemailverifier.com/images/email-verification.png" />


        <meta name="twitter:card" content="summary_large_image">        
        <meta name="twitter:creator" content="@KaushalKapadiy3">
        <meta name="twitter:title" content="MyEmailVerifier">
        <meta name="twitter:description" content="MEV - Verify your email list. The guest list and parade of limousines with celebrities emerging from them seemed more suited to a red carpet event in Hollywood or New York than than a gritty stretch of Sussex Avenue near the former site of the James M. Baxter Terrace public housing project here.">
        <meta name="twitter:image" content="https://myemailverifier.com/images/email-verification.png">


        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/img/mev_favicon.png') }}">

        <link rel="stylesheet" href="{{ asset('public/css/app.css?v=3.19') }}" > 

        <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
</script> 
        
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" > -->

        <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
        
        <script src="http://kaushalproject.com/myadmin/node_modules/vue-awesome/dist/vue-awesome.js"></script> -->        
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130160830-1"></script>
        
    </head>
   

    <body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
        
        <div id="app" >
             
        </div>

    <!-- ./wrapper -->

    <!-- jQuery -->        
        <script>
            var umsg = "{{  $value = session('umsg') }}";

            if(umsg != ''){
                alert(umsg);
            }                        

            var app_url='{{ url('') }}';
            var img_url="{{ url('public/img/') }}"+"/"; 
            var api_url=app_url+'/api/'; 
            var g_recaptcha_key="{{ config('app.recaptcha_site_key') }}";
            var google_client_id="{{ config('app.google_client_id') }}";
            var global_main_header_msg = "";    
            //alert("in blade: "+global_main_header_msg);

        </script>

        @php
            
            session(['umsg'=>'']);
            Session::forget('umsg');

        @endphp

        <script src="{{ asset('public/js/app.js?v=4.19') }}"  ></script>



        <div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0&appId=299150295007457&autoLogAppEvents=1" nonce="LQfBsXwK"></script>

    </body>
    
</html>
