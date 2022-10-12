
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
                            Set your new password.
                        </p>

                        <form @submit.prevent="submitForgotEmail" method="post" > 
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="email" 
                                                        v-model="logininfo.email"  placeholder="Email Address" 
                                                        @keyup="clearmsg"  />

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                <br/>                                            
                                <span class="text-danger" v-if="msg.email" >{{msg.email}}</span>
                            </div>


                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>                      
                            </div>

                        </form>

                        <p class="mt-3 mb-1"><router-link to="/Login" class="nav-item nav-link ">Login</router-link>
                        </p>
                    
                    </div>            
                </div>
            </div>
        </div>        
</div>

</template>


<style>
    .login-box-msg{
        padding: 0 0 0 !important;
    }
    .whitebox{
        background-color: #fff;            
    }
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
        name:'ForgotPassword',        
        mounted(){
                       
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
                logininfo:{                    
                    email:'',                    
                },   
            }
        },        
        methods:{
            clearmsg(){

                if(this.logininfo.email != ''){
                    this.msg.email = '';
                }                 
                
            },            
            submitForgotEmail(e){                 
                e.preventDefault(); 
                                                        
                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                
                if(!this.logininfo.email){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['email'] = "please enter email ";
                }else if (!this.validEmail(this.logininfo.email)) {
                    
                    this.hasError=true;
                    this.msg['email'] = "Please enter Valid email address.";
                }
                
                
                if(this.hasError == false){ 

                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'ForgotPassword',this.logininfo)
                    .then(function(response){
                        //console.log(response);                        
                                                                    

                        if(response.data.status==0 || response.data.status=='0'){
                            thisObj.msgerror = true;            
                            thisObj.textmsg=response.data.msg;            
                        }

                        if(response.data.status==1 || response.data.status=='1'){
                            
                            thisObj.msgsuccess=true;             
                            thisObj.textmsg=response.data.msg;          

                            localStorage.msgsuccess  = true; 
                            localStorage.textmsg = response.data.msg;          

                            //localStorage.mevid = response.data.reg_id;                        
                            
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