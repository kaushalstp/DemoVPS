<template>
	<!-- <div style="background: url(https://client.myemailverifier.com/public/img/promo_img.jpg) center center no-repeat;background-size: cover;height: 100vh;"> -->
	<div style="background:#edf7ff;">
		<div class="row justify-content-end mr-0" style="min-height: 100vh;" >
			<!-- <div class="col-12 col-sm-6 col-md-6 col-lg-8 col-xl-8 text-center">
				<img :src="adminlogo" alt="AdminLTE Logo"  />
			</div> -->
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 whitebox py-4 order-md-1">
				<div class="login-box">
					<div class="login-logo mb-0">
						<img :src="adminlogo" alt="AdminLTE Logo" class="brand-image" />
					</div>
					<div class="card" style="box-shadow: none;border: 0;">
						<div class="card-body login-card-body">
							<p class="login-box-msg mb-2">Sign in to start your session</p>
							<p class="text-center" v-if="showResendlink" > 
								<button class="btn btn-primary" @click="resendMyLink" >Resend Actication Link</button> 
							</p>
							<div class="alert alert-success mb-3" role="alert" v-show="msgsuccess" > 
								{{ textmsg }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="alert alert-danger" role="alert" v-show="msgerror" > 
								{{ textmsg }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							<div class="" >

								<!-- <GoogleLogin :params="gparams" :onSuccess="onSuccess" :onFailure="onFailure">Login</GoogleLogin> -->
								<!-- <button @click="googleSignIn" >Login with Google</button> -->
								<div class="text-center">
									<div>
										<a href="javascript:void(0);" @click="gotoGoogle"  > 
											<img :src="img_glogin"  width="200" />
										</a>
									</div>
								</div>
								<div class=" text-center">
									<a href="javascript:void(0);" data-toggle="modal" data-target="#otpLoginModal" @click="openotpform"  > 
										<img :src="img_otp_login"  width="200" />
									</a>
								</div>
							</div>
							<div class="">
								<div class="text-center">
									<b style="line-height: 2.5;" >OR</b>
								</div>
							</div>

							<form  method="post" id="frmLogin" name="frmLogin"  @submit.prevent="submitLogin" >
								<div class="input-group mb-3">
									<input type="email" class="form-control form-control-lg" placeholder="Email" 
									v-model="logininfo.email"  @keyup="clearmsg"   />
									<div class="input-group-append">
										<div class="input-group-text" style="border-top-right-radius: 0.3rem;border-bottom-right-radius: 0.3rem;">
											<span class="fas fa-envelope"></span>
										</div>  
									</div>
									<span class="text-danger" v-if="msg.email" >{{msg.email}}</span>
								</div>

								<div class="input-group mb-3">
									<input type="password" class="form-control form-control-lg" placeholder="Password" 
									v-model="logininfo.password"  @keyup="clearmsg"   />
									<div class="input-group-append">
										<div class="input-group-text" style="border-top-right-radius: 0.3rem;border-bottom-right-radius: 0.3rem;">
											<span class="fas fa-lock"></span>
										</div>
									</div>
									<span class="text-danger" v-if="msg.password" >{{msg.password}}</span>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="icheck-primary"> 
											<input type="checkbox" id="remember"  v-model="remember_me" /> 
											<label for="remember">Remember</label>
										</div>
									</div>

									<div class="col-6">
										<p class="mb-1 forgot-block ml-0 text-right">
											<router-link to="/ForgotPassword" class="d-block">Forgot Password?</router-link> 
											<router-link to="/Register" class="d-block">
												<img :src="createaccnticon" alt=" " width="16" /> Create Account
											</router-link>
										</p>
									</div>
								</div>
								<button type="submit" class="btn btn-primary btn-block my-3 sign_btn">Sign In</button>
								<vue-recaptcha 
									ref="recaptcha"
									@verify="onVerify"
									sitekey="6LdOkTAbAAAAABpXJqrbR5k7ftwEJO4HLPlwLHj9" >
								</vue-recaptcha>

								<!-- site key for kaushalproject.com: 6Ld1Ag8aAAAAAEnEcZo_3I2UevAgH6AMK8WSLZzJ     
									site key for harrtest.tk:  6Lev9g4aAAAAAL1wmdDqEpAM3F94gm6GVXL3_oNg-->
							</form>
							<!-- /.social-auth-links -->
						</div>
					</div>
					<!--  OTP Login form  -->
					<div class="">
						<div class="modal fade" id="otpLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Login In with OTP</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>

									<div class="modal-body">  
										<div class="" v-show="sendotpform" >                            
											<form  name="frmOTPLogin" @submit.prevent="submitOTPLogin" id="frmOTPLogin" >
												<div class="" >
													<span style="font-size: 12px;" >
														&nbsp;&nbsp;&nbsp;&nbsp;  OTP will be sent to your Email Address 
													</span>
												</div>
												<div class="row justify-content-center" >
													<div class="col-sm-7" > 
														<div class="form-group">
															<input type="text" class="form-control form-control-lg" id="new_email" 
															v-model="logininfo.new_email"  placeholder="Email Address" 
															@keyup="clearmsg"  />
														</div>    
														<br/>
														<span class="text-danger" v-if="msg.new_email" >{{msg.new_email}}</span>
													</div>
													<div class="col-sm-4">
														<button type="submit" class="btn btn-primary">Send OTP</button>
													</div>
												</div>
											</form>
										</div>
										<div class="" v-show="verifyotpform" >
											<form  name="frmVerifyOTP" @submit.prevent="submitVerifyOTP" id="frmVerifyOTP" >
												<input type="hidden"  name="user_email" 
												id="user_email" v-model="otpinfo.user_email" />
												<div class="" >
													<span style="font-size: 14px;" v-html="msg_otp_verification" ></span>
												</div>
												<div class="row" >
													<div class="col-sm-1" > </div>
													<div class="col-sm-6" > 
														<div class="form-group">
															<input type="text" class="form-control form-control-lg" id="new_otp" 
															v-model="otpinfo.new_otp"  name="new_otp" placeholder="Enter OTP" 
															@keyup="clearmsg"  />
														</div>    
														<br/>
														<span class="text-danger" v-if="msg.new_otp" >{{msg.new_otp}}
														</span>
													</div>
													<div class="col-sm-4" > 
														<button type="submit" class="btn btn-primary">Verify OTP</button>
													</div>
												</div>
												<div class="row"> 
													<div class="col-sm-6"> 
														<a href="javascript:void(0);" @click="closeotpwindow">Resend OTP</a>
														<br/>
														<a href="javascript:void(0);" @click="showmainlogin" data-dismiss="modal" >Login with Password</a>
													</div>
												</div>
											</form>
										</div>

										<div class="text-danger">
											<p>Notes:- Disposable domains are strictly prohibited.</p>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-8 col-xl-8 text-center py-4">
				<img :src="adminlogo" alt="AdminLTE Logo"  />
				<div class="imp_content">
					<p>Take advantage of our daily offer of 100 email verification credits.</p>
					<p>Our proprietary AI and ML driven technology provides unparalleled accuracy and speed.</p>
					<p>Seamless reprocessing of unknown emails.</p>
					<p>Utilizing innovative processes, our machines are highly intelligent and efficient -  allowing us to offer the lowest prices.</p>					
					<p>Take advantage of our value bundle: <b>1 Million Credits for just $299/mon.</b></p>
				</div>
				<div class="row justify-content-center mt-4">
					<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
						<a class="ratingmed" href="https://www.trustpilot.com/review/myemailverifier.com" >	
							<div class="ratingsicon">
								<img :src="tpilot_img" alt="Trustpilot" title="Trustpilot" class="img-fluid" />
								<div class="namerating text-left">
									<p>Trustpilot</p>
									<span>4.8 / 5</span>
								</div>
							</div>
						</a>		
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
						<a class="ratingmed" href="https://www.capterra.com/p/201337/MyEmailVerifier/reviews/" >							
							<div class="ratingsicon">
								<img :src="capterra_img" alt="Capterra" title="Capterra" class="img-fluid"/>
								<div class="namerating text-left">
									<p>Capterra</p>
									<span>4.7 / 5</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
						<a class="ratingmed" href="https://www.g2.com/products/myemailverifier/reviews" >
							<div class="ratingsicon">
								<img :src="g2_img" alt="G2" title="G2" class="img-fluid" /> 
								<div class="namerating text-left">
									<p>G2</p>
									<span>4.5 / 5</span>
								</div>
							</div>
						</a>
					</div>	
				</div>
				<div class="mt-3">
					<carousel :per-page="1" :navigate-to="someLocalProperty" :mouse-drag="false" 
					:navigationEnabled="false" paginationActiveColor="#964B00" 
					paginationColor="#000000">
					    <slide>
					    	<video width="500" controls style="background: #fff;padding: 8px;border: 1px solid #d2e2ef;border-radius: 3px;">
								<source src="http://client.myemailverifier.com/public/img/Review.mp4" type="video/mp4">
							</video>  
					    </slide>
					    <slide>
					    	<video width="500" controls style="background: #fff;padding: 8px;border: 1px solid #d2e2ef;border-radius: 3px;">
								<source src="http://client.myemailverifier.com/public/img/review2.mp4" type="video/mp4">
							</video>  
					    </slide>					    
					</carousel>
				</div>
			</div>
		</div>
	</div>
</template>

<style>
	.sign_btn{background-color: #2135cc;font-size: 22px;font-weight: bold;}
	.sign_btn:hover{background-color: #2135cc;}
	.ratingmed{
		text-decoration: none;
	}

	.ratingmed:hover{
		text-decoration: none;
	}

	.forgot-block a{
		font-size: 16px;color: #2135cc;
	}
	.login-box-msg{
		padding: 0 0 0 !important;
	}
	.whitebox{
		background-color: #fff;
	}
	.login-box{
		margin:auto;width: auto;
	}
	.text-danger{
		width: 100%;
	}
	#frmOTPLogin{
		width:100%;
	}
	#frmVerifyOTP {
		width: 100%;
	}

	#frmVerifyOTP  a{
		font-size: 13px;
	}

	.imp_content {max-width: 780px;margin: 20px auto 0;}
	.imp_content p{font-size: 18px;line-height: normal;}
	.namerating p{font-size: 18px;color: #212529;margin: 0;font-weight: 500;}
	.namerating span{font-size: 18px}
	.ratingsicon{margin-bottom: 12px;background: #fff;padding: 8px 7px;border-radius: 6px;display: flex;align-items: center;line-height: normal;color: #212529;}
	.ratingsicon img{width: 30px;margin: 0 5px 10px 0;}
</style>

		<script>

	//import store from '../store';
	import VueRecaptcha from 'vue-recaptcha';
	import GoogleLogin from 'vue-google-login';
	import { Carousel, Slide } from 'vue-carousel';


	export default {
		name:'Login', 
		beforeMount(){

			if(this.remember_me == true || this.remember_me == 'true'){
				this.checkSelected = " checked "; 
			}else{
				this.checkSelected = ""; 
			}
		},
		updated(){

		},
		mounted(){
			
			/* check user's Login */ 

			var thisObj = this;    
			/*var cookie_val = this.getCookie();   
			alert(cookie_val);*/
			
			var gotosupportpage = this.$route.query.gotosupportpage;
			this.registered_email = this.$route.params.registered_email;
			
			if(this.registered_email == undefined || this.registered_email == '' ){
				this.showResendlink=false;
			}


			if(gotosupportpage == '1'){
				
				document.cookie = "gotosupport=1";
			}

			this.axios
			.get(api_url+'checkusersession')
			.then(function(response){

				if(response.data.status == 1 || response.data.status == '1'){ 

					var cookie_val = thisObj.getCookie();
					
					if(cookie_val == '1'){						
						thisObj.$router.push({  
							name:'Support',
						});
					}

					


					thisObj.$router.push({  
						name:'dashboard',
					});

					/*if(localStorage.mevid > 0 ){                
						
					}*/

				}

			});

			
			
			/* ------------- */

			//this.remember_me = localStorage.remember_me;
			
			if(localStorage.remember_me == false || localStorage.remember_me == 'false'){  
				this.remember_me = false; 
			}else{
				this.remember_me = true; 
			}

			this.msgsuccess = localStorage.msgsuccess;            
			this.textmsg = localStorage.textmsg;       
			if(localStorage.textmsg == ''){
				this.msgsuccess = false;
			} 

			this.logininfo.email = localStorage.remember_email;
			this.logininfo.password = localStorage.remember_pass;
			//this.remember_me = localStorage.remember_me;

			localStorage.msgsuccess = false;        
			localStorage.textmsg = '';   

			this.recaptcha_site_key = g_recaptcha_key;
			this.gparams.client_id = google_client_id;
		},
		data(){
			return{       
				g2_img:img_url+'g2_img.png',                
                tpilot_img:img_url+'tpilot_img.png',      
                capterra_img:img_url+'capterra_img.png',                               
				registered_email:'',    
				adminlogo:img_url+'mev-logo-2.svg',  
				createaccnticon:img_url+'create_account.png',  
				promo_img_url:img_url+'promo_img.jpg',    
				img_glogin:img_url+'gsignin.png', 
				img_otp_login:img_url+'otp-img.jpg',  
				google_login_link:app_url+'/auth/redirect/',
				hasError:false,
				showResendlink:true,
				//errors:[],
				vlds:[],
				msg:[],
				msgsuccess:false,
				msgerror:false,
				textmsg:'',  
				remember_email:'',
				remember_pass:'',
				remember_me:false,
				checkSelected:'',
				sendotpform:true,
				verifyotpform:false,
				msg_otp_verification:'',
				checked:false,
				loginError: false,
				errors: {},
				isProgress: false,
				recaptcha_site_key:'', 
				recaptcha:'',                
				logininfo:{                    
					email:'',
					password:'',  
					new_email:'',  
					recaptcha:'',                
				},   
				otpinfo:{
					user_email:'',
					new_otp:'',
				},    
				gparams:{   
					client_id: '',
					theme: 'dark',
					ux_mode:'redirect',
					redirect_uri:app_url+'/Login'
				},
				renderParams:{  
					width: 250,
					height: 50,
					longtitle: true
				},
			}
		},
		methods:{
			resendMyLink(){
				if(!confirm('Resend Activation Link?')){
					return false;
				}

				var thisObj = this;
				
				this.axios
				.post(api_url+'resendactivationlink',{ registered_email:thisObj.registered_email })
				.then(function(response){
					
					if(response.data.status==1 || response.data.status=='1'){						
						alert(response.data.msg);	
					}else{
						alert("Please try again after some time");	
					}
				}); 

			},
			getCookie(c_name="gotosupport") {
				var c_start = 0;
				if (document.cookie.length > 0) {
					c_start = document.cookie.indexOf(c_name + "=");
					if (c_start != -1) {
						c_start = c_start + c_name.length + 1;
						c_end = document.cookie.indexOf(";", c_start);
						if (c_end == -1) {
							c_end = document.cookie.length;
						}
						return unescape(document.cookie.substring(c_start, c_end));
					}
				}
				return "";
			},
			resetRecaptcha () {
				
				this.$refs.recaptcha.reset();
		    	//this.$refs.logininfo.recaptcha.reset();  
		    	//this.logininfo.recaptcha.reset();  

		    },	
		    gotoGoogle(){
		    	window.location = this.google_login_link;
		    },
		    googleSignIn(){ 

				//alert('hello oo');

				this.axios
				.get('http://kaushalproject.com/myadmin/auth/redirect/')
				.then(function(response){
						//console.log(response);
					});
			},
			onSuccessOfGoogle(response){
				//console.log(response);

			},
			onErrorOfGoogle(response){
				//console.log(response);

			},
			onSuccess(googleUser) {
				alert('gplus success');

				//console.log(googleUser);     
				// This only gets the user information: id, name, imageUrl and email
				//console.log(googleUser.getBasicProfile());
			},
			onFailure(googleResponse) {
				alert('gplust login failed');
				//console.log(googleResponse);                    
			},
			onVerify(recaptcha_response){
				this.recaptcha = recaptcha_response; 
			},
			closeotpwindow(){
				this.msg_otp_verification='';
				this.verifyotpform=false;
				this.sendotpform=true;                
			},
			showmainlogin(){

				this.msg_otp_verification='';
				this.verifyotpform=false;
				this.sendotpform=true;

				jQuery('#otpLoginModal').modal('hide');

			},
			openotpform(){
				this.verifyotpform=false;
				this.sendotpform=true;
			},
			clearmsg(){

				if(this.logininfo.email != ''){
					this.msg.email = '';
				}                 

				if(this.logininfo.password != ''){
					this.msg.password = ''; 
				} 

				if(this.logininfo.new_email != ''){
					this.msg.new_email = ''; 
				} 

				if(this.otpinfo.new_otp != ''){
					this.msg.new_otp = ''; 
				}                 
			},
			submitVerifyOTP(e){
				e.preventDefault(); 

				this.errors=[];   
				this.hasError=false;   
				this.msg=[];
				this.vlds=[];

				if(!this.otpinfo.new_otp){
					this.hasError=true;
					this.msg['new_otp']  = "Please enter otp";              
				}

				if(this.hasError == false){ 
					var thisObj = this;
					

					this.axios
					.post(api_url+'verifyotp',thisObj.otpinfo)
					.then(function(response){
						//console.log(response);


						/*if(response.data.status==0 || response.data.status=='0'){
							thisObj.msgerror = true;            
							thisObj.textmsg=response.data.msg;            
						}*/

						if(response.data.status==1 || response.data.status=='1'){
							
							thisObj.verifyotpform=true;
							thisObj.sendotpform=false;
							thisObj.msg_otp_verification = response.data.msg;

							//localStorage.mevid = 26;

							localStorage.msgerror = false;            
							localStorage.textmsg='Logged In successfully';
							
							jQuery('#otpLoginModal').modal('hide');

							thisObj.$router.push({
								name:'dashboard',
								/*params:{
									message : 'Logged In successfully',
								}*/
							});

							//thisObj.$parent.msgsuccess=true;
							//thisObj.$parent.textmsg=response.data.msg;
						}else{
							thisObj.msg_otp_verification = response.data.msg;
						} 
					});
				}        
			},
			submitOTPLogin(e){
				e.preventDefault(); 

				this.errors=[];   
				this.hasError=false;   
				this.msg=[];
				this.vlds=[];    

				if(!this.logininfo.new_email){
					//this.errors.push('please select department');
					this.hasError=true;
					//this.vlds['email']=true;
					this.msg['new_email'] = "please enter your email address";
				}else if (!this.validEmail(this.logininfo.new_email)){
					this.msg['new_email'] = "Please enter Valid email address. ";
				}


				if(this.hasError == false){ 

					var thisObj = this;

					this.axios
					.post(api_url+'sendotp',this.logininfo)
					.then(function(response){
						//console.log(response);

						/*if(response.data.status==0 || response.data.status=='0'){
							thisObj.msgerror = true;            
							thisObj.textmsg=response.data.msg;            
						}*/

						if(response.data.status==1 || response.data.status=='1'){
							
							thisObj.verifyotpform=true;
							thisObj.sendotpform=false;
							thisObj.msg_otp_verification = response.data.msg;
							thisObj.otpinfo.user_email = thisObj.logininfo.new_email;
							//thisObj.$parent.msgsuccess=true;             
							//thisObj.$parent.textmsg=response.data.msg;
						}else{
							thisObj.msg_otp_verification = response.data.msg;
						}
					}); 
				}
			},
			submitLogin(e){ 
				
				e.preventDefault(); 
				
				if(this.remember_me == true){
					localStorage.remember_email = this.logininfo.email;
					localStorage.remember_pass = this.logininfo.password;
					localStorage.remember_me = true;
				}else{
					localStorage.remember_email = '';
					localStorage.remember_pass = '';
					localStorage.remember_me = false;
				}

				

				this.errors=[];   
				this.hasError=false;   
				this.msg=[];
				this.vlds=[];

				
				if(!this.logininfo.email){
					//this.errors.push('please select department');
					this.hasError=true;
					//this.vlds['email']=true;
					this.msg['email'] = "Please enter email ";
				}else if (!this.validEmail(this.logininfo.email)){
					this.errors.push('Please enter Valid email address.');
				}

				if(!this.logininfo.password){
					//this.errors.push('please select department');
					this.hasError=true;
					//this.vlds['password']=true;
					this.msg['password'] = "Please enter password";
				}

				
				if(this.hasError == false){ 

					var thisObj = this;

					this.axios.post(api_url+'signin',{
						email:this.logininfo.email,
						password:this.logininfo.password,
						new_email:this.logininfo.new_email,
						recaptcha:this.recaptcha
					}).then(response=>{

						//console.log(response);                     

						if(response.data.status==0 || response.data.status=='0'){
							thisObj.msgerror = true;            
							thisObj.textmsg=response.data.msg;        

							//thisObj.logininfo.recaptcha    							
							thisObj.resetRecaptcha();

						}

						if(response.data.status==1 || response.data.status=='1'){


							if(response.data.data.is_admin == 1 || response.data.data.is_admin == '1'){ 
								localStorage.is_admin = true;
							}else{
								localStorage.is_admin = false;
							}

							localStorage.setItem('username',response.data.data.firstname);
							localStorage.setItem('mevid',response.data.data.reg_id);
							localStorage.setItem('credits',response.data.data.credits);


							thisObj.$router.push({ 
								name:'dashboard',
								params:{
									message : response.data.msg,
								}
							});
						}
					});					
				}
			},
			validEmail: function (email) {
				var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(email);
			},
			otherlogin(){

				var thisObj = this;
				this.loginError = false;

				$auth.login({
					data: {
						email: thisObj.logininfo.email,
						password: thisObj.logininfo.password
					},
					success: function() {
						// handle redirection
						app.success = true;
						const redirectTo = 'dashboard';
						this.$router.push({name: redirectTo})
					},
					error: function() {
						app.has_error = true
						app.error = res.response.data.error
					},
					rememberMe: true,
					fetchUser: true
				});
			},
			newlogin() {
				var thisObj = this;
				this.loginError = false;
				this.axios.post(api_url+'auth/login', {
					email: thisObj.logininfo.email,
					password: thisObj.logininfo.password
				}).then(response => {
					this.isProgress = true;
					if(response.data.success == true)
					{
						setTimeout(() => {
							this.isProgress = false;
							store.commit('LoginUser', response.data);
							this.$router.push({name: 'dashboard'})
						},2000);
					}
					else {
						this.isProgress = true;
						setTimeout(() => {
							this.isProgress = false;
							this.loginError = true;
							this.errors = response.data.errors
						},1000);
					}
				}).catch(error => {
					this.isProgress = false;
					this.loginError = true;
					this.errors = error.response.data.errors
				});
			},
		},
		created(){
			var thisObj = this;
		},
		watch:{            
			
		},
		components: { VueRecaptcha,GoogleLogin,Carousel,Slide },  
	}            

</script>