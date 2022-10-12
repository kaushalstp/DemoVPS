<template>
	<div>
		<section class="content-header"> 
			<div class="container-fluid">
				<!-- <div class="row mb-2">
					<div class="col-sm-6">
						<h1>Update Profile</h1>
					</div> 
				</div> -->
			</div><!-- /.container-fluid -->
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
			<!-- Default box -->
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">Profile</h3>
				</div>
				<!-- /.card-header -->
				<!-- form start -->

				<div class="row justify-content-sm-center">
					<div class="col-sm-6" >
						<form action="" @submit.prevent="submitProfileDetails"> 
							<vue-form-generator tag="div" :schema="schema" :options="formOptions" :model="model" />
							<div class="d-flex mt-3 pr-4">
								<button type="submit" class="btn btn-primary btn-lg">
									{{ isSaving ? 'Saving...' : 'Save'}}
								</button> 
							</div>
						</form>
						<Br/>
					</div>
				</div>
			</div>
		</div>
			<!-- /.card-footer-->
			<!-- /.card -->
		</section>
	</div>    
</template>


<style>

input[type="text"]:disabled {
	background: #f7f7f7;
}

</style>

<script>    

	import UpdateProfileSchema from '../forms/UpdateProfileSchema';

	export default{
		name:'UpdateProfile',
		mounted(){

			/*this.$bvToast.toast('Toast body content', {
			  title: `Variant ${'default'}`,
			  variant: 'success',
			  toaster:'b-toaster-top-full',
			  solid: true
			});*/

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

			
			var uid = localStorage.mevuid; 


			this.axios.get(api_url+'getProfile')
			.then(function(response){
				

				if(response.data.status == '1' || response.data.status == 1 ){

					thisObj.model.title = response.data.user.title;
					thisObj.model.first_name = response.data.user.firstname;
					thisObj.model.last_name = response.data.user.lastname;
					thisObj.model.email = response.data.user.email;
					thisObj.model.phone = response.data.user.phone;
					thisObj.model.website = response.data.user.company_website;
					thisObj.model.address = response.data.user.address;
				}else{

					localStorage.msgsuccess  = true;
					localStorage.textmsg = response.data.msg;                                            

					localStorage.mevid = 0;
					/*thisObj.$router.push({ 
						name:'Login',                    
					});*/


				}
				
			});
		},       
		data:function(){
			return {
				error:[],
				schema: UpdateProfileSchema,
				model:{         
					userid:localStorage.mevuid,           
					email:'',
					title:'',
					first_name:'',
					last_name:'',
					phone:'',
					website:'',
					address:'',
				},
				formOptions:{
					validateAfterChanged: true
				},
				isSaving: false
			}
		},
		methods:{
			submitProfileDetails:function(e){   
				e.preventDefault();

				var thisObj = this;

				this.axios.post(api_url+'saveProfile',{
					userdata:thisObj.model,
				}).then(function(response){
					
					if(response.data.status == '1'){
						//alert(response.data.msg);
						thisObj.$toast.open({
							message: response.data.msg,
							type:'success',
							position:'top-right'
						});
					}else{

						thisObj.$toast.open({
							message: response.data.msg,
							type:'error',
							position:'top-right'
						});
					}
				});
			}
		},
		created(){

			var thisObj = this;

			setInterval(function(){
				
				if(localStorage.mevid <= 0 || localStorage.mevid == '' || typeof localStorage.mevid === 'undefined'){

					thisObj.$router.push({ 
						name:'Login',
					});
				}
			}, 2000);

		}


	}
</script>
