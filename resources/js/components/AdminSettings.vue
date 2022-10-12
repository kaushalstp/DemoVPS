<template>  
    <div class="admin-page-settings" > 
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Settings</h1>
                    </div>                   
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <p></p>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid stat-widgets" >
               
                  <!--  Users Stats  -->
                <!-- ******* -->
                <!--  Orders Stats  -->
                <div class="card" >
                    <div class="row card-body min-vh-100"  >                                                                              
                        <div class="col-lg-12 col-12"  >
                        	
                        	<div class="form-group">                           		            
                                  <label>Twitter/FB post description. Max (160 chars) </label>
                                  <textarea class="form-control" v-model="social_article_text" >
                                      
                                  </textarea>        						 
    						</div>


                            <div class="form-group">                                            
                                  <label>Interval of </label>
                                  <input type="number"  v-model="social_share_interval" size="10" />
                                  &nbsp; Days
                            </div>

                            <div class="form-group">                                            
                                &nbsp;
                                <button class="btn btn-primary"  @click="saveAdminSettings" >
                                  Save
                                </button>
                            </div>

                        </div>                
                    </div>
                </div>

                <!-- ******* -->

                <!--  API Calls Stats  -->                          
            </div>                
        </section>
    </div>    
</template>


<style>
    
    .stat-widgets svg{
        font-size: 70px;
        top: 20px;
        right: 15px;
        position: absolute;
    }

    .admin-page-dashboard .bg-info{
        background-color: #17a2b8!important;
    }

    .admin-page-dashboard .bg-success{
        background-color:  #28a745!important;   
    }

    .admin-page-dashboard .bg-warning{
        background-color:  #ffc107!important;   
    }

    .small-box-footer svg{
        font-size: 15px;
        position: static;
    }

    .sec-recent-upload .todo-list li{
        background: #fff;
        border-left:none;
    }


    .sec-recent-order .todo-list li{
        background: #fff;
        border-left:none;
    }

</style>

<script>
    
    export default{
        name:'AdminSettings',         

        data:function(){
            return{   
                social_article_text:'',                
                social_share_interval:7,
            }
        },        
        async mounted(){                    
            this.$parent.is_user_page=false;      
            var thisObj = this;

            if(localStorage.is_admin == "false" || localStorage.is_admin == false){
                this.is_admin = false;    
            }

            if(localStorage.is_admin == "true" || localStorage.is_admin == true){
                this.is_admin = true;    
            }
            
            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    //window.location=app_url;
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }                
            });
            

            if(this.is_admin == false){
                this.$router.push({ 
                    name:'dashboard',                    
                });
            } 
            
            this.axios
            .post(api_url+'getAdminSettings',{})
            .then(function(response){                
                thisObj.social_article_text =     response.data.data.admin_settings.social_article_text;
                thisObj.social_share_interval =     response.data.data.admin_settings.social_share_interval;
                
            });
        }, 
        components: {            

        },         
        methods:{
            saveAdminSettings(){

                var thisObj = this;

                this.axios
                .post(api_url+'saveAdminSettings',{
                    'social_article_text':thisObj.social_article_text,
                    'social_share_interval':thisObj.social_share_interval,
                })
                .then(function(response){                
                
                    if(response.data.msg != ''){
                        thisObj.$toast.open({
                            message: response.data.msg,
                            type:'success',
                            position:'top-right'
                        }); 
                    }                  
                });    
            },
        	updateLowCreditLimit(){
        		
        		var thisObj = this;

        		this.axios
              	.post(api_url+'updateCreditLimit',{'low_credit_limit':thisObj.low_credit_limit})
              	.then(function(response){                
                
                	if(response.data.msg != ''){
                		thisObj.$toast.open({
						      message: response.data.msg,
							type:'success',
							position:'top-right'
						});	
                	}	               
                });
            },  
            toggleSwitch(){
                
                var thisObj = this;

                this.axios
                .post(api_url+'toggleSwitch',{'low_credit_switch':thisObj.low_credit_switch})
                .then(function(response){                
                //console.log(response);                 
                                     
                });
            },  

        },        
        computed:{
            
        },
        created(){            

        },
           

    }
        

   

</script>
