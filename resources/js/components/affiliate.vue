<template>
	<div>
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
				  <div class="col-sm-6">
					<h1>Affiliate</h1>
				  </div>                  
				</div>
			</div><!-- /.container-fluid -->
		</section>

		<!-- Main content -->

		<section class="content">  
			<div class="container-fluid">				
				<section class="blue-purple-header text-center padding-tb50">
					<div class="padding-tb20" ref="my_aff_program" >
						<h1 class="cd-headline text-center home-headline">Affiliate Program</h1>
						<p>Become a MyEmailVerifier affiliate and earn <b>25% highest commission</b> on every sale.</p>
											
						<div style="margin-bottom: 30px;">
							<p>								
								<a target="_blank" v-bind:href="affl_signup_url" class="btn btn-dark" > 
									Sign Up Affiliate
								</a>

								&nbsp;&nbsp;&nbsp; 

								<a  target="_blank" v-bind:href="affl_signin_url" class="btn btn-dark" > 
									Log In to Affiliate Dashboard
								</a>
							</p>					
						</div>
					</div>
				</section>
				<section class="padding-tb70"> 
					
					<div class="">					
						<div class="row ligth-gray-bg">
							<div class="col-sm-12 col-md-12 col-lg-6">
								<img :src="img_earnprofit" alt="Earn High Commission With MyEmailVerifier" 
								title="Earn High Commission With MyEmailVerifier" class="img-fluid">
							</div>
							<div class="col-sm-12 col-md-12 col-lg-6 pt-4">
								<p>&nbsp;</p>
								<h3 class="white-title black-font" style="font-size: 30px;">
									Earn High Commission With MyEmailVerifier
								</h3>
								<p>Love sharing ideas on blogs and social media?</p>
								<p>Earn a decent commission simply by telling your audience how MyEmailVerifier helps boost your email deliverability.</p>
								<p>Use your affiliate links to promote MyEmailverifier's software via blog articles, email newsletters, Youtube, and other digital media.</p>
								<p><a href="https://myemailverifier.idevaffiliate.com/signup.php" target="_blank" class="btn btn-primary" type="submit">JOIN NOW</a></p>
							</div>
						</div>
					</div>				
					
					<p>&nbsp;</p> 

					<div class="">					
						<h5 class="text-center">Why join MyEmailVerifier?</h5>
						<p>&nbsp;</p>
						<div class="row" >

							<div class="col-sm-6" >
								<h5>Decent commissions </h5>
								<p class="text-justify" >
									We give decent commissions as per Industry standards. We are delighted to offer the highest commission than any other company. Every affiliate marketer will get a <b>25% commission</b> on each sale.
								</p>
							</div>	
							<div class="col-sm-6" >
								<h5>Simple ways to promote</h5>
								<p class="text-justify" >
									With our advanced software, conversions are easy to make. You can choose any platform to promote the affiliate links and get the commission directly into your dashboard.
								</p>
							</div>	
							<div class="col-sm-6 pt-2" >
								<h5>Powerful Tracking</h5>
								<p class="text-justify" >
									MyEmailVerifier affiliate dashboard gives accurate information of your clicks, sales, and commissions. In addition, you can view your earnings and track performance.
								</p>
							</div>	

							<div class="col-sm-6 pt-2" >
								<h5>A Dedicated Affiliate Manager</h5>
								<p class="text-justify" >
									MyEmailVerifier provides an affiliate manager to help you grow, increase your website's visibility, and drive more sales. 
								</p>
							</div>	
						</div>									
					</div>

				</section>

				<section class="padding-tb70 ligth-gray-bg">
					<div class="">
					
						<h3 class="white-title black-font text-center"style="font-size: 30px;">How to join MyEmailVerifier?</h3>
						<p>&nbsp;</p>
						<div class="row" >				
							<div class="col-sm-6 pt-3" >

								<p>
									<b>Step 1</b><br/>Click on the sign-up button below. 
								</p>
								<p>
									<b>Step 2</b><br/>Fill out a form to apply to the MyEmailVerifier Affiliate Program.
								</p>
								<p>
									<b>Step 3</b> <br/> 
									We'll review your application and notify you when your account is approved, typically within two business days.<br/><br/>

									If you do not get a response from us, please send us an email at  affiliates@myemailverifier.com 
								</p>													

								<p>
									<p><a class="btn btn-primary" href="https://myemailverifier.idevaffiliate.com/signup.php" target="_blank" >Sign Up</a></p>
								</p>												
							</div>

							<div class="col-sm-6" > 
								<p class="text-center" > <img :src="imgaffsteps" width="450" height="280" /></p>		
							</div>				
						</div>

					</div>
				</section>

				<section>
					
					<div>
						<p>&nbsp;</p>
						<h3 class="white-title black-font text-center"style="font-size: 30px;">FAQs</h3>						
						<div class="content px-2 page-faq">
							<div id="accordion" >
								<div class="container-fluid">
									<div class="row justify-content-sm-center" >
										<div class="col-sm-6" > 
											<div class="card card-success">
												<div class="card-header">
													<h4 class="card-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="" aria-expanded="true">
															How does payment work?
														</a>
													</h4>
												</div>
												<div id="collapseOne" class="panel-collapse in collapse " style="">
													<div class="card-body">
														The payout will be released within five business days of the new month. If the commission balance is below $50, it will be held until the next payout period.<br/>

														For any payment issues, contact us with the full details of the issue. We'll review your message and do what we can to resolve it.											
													</div>
												</div>
											</div> 

											<div class="card card-success">
												<div class="card-header">
													<h4 class="card-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="" aria-expanded="true">
															How will I know whether my application has been accepted?
														</a>
													</h4>
												</div>
												<div id="collapseTwo" class="panel-collapse in collapse " style="">
													<div class="card-body">
														Our team will review your application. Affiliate Program Applications are typically processed within two business days. If the application is approved, you'll receive an email with a MyEmailVerifier affiliate connection and some information to help you get started.										
													</div>
												</div>
											</div> 

											<div class="card card-success">
												<div class="card-header">
													<h4 class="card-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="" aria-expanded="true">
															How much can I earn from MyEmailVerifier's Affiliate  Program?
														</a>
													</h4>
												</div>
												<div id="collapseThree" class="panel-collapse in collapse " style="">
													<div class="card-body">
														Your profits are limitless! It all depends on you, how many sales you would generate through your affiliate link. 
													</div>
												</div>
											</div> 										
										</div>


										<div class="col-sm-6" > 
											<div class="card card-success">
												<div class="card-header">
													<h4 class="card-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="" aria-expanded="true">
															What is the MyEmailVerifier Affiliate Program? 
														</a>
													</h4>
												</div>
												<div id="collapseFour" class="panel-collapse in collapse " style="">
													<div class="card-body">
														The program is free to join and monetizes your audience and lets you earn commission on each successful signup. <br/>

														The MyEmailVerifier Affiliate Program includes entrepreneurs, influencers, and content creators who inspire and teach their audience about entrepreneurship with MyEmailVerifier. 										
													</div>
												</div>
											</div> 											


											<div class="card card-success">
												<div class="card-header">
													<h4 class="card-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="" aria-expanded="true">
															What is the payment channel?
														</a>
													</h4>
												</div>
												<div id="collapseFive" class="panel-collapse in collapse " style="">
													<div class="card-body">
														The MyEmailVerifier Affiliate Program supports only PayPal for affiliate payouts.
													</div>
												</div>
											</div> 


											<div class="card card-success"> 
												<div class="card-header">
													<h4 class="card-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" class="" aria-expanded="true">
															Do you still have doubts about MyEmailVerifier's affiliate program?
														</a>
													</h4>
												</div>
												<div id="collapseSix" class="panel-collapse in collapse " style="">
													<div class="card-body">
														Don't hesitate to reach out to us! We are always ready to help you. Drop us an email at affiliates@myemailverifier.com, and we will get back to you as soon as possible.
													</div>
												</div>
											</div> 										
										</div>
									</div>        
								</div>                                  
							</div> 
						</div>
						<p>&nbsp;</p>

					</div>

				</section>
				

			</div><!-- /.container-fluid -->
		</section>        
	</div>     

