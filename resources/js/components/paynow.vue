<template>
    <div class="page-admin-users" > 
        <section class="content-header"> 
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"> 
                        <h1>Pay Now</h1> 
                    </div>                                  
                </div>               	 
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->

        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-12">                    
                    	   
                    	<paypal-checkout
						    amount="11.00"
						    currency="INR"
						    :client="paypal"
						    env="sandbox" 
                            notify-url="https://kaushalproject.com/myadmin/paypalnotify"
                            v-on:payment-completed="paymentcompleted" 
                            v-on:payment-authorized="paymentauthorized" 
						>
						</paypal-checkout>

                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>        
    </div>    
</template>


<script>       

    export default {
        name:'Paynow', 
        mounted(){

        },
        data:function(){
            return{ 
            	paypal: {
			      sandbox: 'AS-9_ktSUONpwGyOqjXlipNTdWsaAoydOjOaBXcdqe0EdljHXh1RHHf-dWSlI3tAys6x-MgBdIqV_GXL',
			      production:'AS-9_ktSUONpwGyOqjXlipNTdWsaAoydOjOaBXcdqe0EdljHXh1RHHf-dWSlI3tAys6x-MgBdIqV_GXL'
			    },
            }
        },
	    components:{
	            
	    },
	    methods:{
	        paymentcompleted:function(event){
                

            },
            paymentauthorized(event) {            
             
           }    
	    }  
	}    

</script>
