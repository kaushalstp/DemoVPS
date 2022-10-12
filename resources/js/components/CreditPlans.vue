<template> 
	<div>		
		<section class="content-header">   
			<div class="container-fluid">
				<div class="row">
					<span class="col-sm-12 p-2 mb-1 rounded text-white" style="background-color:green;font-size: 18px;" > 
						<!-- Hurry! buy 1 Million credits for $399. Apply coupon code <b>MEVSPL1MLN</b> -->
						<img :src="newpromoimg"  width="48" />
						Now get 1 Million @ $299. Limited time promotion. Get it now!
						&nbsp;&nbsp; <span style="border:2px dashed gold;padding: 2px;">MEVSPL1MLN</span>
					</span>
				</div>

				<div class="row mb-2">
					<div class="col-sm-12">
						<h1>Buy Credits</h1><br/>						
						<h5>Credits will never expire.</h5> 					
					</div>	          
				</div>
			</div><!-- /.container-fluid --> 			

			<div class="callout callout-danger notes "> 
				<p>Please do not close or refresh window after payment. It will be automatically redirected within 10 seconds.</p>

				<p>There might be need of additional verification via OTP in case of PayPal and Registered email are different. This checkpoint is necessary to avoid unauthorised payments.
				</p>

				<p>Please clear cache and try in incognito mode of your browser if your paypal window doesn't open.</p>

			</div>	      
		</section>


		<!-- Main content -->

		<section class="content page-creditplans">
			<div class="container-fluid">

				<div class="row" >
					<div class="col-sm-6" >
						<div class="card card-default">
							<div class="card-header">
								<h3 class="card-title">					                  
									Custom Quantity  
								</h3>
							</div>
						  <!-- /.card-header -->
							<div class="card-body">
								
								<form role="form">
									<div class="form-group">
										<label for="exampleInputEmail1">No of Emails</label> 
										<small>(Minimum 2000 credits required)</small> 
										<input type="number" name="no_of_emails" id="no_of_emails" placeholder="" min="2000" value="2000" class="form-control" 
										v-model="no_of_emails" @keyup="chechmincredit" @change="validateCredits" >
									</div> 
									<p>
										<span class="payable_amt_txt" v-html="getfixprice" >											
										</span>
										
										<div class="alert alert-success alert-dismissible cls_coupon_applied" v-show="show_applied_coupon"  >
											{{ msg_coupon_applied }}	 	                       
										</div>
									</p>												
								</form>

									<form role="form">
									  
										<div class="form-group">					                		
											<label for="exampleInputEmail1"></label>						                    
											<div class="row" >
												<div class="col-sm-8" >
												   <input type="text" class="form-control" name="coupon_code" 
													id="coupon_code" placeholder="Coupon Code"  
													v-model="coupon_code"  >
													
													<a href="javascript:void(0);" @click="removecoupon" v-show="showremovecoupon" style="color:red;" >
														Remove Coupon code
													</a>
												</div>

												<div class="col-sm-4" > 
													<input v-if="!applyCouponDisable" type="button" class="btn btn-primary" 
													value="Apply" @click="applyCoupon" />							                    	
												</div>
											</div> 
										</div>				                  			              		
										<div class="form-group"> 					                  					                	<div class="row" >
												<div class="col-sm-8" >										
													
													<div v-bind:class="{ disableCheckout: isDisableCheckout }" >

														<paypal-checkout 														
															v-bind:amount="payable_amount"
															currency="USD"
															:client="paypal"
															env="production" 	    
															notify-url="https://client.myemailverifier.com/paypalnotify"
															v-on:payment-completed="paymentcompleted"                            
															v-on:payment-authorized="paymentauthorized" 
														>
														</paypal-checkout>
													</div>
												</div> 												
											</div>

											<div class="row" >

												<div class="col-sm-12" >
													<span>
														<br/>
							                            <img :src="dwnldlogo" width="24" /> 
							                            <span style="color:red;font-size:14px;" >
							                            	Please do not close or refresh the page for 10 seconds after payment.
							                            </span>
							                            <br/>

							                            <img :src="dwnldlogo" width="24" /> 
							                            <span style="color:red;font-size:14px;" >
							                            	Please clear cache and try in incognito mode of your browser if your &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; paypal window doesn't open.
							                            </span>
							                            <br/><br/>
							                        </span>
						                    	</div>
												
												<div class="col-sm-12" >

													<ul class="list-group">
  														<li class="list-group-item list-group-item-success">
  															
  															How to use coupon code?  <a  target="_blank" href="https://myemailverifier.com/blog/how-to-complete-payment-with-a-creditcard-using-paypal-at-myemailverifier-com/">(Click for Instructions)</a>  															
  														</li>

  														<li class="list-group-item list-group-item-success">
  															
  															Want to pay with Credit Card?  <a target="_blank" href="https://myemailverifier.com/blog/how-to-complete-payment-with-a-creditcard-using-paypal-at-myemailverifier-com/">(Click for Instructions)</a>
  															
  														</li>

  													</ul>	

													<br><br>
													
												</div>

												<!-- <div class="col-sm-6" >

													<ul class="list-group">

  													</ul>																										
												</div> -->

											</div>

										</div>

																																			 
									</form>						                
							</div>
							  <!-- /.card-body -->
						</div>
					</div>

					<div class="col-sm-6" >
						<div class="card card-default mevclient">
							<div class="card-header">
								<h3 class="card-title">					                  
									We are client friendly!
								</h3>
							</div>
						  <!-- /.card-header -->
							<div class="card-body">
								
							<div class="box box-solid">											
								<div class="box-body">
									
										<b-carousel
										  id="carousel-1"
										  v-model="slide"
										  :interval="4000"
										  controls
										  indicators
										  background="#ababab"									      
										  style="color:#000;"
										  img-width="800"
										  img-height="400"
										  @sliding-start="onSlideStart"
										  @sliding-end="onSlideEnd"
										>
									   
										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													<span class="clientthumb" >		
														<img :src="nathjimg"  />
													</span>
													<div class="client-detail">
														<h4>Nath J <small class="clientdesg" > (Lead Web Designer) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>29-Nov-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>My Email Verifyer is an email verification service that allows you to verify email lists. The reason I chose to use the service was simply because You can verify small lists.</p>
												<p>
													<a href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-3763042" >Read More</a>
												</p>
												</div>

											</div>									        
										</b-carousel-slide>


										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													<span class="clientthumb" >		
														<img :src="hmsimg"  />
													</span>
													<div class="client-detail">
														<h4>HM S 
															<small class="clientdesg" > 
																(Sr. Researcher & producer) 
															</small>
														</h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>29-Nov-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>I like myemailverifier.com because of it perfection of email verification that is almost 99%. They have very very process of verification and they do this job within a very short time.</p>
												<p>
													<a href="#" >Read More</a>
												</p>
												</div>
											</div>									        
										</b-carousel-slide>

										
										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													
													<div class="client-detail">
														<h4>Stephanei N <small class="clientdesg" > 
														(Web Design / Tech Support) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>8-Nov-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>I like myemailverifier.com because of it perfection of email verification that is almost 99%. They have very very process of verification and they do this job within a very short time.</p>
												<p>
													<a href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-3612359" >Read More</a>
												</p>
												</div>

											</div>									        
										</b-carousel-slide>

										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													<span class="clientthumb" >		
														<img :src="kirbyzimg"  />
													</span>
													<div class="client-detail">
														<h4>Kriby Z <small class="clientdesg" > 
														(Founder & Creative Director) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>22-Aug-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>I love the ease of use in the software, you simply just upload your list, choose whether you want "relax" or "rapid" and wait a little of time for them to verifier, download and it's all good to go!...</p>
												<p>
													<a target="_blank" href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-4124960" >Read More</a>
												</p>
												</div>
											</div>									        
										</b-carousel-slide>


										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													
													<div class="client-detail">
														<h4>Marcus G<small class="clientdesg" > 
														(Business Promotion Executive) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>22-Aug-2020</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>The accuracy of the email list validation is 96% for my email list. Also, I had faced an issue in getting started hence contacted the support team and Brenda was fast enough to help me get started.</p>
												<p>
													<a  target="_blank" href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-3039172" >Read More</a>
												</p>
												</div>
											</div>									        
										</b-carousel-slide>

										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 									   				
													<div class="client-detail">
														<h4>Araz M <small class="clientdesg" > 
														(Investment Management) </small>
														</h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>2-Dec-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													<p>this tool is just awesome, not only its cheaper than its competitors, but it has a 98% accuracy which is higher than other tools.</p> 
													<p>
														<a  target="_blank" href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-3786423" >Read More</a>
													</p>
												</div>
											</div>									        
										</b-carousel-slide>

										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												
													<div class="client-detail">
														<h4>Vlad R <small class="clientdesg" > 
														(Small Business) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>25-Nov-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>I have tried a couple of email verification services on a trial period and realize that MyEmailVerifier is one of the stands out above its competitors.</p>
												<p>
													<a  target="_blank" href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-4123520" >Read More</a>
												</p>
												</div>
											</div>									        
										</b-carousel-slide>



										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
													<span class="clientthumb" >		
														<img :src="scottimg"  />
													</span>
													<div class="client-detail">
														<h4>Scott A <small class="clientdesg" > 
														(Marketing Team Lead) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>25-Nov-2019</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>This platform gives high level with reliable and dependable results, your list is broken down accordingly</p>
												<p>
													<a  target="_blank" href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-3746003" >Read More</a>
												</p>
												</div>

											</div>									        
										</b-carousel-slide>


										<b-carousel-slide  :img-src="whitebgimg">
											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 									   				
													<div class="client-detail">
														<h4>Administrator in Computer & Network Secutiry <small class="clientdesg" > (Small Business) </small></h4>
														<ul class="client-reting">
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
															<li><i class="fa fa-star"></i></li>
														</ul>
														<p>22-sep-2020</p>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-12" style="padding: 0;"> 
												<p>I like the way the website works and the ability to filter thru all the emails in a list</p>
												<p>
													<a  target="_blank" href="https://www.g2.com/products/myemailverifier/reviews/myemailverifier-review-3728091" >Read More</a>
												</p>
												</div>
											</div>									        
										</b-carousel-slide>	
									</b-carousel>								
								</div>            
							</div>	

																
							</div>
							  <!-- /.card-body -->
						</div>
					</div>

				</div>		    	
				<br>
				<div class="row" >
					<div class="col-sm-12">
						<h1>Pay as you Go <small>(One time)</small></h1>
					</div>	          	
				</div>


				<div class="row" >
					
					<!-- <div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  40% Off
								</div>
							</div> 
							<b>500 credits</b>
							<br>   
							<big> $ 1.44 </big> <small><strike class="baseprice" >($2.4)</strike></small>	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<span class="plancoupon"><span class="plancouponcode">MEV40F2019</span></span>
							</div>		 

							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary" 

									@click="purchasemyplan(500,1.44,'MEV40F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal" >
										Buy Now
									</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	 -->

					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  40% Off
								</div>
							</div> 
							<b>5000 credits</b>
							<br>   
							<big> $ 12.06 </big> <small><strike class="baseprice" >($21.00)</strike></small>
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<span class="plancoupon"><span class="plancouponcode">MEV40F2019</span></span>
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"

									@click="purchasemyplan(5000,12.06,'MEV40F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"	

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>		             	


					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  40% Off
								</div>
							</div> 
							<b>10,000 credits</b>
							<br>   
							<big> $ 21.6 </big> <small><strike class="baseprice" >($36.00)</strike></small>	                      	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<span class="plancoupon"><span class="plancouponcode">MEV40F2019</span></span>
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"

									@click="purchasemyplan(10000,21.60,'MEV40F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	
	
					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  spl off
								</div>
							</div> 
							<b>50,000 credits</b>
							<br>   
							<big> $ 69.00 </big> <small><strike class="baseprice" >($120)</strike></small>	                      	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<p>&nbsp;</p>
								<!-- <span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span> -->
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"

									@click="purchasemyplan(50000,69,'MEV50F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	

					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  spl off
								</div>
							</div> 
							<b>100,000 credits</b>
							<br>   
							<big> $ 89 </big> <small><strike class="baseprice" >($240)</strike></small>	                      	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<p>&nbsp;</p>
								<!-- <span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span> -->
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"

									@click="purchasemyplan(100000,89,'MEV50F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	
					
					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  spl off
								</div>
							</div> 
							<b>250,000 credits</b>
							<br>   
							<big> $ 129 </big> 	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<p>&nbsp;</p>
								<!-- <span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span> -->
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"

									@click="purchasemyplan(250000,129,'MEV50F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	



					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  SPL OFF
								</div>
							</div> 
							<b>500,000 credits</b>
							<br>   
							<big> $ 149 </big> <small><strike class="baseprice" >($1000)</strike></small>	                      	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<!-- <span class="plancoupon"><span class="plancouponcode">MEV60F2019</span></span> -->
								<p>&nbsp;</p>
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"
									@click="purchasemyplan(500000,149,'MEV60F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	
					

					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  60% Off
								</div>
							</div> 
							<b>1 Million credits</b>
							<br>   
							<big> $ 600 </big> <small><strike class="baseprice" >($1500)</strike></small>	                      	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<span class="plancoupon"><span class="plancouponcode">MEV60F2019</span></span>
							</div>		
							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"
									@click="purchasemyplan(1000000,600,'MEV60F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>


					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 220px">
							<div class="ribbon-wrapper ribbon-lg">
								<div class="ribbon bg-success text-lg">
								  60% Off
								</div>
							</div> 
							<b>5 Million credits</b>
							<br>   
							<big> $ 1800 </big> <small><strike class="baseprice" >($ 4500)</strike></small>	                      	
							<br><br>  	                    	
							1 Credit = 1 Email verification

							<div class="row">
								<span class="plancoupon"><span class="plancouponcode">MEV60F2019</span></span>
							</div>		

							<div class="row">
								<div class="col-sm-2">
								</div>
								<div class="col-sm-8 text-center">
									<button type="button" class="btn btn-block btn-primary"

									@click="purchasemyplan(5000000,1800,'MEV60F2019')"  
									data-toggle="modal" data-target="#purchaseplanmodal"

									>Buy Now</button>
								</div>
								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>
				</div>

				<p>&nbsp;</p>
				<hr/>
				<p>&nbsp;</p> 		        

				<div class="row" >		        	
					<div class="col-sm-12" ref="mynewdiv" >
						<h1>Subscription Plans <small>(Monthly)</small></h1>
					</div>	          	
				</div>

				<div class="row" >
					
					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 200px">	                    	
							<b>5,000 Credits / Mon</b>
							<br>   
							<big> <b>$ 10</b> </big>
							<br><br>  	                    	
							1 Credit = 1 Email verification
							<br><br>
							
							<div class="row"> 
								<div class="col-sm-2"> 
								</div>

								<div class="col-sm-8 text-center"  >
									
									<div v-show="planinfo.is_subscribe_mev5k_plan" >
										<h4 class="lblplanactive"  >Active</h4>									
									</div>
									

									<div v-show="!planinfo.is_subscribe_mev5k_plan" >										
										<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
											<input type="hidden" name="cmd" value="_s-xclick">
											<input type="hidden" name="custom" v-model="plan_mev5k_custom_value" />
											<input type="hidden" name="hosted_button_id" value="KGL4EV56CRZTS">
											<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
											<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
										</form>
									</div>

								</div>

								<div class="col-sm-2">
								</div>
							</div>  	

							<div class="row"> 
								<div class="col-sm-2"> 
								</div>

								<div class="col-sm-8 text-center"  >
																		
									<div v-show="planinfo.is_subscribe_mev5k_plan" >
										<h4 class="lblcancelplan" 
										@click="unsubscribePlan(planinfo.mev5k_plan_subscription_id,'mev5k')" >
											Cancel
										</h4>
									</div>								
								</div>

								<div class="col-sm-2">
								</div>
							</div>

						</div>
					</div>


					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 200px">	                    	
							<b>10,000 Credits / Mon</b>
							<br>   
							<big> <b>$ 18</b> </big>
							<br><br>  	                    	
							1 Credit = 1 Email verification
							<br><br>
							
							<div class="row">
								<div class="col-sm-2">
								</div>

								<div class="col-sm-8 text-center">	

									<div v-show="planinfo.is_subscribe_mev10k_plan" >
										<h4 class="lblplanactive"  >Active</h4>										
									</div>


									<div v-show="!planinfo.is_subscribe_mev10k_plan" >
										<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="custom" v-model="plan_mev10k_custom_value" />
