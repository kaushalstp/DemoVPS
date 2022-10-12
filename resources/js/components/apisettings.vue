<template>
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                	<div class="col-sm-6">
                    	<h1>API Settings</h1>
                  	</div>                  
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="callout callout-info">              
                        	Integrate MyEmailVerifier API into your various applications and sites
                        </div>
                        
                        <div class="card card-widget">
                            <div class="card-header">
                                <div class="user-block">                                  
                                  <span class="username">
                                    <a href="javascript:void(0);">Your API Key</a></span>                           
                                </div>

                                <!-- /.user-block -->
                                <div class="card-tools">
                                  
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                  </button>                                                                

                                </div>

                                <!-- /.card-tools -->
                              </div>
                              <!-- /.card-header -->
                            <div class="card-body" >
                                <!-- post text -->
                                <p>This API Key is required to use our Email verification API. Store it to the safe place.You can change it any time. </p>
                                

                                <p>

                              		<div class="form-group">		
                              			<div class="row" >                              			

                              				<div class="col-sm-2" >
                              					
                              				</div>                              			

                              				
                              				<div class="col-sm-8" >      
                              					<div class="row">	

                              						<big class="text-danger" ><b>This is your API KEY - You should NEVER SHARE IT WITH OTHERS.</b></big>

                              						<br/><br/><br/>

	                              				    <div class="col-sm-6" >
									                    <input type="text" class="form-control" id="apikey" 
									                    placeholder="" v-model="apikey" />
									                </div>    

									                <div class="col-sm-6" >	
									                    <button type="button" 
									                    name="btn_generate_key" 
									                    id="btn_generate_key" 
									                    class="btn btn-block btn-primary" 
                                                        @click="generateAPIKey" >
									                    	Generate new API key
									                    </button>
								                	</div>
							                	</div>
						                	</div>
							                


						                	<div class="col-sm-2" >
                              					
                              				</div>                              			

					                    </div>				                    
					                </div>				

                                </p>	


                            </div>    
                        </div> 

                        <div class="card card-widget collapsed-card">
                            <div class="card-header">
                                <div class="user-block">                                  
                                    <span class="username">
                                        <a href="javascript:void(0);">
                                            API-Validate Single Email Documentation
                                        </a>
                                    </span>                                  
                                </div>

                                <!-- /.user-block -->
                                <div class="card-tools">
                                  
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                  </button>                                
                                </div>

                                <!-- /.card-tools -->
                            </div>
                              <!-- /.card-header -->
                            <div class="card-body" >
                                <!-- post text -->
                                <p>Looking to validate emails via our API? Click the documentation button above to help you get started. Be sure to check out all the easy to use API Wrappers to speed up the development process. </p> 

                                <p>
                                	<div class="col-sm-12">

                                		<big><b>EndPoint</b></big><br/>
                                		<div class="input-group">
											<span class="input-group-btn mod_btn"><button type="button" class="btn btn-info">GET</button></span> 

											<input type="text" class="form-control" disabled="disabled" v-model="api_ep_single_email" />
										</div>
									</div>  

									<div class="col-sm-12">

                                		<br/><big><b>Example Request</b></big><br/>
                                		<pre>curl --get --include "{{ api_ep_single_email }}"</pre>

                                	</div>		

                                	<div class="col-sm-12">

                                		<br/><big><b>Example Respone</b></big><br/>
                                		<pre>{"Address":"support@myemailverifier.com",
                                			"Status":"Valid",                              "StatusCode":"2.1.5",
				                            "Disposable_Domain":"false",
				                            "Free_Domain":"false",
				                            "Greylisted":"false",                              
				                            "Diagnosis":"Mailbox Exists and Active"
				                            }</pre>

				                        <p><b>Address:</b> provided email.<br><b>Status:</b> Can be either <b>Valid</b>, <b>Invalid</b> or <b>Unknown</b><br><b>StatusCode:</b> SMTP server status code for advance users<br><b>Disposable_Domain:</b> If domain is disposable<br><b>Free_Domain:</b> If email is not business address<br><b>Greylisted:</b> Server will not response properly in first attempt. Frequent multiple attempt is no advised.<br><b>Diagnosis:</b> Helpful information about <b>Invalid</b> addresse</p>    
                                	</div>		
                                </p>                                
                            </div>    
                        </div> 


                        <div class="card card-widget collapsed-card">
                            <div class="card-header">
                                <div class="user-block">                                  
                                    <span class="username">
                                        <a href="javascript:void(0);">
                                            API-Get Credits Documentation
                                        </a>
                                    </span>                                  
                                </div>

                                <!-- /.user-block -->
                                <div class="card-tools">
                                  
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                  </button>                                
                                </div>

                                <!-- /.card-tools -->
                              </div>
                              <!-- /.card-header -->
                            <div class="card-body" >
                                <!-- post text -->
                                <p>Looking to validate emails via our API? Click the documentation button above to help you get started. Be sure to check out all the easy to use API Wrappers to speed up the development process. </p>

                                <p>
                                	<div class="col-sm-12">

                                		<big><b>EndPoint</b></big><br/>
                                		<div class="input-group">
											<span class="input-group-btn mod_btn"><button type="button" class="btn btn-info">GET</button></span> 

											<input type="text" class="form-control" disabled="disabled" v-model="api_ep_get_credits" >
										</div>
									</div>  

									<div class="col-sm-12">

                                		<br/><big><b>Example Request</b></big><br/>
                                		<pre>{{ api_ep_get_credits }}</pre>

                                	</div>		

                                	<div class="col-sm-12">

                                		<br/><big><b>Example Respone</b></big><br/>
                                		<pre>{"credits":"7800"}</pre>
				                       
                                	</div>		
                                </p>                                
                            </div>    
                        </div> 


                        <div class="card card-widget collapsed-card">
                            <div class="card-header">
                                <div class="user-block">                                  
                                    <span class="username">
                                        <a href="javascript:void(0);">
                                        	API-Upload File for bulk verification Documentation
                                        </a>
                                    </span>                                  
                                </div>

                                <!-- /.user-block -->
                                <div class="card-tools">
                                  
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                  </button>                                
                                </div>

                                <!-- /.card-tools -->
                              </div>
                              <!-- /.card-header -->
                            <div class="card-body" >
                                <!-- post text -->
                                <p>Upload TXT/CSV file for bulk validation.</p>

                                <p>
                                	<div class="col-sm-12">

                                		<big><b>EndPoint</b></big><br/>
                                		<div class="input-group">
											<span class="input-group-btn mod_btn"><button type="button" class="btn btn-info">POST</button></span> 

											<input type="text" class="form-control" disabled="disabled" v-model="api_ep_upload_file">
											<span class="input-group-btn mod_btn"><button type="button" class="btn btn-info">POST Variable</button></span> 

											<input type="text" class="form-control" disabled="disabled" value="api_key (string), filename (fileupload)">
										</div>
									</div>  

									<div class="col-sm-12">

                                		<br/><big><b>Example Request</b></big><br/>
                                		<pre>if (function_exists('curl_file_create'))
                              {
                                 $file = curl_file_create('c:\temp\emails.txt');
                              }
                              else
                              {
                                 $file = '@c:\tempemails.txt';
                              }
                              $data['filename'] = $f;
                              $data['api_key'] = 'API_KEY';
                              $url = '{{ api_ep_upload_file }}';
                              $ch = curl_init($url);
                              curl_setopt($ch, CURLOPT_POST, true);
                              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                              $file_id = curl_exec($ch); //return FILE ID to get file status and download url
                              curl_close($ch);</pre>

                                	</div>		

                                	<div class="col-sm-12">

                                		<br/><big><b>Example Respone</b></big><br/>
                                		<pre>{
			                              "status": true,
			                              "file_name": "emails.txt",
			                              "file_id": 1670,
			                              "msg": "File uploaded successfully."
			                            }</pre>
				                       
                                	</div>		
                                </p>                                
                            </div>    
                        </div> 

                        <div class="card card-widget collapsed-card">
                            <div class="card-header">
                                <div class="user-block">                                  
                                    <span class="username">
                                        <a href="javascript:void(0);">
                                            API-Get File info Documentation
                                        </a>
                                    </span>                                  
                                </div>

                                <!-- /.user-block -->
                                <div class="card-tools">
                                  
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                  </button>                                
                                </div>

                                <!-- /.card-tools -->
                              </div>
                              <!-- /.card-header -->
                            <div class="card-body" >
                                <!-- post text -->
                                <p>Looking to validate emails via our API? Click the documentation button above to help you get started. Be sure to check out all the easy to use API Wrappers to speed up the development process. </p>

                                <p>
                                	<div class="col-sm-12">

                                		<big><b>EndPoint</b></big><br/>
                                		<div class="input-group">
											<span class="input-group-btn mod_btn"><button type="button" class="btn btn-info">GET</button></span> 

											<input type="text" class="form-control" disabled="disabled" v-model="api_ep_file_info" >
											
										</div>
									</div>  

									<div class="col-sm-12">

                                		<br/><big><b>Example Request</b></big><br/>
                                		<pre>curl --get --include "{{ api_ep_file_info }}"</pre>

                                	</div>		

                                	<div class="col-sm-12">

                                		<br/><big><b>Example Respone</b></big><br/>
                                		<pre>{
                                          "upload_id": "1669",
                                          "file_name": "test_20190909001723.xlsx",
                                          "file_path": "https://client.myemailverifier.com/downloadreport/csv/FILEID",
                                          "xls_file_path": "https://client.myemailverifier.com/downloadreport/xls/FILEID",
                                          "user_id": "26",
                                          "created_at": "2019-09-09 00:17:23",
                                          "ready_for_download": "1",
                                          "valid": "27",
                                          "invalid": "2",
                                          "unknown": "1",
                                          "credit_used": "29",
                                          "total": "31",
                                          "status": "finished"
                                        }</pre>
				                       
                                	</div>		
                                </p>                                
                            </div>    
                        </div> 

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        
    </div>    

