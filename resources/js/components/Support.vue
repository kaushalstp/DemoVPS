<template>
	<div> 
		<section class="content-header">    
			<div class="container-fluid">
				<div class="row mb-2">
				  <!-- <div class="col-sm-6">
					<h1>Change Password</h1>
				  </div> -->            
				</div>
			</div><!-- /.container-fluid -->
		</section>  

		<!-- Main content -->
		<div class="container-fluid">
			<section class="content">
				<div class="alert alert-success" v-show="msgsuccess" >
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
					<strong>Success!</strong> {{ textmsg }}
				</div>
				<div class="alert alert-danger" v-show="msgerror"  >
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
					<strong>Fail!</strong> {{ textmsg }}
				</div>

				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Support</h3> 
					</div>
				  <!-- /.card-header -->
				  <!-- form start -->
					<div class="text-center" >    
						<br/>
						<p> 
							<span class="question_note" >(we generally respond in 2 hours but sometimes it may take upto 24 hours)</span>
						</p>

						<p> 
							<span class="question_note" >
								Please refer our detailed blog about 
								<a href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >"How it works"</a>,
								to find answers of common queries.
							</span>
						</p>
						<p>	

							<span class="question_note" >	
								Please check our 
								<router-link to="/FAQ">FAQ</router-link>
								section for most common questions				
							</span>
						</p>
					</div>
					

					<div class="row justify-content-sm-center" >
						<div class="col-sm-6" >

							<form role="form" id="frmSupport" @submit.prevent="submitSupportDetails" >
								<div class="card-body">
									<div class="form-group">
										<label for="subject">Subject</label>
										<input type="text" class="form-control" id="subject" 
										name="subject" placeholder="" v-model="subject" 
										@keyup="clearmsg('subject')" >

										
										<span class="text-danger" >
											{{ msg.subject }}
										</span>
									</div>                            

									<div class="form-group">
										<label for="message">Message</label>                                            

										<textarea class="form-control" id="message" v-model="message" 
										 @keyup="clearmsg('message')" >
											
										</textarea>

										
										<span class="text-danger" >
											{{ msg.message }}
										</span>
									</div>                              
								   

									<div class="form-group">
										<button  name="submit" type="submit" class="btn btn-primary">
											Send Email
										</button>
									</div>
								</div>
								<!-- /.card-body -->														
							</form>
						</div>
				  	</div>				  	
				</div>
                 
			</section>
		</div>
	</div>    
</template>

<style>
	.question_note{
		background-color: #007fcd;        
		color:#fff;
		padding: 5px;
		font-size: 14px;
		border-radius: 3px;
	}

	.question_note a{		
		color:#fff;
		font-weight: bold;
		text-decoration: underline;
	}


	.question_note .nav-link{

	}
</style>

<script>     
			
	export default {
		name:'Support',  
		data:function(){
			return{
				subject:'',
				message:'',
				msgsuccess:'',
				msgerror:'',
				textmsg:'',    
				msg:[],
			}
		},
		mounted(){        

			var thisObj = this;

            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }
            });
					  
		},      
		methods:{
			clearmsg:function(field){
				if(field == 'subject'){
					this.msg.subject = '';
				}
				if(field == 'message'){
					this.msg.message = '';
				}
			},
			submitSupportDetails:function(e){
				e.preventDefault();
				var thisObj = this;
				thisObj.hasError=false;
				thisObj.msgerror=false;
				thisObj.textmsg="";

				if(thisObj.subject == ''){
					/*thisObj.textmsg="please enter old password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.subject="please enter subject";
				}

				if(thisObj.message == ''){
					/*thisObj.textmsg="please enter new password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.message="please enter your message";
				}

				if(thisObj.hasError==false){

					this.axios.post(api_url+'sendsupportmessage',{
						subject:thisObj.subject,
						message:thisObj.message,
					}).then(function(response){

						alert(response.data.msg);
						thisObj.subject='';
						thisObj.message='';

						thisObj.msg.subject='';
						thisObj.msg.message='';
					});
				}

			}
		},
		created(){

			
		} 
	}
</script>