<input type="hidden" name="hosted_button_id" value="4J3PW8E7AL4X6">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
									</div>
								</div>

								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	

					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 200px">	                    	
							<b>50,000 Credits / Mon</b>
							<br>   
							<big> <b>$ 40</b> </big>
							<br><br>  	                    	
							1 Credit = 1 Email verification
							<br><br>
							
							<div class="row">
								<div class="col-sm-2">
								</div>

								<div class="col-sm-8 text-center">


									<div v-show="planinfo.is_subscribe_mev50k_plan" >
										<h4 class="lblplanactive" >Active</h4>										
									</div>
									

									<div v-show="!planinfo.is_subscribe_mev50k_plan" >
										<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="custom" v-model="plan_mev50k_custom_value" />
<input type="hidden" name="hosted_button_id" value="W74ZF65QC79VC">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
									</div>
								</div>

								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	

					<div class="col-sm-3">
						<div class="position-relative p-3 creditplanbox" style="height: 200px">	                    	
							<b>100,000 Credits / Mon</b>
							<br>   
							<big> <b>$ 99</b> </big>
							<br><br>  	                    	
							1 Credit = 1 Email verification
							<br><br>
							
							<div class="row">
								<div class="col-sm-2">
								</div>

								<div class="col-sm-8 text-center">

									<div v-show="planinfo.is_subscribe_mev100k_plan" >
										<h4 class="lblplanactive" >Active</h4>										
									</div>
									

									<div v-show="!planinfo.is_subscribe_mev100k_plan" >
											<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="custom" v-model="plan_mev100k_custom_value" />
	<input type="hidden" name="hosted_button_id" value="YBXHG3JFP49E6">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
									</div>	
								</div>

								<div class="col-sm-2">
								</div>
							</div>  	
						</div>
					</div>	
				</div>
			</div>
			  <!-- /.container-fluid -->
				<p>&nbsp;</p>
				<p>&nbsp;</p>
		</section>    

		<!--  Verify payer OTP -->
			<div class="" >
				<div class="modal fade" id="otpVerifyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
							
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">
								Verify Paypal Email</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

								<div class="modal-body">  
									<div class=""   >
										
										<form  name="frmVerifyOTP" @submit.prevent="submitVerifyOTP" 
										id="frmVerifyOTP" >
											<input type="hidden"  name="user_email" 
											id="invoice_id" v-model="otpinfo.invoice_id" />

											<div class="" >
												<span style="font-size: 14px;color:green;" 
												v-html="msg_otp_verification" >
													
												</span>
											</div>

											<div v-html="msg.new_otp" >
												
											</div>		

											<div class="row" >
												<div class="col-12 col-sm-8 col-md-8 col-lg-8">
													<div class="form-group">                                        
														<input type="text" class="form-control" 
														id="new_otp" 
														v-model="otpinfo.new_otp"  name="new_otp" placeholder="Enter Code" 
														  />
													</div>
												</div>
												<div class="col-12 col-sm-4 col-md-4 col-lg-4">
													<button type="submit" class="btn btn-primary d-block w-100 verify_btn" style="font-size: 16px;">Verify Code</button>
												</div>
											</div>
										</form>        
									</div>									
								</div>

								<div class="modal-footer d-block">
									<button class="btn btn-primary" @click="resendCode" style="font-size: 18px;">Resend Code</button>
									<button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>                            
								</div>                    
						</div>
					  </div>
				</div>
			</div>

		<!-- ******** -->


		<!-- Payment Confirmation -->

		<!--  Verify payer OTP -->
			<div class="" >
				<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
							
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">
									Payment Confirmation</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>

								<div class="modal-body">  
									<p>
										Thank you for your payment. According to PayPal we need further confirmation that the payment is authorised. 
									</p>		
									<p>

										Please send below details to <b>support@myemailverifier.com</b>
										<br/><br/>

						                - Your paypal email address.<br/>
						                - Your mobile number associated with PayPal account.<br/>
						                - Any government issued Photo ID (you can strike out sensitive information).<br/>
									</p>
								</div>


								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                            
								</div>                    
						</div>
					  </div>
				</div>
			</div>

		<!-- ******** -->


		<!-- *********** -->

		<!-- Buy Plan popup  -->

		<div class="modal fade" id="purchaseplanmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  	<div class="modal-dialog" role="document">
				<div class="modal-content">
			  	<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><i><b>Pay Now</b></i></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
			  	</div>

			  	<div class="modal-body">              		

					<div class="row" >
								
						<div class="col-sm-4" >
							<b> {{ plan_credits }} Credits </b> 
						</div>	

						<div class="col-sm-3" >              					
							<h3 class="planamttitle" >$ {{ plan_amount }}</h3>			              			
						</div>

						<div class="col-sm-5" >
							<paypal-checkout 
									v-bind:amount="plan_amount"
									currency="USD"
									:client="paypal"
									env="production" 
									notify-url="https://client.myemailverifier.com/paypalnotify"
									v-on:payment-completed="purchaseplancompleted"                                                        
							>
							</paypal-checkout> 										
						</div>	              				
					</div>                                           			
			  	</div>
			  <div class="modal-footer">                
				<!-- <button type="button" class="btn btn-primary" data-dismiss="modal" >Cancel</button> -->
			  </div>
				</div>
		  	</div>
		</div>
		<!-- **** -->
	</div>    