</template>

<style type="text/css">
	.blue-purple-header {background: #fff;}
	.padding-tb50 {padding: 50px 15px;}
	.padding-tb70 {padding: 70px 15px;}
	.home-headline {font-size: 38px;color: #0255bb;letter-spacing: 0;font-weight: 900;margin-bottom: 15px;}
	.text-white {color: #fff!important;}
	h5 {font-size: 22px;margin-bottom: 10px;font-weight: 600;line-height: normal;}
	.black-font {
    color: #222!important;
}
.white-title {
    margin-bottom: 20px;
}
h3 {
    font-size: 36px;
    margin-bottom: 20px;
    font-weight: 900;
}
ol, ul, dl {
    padding-left: 18px;
}
.ligth-gray-bg {
    background: #fff;
}
</style>

<script>
		
	export default {
		name:'Affiliate',        
		data:function(){
			return{
				affl_signup_url:"https://myemailverifier.idevaffiliate.com/signup.php",
				affl_signin_url:"https://myemailverifier.idevaffiliate.com/index.php",
				requested_text:"",
				caption_create_account:"Request Affiliate Link",
				caption_request_again:"Request Again",
				requested:false,
				show_request_again:false, 
				myafflurl:"xxxxxxxxxxxxxxxxxxxxxxxx",
				//brandimg:img_url+'email-validation-important-2.png',
				img_earnprofit:img_url+'email-validation-important-2.png',
				imgaffsteps:img_url+'affsteps.jpg',

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
			
			

			this.axios
			.get(api_url+'check_affiliate_account')
			.then(function(response){                
				if(response.data.data.affiliate_request == 'y'){ 

					thisObj.requested_text = "We have received your request to create Affiliate link for You.";
					thisObj.requested = true;
					thisObj.show_request_again = true;                        
					if(response.data.data.affiliate_url != ''){
						thisObj.myafflurl = response.data.data.affiliate_url;
						thisObj.requested_text = "";
						thisObj.show_request_again = false;
					}                                        
				}
				
			});
			
		},          
		methods:{
			goto(refName) {					
		        var element = this.$refs[refName];
		        //console.log(element);
		        var top = element.offsetTop;
		        window.scrollTo(0,0);
		    },
			createAfflAccount(){

				if(!confirm("Request for Affiliate?")){
					return false;
				}
				
				var thisObj = this;

				this.axios
				.get(api_url+'requestAfflLink')
				.then(function(response){
					if(response.data.status == '1'){
						thisObj.requested_text = "We have received your request to create Affiliate link for You.";
						thisObj.requested = true;
						thisObj.show_request_again = true; 
					}
				});
			}                
		}       
	}
</script>