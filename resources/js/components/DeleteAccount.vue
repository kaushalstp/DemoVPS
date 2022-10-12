<template>
    <div>
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Delete Account</h1>
                </div>              
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Default box -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"></h3>

            </div>
            <div class="card-body">

            	<div class="row" >            		
            		<div class="col-sm-12" >
            						                             
                        <p><big><b>Deleting your account will:</b></big></p>

                        <div class="row" > 
                            <div class="col-sm-6" >
                                <i class="ion-android-mail"></i> &nbsp;
                                1) Delete all your Email Validations
                            </div>
                            <div class="col-sm-6" >
                                <i class="ion-android-person-add"></i> &nbsp;
                                2) Remove your access to the Members Area
                            </div>
                        </div>

                        <p>&nbsp;</p>
                        <p>
                            If you want to create another account on MyEmailVerifier in the future, you will be able to do so with the same email address. If you want to remove your profile permanently then please send email to 
                            <a href="mailto:support@myemailverifier.com">support@myemailverifier.com</a>
                        </p>


                        <div class="row" > 
                            <div class="col-sm-3" >
                                <button type="button" class="btn btn-block btn-danger" @click="deleteMyAccount" >Delete my Account</button>
                            </div>    
                        </div>    
                        
			              <!-- /.card-header -->                          
			              <!-- form start -->			              			        
            		</div>            	
            	</div>    


            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              
            </div>
            <!-- /.card-footer-->
          </div>
          <!-- /.card -->
        </section>
    </div>    
</template>

<script>    
    export default {
        name:'DeleteAccount', 
         data:function(){
            return{
                            
            }
        },
        mounted(){
            var thisObj = this;
            var uid = localStorage.mevuid;

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
            deleteMyAccount:function(){

                if(!confirm("Are you sure you want to delete your account?")){
                    return false;
                }

                var thisObj = this;
                var uid = localStorage.mevid;
                
                this.axios.get(api_url+'deleteMyAccount')
                .then(function(response){
                    //console.log(response);                    

                    localStorage.mevid = 0;
                    localStorage.msgsuccess  = true;
                    localStorage.textmsg = 'Your Account Deleted Successfully !...';

                    thisObj.$router.push({
                        name:'Login',                    
                    });

                    /*if(response.data.status == "1"){
                        alert(response.data.msg);
                    }*/




                });
            }
        }       
    }
</script>