</template>

<style>
	pre{
		background-color: lightgrey;
	}

    .username{
        margin-left:0px !important;        
    }

    .username a:hover{
        cursor:text !important;        
        text-decoration:none;
    }

</style>

<script>
        
    export default {
        name:'apisettings',
        data:function(){
            return{
                apikey:'',
                api_ep_single_email:app_url+'/verifier/validate_single/example@example.com/API_KEY',
                api_ep_get_credits:app_url+'/verifier/getcredits/API_KEY',
                api_ep_upload_file:app_url+'/verifier/upload_file/',
                api_ep_file_info:app_url+'/verifier/file_info/API_KEY/FILE_ID',
            }
        },
        mounted(){        
                    
            var thisObj = this;
            var uid = localStorage.mevuid;

           

            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }
            });

            
            this.axios.get(api_url+'getAPIKey')
            .then(function(response){ 
                // console.log(response);
                thisObj.apikey = response.data.user.apikey;


            });
            
        },          
        methods:{
            generateAPIKey:function(){
                var thisObj = this;
                var uid = localStorage.mevuid;
                
                this.axios.get(api_url+'generateAPIKey')
                .then(function(response){
                    // console.log(response);
                    thisObj.apikey = response.data.data.newapikey;


                });
            }                
        }       
    }
</script>
