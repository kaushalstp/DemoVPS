
<template>

    <div class="row" style="background: url(https://client.myemailverifier.com/public/img/promo_img.jpg);width: 100%;    min-height: 620px;" >
            <div class="col-sm-8" ></div>


            <div class="col-sm-4 whitebox ">
                <div class="login-box" >    
                    <div class="login-logo">
                        <img :src="adminlogo"
                               alt="AdminLTE Logo"
                               class="brand-image"
                               style="opacity: .8" />
                    </div>

                  <!-- /.login-logo -->
                    <div class="">
                        <div class="">
                            <p class="login-box-msg">Create new Account</p>

                            <div class="alert alert-danger" role="alert" v-show="msgerror" >
                                {{ textmsg }}

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                            </div>

                            <form role="form" method="post" id="frmRegister" name="frmRegister" 
                            @submit.prevent="submitRegisterData"  > 
                                <div class="input-group mb-3"> 
                                    <input type="text" name="firstname" id="firstname" v-model="reginfo.firstname"  
                                    class="form-control form-control-lg" placeholder="First Name" @keyup="clearmsg" >                

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                          <span class="fas fa-user"></span>
                                        </div>
                                    </div>

                                    <br/>
                                    <span class="text-danger" v-if="msg.firstname" >{{msg.firstname}}</span>
                                </div>


                                <div class="input-group mb-3">
                                  <input type="text" name="lastname" id="lastname" v-model="reginfo.lastname"   
                                  class="form-control form-control-lg" placeholder="Last Name"  @keyup="clearmsg"  >
                                  
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span class="fas fa-user"></span>
                                    </div>
                                  </div>

                                  <br/>
                                  <span class="text-danger" v-if="msg.lastname">{{msg.lastname}}</span>

                                </div>

                                <div class="input-group mb-3">
                                  <input type="email"  name="email" id="email" v-model="reginfo.email" class="form-control form-control-lg" placeholder="Email" @keyup="clearmsg" >
                                  
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>

                                    <br/>
                                    <span class="text-danger" v-if="msg.email">{{msg.email}}</span>

                                </div>
                                <div class="input-group mb-3">
                                  <input type="password"  name="password" id="password" v-model="reginfo.password" class="form-control form-control-lg" placeholder="Password" @keyup="clearmsg" >
                                  
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span class="fas fa-lock"></span>
                                    </div>
                                  </div>
                                  <br/>
                                  <span class="text-danger" v-if="msg.password">{{msg.password}}</span>

                                </div>
                                <div class="input-group mb-3">
                                  <input type="password"  name="confirm_password" id="confirm_password" v-model="reginfo.confirm_password" class="form-control form-control-lg" placeholder="Retype password" @keyup="clearmsg" >
                                  
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      <span class="fas fa-lock"></span>
                                    </div>
                                  </div>

                                  <br/>
                                  <span class="text-danger" v-if="msg.confirm_password">{{msg.confirm_password}}</span>

                                </div>
                                <div class="icheck-primary">
                                  <input type="checkbox" id="agreeTerms" name="terms" 
                                  v-model="reginfo.termscond" value="agree"  >
                                  <label for="agreeTerms">
                                   &nbsp; <a href="javascript:void(0);" data-toggle="modal" 
                                   data-target="#termsconditions" >Terms & Conditions</a>
                                  </label>
                                </div>
                                    <button type="submit" class="btn btn-primary sign_btn btn-block">Register</button>
                            </form>
                                                  
                          <div class="mt-3 mb-1">            
                            <router-link to="/Login" class="btn btn-light d-block" style="font-size: 22px">
                                Already Registered? Sign In
                            </router-link>
                          </div>
                        </div>
                    <!-- /.login-card-body -->                          

                        <span class="text-danger">
                          &nbsp; Notes:- Disposable domains are strictly prohibited.
                        </span> 

                    </div>

                    <div class="modal fade" id="termsconditions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel"><b>Terms & Conditions</b></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">                        
                          <p>These Terms and Conditions ("Agreement") govern the use of the services ("Service") that are made accessible by MyEmailVerifier ("myemailverifier.com", "Website", "we", and "us"). These Terms and Conditions attest to the complete agreement and understanding between MyEmailVerifier and the individual who uses any of our services ("User", "Client", or "you").</p>
									<p>By using our services, you are agreeing entirely to these Terms and Conditions. In the event that you do not agree with these Terms and Conditions or any part of them, you should not use any of the services made available on this website.</p>
									<h4>Using Our Services</h4>
									<p>To use our services, you must follow every policy made available to you within the Service and you must not misuse the Service in any way whatsoever. You must not use the Website to transmit or send unsolicited advertisements, marketing contents or commercial contents. You must not use the Website in any way that causes or may cause damage to the Website or its availability and accessibility. You must not use the Website in any way that is illegal, unlawful, harmful, fraudulent, or in connection with any illegal, unlawful, harmful or fraudulent purpose or activity. You must not use MyEmailVerifier for any purpose that is either unlawful or prohibited by these Terms and Conditions; you must not use the Website in any way other than the ways mentioned.</p>
									<h4>Restricted Access</h4>
									<p>On this Website, some areas and webpages are restricted or limited to specific individuals. If you do not have access to such area or webpage, you must not try to access it using a method other than the usual interface and the instructions we provide.</p>
									<p>MyEmailVerifier, in its sole discretion, reserves the right to restrict access to areas of this Website or the whole Website. In case we provide you with an account to enable access to restricted areas of the website "myemailverifier.com", its services or content, please be informed that the information is treated with high confidentiality.</p>
									<h4>Legal Advice</h4> 
									<p>None of the contents on the website "myemailverifeir.com" constitute legal advice; they should not be relied upon when making a legal decision or when refraining from a legal decision. Do note that all contents on MyEmailVerifier are provided without any warranty whatsoever. You agree that you are using MyEmailVerifier is at your own discretion.</p>
									<h4>Change of Use</h4>
									<p>MyEmailVerifier may, in its sole discretion, change or modify these Terms and Conditions at any time, with or without notice. MyEmailVerifier reserves the right to change or remove the Website or any part of it temporarily or permanently with or without notice. You agree that we shall not be liable to you for any such change/modification or removal. Your continued use of the Website following any changes shall be deemed to be your acceptance of such change.</p>
									<h4>User Content</h4>
									<p>User content, in these Terms and Conditions, refers to uploaded files that are verifiable, emails tracked and verified through the service and emails acquired from API integration that you submit to the Website regardless of the purpose. User content is the sole responsibility of the individual or entity that makes it available. It must not be illegal or unlawful, must not infringe any third party's legal rights, and must not be capable of giving rise to legal action against you, MyEmailVerifier, or a third party.</p>
									<p>User content that is or has been subjected to any past or current legal complaints or other similar complaints must not be submitted to the Website. MyEmailVerifier, in its sole discretion, reserves the right to edit or remove any user content submitted to this website, published or hosted on this website, and stored on MyEmailVerifierâ€™s servers.</p>
									<p>By using this website, you agree that MyEmailVerifier reserves the right to access your data for the sole purpose of verification or validation. For example, MyEmailVerifier may view your email address(es) when submitted for email confirmation whether the email address exists or does not.</p>
									<p>To ensure the constant safety of your data, MyEmailVerifier uses 2-way encryption methods to encrypt the data. Your data are kept safe and we do not sell, share, or use your data in any other way than validating email addresses.</p>
									<p>Do note that when you delete your data from our servers, it will be permanently deleted and you will not be able to recover it/them.</p>
									<h4>Linking to Other Websites</h4>
									<p>MyEmailVerifier may contain links to third party websites. These links are provided solely as a convenience to you and by linking to them, we do not form or have an affiliation with or sponsor such third party websites. The inclusion of third party links within MyEmailverifier does not constitute any guarantee, endorsement, or recommendation of such third party websites. MyEmailVerifier has no control over the legal documents and privacy practices of third party websites; as such, you access any such third party websites at your own risk.</p>
									<h4>Copyright</h4>
									<p>All copyright, trademarks, patents, service marks, trade names, trade secrets, and other intellectual property rights of this Website and its content (including, but not limited to, the Website design, text, graphics and all programs and source codes connected with the Website) are owned by or licensed to MyEmailVerifier, or otherwise used by MyEmailVerifier as permitted by law.</p>
									<p>By accessing the Website, you agree that none of the content may be downloaded, copied, reproduced, stored, sold or distributed without the prior written consent of the copyright holder. You will access the content solely for your personal, non-commercial use; as such, the downloading, copying and/or printing of pages of the Website is exclusively available for personal, non-commercial, or home use only.</p>
									<h4>No Warranties</h4>
									<p>MyEmailVerifier does not make any specific commitments about the Service. The Website does not make any promises about certain functions of the Service, its reliability, or availability of this Website at any given time. The Service is provided on an "as is" basis and MyEmailVerifier excludes all warranties or guarantees.</p>
									<p>Without prejudice to the generality of the previous paragraph, MyEmailVerifier does not warrant that:</p>
									<ul class="hidden-text-list black-font">
									<li>This Website will be available at all times, nor available at all.</li>
									<li>The information on this Website is complete, accurate, non-misleading, or true.</li>
									</ul>
									<h4>Limitations and Exclusions of Liability</h4>
									<p>The Website is provided on an "as is" and "as available" basis and the use of MyEmailVerifier is at your own risk. MyEmailVerifier makes no endorsements, representations or warranties, either expressed or implied (including, but not limited to, the implied warranties of satisfactory quality, suitability for a particular purpose, non-infringement, compatibility, security, and accuracy), with respect to the Website, or any information or service provided through the Website.</p>
									<p>The Website will not be provided free-of-charge, for any loss. In no event will MyEmailVerifier be responsible for any indirect, direct, consequential or incidental damages, injury, or economic or financial loss (which includes loss of revenue, income, profits or anticipated savings, any business losses, loss of reputation or goodwill, loss of contracts or business relationships, or loss and/or corruption of information or data) arising from the use of the content or services provided by the website "myemailverifier.com".</p>
									<p>No part of these Terms and Conditions shall be construed so as to exclude or limit the liability of MyEmailVerifier for personal injury or death as a result of the negligence of MyEmailVerifier or that of its owners, staff, agents, employees, subsidiaries, successors, or sub-contractors.</p>
									<p>In no event shall MyEmailVerifier be obligated to complete repayment of any costs to the user but the credit package purchased by said user, at all times. Even if MyEmailVerifier or its owners, staff, agents, employees, subsidiaries, successors, or sub-contractors have been expressly advised or informed of the potential loss, MyEmailVerifier shall not be held liable for any damages or economic loss whatsoever and these limitations of liability apply.</p>
									<h4>Third Parties</h4>
									<p>MyEmailVerifier, as a limited liability entity, has an interest in limiting the personal liability of its staff or employees; you accept and agree with this. You agree that you will not bring any personal claim against MyEmailVerifier's staff or employees regarding any damage, injury or loss resulting from the use of the Service or of the Website.</p>
									<p>MyEmailVerifier's owners, staff, agents, employees, subsidiaries, successors, or sub-contractors as well as MyEmailVerifier itself are all protected by the limitations of liability and warranties expressed or implied in these Terms and Conditions.</p>
									<h4>Indemnity</h4>
									<p>You hereby consent to indemnify and hold MyEmailVerifier, its subsidiaries, affiliates, sponsors, partners, owners and employees safe from and against, and to reimburse MyEmailVerifier regarding, any and all misfortunes, harms, losses, liabilities, claims, damages, settlements, fines, judgments, expenses and costs (which includes sensible related costs, lawful charges or legal fees, investigation expenses) emerging out of or identifying with your breach of these Terms and Conditions or any claim that you have breached any provision of these Terms and Conditions or use by you or any other individual or entity of the Services.</p>
									<h4>Assignment</h4>
									<p>MyEmailVerifier may, in its sole discretion, transfer, sub-contract or deal with its rights and/or obligations under these Terms and Conditions without obtaining your consent or notifying you.</p>
									<h4>Severability</h4>
									<p>If by any chance a part or provision of these Terms and Conditions are judged by any court of law to be unlawful and/or unenforceable, the remaining provisions will continue to be applicable. If any unlawful and/or unenforceable provision turns out to be lawful or enforceable but a part of it has been deleted, this part will be considered as deleted and the rest of the provision will still be applicable.</p>
									<h4>Law and Jurisdiction</h4>
									<p>These Terms and Conditions shall be governed and construed in accordance with the law and you hereby submit to the exclusive jurisdiction of the courts.</p>
									<h4>Contact Us</h4>
									<p>If you have any question or inquiry related to these Terms and Conditions, MyEmailVerifier's services or practices, or your dealings with us, kindly contact us today so we can quickly deliver impeccable answers.</p> 
                              </div>
                              <div class="modal-footer">                
                                <button type="button" class="btn btn-primary" data-dismiss="modal" >Ok</button>
                              </div>
                            </div>
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
        name:'Register',
        mounted(){

            

            

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


            this.msgsuccess = localStorage.msgsuccess;            
            this.textmsg = localStorage.textmsg;       
            if(localStorage.textmsg == ''){
                this.msgsuccess = false;
            }

             /* check user's Login */        

                     
            
            /* ------------- */            
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
                reginfo:{
                    firstname:'',
                    lastname:'',
                    email:'',
                    password:'',
                    confirm_password:'',
                    termscond:'',
                },            
            }
        },
        methods:{
            getCookie(c_name="gotosupport") {
              var c_start = 0;
                if (document.cookie.length > 0) {
                    c_start = document.cookie.indexOf(c_name + "=");
                    if (c_start != -1) {
                        c_start = c_start + c_name.length + 1;
                        c_end = document.cookie.indexOf(";", c_start);
                        if (c_end == -1) {
                            c_end = document.cookie.length;
                        }
                        return unescape(document.cookie.substring(c_start, c_end));
                    }
                }
                return "";
            },
            submitRegisterData(e){
                e.preventDefault();                     
            
                

                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                if(!this.reginfo.firstname){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['firstname']=true;
                    this.msg['firstname'] = "please enter first name";
                }


                if(!this.reginfo.lastname){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['lastname']=true;
                    this.msg['lastname'] = "please enter last name";
                }

                if(!this.reginfo.email){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['email']=true;
                    this.msg['email'] = "please enter email ";
                }else if (!this.validEmail(this.email)) {
                    this.errors.push('Please enter Valid email address.');
                }

                if(!this.reginfo.password){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['password']=true;
                    this.msg['password'] = "please enter password";
                }

                if(!this.reginfo.confirm_password){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    //this.vlds['confirm_password']=true;
                    this.msg['confirm_password'] = "please confirm password";
                }

                if(this.reginfo.password != this.reginfo.confirm_password){
                    //this.errors.push('please select department');
                    this.hasError=true;
                    this.vlds['confirm_password']=true;
                    this.msg['confirm_password'] = "confirm password did not match";
                }

                if(!this.reginfo.termscond){
                    alert('Please check “Terms and Conditions” checkbox to continue.');
                    return false;
                }

                if(this.hasError == false){

                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'createUser',this.reginfo)
                    .then(function(response){
                        //console.log(response);                        
                        if(response.data.status==0 || response.data.status=='0'){
                            thisObj.msgerror = true;            
                            thisObj.textmsg=response.data.msg;            
                        }

                        if(response.data.status==1 || response.data.status=='1'){
                            
                            thisObj.$parent.msgsuccess=true;            
                            thisObj.$parent.textmsg=response.data.msg;          

                            localStorage.msgsuccess  = true;
                            localStorage.textmsg = response.data.msg;          

                            thisObj.$router.push({
                                name:'Login',
                                params:{
                                    registered_email:response.data.registered_email,
                                    /*msgsuccess:true,
                                    msgerror:false,
                                    textmsg:'Account activated. please login to continue'*/
                                }
                            });
                        }

                    });
                }
            },
            clearmsg:function(){
            

                if(this.reginfo.firstname != ''){
                    this.msg.firstname = '';
                }                

                if(this.reginfo.lastname != ''){
                    this.msg.lastname = '';
                }                

                if(this.reginfo.email != ''){
                    this.msg.email = '';
                }                

                if(this.reginfo.password != ''){
                    this.msg.password = '';
                }                

                if(this.reginfo.confirm_password != ''){
                    this.msg.confirm_password = '';
                }                            
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

        }        
    }            

</script>