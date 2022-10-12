
<template> 

    <div class="login-box" >     
        <div class="login-logo">
            <img :src="adminlogo"
                   alt="AdminLTE Logo"
                   class="brand-image"
                   style="opacity: .8" />
        </div>
     
      <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

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

                <form  method="post" id="frmLogin" name="frmLogin"  @submit.prevent="submitLogin" >
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" 
                        v-model="logininfo.email"  @keyup="clearmsg"   />
                        <div class="input-group-append">
                            <div class="input-group-text">
                              <span class="fas fa-envelope"></span>
                            </div>  
                        </div>
                        <br/>
                        <span class="text-danger" v-if="msg.email" >{{msg.email}}</span>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" 
                        v-model="logininfo.password"  @keyup="clearmsg"   />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <br/>
                        <span class="text-danger" v-if="msg.password" >{{msg.password}}</span>
                    </div>

                    <div class="row">
                        <div class="col-6"> 
                            <div class="icheck-primary"> 
                                <input type="checkbox" id="remember"  v-model="remember_me" /> 
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>                            
                    </div>
                      <!-- /.col -->                      
                      <!-- /.col -->
                    </div>

                    <div class="row">                        
                        <div class="col-4">                        
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>

                        <div class="col-2">
                            <b style="line-height: 2.5;" >OR</b>
                        </div>

                        <div class="col-6">
                            
                            <button type="button" class="btn btn-dark" data-toggle="modal" 
                            data-target="#otpLoginModal"  @click="openotpform" >Sign In with OTP </button>                        
                        </div>
                    </div>


                </form>
             
              <!-- /.social-auth-links -->
                <p>&nbsp;</p>  

                <p class="mb-1">
                   <router-link to="/ForgotPassword" class="nav-item nav-link ">Forgot Password?</router-link>
                </p>
                <p class="mb-0">
                    <router-link to="/Register" class="nav-item nav-link ">Create New Account</router-link>
                </p>               
            </div>
        <!-- /.login-card-body -->
        </div>


        <!--  OTP Login form  -->

        <div class="modal fade" id="otpLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Login In with OTP</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="row" v-show="sendotpform" >                            
                                <form  name="frmOTPLogin" @submit.prevent="submitOTPLogin" id="frmOTPLogin" >
                                    <div class="col-sm-12" >
                                        <span style="font-size: 12px;" >
                                            &nbsp;&nbsp;&nbsp;&nbsp;  OTP will be sent to your Email Address 
                                        </span>
                                    </div>


                                    <div class="row" >    
                                        <div class="col-sm-1" >
                                            
                                        </div>

                                        <div class="col-sm-7" > 
                                            <div class="form-group">                                        
                                                <input type="text" class="form-control" id="new_email" 
                                                v-model="logininfo.new_email"  placeholder="Email Address" 
                                                @keyup="clearmsg"  />
                                            </div>    
                                            <br/>
                                            <span class="text-danger" v-if="msg.new_email" >{{msg.new_email}}</span>
                                        </div>
                                    
                                        <div class="col-sm-4" > 
                                            <button type="submit" class="btn btn-primary">Send OTP</button>
                                        </div>                                    
                                    </div>
                                </form>
                            </div>


                            <div class="row" v-show="verifyotpform"  >
                                
                                <form  name="frmVerifyOTP" @submit.prevent="submitVerifyOTP" id="frmVerifyOTP" >
                                    <input type="hidden"  name="user_email" 
                                    id="user_email" v-model="otpinfo.user_email" />

                                    <div class="col-sm-12" >
                                        <span style="font-size: 14px;" v-html="msg_otp_verification" >
                                            
                                        </span>
                                    </div>


                                    <div class="row" >
                                        <div class="col-sm-1" > </div>
                                        <div class="col-sm-6" > 
                                            <div class="form-group">                                        
                                                <input type="text" class="form-control" id="new_otp" 
                                                v-model="otpinfo.new_otp"  name="new_otp" placeholder="Enter OTP" 
                                                @keyup="clearmsg"  />
                                            </div>    
                                            <br/>
                                            <span class="text-danger" v-if="msg.new_otp" >{{msg.new_otp}}
                                            </span>
                                        </div>

                                        <div class="col-sm-4" > 
                                            <button type="submit" class="btn btn-primary">Verify OTP</button>
                                        </div>
                                    </div>    

                                    <div class="row"> 
                                        <p>&nbsp;</p>

                                        <div class="col-sm-6"> 
                                            <a href="javascript:void(0);" @click="closeotpwindow"  >
                                                Resend OTP
                                            </a>    
                                            <br/>
                                            <a href="javascript:void(0);" @click="showmainlogin" data-dismiss="modal" >
                                                Login with Password 
                                            </a>      
                                        </div>                                        
                                    </div>
                                </form>        
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                            
                        </div>                    
                </div>
              </div>
        </div>

        <!-- ******** -->
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
    #frmOTPLogin{
        width:100%;
    }
    #frmVerifyOTP {
        width: 100%;
    }

    #frmVerifyOTP  a{
        font-size: 13px;
    }

