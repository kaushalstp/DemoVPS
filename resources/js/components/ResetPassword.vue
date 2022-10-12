
<template>


    <div class="row" style="background: url(https://client.myemailverifier.com/public/img/promo_img.jpg);width: 100%;    min-height: 620px;" >
            <div class="col-sm-8" ></div> 

            <div class="col-sm-4 whitebox ">
                <div class="login-box">
                    <div class="login-logo">
                        <img :src="adminlogo"
                               alt="AdminLTE Logo"
                               class="brand-image"
                               style="opacity: .8" />
                    </div>
              <!-- /.login-logo -->
                    <div class="card">
                        <div class="card-body login-card-body">

                            <div class="alert alert-success" role="alert" v-show="msgsuccess" > 
                                {{ textmsg }}

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="alert alert-danger" role="alert" v-show="msgerror" > 
                                {{ textmsg }}

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <p class="login-box-msg">
                                You forgot your password? Here You will get a password reset link via email.
                            </p>

                            <form @submit.prevent="submitResetPassword" method="post" > 
                                <input type="hidden" name="reset_token" v-model="reset_token"  />
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id="new_password" 
                                                            v-model="logininfo.new_password"  placeholder="New password" 
                                                            @keyup="clearmsg"  />


                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>


                                    <br/>                                            
                                    <span class="text-danger" v-if="msg.new_password" >{{msg.new_password}}</span>
                                </div>

                                <div class="input-group mb-3"> 
                                    <input type="password" class="form-control" id="confirm_password" 
                                                            v-model="logininfo.confirm_password"  
                                                            placeholder="Confirm password" 
                                                            @keyup="clearmsg"  />

                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    <br/>                                            
                                    <span class="text-danger" v-if="msg.confirm_password" >{{msg.confirm_password}}</span>
                                </div>


                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>                      
                                </div>

                            </form>
                            
                        
                        </div>            
                    </div>
                </div>
            </div>
    </div>            

</template>


<style>
    .login-box{
        margin:auto;
    }
     .text-danger{
        float:left;
        width: 100%;
    }
   

</style>

<script>
    
    export default {
        name:'ResetPassword',        
        mounted(){            
            //// console.log("this are: "+this.$route.params.token); 

            var thisObj = this;       

            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                                
                if(response.data.status == 1 || response.data.status == '1'){ 

                    thisObj.$router.push({  
                        name:'dashboard',                    
                    });
                                                                        
                    /*if(localStorage.mevid > 0 ){                
                        
                    }*/

                }

            });
                            
            this.logininfo.reset_token = this.$route.params.token;
        },
        data(){
            return{
                adminlogo:img_url+'mev-logo-2.svg',                   
                hasError:false,
                errors:[],
                vlds:[],
                msg:[],
                msgsuccess:false,
                msgerror:false,
                textmsg:'',  
                reset_token:'',
                logininfo:{    
                    reset_token:'',                
                    new_password:'',                    
                    confirm_password:'',                    
                },   
            }
        },        
        methods:{
            clearmsg(){

                if(this.logininfo.new_password != ''){
                    this.msg.new_password = '';
                }                 

                if(this.logininfo.confirm_password != ''){
                    this.msg.confirm_password = '';
                }                 
                
            },            
            submitResetPassword(e){                 
                e.preventDefault(); 
                                                             
                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                
                if(!this.logininfo.new_password){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['new_password'] = "please enter password ";
                }

                if(!this.logininfo.confirm_password){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['confirm_password'] = "please confirm password ";
                }
                

                if(this.logininfo.confirm_password != this.logininfo.new_password){
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['confirm_password'] = "confirm password did not match";   
                }
                
                

                if(this.hasError == false){ 

                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'ResetUserPassword',thisObj.logininfo)
                    .then(function(response){
                        // console.log(response);                        
                                                                    

                        if(response.data.status==0 || response.data.status=='0'){
                            thisObj.msgerror = true;            
                            thisObj.textmsg=response.data.msg;            
                        }

                        if(response.data.status==1 || response.data.status=='1'){
                            
                            //thisObj.$parent.msgsuccess=true;             
                            //thisObj.$parent.textmsg=response.data.msg;          

                            localStorage.msgsuccess  = true; 
                            localStorage.textmsg = response.data.msg;          

                            localStorage.mevid = response.data.reg_id;
                            
                            thisObj.$router.push({
                                name:'Login',
                                params:{
                                    message : response.data.msg,
                                }
                            });
                        }
                    });  
                }
            },     

            validEmail: function (email) {
              var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              return re.test(email);
            }
        },            
        created(){
                        
            setInterval(function(){                

                localStorage.msgsuccess = false;        
                localStorage.msgerror = false;        
                localStorage.textmsg = '';   

                this.msgsuccess = false;        
                this.msgerror = false;        
                this.textmsg = '';   

            }, 5000);

        }  
    }            

</script>