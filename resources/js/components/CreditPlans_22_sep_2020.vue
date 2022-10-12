<template>
    <div>
        <section class="content-header">
	      	<div class="container-fluid">
		        <div class="row mb-2">
		        	<div class="col-sm-6">
		            	<h1>Buy Credits</h1><br/>
		            	<h5>Credits will never expire.</h5>
		          	</div>	          
		        </div>
	      	</div><!-- /.container-fluid -->

	      	<div class="callout callout-danger notes "> 
	      		<p>Normally credits will reflect in your account within 10 seconds. If you see your credits are not reflected in your account after payment then please check inbox/spam folder of your PayPal email address. You will find an email regarding your order. Please click on "Confirm Order" link to confirm your order.</p><p>This will happen when your registered email address with MyEmailVerifier is different then your PayPal email address. This checkpoint is necessary to avoid unauthorised payments.</p>
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
										<small>(Minimum 500 credits required)</small> 
										<input type="number" name="no_of_emails" id="no_of_emails" placeholder="" min="500" value="500" class="form-control" v-model="no_of_emails" >
									</div> 
									<p>
										$ {{ getfixprice }}

										<span class="highlight cls_coupon_applied" 
										v-show="show_applied_coupon" >
											{{ msg_coupon_applied }}
										</span>	

									</p>												
								</form>

									<form role="form">
						              
					                	<div class="form-group">

						                    <label for="exampleInputEmail1">Coupon Code</label>
						                    Buy <span class="highlight" >5,000</span> credits for <span class="highlight" >$21.00</span>

						                    <input type="text" class="form-control" name="coupon_code" 
						                    id="coupon_code" placeholder="Coupon Code"  
						                    v-model="coupon_code" @keyup="applyCoupon" >
					                  	</div>				                  			              			 		                  			                  	
					                  	<input type="image" :src="paypalbtn" alt="Submit" width="132" > 
								                 							                                							       
									</form>						                
			              	</div>
				              <!-- /.card-body -->
				        </div>
		    		</div>

		    		<div class="col-sm-6" >
		    			<div class="card card-default">
			              	<div class="card-header">
				                <h3 class="card-title">					                  
				                	Review
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
									   		<div class="row" >
									   			
										   			<div class="col-sm-2" >
										   				<span class="clientthumb" >		
										   					<img :src="nathjimg"  />
										   				</span>

										   			</div>
										   			<div class="col-sm-10" >
										   				<h4>Nath J 
										   					<small class="clientdesg" > (Lead Web Designer) 
										   					</small>
										   				</h4>										   				
										   				<p>ratings	22-sep-2020</p>									   		
										   			</div>									   			
									   		</div>		

									   		<div class="row" >
									   			<div class="col-sm-12" > 
									        	<p>My Email Verifyer is an email verification service that allows you to verify email lists. The reason I chose to use the service was simply because You can verify small lists.</p>
									        	<p>
									        		<a href="#" >Read More</a>
									        	</p>
									        	</div>

									   		</div>									        
								      	</b-carousel-slide>

		<b-carousel-slide  :img-src="whitebgimg">
	        <h1>Nath J <small>Lead Web Designer</small></h1>
	        <p>This is description </p>
      	</b-carousel-slide>

									   <b-carousel-slide  :img-src="whitebgimg">
        <h1>Hello world!</h1>
        <p>This is description </p>
      </b-carousel-slide>

									</b-carousel>

									<!-- <tiny-slider :mouse-drag="true" :loop="false" items="2" gutter="20">
									  	<div>Slider item #1</div>
									  	<div>Slider item #2</div>  
									</tiny-slider>        -->

					            </div>            
         					</div>	

													                
			              	</div>
				              <!-- /.card-body -->
				        </div>
		    		</div>

		    	</div>

		    	<div class="row" >
		    		<div class="col-sm-12" >

			    		<div class="card">
			                <div class="card-body">
			                		
			                	<div class="row" >
				              		<div class="col-sm-5">
							            <h3>Custom Quantity</h3>
							        </div>	          	
				              	</div>

								<div class="row">
									
									<div class="col-sm-6">
										<form role="form">
											<div class="form-group">
												<label for="exampleInputEmail1">No of Emails</label> 
												<small>(Minimum 500 credits required)</small> 
												<input type="number" name="no_of_emails" id="no_of_emails" placeholder="" min="500" value="500" class="form-control" v-model="no_of_emails" >
											</div> 
											<p>
												$ {{ getfixprice }}

												<span class="highlight cls_coupon_applied" 
												v-show="show_applied_coupon" >
													{{ msg_coupon_applied }}
												</span>	

											</p>												
										</form>

										<form role="form">
							              
						                	<div class="form-group">

							                    <label for="exampleInputEmail1">Coupon Code</label>
							                    Buy <span class="highlight" >5,000</span> credits for <span class="highlight" >$21.00</span>

							                    <input type="text" class="form-control" name="coupon_code" 
							                    id="coupon_code" placeholder="Coupon Code"  
							                    v-model="coupon_code" @keyup="applyCoupon" >
						                  	</div>				                  			              			 		                  			                  	
						                  	<input type="image" :src="paypalbtn" alt="Submit" width="132" > 
									                 							                                							        
										</form>		

									</div>


									<div class="col-sm-6">
													
									</div>

								</div>
 								
								

			    			</div> 
			    		</div>		
			    	</div>	
		    	</div>	

		    	<br>

		    	<div class="row" >
              		<div class="col-sm-3">
			            <h1>Popular Plans</h1>
			        </div>	          	
              	</div>



		        <div class="row" >
		        	                  	                  	
                  	<div class="col-sm-3">
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
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      	<b>1000 credits</b>
	                      	<br>   
	                      	<big> $ 2.88 </big> <small><strike class="baseprice" >($4.8)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV40F2019</span></span>
	                      	</div>		

	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      	<b>2000 credits</b>
	                      	<br>   
	                      	<big> $ 5.04 </big> <small><strike class="baseprice" >($8.4)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV40F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      	<b>20,000 credits</b>
	                      	<br>   
	                      	<big> $ 43.2 </big> <small><strike class="baseprice" >($72)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV40F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
		                          50% Off
		                        </div>
	                      	</div> 
	                      	<b>50,000 credits</b>
	                      	<br>   
	                      	<big> $ 60.00 </big> <small><strike class="baseprice" >($120)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
		                          50% Off
		                        </div>
	                      	</div> 
	                      	<b>100,000 credits</b>
	                      	<br>   
	                      	<big> $ 120 </big> <small><strike class="baseprice" >($240)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
		                          50% Off
		                        </div>
	                      	</div> 
	                      	<b>250,000 credits</b>
	                      	<br>   
	                      	<big> $ 300 </big> <small><strike class="baseprice" >($600)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      	<b>500,000 credits</b>
	                      	<br>   
	                      	<big> $ 400 </big> <small><strike class="baseprice" >($1000)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      	<b>2 Million credits</b>
	                      	<br>   
	                      	<big> $ 900 </big> <small><strike class="baseprice" >($ 2250)</strike></small>	                      	
	                      	<br><br>  	                    	
	                    	1 Credit = 1 Email verification

	                      	<div class="row">
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
	                      		<span class="plancoupon"><span class="plancouponcode">MEV50F2019</span></span>
	                      	</div>		
	                      	<div class="row">
		                      	<div class="col-sm-2">
		                      	</div>
		                      	<div class="col-sm-8 text-center">
		                    		<button type="button" class="btn btn-block btn-primary">Buy Now</button>
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
    </div>    
