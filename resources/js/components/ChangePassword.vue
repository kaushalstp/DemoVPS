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
		<section class="content">

			<div class="container-fluid" >
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
						<h3 class="card-title">Change Password</h3>
					</div>
				  <!-- /.card-header -->
				  <!-- form start -->
				  <div class="row justify-content-sm-center">
				  	<div class="col-sm-6" >    
						<form role="form" id="frmChangePwd" @submit.prevent="submitPwdDetails" >
							<div class="card-body">
								<div class="form-group">
									<label for="old_password">Old Password</label>
									<input type="password" class="form-control" id="old_password" 
									name="old_password" placeholder="" v-model="old_password" 
									@keyup="clearmsg('old_password')" >

									<span class="text-danger" >
										{{ msg.old_password }}
									</span>

								</div>

								<div class="form-group">
									<label for="new_password">New Password</label>
									<input type="password" class="form-control" id="new_password" 
									name="new_password" placeholder="" v-model="new_password" 
									@keyup="clearmsg('new_password')" >


									<span class="text-danger" >
										{{ msg.new_password }}
									</span>
								</div>

								<div class="form-group">
									<label for="new_password">Confirm New Password</label>
									<input type="password" class="form-control" id="confirm_password" 
									name="confirm_password" placeholder="" v-model="confirm_password" @keyup="clearmsg('confirm_password')" >

									
									<span class="text-danger" >
										{{ msg.confirm_password }}
									</span>

								</div> 

								<div class="form-group">
									<button  name="submit" type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
							<!-- /.card-body -->

							<p>&nbsp;</p>
						</form>
				</div>

				  </div>

				</div>
			</div>
				
		</section>
	</div>    
</template>

<script>    

	
	

	export default {
		name:'ChangePassword',  
		data:function(){
			return{
				old_password:'',
				new_password:'',
				confirm_password:'',
				msgerror:false,
				msgsuccess:false,
				textmsg:'',
				hasError:false,
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
			clearmsg(msgbox=""){                

				

				if(msgbox == "old_password"){
					this.msg.old_password="";                    
				}

				if(msgbox == "new_password"){
					this.msg.new_password="";                    
				}

				if(msgbox == "confirm_password"){
					this.msg.confirm_password="";                    
				}

			},
			submitPwdDetails:function(e){
				e.preventDefault();
				var thisObj = this;
				thisObj.hasError=false;
				thisObj.msgerror=false;
				thisObj.textmsg="";

				if(thisObj.old_password == ''){
					/*thisObj.textmsg="please enter old password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.old_password="please enter old password";
				}

				if(thisObj.new_password == ''){
					/*thisObj.textmsg="please enter new password";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.new_password="please enter new password";
				}
				

				if(thisObj.confirm_password == ''){
					/*thisObj.textmsg="please confirm your new password";
					thisObj.msgerror=true;*/

					thisObj.hasError=true;
					this.msg.confirm_password="please confirm your new password";
				}

				if(thisObj.new_password != thisObj.confirm_password){
					/*thisObj.textmsg="confirm password did not match";
					thisObj.msgerror=true;*/


					thisObj.hasError=true;
					this.msg.confirm_password="confirm password did not match";

				}

				if(thisObj.hasError==false){

					this.axios.post(api_url+'changePassword',{
						userid:localStorage.mevuid,           
						old_password:thisObj.old_password,
						new_password:thisObj.new_password,
						confirm_password:thisObj.confirm_password,
					}).then(function(response){

						//thisObj.textmsg=response.data.msg;

						if(response.data.status=="0"){                        
							
							thisObj.$toast.open({
								message: response.data.msg,
								type:'error',
								position:'top-right'
							});

						}


						if(response.data.status=="1"){                          

							thisObj.confirm_password=""; 
							thisObj.old_password="";
							thisObj.new_password="";

							 thisObj.$toast.open({
								message: response.data.msg,
								type:'success',
								position:'top-right'
							});
						}

					});  

				}

							 
			}
		},
		created(){

			setInterval(() => this.textmsg = false, 3000);
			setInterval(() => this.msgsuccess = false, 3000);
			setInterval(() => this.msgerror = false, 3000); 
			setInterval(() => this.hasError = false, 3000); 
		} 
	}
</script>
