<template>
    <!-- Main Sidebar Container -->
    <div>
        <div class="alert alert-success alert-dismissible fade show toastr_suc" role="alert" 
        v-show="showtoastr" >
            <strong>{{ main_header_message  }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>     

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        
        <!-- Left navbar links -->
        <div class="col-sm-12" >
            <div class="row" >

                <div class="col-sm-3" >
                    <ul class="navbar-nav">

                        <li class="nav-item" >
                            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>        
                        </li>

                        <li class="nav-item" >
                                    
                        </li>

                        <router-link to="/Dashboard" class="nav-item nav-link">                                                
                            &nbsp; Home
                        </router-link> 
                    </ul>
                </div>
 

                <div class="col-sm-9"  >

                    <div style="float:right;" class="pt-2" >                                
                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                        <a href="javascript:void(0);" @click="logout" >Log Out</a>                        
                    </div>


                    <div v-show="is_user" style="float:right;" >
                        <div style="float: left;"  >        
                            You have <big><b>{{ available_credits }}</b></big> available credits &nbsp;&nbsp;                                                    
                        </div>    

                        <div style="float: left;"> 
                            <router-link to="/CreditPlans" class="nav-item nav-link btn-block btn-primary" style="width:110px;" >
                                Buy Credits
                            </router-link>            
                        </div>

                        <div style="float: left;"  >                           
                            <button style="border-radius: 3px; margin: 5px;border: 2px dashed blue;padding: 3px 7px;" type="button" class="ant-btn btnFirebase ant-btn-dashed" data-toggle="modal" data-target="#usercoupons" >
                                <span>
                                    <img :src="dwnldlogo" width="22" /> Your Coupon Codes
                                </span>
                            </button>
                        </div>

                        <div style="float:left;" >                                 
                            <a class="nav-link"  href="javascript:void(0);" 
                            aria-expanded="false" data-toggle="modal" data-target="#new_notifications" >
                                <i class="fa fa-bell"></i>
                                <span class="badge badge-warning navbar-badge">{{ new_alerts }}</span>
                            </a>                            
                        </div>
                    </div>
                

                </div>
            </div>    
        </div>
        <!-- SEARCH FORM -->                
        </nav>  


        <!--  Notification Pop Up -->

            <div class="modal fade" id="new_notifications" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">                                                
                        <h5 class="modal-title" id="exampleModalLabel">                            
                            <i class="fa fa-bell"></i> <b>Notifications</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" 
                        @click="markasread" >
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                    
                        <div v-for="message in notifications"  >    

                            <div class="row mb-3" :class="message.alert_class" >
                                <div class="col-sm-8 dropdown-item-title" >
                                    {{ message.subject }}
                                </div>                                

                                <div class="col-sm-4 text-right" >
                                    <small>
                                        <i class="fa fa-clock mr-1"></i>    
                                        <i> {{ message.time_elapsed }} </i>
                                    </small>                                    
                                </div>                                    
                            </div> 
                        
                           
                            <div class="row" :class="message.alert_class" >
                                                            
                                <div class="col-sm-10 txt-sm" >
                                    {{ message.text }}
                                </div> 
                                <div class="col-sm-2" >
                                    <img style="float: right" v-show="message.is_read"  :src="readlogo" 
                                    width="20" />    
                                </div>
                            </div>                                                       

                            <div class="dropdown-divider"></div>

                        </div>
                    </div>

                  <div class="modal-footer">                
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="markasread" >Ok</button>
                  </div>
                </div>
              </div>
            </div>
        <!--  ***  -->
    </div>
</template>

<style>
    
    .unreadalert{
        font-weight: bold;
    }

</style>

<script>
    export default{
        name:'Header',        
        mounted(){

            var thisObj = this;                
            var mevid = localStorage.mevid;                         
                        
            if(this.$route.params.message){
                
                this.showtoastr = true;
                this.main_header_message = this.$route.params.message;
            }

            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                

                

                
                if(response.data.status == 0 || response.data.status == '0'){
                    //window.location="http://kaushalproject.com/myadmin"; 
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }

                if(response.data.status == 1 || response.data.status == '1'){ 
                                                    
                    localStorage.setItem('mevid',response.data.data.userid);
                }
            });  


            this.axios.get(api_url+'getProfile')
            .then(function(response){
                
                thisObj.notifications = response.data.notifications;
                thisObj.new_notifications = thisObj.notifications.length + " new ";
                thisObj.available_credits = response.data.user.credits;                             
                thisObj.new_alerts = response.data.new_alerts;
            });

            this.axios.get(api_url+'getsessionmsg')
            .then(function(response){ 
                

                if(response.data.sessMsg != '' &&  response.data.sessMsg != null){
                        
                    thisObj.main_header_message = response.data.sessMsg;
                    thisObj.showtoastr = true;                                                                                                                                                                            
                }else{
                        
                    thisObj.main_header_message = '';
                    thisObj.showtoastr = false;                                    
                }   
            });            

            
            setInterval(function(){   
                
                var mevid = localStorage.mevid;
                 
                this.axios.get(api_url+'getProfile')
                .then(function(response){
                    
                    thisObj.notifications = response.data.notifications;
                    thisObj.new_notifications = thisObj.notifications.length + " new ";
                    thisObj.available_credits = response.data.user.credits;                             
                    thisObj.new_alerts = response.data.new_alerts;
                });    
                

                this.axios
                .get(api_url+'checkusersession')
                .then(function(response){      

                    //console.log(response);          
                    
                    if(response.data.status == 0 || response.data.status == '0'){
                        
                        thisObj.$router.push({ 
                            name:'Login',                    
                        });
                    }

                    /*if(response.data.data.is_admin == 1 || response.data.data.is_admin == '1'){ 
                        localStorage.is_admin = true;
                    }else{
                        localStorage.is_admin = false;
                    }*/

                    if(response.data.status == 1 || response.data.status == '1'){ 
                                                        
                        localStorage.setItem('mevid',response.data.data.userid);
                    }
                }).catch(function(error){ 
                    //console.log("error:"+error);  
                    //alert("it is error");
                });



            }, 10000);
            
            
            /*if(localStorage.is_admin == "false"){ 
                this.is_user = true;
            }else{
                this.is_user = false;
            }                    */

            if(localStorage.is_admin == "true"){ 
                this.is_user = false;
            }else{
                this.is_user = true;
            }                    


        },
        data:function(){
        	return{
                userid:0,
                dwnldlogo:img_url+'download-arrow.gif',
        		available_credits:0,
                showtoastr:false,   
                main_header_message:'',
                is_user:false,  
                new_notifications:' No ', 
                notifications:[],
                readlogo:img_url+'readlogo.png',
                new_alerts:'',
        	}
        },
        methods:{
            markasread(){
                var thisObj = this;
                this.axios.get(api_url+'markasread')
                .then(function(response){
                    thisObj.new_alerts = '';        
                });            
            },
            logout(){                   

                var thisObj = this;

                localStorage.mevid = 0;
                localStorage.is_admin = false;
                localStorage.msgsuccess  = true;
                localStorage.textmsg = 'Logged Out Successfully';                                

                this.axios.get(api_url+'logout').then(response => {                

                    this.$router.push({
                        name:'Login',                    
                    });                
                });                
            }
        },
        created(){
            var thisObj = this;
            setInterval(function(){                
                thisObj.showtoastr = false;
                thisObj.main_header_message = '';                 

            }, 3000);
        },
        computed:{
            user_new_id:function(){
                return localStorage.mevid;
            }
        }  
    }
</script>