</template>

<style>
	.verify_btn{background-color: #2135cc;font-size: 22px;font-weight: bold;}
    .verify_btn:hover{background-color: #2135cc;}

	.swal2-title{
		font-size: 16px !important;
	}

	.selectfmodetext{
		color: red;
		font-size: 12px;
		font-weight: bold;	
	}

	.disableCheckout{
		opacity: 0.3;
		pointer-events:none;
	}
	.lblplanactive{
		color: white;
		background-color: green;
		border-radius: 3px;
		padding: 2px 0px;
	}
	.lblcancelplan{
		color: white;
		background-color: darkred;
		border-radius: 3px;
		padding: 2px 0px;
		font-size: 15px;
	}
	.lblcancelplan:hover{
		cursor: pointer;
	}
	.planamttitle{
		color: brown;
		font-weight: bold;
	},
	.carousel-indicators{
		display: none;
	}
	.plancoupon{
		float:left;
		width: 100%;
		text-align: center;
		margin: 15px 0;
	}

	.plancouponcode {
		padding: 5px;
		border: 1px dashed darkgoldenrod;
	}
	
	.baseprice{
		color:red;
		font-size: 13px;
	}

	.content-header{
		/*background-color: #d3d3d3;*/
		background-color: #f4f6f9;
	}

	.page-creditplans{
		/*background-color: #d3d3d3;	*/ 
		background-color: #f4f6f9;
	}

	.notes{
		color:#7b4392;	
	}
	.notes p{
		font-size: 18px;
	}

	.highlight{
		color:#00834f;
		font-size: 16px;
		font-weight: bold;
	}
	.page-creditplans .bg-gray{ 
		border-radius: 5px;				
	}

	.creditplanbox{
		background-color: lightgray !important;
		border-radius: 5px;				
		margin:10px 0 0 0;
	}
	.creditplanbox b{
		color:brown;
	}

	h4,p{
		/*margin-left:15px;*/
	}
	.clientthumb img{
		width: 50px;				
		height: 50px;				
	}

	.clientdesg{
		font-size: 13px;
	}

	.mevclient{ min-height: 386px; }
	.mevclient .carousel-item{ height: 286px; }
	.mevclient .clientthumb{ display: inline-block; width: 60px; height: 60px; overflow: hidden; border-radius: 50%; }
	.mevclient .clientthumb img{ object-fit: fill; height: 100%; width: 100%; }
	.mevclient img{ width: auto!important; display: inline-block !important; height: 100%; }
	.mevclient .carousel-caption{ bottom: 0; padding: 10px 0; }
	.mevclient .carousel-caption h4{ color: #000; margin-left: 0; margin-top: 0; font-size: 18px; font-weight: bold; line-height: 20px; }
	.mevclient .carousel-caption p{ color: #262a2e; margin-left: 0; font-size: 15px; line-height: 20px; }
	.mevclient .carousel-indicators{ margin-bottom: 0; }
	.mevclient .client-detail{ display: inline-block; width: calc(100% - 64px); padding-left: 10px; }
	.mevclient .client-detail p{ font-size: 13px; }
	.mevclient .client-reting{ list-style: none; display: inline-block; margin: 0; padding: 0; }
	.mevclient .client-reting li{ display: inline-block; color: #000; font-size: 16px; }

	.mevclient a{
		font-weight: bold;
	}			
			

	.card-title {
		margin-bottom: -0.25rem;
		text-align: center;
		width:100%;
	}
	.amtafterdiscount {
		color: green;
		background-color: #f1f1f1;
		padding: 5px;
		border: 2px dashed blue;
	}

	.amtbeforediscount{
		color:red;
		text-decoration: line-through;
	}

	.amtbeforediscountnew{
		color:red;
		text-decoration: line-through;
	}

</style>

<script>

	export default {
		name:'CreditPlans',
		mounted(){
			//alert(localStorage.mevid);
			var thisObj = this;        
			
			this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    //window.location=app_url;
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }

                if(response.data.status == 1 || response.data.status == '1'){ 
                                                    
                    localStorage.setItem('mevid',response.data.data.userid);
                }
            });


			/*this.axios.get(api_url+'getusername')
            .then(function(response){
                                
                thisObj.userid = response.data.data.userid;
                
            });
            console.log("uid: "+thisObj.userid);*/

			//thisObj.userid = localStorage.mevid;
			thisObj.test_plan_custom_value = thisObj.userid+"|mevweekly"; 
			thisObj.plan_mev5k_custom_value = thisObj.userid+"|mev5k"; 
			thisObj.plan_mev10k_custom_value = thisObj.userid+"|mev10k"; 
			thisObj.plan_mev50k_custom_value = thisObj.userid+"|mev50k"; 
			thisObj.plan_mev100k_custom_value = thisObj.userid+"|mev100k"; 
			

			this.msgsuccess = localStorage.msgsuccess;            
			this.textmsg = localStorage.textmsg;       
			if(localStorage.textmsg == ''){
				this.msgsuccess = false;
			}



			this.axios
			.get(api_url+'getSubscriptionDetails')
			.then(function(response){
				//console.log(response);                        
			   
				if(response.data.status==1 || response.data.status=='1'){
					thisObj.planinfo = response.data.data.planinfo;
					

					/*thisObj.is_subscribe_test_plan = response.data.planinfo.is_subscribe_test_plan;
					thisObj.is_subscribe_5k_plan = response.data.planinfo.is_subscribe_5k_plan;
					thisObj.is_subscribe_10k_plan = response.data.planinfo.is_subscribe_10k_plan;
					thisObj.is_subscribe_50k_plan = response.data.planinfo.is_subscribe_50k_plan;
					thisObj.is_subscribe_100k_plan = response.data.planinfo.is_subscribe_100k_plan;*/                           
				}				
			});
		},
		data:function(){        	
			return{  
				dwnldlogo:img_url+'download-arrow.gif',
				newpromoimg:img_url+'newprom.jpg',
				isDisableCheckout:false,
				applyCouponDisable:false,
				planinfo:{  
					is_subscribe_test_plan:false,
					is_subscribe_5k_plan:false,
					is_subscribe_10k_plan:false,
					is_subscribe_50k_plan:false,
					is_subscribe_100k_plan:false,					
				},	
				msg:[],
				otpinfo:{
					new_otp:'',
					invoice_id:'',
				},        	
				userid:0,    		
				off_amount:'',
				oldprice:0.0048,
				msg_otp_verification:"",
				test_plan_custom_value:"",
				plan_mev5k_custom_value:"",
				plan_mev10k_custom_value:"",
				plan_mev50k_custom_value:"",
				plan_mev100k_custom_value:"",
				whitebgimg:img_url+'white-slide.jpg',
				price_per_credit:0.0048,        			
				no_of_emails:2000,
				paypalbtn:img_url+"paypalbtn.png",
				coupon_code:'', 
				plan_coupon_code:'', 
				show_applied_coupon:false,
				msg_coupon_applied:'',
				discount_off:0,
				splonemdiscount:'n',
				splpricediscount:'n',
				slide: 0,
				sliding: null,
				nathjimg:img_url+'nath_j.jpeg',
				hmsimg:img_url+'hms.jpg',
				kirbyzimg:img_url+'kirby-z.png', 
				scottimg:img_url+'scott.jpg',       
				showremovecoupon:false,
				plan_amount:0,
				plan_credits:0,
				paypal: {
				  sandbox:'AbDMPzSz5gKBdUMMoU7bNv0nYd9qoeIIizYsEGx8bCoQr2asBgH4b8VUXU36Fp7RrAugbhSZX5F7UaZL',
				  production:'AbDMPzSz5gKBdUMMoU7bNv0nYd9qoeIIizYsEGx8bCoQr2asBgH4b8VUXU36Fp7RrAugbhSZX5F7UaZL'
				}, 						
				paypal_demo: {
				  sandbox:'AS-9_ktSUONpwGyOqjXlipNTdWsaAoydOjOaBXcdqe0EdljHXh1RHHf-dWSlI3tAys6x-MgBdIqV_GXL',
				  production:'AS-9_ktSUONpwGyOqjXlipNTdWsaAoydOjOaBXcdqe0EdljHXh1RHHf-dWSlI3tAys6x-MgBdIqV_GXL'
				}, 		
			}       	
		},
		computed :{
			payable_amount:function(){
				
				if(this.splonemdiscount == 'y'){
					return (299);
				}				

				if(this.discount_off>0){

					var tmpans = ((this.price_per_credit * this.no_of_emails).toFixed(2));
					var offamount  = (( tmpans * this.discount_off ) / 100);
					var newans = (tmpans - offamount);
					newans = newans.toFixed(2);
					return newans;
				}else{
					//var newans = ((this.price_per_credit * this.no_of_emails).toFixed(2)); 
					return ((this.price_per_credit * this.no_of_emails).toFixed(2)); 
					//return newans;
				}   				
			},		
			getfixprice:function(){  
			

				if(this.splonemdiscount == 'y'){
					
					return "<span class='amtafterdiscount' >$ 299</span>";
				}

				if(this.discount_off>0){

					var tmpans = ((this.price_per_credit * this.no_of_emails).toFixed(2));
					var offamount  = (( tmpans * this.discount_off ) / 100);

					return "<span class='amtbeforediscount' >$ "+tmpans+"</span> &nbsp;&nbsp; <span class='amtafterdiscount' >$ "+this.payable_amount+"</span>";
				}else{

					if(this.splpricediscount == 'y'){

						var tmpans = ((this.oldprice * this.no_of_emails).toFixed(2));
						//var offamount  = (( tmpans * this.discount_off ) / 100);

						return "<span class='amtbeforediscount' >$ "+tmpans+"</span> &nbsp;&nbsp; <span class='amtafterdiscount' >$ "+this.payable_amount+"</span>";
					}else{
						return "$ "+this.payable_amount;
					}

					//return ((this.price_per_credit * this.no_of_emails).toFixed(2));						
				}					
			}
		},       	       
		methods:{
			resendCode(){
				if(!confirm('Resend Verificaton code?')){
					return false;
				}

				var thisObj = this;        
				var invoice_id = this.otpinfo.invoice_id;

				this.axios.post(api_url+'resendCode',{						
					invoice_id:invoice_id,						
				})
				.then((response)=>{
					
					
				});


				alert('Verification code sent successfully!');
						
			},
			unsubscribePlan(subscrid="",planname=""){				
				var thisObj = this;


				if(!confirm("Are you sure you want to Unsubscribe this plan ?")){
					return false;	
				}

				this.axios
				.post(api_url+'cancelMySubscription',{
					subscrid:subscrid,						
				})
				.then(function(response){
					
					if(response.data.status == '1'){
						if(planname == 'mev5k'){
							thisObj.planinfo.is_subscribe_mev5k_plan = false;
						}

						alert(response.data.msg);
					}			
				});			
			},
			validateCredits(e){
				e.preventDefault();
				
				if(this.no_of_emails.trim() < 2000){
					//this.no_of_emails = 2000;										
				}							
			},
			chechmincredit(e){

				//alert('helooo');
				this.applyCouponDisable=false;				
				this.coupon_code = '';

				var thisObj = this;
				thisObj.show_applied_coupon = false;
				thisObj.msg_coupon_applied='';
				thisObj.discount_off = 0; 
				thisObj.showremovecoupon=false; 		           			
				this.splpricediscount='n';	

				if(this.no_of_emails != 1000000){ 
					this.splonemdiscount = 'n';
					this.showremovecoupon=false; 

					this.show_applied_coupon = false;
					this.msg_coupon_applied='';					
					this.coupon_code = '';
				}

				if(this.no_of_emails.trim() < 2000){
					//this.no_of_emails = 500;
					//this.price_per_credit = 0;
					this.isDisableCheckout=true;
				}else{
					this.isDisableCheckout=false;
				}

				if(this.no_of_emails < 2000){
					this.price_per_credit = 0.0048;
				}				

				if(this.no_of_emails >= 2000){
					this.price_per_credit = 0.0042;
				}

				if(this.no_of_emails >= 10000){
					this.price_per_credit = 0.0036;
				}

				if(this.no_of_emails >= 50000){
					this.price_per_credit = 0.0024;
				}

				if(this.no_of_emails >= 500000){
					this.price_per_credit = 0.0020;
				}

				if(this.no_of_emails >= 1000000){
					this.price_per_credit = 0.0015;
				}

				if(this.no_of_emails >= 2000000){
					this.price_per_credit = 0.001125;
				}

				if(this.no_of_emails >= 5000000){
					this.price_per_credit = 0.0009;
				}			

				if(this.no_of_emails == 50000){

					this.splpricediscount='y';
					this.oldprice = this.price_per_credit;
					
					this.price_per_credit = 0.00138;					

					this.show_applied_coupon = true;
					this.msg_coupon_applied='Special Price Discount applied. Lowest price for the day.';					
					this.coupon_code = "MEVSPLPRICE";
					this.applyCouponDisable=true;
				}								

				if(this.no_of_emails == 100000){

					this.splpricediscount='y';
					this.oldprice = this.price_per_credit;
					
					this.price_per_credit = 0.00089;					

					this.show_applied_coupon = true;
					this.msg_coupon_applied='Special Price Discount applied. Lowest price for the day.';					
					this.coupon_code = "MEVSPLPRICE";
					this.applyCouponDisable=true;
				}								


				if(this.no_of_emails == 250000){

					this.splpricediscount='y';
					this.oldprice = this.price_per_credit;
					
					this.price_per_credit = 0.000516;					

					this.show_applied_coupon = true;
					this.msg_coupon_applied='Special Price Discount applied. Lowest price for the day.';					
					this.coupon_code = "MEVSPLPRICE";
					this.applyCouponDisable=true;
				}								

				if(this.no_of_emails == 500000){

					this.splpricediscount='y';
					this.oldprice = this.price_per_credit;
					
					this.price_per_credit = 0.000298;					

					this.show_applied_coupon = true;
					this.msg_coupon_applied="Special Price Discount applied. Lowest price for the day.";					
					this.coupon_code = "MEVSPLPRICE";
					this.applyCouponDisable=true;
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
					this.msg['new_otp']  = "<span style='color:red;' >Please enter code</span>";              
				}

				if(this.hasError == false){ 
					var thisObj = this;					
					
					this.axios
					.post(api_url+'verify_payer',{
						invoice_id:thisObj.otpinfo.invoice_id,
						new_otp:thisObj.otpinfo.new_otp						
					})
					.then(function(response){
						console.log(response);                        


						//window.location=app_url+"/Invoice";            							           		
						/*if(response.data.status==0 || response.data.status=='0'){
							thisObj.msgerror = true;            
							thisObj.textmsg=response.data.msg;            
						}*/

						if(response.data.status==1 || response.data.status=='1'){
							alert(response.data.msg);
							window.location=app_url+"/Invoice";   
							                                    
						}else{
							thisObj.msg['new_otp']  = "<span style='color:red;' >Incorrect code</span>";
						} 
					});
				}        
			},
			goto(refName) {
		        var element = this.$refs[refName];
		        //console.log(element);
		        var top = element.offsetTop;
		        window.scrollTo(0, top);
		    },			
			callbtntwo(){
				
			},
			purchasemyplan(credits=0,amount=0,plancouponcode=''){
				this.plan_amount = amount;       			
				this.plan_credits = credits;			
				this.plan_coupon_code = plancouponcode;
			},
			paymentauthorized(event){                        	
				
				var thisObj = this;
				var uid = localStorage.mevid;
			}, 
			purchaseplancompleted(event){                        	
				console.log(event); 

				var thisObj = this;
				var uid = localStorage.mevid;    
				var payer_email = event.payer.payer_info.email;  

				var coupon_applied = '0';
				var applied_code='';				

				coupon_applied = '1';
				applied_code = thisObj.plan_coupon_code;
				

				/* conversation  michanism */

				window.dataLayer = window.dataLayer || [];
				function gtag(){
					dataLayer.push(arguments);
					//console.log("code ran successfully... ");
				}
				gtag('js', new Date());
				gtag('config', 'UA-130160830-1');				

				/* conversation  michanism ends */

				var paidamount = event['transactions'][0]['amount']['total'];

				if(paidamount > 300){
						this.axios.post(api_url+'askPaymentConfirmation',{
						userid:uid,
						credits:thisObj.plan_credits,
						paypal_response:event,
						payer_email:payer_email,
						coupon_applied:coupon_applied,
						applied_code:applied_code,
					})
					.then((response)=>{
						//console.log(response);		
						thisObj.otpinfo.invoice_id = response.data.data.invoice_id;
						thisObj.show_applied_coupon = false;
						thisObj.msg_coupon_applied='';
						thisObj.discount_off = 0;	
						thisObj.showremovecoupon=false; 

						thisObj.msg_otp_verification = response.data.msg;				

						jQuery('#purchaseplanmodal').modal('hide');
						jQuery('#paymentConfirmationModal').modal('show');						       					
					});
				}

				if(paidamount < 300){

					this.axios.post(api_url+'buyCreditsPaymentAuthorized',{
						userid:uid,
						credits:thisObj.plan_credits,
						paypal_response:event,
						payer_email:payer_email,
						coupon_applied:coupon_applied,
						applied_code:applied_code,
					})
					.then((response)=>{
						//console.log(response);		
						thisObj.otpinfo.invoice_id = response.data.data.invoice_id;
						thisObj.show_applied_coupon = false;
						thisObj.msg_coupon_applied='';
						thisObj.discount_off = 0;	
						thisObj.showremovecoupon=false; 

						if(response.data.suspicious_order == 'y'){

							thisObj.$swal('Thank You for purchase. <Br/><Br/> Your credits are not available for use right now. <Br/><Br/>Please provide your government issued photo ID for authentication to support@myemailverifier.com ');

						}else{

							if(response.data.paypal_email_verified == 'y'){

								alert(response.data.msg);
								window.location=app_url+"/Invoice"; 

							}else if(response.data.paypal_email_verified == 'n'){

								thisObj.msg_otp_verification = response.data.msg;				

								jQuery('#purchaseplanmodal').modal('hide');
								jQuery('#otpVerifyModal').modal('show');
							}	
						}          						
					});
				}
			},
			paymentcompleted(event){                        	
				//console.log(event); 

				var thisObj = this;
				var uid = localStorage.mevid;       
				var payer_email = event.payer.payer_info.email; 

				var coupon_applied = '0';
				var applied_code='';				

				if(thisObj.show_applied_coupon){
					coupon_applied = '1';
					applied_code = thisObj.coupon_code;
				}

				/* conversation  michanism */

				window.dataLayer = window.dataLayer || [];
				function gtag(){
					dataLayer.push(arguments);
					//console.log("code ran successfully... ");
				}
				gtag('js', new Date());
				gtag('config', 'UA-130160830-1');				

				/* conversation  michanism ends */

				var paidamount = event['transactions'][0]['amount']['total'];

				if(paidamount > 300){
						this.axios.post(api_url+'askPaymentConfirmation',{
						userid:uid,
						credits:thisObj.no_of_emails,
						paypal_response:event,
						payer_email:payer_email,
						coupon_applied:coupon_applied,
						applied_code:applied_code,
					})
					.then((response)=>{
						//console.log(response);		
						thisObj.otpinfo.invoice_id = response.data.data.invoice_id;
						thisObj.show_applied_coupon = false;
						thisObj.msg_coupon_applied='';
						thisObj.discount_off = 0;	
						thisObj.showremovecoupon=false; 

						thisObj.msg_otp_verification = response.data.msg;				

						jQuery('#purchaseplanmodal').modal('hide');
						jQuery('#paymentConfirmationModal').modal('show');						       					
					});
				}

				if(paidamount < 300){

						this.axios.post(api_url+'buyCreditsPaymentAuthorized',{
						userid:uid,
						credits:thisObj.no_of_emails,
						paypal_response:event,
						payer_email:payer_email,
						coupon_applied:coupon_applied,
						applied_code:applied_code,
					})
					.then((response)=>{
						//console.log(response);		
						thisObj.otpinfo.invoice_id = response.data.data.invoice_id;
						thisObj.show_applied_coupon = false;
						thisObj.msg_coupon_applied='';
						thisObj.discount_off = 0;	
						thisObj.showremovecoupon=false; 

						if(response.data.suspicious_order == 'y'){

							thisObj.$swal('Thank You for purchase. <Br/><Br/> Your credits are not available for use right now. <Br/><Br/>Please provide your government issued photo ID for authentication to support@myemailverifier.com ');

						}else{

							if(response.data.paypal_email_verified == 'y'){

								alert(response.data.msg);
								window.location=app_url+"/Invoice"; 

							}else if(response.data.paypal_email_verified == 'n'){

								thisObj.msg_otp_verification = response.data.msg;				

								jQuery('#purchaseplanmodal').modal('hide');
								jQuery('#otpVerifyModal').modal('show');
							}	
						}          						
					});
				}

				
			}, 
			removecoupon:function(){
				
				if(!confirm("Are you sure you want to remove this coupon?")){
					return false;
				}

				this.show_applied_coupon = false;
				this.msg_coupon_applied='';
				this.discount_off = 0;
				this.coupon_code = "";
				this.showremovecoupon=false; 	 
				this.splonemdiscount = 'n';	           			
			},
			applyCoupon:function(){
				
				var thisObj = this;				
				
				this.axios.post(api_url+'applyCoupon',{
					coupon_code:this.coupon_code,
					no_of_credits:this.no_of_emails,
				})
				.then((response)=>{            		

					if(response.data.status == '1'){

						if(response.data.data.special_discount == 'y'){
							thisObj.splonemdiscount = 'y';
							thisObj.showremovecoupon=true; 
							thisObj.msg_coupon_applied=response.data.msg;
							thisObj.show_applied_coupon = true;
						}else{

							thisObj.show_applied_coupon = true;
							thisObj.msg_coupon_applied=response.data.msg;
							thisObj.discount_off = response.data.data.offper; 
							thisObj.showremovecoupon=true; 		           			
						}					
					}else{            			

						thisObj.show_applied_coupon = false;
						thisObj.msg_coupon_applied='';
						thisObj.discount_off = 0;
						
						this.$toast.open({
							message: 'Invalid Coupon Code',
							type:'error',
							position:'top-right'
						});
					}
				});
			},
			onSlideStart(slide) {
				this.sliding = true;

			},
			onSlideEnd(slide) {
				this.sliding = false;
			}                
		},
		created(){
					
			let self = this;

			/*setInterval(function(){
				this.textmsg = '';                 
				this.msgsuccess = false;
				this.msgerror = false;				 
			}, 5000);*/

			setInterval(function(){
				
				this.axios.get(api_url+'getusername')
	            .then(function(response){
	                
	                //thisObj.username = response.data.data.username;
	                self.userid = response.data.data.userid;

	                self.test_plan_custom_value = self.userid+"|mevweekly"; 
					self.plan_mev5k_custom_value = self.userid+"|mev5k"; 
					self.plan_mev10k_custom_value = self.userid+"|mev10k"; 
					self.plan_mev50k_custom_value = self.userid+"|mev50k"; 
					self.plan_mev100k_custom_value = self.userid+"|mev100k"; 
			                
	                
	            });

	            //console.log(self.userid);

			}, 5000);


		}         
	}
</script>