</style>

<script>

    import store from '../store';

    export default {
        name:'Login', 
        beforeMount(){

            this.remember_me = localStorage.remember_me;
                        
            if(this.remember_me == true){
                this.checkSelected = " checked "; 
            }else{
                this.checkSelected = ""; 
            }
        },
        updated() {

        },
        mounted(){
        	
             
            if(localStorage.mevid > 0 ){                
                this.$router.push({ 
                    name:'dashboard',
                    
                });
            }

            this.remember_me = localStorage.remember_me;
            
            if(this.remember_me == true){
                this.checkSelected = " checked "; 
            }else{
                this.checkSelected = ""; 
            }

            this.msgsuccess = localStorage.msgsuccess;            
            this.textmsg = localStorage.textmsg;       
            if(localStorage.textmsg == ''){
                this.msgsuccess = false;
            } 

            this.logininfo.email = localStorage.remember_email;
            this.logininfo.password = localStorage.remember_pass;
            this.remember_me = localStorage.remember_me;
            
            if(this.remember_me == true){
                this.checkSelected = " checked "; 
            }else{
                this.checkSelected = ""; 
            }

           
            localStorage.msgsuccess = false;        
            localStorage.textmsg = '';   

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
                remember_email:'',
                remember_pass:'',
                remember_me:false,
                checkSelected:'',
                sendotpform:true,
                verifyotpform:false,
                msg_otp_verification:'',
                checked:false,
                logininfo:{                    
                    email:'',
                    password:'',  
                    new_email:'',                  
                },   
                otpinfo:{
                    user_email:'',
                    new_otp:'',
                },    
            }
        },
        methods:{
            closeotpwindow(){
                this.msg_otp_verification='';
                this.verifyotpform=false;
                this.sendotpform=true;
                
            },
            showmainlogin(){

                this.msg_otp_verification='';
                this.verifyotpform=false;
                this.sendotpform=true;

                jQuery('#otpLoginModal').modal('hide');

            },
            openotpform(){
                this.verifyotpform=false;
                this.sendotpform=true;
            },
            clearmsg(){

                if(this.logininfo.email != ''){
                    this.msg.email = '';
                }                 

                if(this.logininfo.password != ''){
                    this.msg.password = ''; 
                } 

                if(this.logininfo.new_email != ''){
                    this.msg.new_email = ''; 
                } 

                if(this.otpinfo.new_otp != ''){
                    this.msg.new_otp = ''; 
                }                 
            },
            submitVerifyOTP(e){
                e.preventDefault(); 

                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                if(!this.otpinfo.new_otp){
                    this.hasError=true;
                    this.msg['new_otp']  = "Please enter otp";              
                }

                if(this.hasError == false){ 
                    var thisObj = this;
                    

                    this.axios
                    .post(api_url+'getToken',thisObj.otpinfo)
                    .then(function(response){
                        console.log(response);                        


                        /*if(response.data.status==0 || response.data.status=='0'){
                            thisObj.msgerror = true;            
                            thisObj.textmsg=response.data.msg;            
                        }*/

                        if(response.data.status==1 || response.data.status=='1'){
                            
                            thisObj.verifyotpform=true;
                            thisObj.sendotpform=false;
                            thisObj.msg_otp_verification = response.data.msg;

                            localStorage.mevid = 26;

                            localStorage.msgerror = false;            
                            localStorage.textmsg='Logged In successfully';            
                            
                            jQuery('#otpLoginModal').modal('hide');

                            thisObj.$router.push({
                                name:'dashboard',
                                params:{
                                    message : 'Logged In successfully',
                                }
                            });

                            //thisObj.$parent.msgsuccess=true;             
                            //thisObj.$parent.textmsg=response.data.msg;                                     
                        }else{
                            thisObj.msg_otp_verification = response.data.msg;
                        } 
                    });
                }        
            },
            submitOTPLogin(e){
                e.preventDefault(); 

                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];    

                if(!this.logininfo.new_email){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['new_email'] = "please enter your email address";
                }else if (!this.validEmail(this.logininfo.new_email)){                    
                    this.msg['new_email'] = "Please enter Valid email address. ";
                }


                if(this.hasError == false){ 

                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'sendotp',this.logininfo)
                    .then(function(response){
                        console.log(response);                        


                        /*if(response.data.status==0 || response.data.status=='0'){
                            thisObj.msgerror = true;            
                            thisObj.textmsg=response.data.msg;            
                        }*/

                        if(response.data.status==1 || response.data.status=='1'){
                            
                            thisObj.verifyotpform=true;
                            thisObj.sendotpform=false;
                            thisObj.msg_otp_verification = response.data.msg;
                            thisObj.otpinfo.user_email = thisObj.logininfo.new_email;
                            //thisObj.$parent.msgsuccess=true;             
                            //thisObj.$parent.textmsg=response.data.msg;                                                                                  
                        }else{
                            thisObj.msg_otp_verification = response.data.msg;
                        }
                    }); 
                }
            },
            submitLogin(e){ 
                
                e.preventDefault(); 
                
                if(this.remember_me == true){
                    localStorage.remember_email = this.logininfo.email;
                    localStorage.remember_pass = this.logininfo.password;
                    localStorage.remember_me = true;
                }else{
                    localStorage.remember_email = '';
                    localStorage.remember_pass = '';
                    localStorage.remember_me = false;
                }

                

                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                
                if(!this.logininfo.email){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['email'] = "please enter email ";
                }else if (!this.validEmail(this.logininfo.email)){
                    this.errors.push('Please enter Valid email address.');
                }

                if(!this.logininfo.password){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['password']=true;
                    this.msg['password'] = "please enter password";
                }

                
                if(this.hasError == false){ 
 
                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'signin',this.logininfo)
                    .then(function(response){
                        //console.log("Here data");                   
                        console.log(response);                   
                        

                        localStorage.mevid = 26;
                        localStorage.username = response.data.data.firstname;                        
                        //localStorage.mevid = response.data.data.reg_id;

                        if(response.data.data.is_admin == 1){ 
                            localStorage.is_admin = true;
                        }else{
                            localStorage.is_admin = false;
                        }
                        

                        thisObj.msgerror = false;            
                        thisObj.textmsg='Logged In successfully';            


                        thisObj.$router.push({
                            name:'dashboard',
                            params:{
                                message : 'Logged In successfully',
                            }
                        });


                        /*if(response.data.status==0 || response.data.status=='0'){
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
                                name:'dashboard',
                                params:{
                                    message : response.data.msg,
                                }
                            });
                        }*/


                    }); 
                }
            },            
            validEmail: function (email) {
              var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              return re.test(email);
            }
        },
        created(){

            var thisObj = this;
                        
            setInterval(function(){
                
                thisObj.textmsg = '';                 
                thisObj.msgsuccess = false;
                thisObj.msgerror = false;

                //localStorage.msgsuccess = false;        
                //localStorage.textmsg = '';   

            }, 5000);
        },
        watch:{            
            checked:function(val) {
                if(this.remember_me == true){ 
                    return " checked ";
                }else{
                    return " ";
                }                                              
            },
        }  
    }            

</script>