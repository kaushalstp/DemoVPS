<template>
	<div>
		<!-- <section class="content-header">
			<div class="container-fluid">
				<div class="mb-2">
					<h1 class="text-center">Email Verification</h1>
				</div>
			</div>
		</section> -->

		<!-- <section class="content-header">   
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						
						<h5>Welcome to your Single Email Validator</h5> 
					</div>	          
				</div>
			</div>

			<div class="callout callout-danger notes "> 

				<p>Enter any email address to have it quickly verified or try our email list verifier to validate lists with our highly efficient validation technology.</p>

				<p class="txthighlights" > Have confusion? Please refer our Detailed 
					<a target="_blank" href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >blog</a> about 
					<a target="_blank" href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >"How it works"</a> 
				</p>
			</div>	      
		</section> -->

		<!-- Main content -->
		<section class="content">

			<!-- Default box -->
			<div class="container-fluid">
				<p>&nbsp;</p>
				<div class="row justify-content-sm-center" >
					<div class="col-sm-8" >
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">Validate Email</h3>
							</div>

							<p class="txthighlight" >
								<span>
									<b>Note: We will not charge for "Unknown" status. It is recommended that you try twice after a second because of some temporary technical issue.</b>
								</span>
							</p>

							<!-- /.card-header -->
							<!-- form start -->
							<form role="form" 
							id="frmEmail" name="frmEmail" @submit.prevent="submitEmailData" >
							<div class="card-body">
								<div class="form-group">
									<!-- <label for="email">Email Address</label> -->
									<input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" v-model="email" >
								</div>

							</div>
							<!-- /.card-body -->

							<div class="card-footer">
								<button type="submit" class="btn btn-primary" :disabled='isSubmitDisabled' >Submit to Validate</button>

								&nbsp;&nbsp;
								<div class="spinner-border text-success" role="status" v-show="spinner" >
									<span class="sr-only">Loading...</span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="row" >
			<div class="vld-parent">
				<loading :active.sync="isLoading"                         
				:is-full-page="true"></loading>

				<!-- <label><input type="checkbox" v-model="fullPage">Full page?</label>
					<button @click.prevent="doAjax">fetch Data</button> -->
				</div>
			</div>

			<div v-show="resultinfo"  id="my_validation_result" ref="my_ref_result"  >
				<h2>&nbsp;&nbsp;Validation Result</h2>
				<div class="row" >                    

					<div class="col-sm-12" >

						<table class="table table-striped vldtn_results ">                              
							<tbody>
								<tr>
									<td><b>Result</b></td>
									<td v-html="emaildata.Result" ></td>
								</tr>
								<tr>
									<td><b>User</b></td>
									<td>{{ emaildata.User }}</td>
								</tr>
								<tr>
									<td><b>Domain</b></td>
									<td>{{ emaildata.Domain }}</td>
								</tr>
								<tr>
									<td><b>More Info</b></td>
									<td>{{ emaildata.more_info }}</td>
								</tr>	                                
								<tr>
									<td><b>Disposable</b></td>
									<td>{{ emaildata.Disposable }}</td>
								</tr>
								<tr>
									<td><b>Free</b></td>
									<td>{{ emaildata.Free }}</td>
								</tr>
								<tr>
									<td><b>Greylisted</b></td>
									<td>{{ emaildata.Greylisted }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>                 

			<div  ref="tempbottom" >&nbsp;</div>       
					<!-- /.card -->
				</section>
			</div>    
		</template>


		<style>
		.txthighlights{
			background-color: #daa520;					
			padding: 5px;
			margin: 10px 0 0 0;			
			font-size: 14px;
			border-radius:3px;
		}
		.txthighlight {
		    font-size: 14px;
		    background-color: whitesmoke;
		    padding: 5px;
		    margin: 10px 0 0 0;		    
		    color:slategrey;
		}
		.vldtn_results td{
			text-align: center;
		}

		.restext{
			color: #fff !important;
			font-weight: bold;		
			padding: 3px;
			border-radius: 3px;
		}

		.resvld{
			background-color: green;
		}

		.resinvld{
			background-color: red;
		}

	</style>

	<script>    

		import Loading from 'vue-loading-overlay';    
		import 'vue-loading-overlay/dist/vue-loading.css';

		export default {
			name:'ValidateEmail',     
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


				this.msgsuccess = localStorage.msgsuccess;            
				this.textmsg = localStorage.textmsg;      
				this.userid = localStorage.mevuid; 
				if(localStorage.textmsg == ''){
					this.msgsuccess = false;
				}
			},   
			data:function(){        	
				return{
					spinner:false,
					isSubmitDisabled:false,
					email:'',
					resultinfo:false,
					userid:0,
					isLoading:false,
					emaildata:{
						result:'this is 10th',
					}
				}
			},
			updated(){
				this.goto('tempbottom');
			},
			methods:{
				goto(refName) {					
			        var element = this.$refs[refName];
			        //console.log(element);
			        var top = element.offsetTop;
			        window.scrollTo(0,10000);
			    },
				submitEmailData:function(){
					var thisObj = this;                

					if(thisObj.email == ''){

						this.$toast.open({
							message: 'please enter email address',
							type:'error',
							position:'top-right'
						});

						return false;
					}

					if (!thisObj.validEmail(thisObj.email)) {

						this.$toast.open({
							message: 'please enter a valid email address.',
							type:'error',
							position:'top-right'
						});

						return false;
					}

					thisObj.spinner = true;
					thisObj.isSubmitDisabled = true;

					thisObj.isLoading=true;
					this.axios.post(api_url+'validate_email',{ 
						userid:thisObj.userid,
						email:thisObj.email,
					})
					.then(function(response){
						if(response.data.status == '1'){

							thisObj.emaildata = response.data.data; 
							thisObj.resultinfo = true;														
							thisObj.goto('my_ref_result');						

						//thisObj.$parent.msgsuccess = true;
						//thisObj.$parent.msgerror = false;
						//thisObj.$parent.textmsg = 'Account activated. please login to continue';

						/*thisObj.$router.push({
							name:'login',
							params:{msgsuccess:true,msgerror:false,textmsg:'Account activated. please login to continue'}
						});*/
					}else{

						alert(response.data.msg);

						/*
						thisObj.$parent.msgsuccess = false;
						thisObj.$parent.msgerror = true;
						thisObj.$parent.textmsg = response.data.message;
						*/
					}

					thisObj.isLoading=false;					
					//this.$refs.my_ref_result.scrollBottom = 99999999;
					//this.$refs.my_ref_result.scrollIntoView(); 					


				});

					thisObj.spinner = false;
					thisObj.isSubmitDisabled = false;
				},
				validEmail: function (email) {
					var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
					return re.test(email);
				}
			},
			created(){

				setInterval(function(){
					this.textmsg = '';                 
					this.msgsuccess = false;
					this.msgerror = false;

				//localStorage.msgsuccess = false;        
				//localStorage.textmsg = '';   


			}, 5000);
			},
			components: { Loading },   
		}
	</script>
