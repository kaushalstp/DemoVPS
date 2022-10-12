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
                        	
                        	<div class="custom-control custom-switch"> 
                          		            &nbsp; &nbsp; &nbsp; &nbsp;
        						  <input type="checkbox" class="custom-control-input cursor-pointer" id="customSwitch1" 
        						  v-model="low_credit_switch"  @click="toggleSwitch()" >

        						  <label class="custom-control-label" for="customSwitch1">Receive low credits notification? </label>
        						  <br/><br/>

        						  <span v-show="low_credit_switch" ><b>credits</b></span>
        						  <input type="number" v-model="low_credit_limit"  min="0" value="0" 
        						  v-show="low_credit_switch" size="10" />  						  

        						  <button type="button" class="btn btn-primary btn-sm" 
        						  @click="updateLowCreditLimit()" v-show="low_credit_switch" >Save</button>
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
        name:'Settings',         

        data:function(){
            return{   
                low_credit_switch:false,
                low_credit_limit:0,              
            }
        },        
        async mounted(){                    
            this.$parent.is_user_page=false;      
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
                
            });
            
            
            this.axios
            .post(api_url+'getSettings',{})
            .then(function(response){                
              //console.log(response); 
                                   

                if(response.data.data.low_credits_notify == 'y'){
                  	thisObj.low_credit_switch = true;                                       	
                }else{
                  	thisObj.low_credit_switch = false;                       
                }              

                thisObj.low_credit_limit=response.data.data.low_credits_limit;

            });
        }, 
        components: {            
        },         
        methods:{
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
