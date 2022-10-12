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


			<div class="callout callout-info"> 

                <h5>Special Discount for Students and Non-Profit / Educational Institutes.</h5>

                <!-- <p>Request special discount for Students and Non-Profit/Educational Institues.</p> -->

                <p>Please fill the below details to get discount. You will get notified once your request is approved or rejected. </p>
            </div>   


		</section>  

		<!-- Main content -->
		<div class="container-fluid">
			<section class="content">
				

				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Request Special Discount</h3> 
					</div>
				  <!-- /.card-header -->
				  <!-- form start -->
					
					<div class="row justify-content-sm-center" >
						<div class="col-sm-6" >

							<form role="form" id="frmSupport" @submit.prevent="submitDiscountRequest" >
								<div class="card-body">
									<div class="form-group">
										<label for="organization" class="float-left" >
											Organization Name 
											
										</label>
										<span class="text-danger loat-left w-25" >*</span>
										<input type="text" class="form-control" id="organization" 
										name="organization" placeholder="" v-model="organization" 
										@keyup="clearmsg('organization')" >

										
										<span class="text-danger" >
											{{ msg.organization }}
										</span>
									</div>                            

									<div class="form-group">
										<label for="contact_person" class="float-left" >
											Contact Person 
											
										</label>                                            
										<span class="text-danger loat-left w-25" >*</span>

										<input type="text" class="form-control" id="message" v-model="contact_person" 
										 @keyup="clearmsg('contact_person')" >
																					

										
										<span class="text-danger" >
											{{ msg.contact_person }}
										</span>
									</div> 


									<div class="form-group">
										<label for="contact_email" class="float-left" >
											Contact Email 
											
										</label>                                            
										<span class="text-danger loat-left w-25" >*</span>

										<input type="email" class="form-control" id="message" v-model="contact_email" 
										 @keyup="clearmsg('contact_email')" >
											
										

										
										<span class="text-danger" >
											{{ msg.contact_email }}
										</span>
									</div> 		                             

									<div class="form-group">
										<label for="credits_required" class="float-left" >
											How many email credits required? 
											
										</label>                                            
										<span class="text-danger loat-left w-25" >*</span>

										<input type="number" class="form-control" id="message" v-model="credits_required" 
										 @keyup="clearmsg('credits_required')" >
											
										

										
										<span class="text-danger" >
											{{ msg.credits_required }}
										</span>
									</div> 	
									

									<div class="form-group">
										<label for="credits_required" class="float-left" >
											Detail 
											
										</label>                                            
										<span class="text-danger loat-left w-25" >*</span>

										<textarea class="form-control" id="detail" v-model="detail" 
										 @keyup="clearmsg('detail')" >
											
										</textarea>
											
										

										
										<span class="text-danger" >
											{{ msg.detail }}
										</span>
									</div> 		           


									<div class="row mb-4">

										<div class="float-left w-100 pl-3" >
											<label class="float-left" >
												Is this one time or recurring requirement 
											</label>
											<span class="text-danger loat-left w-25" >*</span>
										</div>	
																
											<div class="float-left w-25 pl-3" >
												<div class="form-check">
													
													<input class="form-check-input" type="radio" 
													  name="paytype" id="paytype"  v-model="paytype" 
													   value="onetime" >
													  <label class="form-check-label" >
													    One Time
													  </label>	
												</div>											 
											</div>

											<div class="loat-left w-25 pl-3" >
												<div class="form-check">
													  <input class="form-check-input" type="radio" v-model="paytype" value="recurring" >
													  <label class="form-check-label" >
													    Recurring
													  </label>
												</div>                 
											</div> 									
										
									</div>
											
								 
								   

									<div class="form-group">
										<button  name="submit" type="submit" class="btn btn-primary">
											Submit
										</button>
									</div>
								</div>
								<!-- /.card-body -->
								

								<p>&nbsp;</p>
							</form>
						</div>
				  </div>
				</div>                 
			</section>
		</div>
	</div>    
</template>



<script>     
			
	export default {
		name:'SpecialDiscount',  
		data:function(){
			return{
				organization:'',
				contact_person:'',
				contact_email:'',
				detail:'',
				credits_required:'',
				paytype:'onetime',
				msgerror:'',
				textmsg:'',    
				msg:{
					organization:'',
					contact_person:'',
					contact_email:'',
					detail:'',
					credits_required:'',
				},
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
				if(field == 'organization'){
					this.msg.organization = '';
				}

				if(field == 'contact_person'){
					this.msg.contact_person = '';
				}

				if(field == 'contact_email'){
					this.msg.contact_email = '';
				}

				if(field == 'credits_required'){
					this.msg.credits_required = '';
				}				

				if(field == 'detail'){
					this.msg.detail = '';
				}
			},
			submitDiscountRequest:function(e){
				e.preventDefault();
				var thisObj = this;
				thisObj.hasError=false;
				thisObj.msgerror=false;
				thisObj.textmsg="";

				if(thisObj.organization == ''){
					/*thisObj.textmsg="please enter old password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.organization="please provide name of the Organization";
				}

				if(thisObj.contact_person == ''){
					/*thisObj.textmsg="please enter new password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.contact_person="please provide name of the contact person";
				}

				if(thisObj.contact_email == ''){
					/*thisObj.textmsg="please enter new password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.contact_email="please provide Email Address of the contact person";
				}

				if(thisObj.detail == ''){
					/*thisObj.textmsg="please enter new password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.detail="please provide some detail";
				}

				if(thisObj.credits_required == ''){
					/*thisObj.textmsg="please enter new password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.credits_required="please provide required credits";
				}

				if(thisObj.hasError==true){

					return false;
				}				

				if(thisObj.hasError==false){

					this.axios.post(api_url+'discountRequest',{
						organization:thisObj.organization,
						contact_person:thisObj.contact_person,
						contact_email:thisObj.contact_email,
						detail:thisObj.detail,
						credits_required:thisObj.credits_required,
						paytype:thisObj.paytype,
					}).then(function(response){

						alert(response.data.msg);
						this.msg.organization="";
						this.msg.contact_person="";
						this.msg.contact_email="";
						this.msg.credits_required="";
						this.msg.detail="";
								

						
					}).catch(function(error){
						alert(error);
					});


				}

			}
		},
		created(){

			
		} 
	}
</script>