</template>

<style>

	.carousel-caption p{
		color:#000;
	}

	.carousel-caption h4{
		color:#000;
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
		background-color: #d3d3d3;
	}

	.page-creditplans{
		background-color: #d3d3d3;	
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
		background-color: whitesmoke !important;
		border-radius: 5px;				
		margin:10px 0 0 0;
	}
	.creditplanbox b{
		color:brown;
	}

	h4,p{
		margin-left:15px;
	}
	.clientthumb img{
		width: 50px;				
		height: 50px;				
	}
	.clientdesg{
		font-size: 12px;
	}
</style>

<script>
        
    export default {
        name:'CreditPlans',
        data:function(){
        	return{
        		whitebgimg:img_url+'white-slide.jpg',
        		price_per_credit:0.0048,        			
       			no_of_emails:500,
       			paypalbtn:img_url+"paypalbtn.png",
       			coupon_code:'', 
       			show_applied_coupon:false,
       			msg_coupon_applied:'',
       			discount_off:0,
       			slide: 0,
        		sliding: null,
        		nathjimg:img_url+'nath_j.jpeg',
        	}
       		
        },
        computed :{
	      	getfixprice : function(){
	      		if(this.discount_off>0){
	      			var tmpans = ((this.price_per_credit * this.no_of_emails).toFixed(2));
	      			var offamount  = (( tmpans * this.discount_off ) / 100);
	      			var newans = (tmpans - offamount);
	      			return newans;
	      		}else{
	      			return ((this.price_per_credit * this.no_of_emails).toFixed(2));	
	      		}
	        	
	      	}
	    },
       	mounted(){        
            

            
       	},          
       	methods:{
        	applyCoupon:function(){
        		//alert('coupone '+this.coupon_code);
        		var thisObj = this;
        		this.axios.post(api_url+'applyCoupon',{
        			coupon_code:this.coupon_code,
        		})
            	.then((response)=>{
            		console.log(response);				 

            		if(response.data.status == '1'){
            			thisObj.show_applied_coupon = true;
            			thisObj.msg_coupon_applied=response.data.msg;
            			thisObj.discount_off = response.data.data.offper;            			
            		}else{
            			thisObj.show_applied_coupon = false;
            			thisObj.msg_coupon_applied='';
            			thisObj.discount_off = 0;
            		}

            	});
        	},
        	onSlideStart(slide) {
		        this.sliding = true;

		    },
		    onSlideEnd(slide) {
		    	this.sliding = false;
		    }                
        }       
    }
</script>
