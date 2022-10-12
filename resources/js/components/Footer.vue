<template>
	<!-- Main Sidebar Container -->
	<div>
		<footer class="main-footer">
			<div class="float-right d-none d-sm-block">
			  <b>Version</b> 2.0.0
			</div>
			<strong>Copyright &copy; {{ new Date().getFullYear() }} MyEmailVerifier</strong>	    
		</footer>
	  

		<div class="modal fade" id="usercoupons" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><b>Your active coupon codes</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
							
				<span v-for="coupons in coupon_codes_list" >
					 <b> {{ coupons.coupon_code }} </b> ({{ coupons.percentage }} % off. Applicable between {{ coupons.from_credits }}-{{ coupons.to_credits }} credits)
				</span>

				<span>
					 <b> MEVSPL1MLN </b> ( $599. Applicable for 1 Million credits only)
				</span>

				<br/><br/>


				How to use coupon code? <a target="_blank" href="https://myemailverifier.com/blog/how-to-complete-payment-with-a-creditcard-using-paypal-at-myemailverifier-com/" >(Click for Instructions)</a><br/><br/>

				Want to pay with Credit Card?  <a target="_blank" href="https://myemailverifier.com/blog/how-to-complete-payment-with-a-creditcard-using-paypal-at-myemailverifier-com/" >(Click for Instructions)</a>


			  </div>
			  <div class="modal-footer">                
				<button type="button" class="btn btn-primary" data-dismiss="modal" >Ok</button>
			  </div>
			</div>
		  </div>
		</div>
	
	</div>    

</template>


<script>
	export default{
		name:'Footer',
		mounted(){ 

			var thisObj = this;

			this.axios.get(api_url+'getusercoupons')
			.then(function(response){ 
				//console.log(response);
				if(response.data.status == '1'){
					
					thisObj.coupon_codes_list = response.data.data;
				}                                
			});
				


		},
		data(){

		  return{
			  coupon_codes_list:[],
		  }  

		}
	}
</script>

