<template>
    <div>
        <section class="content-header">
	      	<div class="container-fluid">
		        <div class="row mb-2">
		          <div class="col-sm-6">
		            <h1>Credit Plans</h1>
		          </div>	          
		        </div>
	      	</div><!-- /.container-fluid -->
	    </section>

        <!-- Main content -->

        <section class="content page-creditplans">
		    <div class="container-fluid">

		    	<div class="row" >
		    		<div class="col-sm-4" >		    			
				            <form role="form">

				              	<div class="card">
					                <div class="card-body">

					                	<div class="form-group">

						                    <label for="exampleInputEmail1">No of Emails</label>
						                    <small>(Minimum 500 credits required)</small>

						                    <input type="number" class="form-control" name="no_of_emails" 
						                    id="no_of_emails" placeholder="" min="500" value="500" 
						                    v-model="no_of_emails" >
					                  	</div>				                  				              	

					                  	<p>
					                  		$ {{getfixprice}}
					                  	</p>

					                  	<button type="submit" class="btn btn-primary">Submit</button>
					                </div>
					            </div>    

				                <!-- /.card-body -->				                		                				                
				            </form>				            
		    		</div>

		    		<div class="col-sm-7" >		    			
				            <form role="form">

				              	<div class="card">
					                <div class="card-body">

					                	<div class="form-group">

						                    <label for="exampleInputEmail1">Coupon Code</label>
						                    Buy 5,000 credits for $21.00

						                    <input type="text" class="form-control" name="coupon_code" 
						                    id="coupon_code" placeholder="Coupon Code"  
						                    v-model="coupon_code" >
					                  	</div>				                  			              					                  			                  	
					                  	<input type="image" :src="paypalbtn" alt="Submit" width="132" > 

					                </div>
					            </div>    

				                <!-- /.card-body -->				                		                
				                
				            </form>				            
		    		</div>


		    	</div>


		        <div class="row" >
		        	<div class="col-sm-3">
	                    <div class="position-relative p-3 bg-gray" style="height: 180px">
	                    	<div class="ribbon-wrapper ribbon-lg">
		                        <div class="ribbon bg-success text-lg">
		                          40% Off
		                        </div>
	                      	</div>
	                      	<b>200 credits</b> 
	                      	<br>  
	                      	<big> $ 150 </big>
	                      	<br>  
	                    	<small>plan description</small>

	                      	<br><br><br>
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
	                    <div class="position-relative p-3 bg-gray" style="height: 180px">
	                    	<div class="ribbon-wrapper ribbon-lg">
		                        <div class="ribbon bg-success text-lg">
		                          40% Off
		                        </div>
	                      	</div>
	                      	<b>200 credits</b>
	                      	<br>   
	                      	<big> $ 150 </big>	                      	
	                      	<br>  	                    	
	                    	<small>plan description</small>

	                      	<br><br><br>
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
	                    <div class="position-relative p-3 bg-gray" style="height: 180px">
	                    	<div class="ribbon-wrapper ribbon-lg">
		                        <div class="ribbon bg-success text-lg">
		                          40% Off
		                        </div>
	                      	</div>
	                      	<b>200 credits</b> 
	                      	<br>  
	                      	<big> $ 150 </big>
	                      	<br>  
	                    	<small>plan description</small>

	                      	<br><br><br>
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
	                    <div class="position-relative p-3 bg-gray" style="height: 180px">
	                    	<div class="ribbon-wrapper ribbon-lg">
		                        <div class="ribbon bg-success text-lg">
		                          40% Off
		                        </div>
	                      	</div>
	                      	<b>200 credits</b> 
	                      	<br>  
	                      	<big> $ 150 </big>
	                      	<br>  
	                    	<small>plan description</small>

	                      	<br><br><br>
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
		</section>        
    </div>    
</template>

<style>
	.page-creditplans .bg-gray{
		border-radius: 5px;				
	},
	h4,p{
		margin-left:15px;
	}
</style>

<script>
        
    export default {
        name:'CreditPlans',
        data:function(){
        	return{
        		price_per_credit:0.0048,        			
       			no_of_emails:500,
       			paypalbtn:img_url+"paypalbtn.png",
        	}
       		
        },
        computed :{
	      	getfixprice : function(){
	        	return (this.price_per_credit * this.no_of_emails).toFixed(2);
	      	}
	   },
       mounted(){        
            

            
        },          
        methods:{
                        
        }       
    }
</script>
