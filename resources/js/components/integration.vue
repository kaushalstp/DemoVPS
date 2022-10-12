<template>
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Integration</h1>
                    </div>                  
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                  <div class="col-12">
                    <div class="callout callout-info">              
                        We offers the following options to integrate with
                    </div>


                    <!-- Main content -->
	                    <div class="invoice p-3 mb-3">
	                      <!-- title row -->
	                                                
	                      <!-- Table row -->
	                        <div class="row">                    
	                            <div class="col-sm-12" >
	                                <div class="row" >

	                                    <div class="col-md-3">
	                                        <div class="card card-danger">
	                                          <div class="card-header">
	                                            <h3 class="card-title">Mailchimp</h3>
	                                          </div>
	                                            <div class="card-body">

	                                                    <p class="text-center">
	                                                        <img :src="mailchimpicon" />
	                                                    </p> 
	                                                    <br/>

	                                                    <button type="button" class="btn btn-block btn-primary"
	                                                    @click="logintoemailapis('mailchimp')" v-show="show_mailchimp_login">
	                                                        Connect                                               
	                                                    </button> 

	                                                    <!-- <button type="button" class="btn btn-block btn-primary"
	                                                    @click="connectIntegration('mailchimp')" 
	                                                    v-show="show_mailchimp_login" data-toggle="modal" data-target="#modalapikey" >
	                                                        Connect                                               
	                                                    </button> -->
	                                                    
	                                                    <select v-show="show_mailchimp_list" 
	                                                    v-model="mycontactid" class="form-control" 
	                                                    name="drp_mailchimplist" id="drp_mailchimplist" >
	                                                        <option value="" > -- Select List -- </option>
	                                                        <option v-for="mylist in mailchimp_contact_list" 
	                                                        :value="mylist.id">{{mylist.name}}</option>                                                    
	                                                    </select><Br/>

	                                                    <span v-show="show_mailchimp_list">
	                                                        <br/>
	                                                        <button @click="importcontacts('mailchimp')" class="btn btn-primary"
	                                                        v-show="show_mailchimp_list"  > Import </button>

	                                                        <button @click="disconnect('mailchimp')" class="btn btn-danger" v-show="show_mailchimp_list" > Disconnect </button>
	                                                    </span>
	                                                    
	                                                    <p v-show="!show_mailchimp_list" >
	                                                        Connect your account to enable integration
	                                                    </p>
	                                            </div>
	                                          <!-- /.card-body -->
	                                        </div>
	                                        <!-- /.card -->
	                                    </div>

	                                    <div class="col-md-3">
	                                        <div class="card card-danger">
	                                          <div class="card-header">
	                                            <h3 class="card-title">Aweber</h3>
	                                          </div>
	                                            <div class="card-body">

	                                                <p class="text-center"><img :src="awebericon" /> </p> <br/>

	                                                    <button  @click="logintoemailapis('aweber')"  
	                                                    type="button" class="btn btn-block btn-primary" 
	                                                    v-show="show_aweber_login" >
	                                                        Connect
	                                                    </button>                                                       

	                                                    <select v-show="show_aweber_list" 
	                                                    v-model="awebercontactid" class="form-control" 
	                                                    name="drp_aweberlist" id="drp_aweberlist" >
	                                                        <option value="" > -- Select List -- </option>
	                                                        <option v-for="mylist in aweber_contact_list" 
	                                                        :value="mylist.id">{{mylist.name}}</option>                                                    
	                                                    </select><Br/>

	                                                    <span v-show="show_aweber_list">
	                                                        <br/>
	                                                        <button @click="importcontacts('aweber')" class="btn btn-primary"
	                                                        v-show="show_aweber_list"  > Import </button>

	                                                        <button @click="disconnect('aweber')" class="btn btn-danger" v-show="show_aweber_list" > Disconnect </button>
	                                                    </span>
	                                                    
	                                                    <p v-show="!show_aweber_list" >
	                                                        Connect your account to enable integration
	                                                    </p>                                                
	                                            </div>
	                                          <!-- /.card-body -->
	                                        </div>
	                                        <!-- /.card -->
	                                    </div>

	                                    <div class="col-md-3">
	                                        <div class="card card-danger">
	                                          <div class="card-header">
	                                            <h3 class="card-title">GetResponse</h3>
	                                          </div>
	                                            <div class="card-body">

	                                                <p class="text-center"><img :src="GetResIconicon" /> </p><br/>

	                                                    <button  @click="logintoemailapis('GetResponse')"  type="button" class="btn btn-block btn-primary" 
	                                                    v-show="show_getresponse_login"  >
	                                                        Connect                                               </button>   
	                                                                                                           
	                                                    <select v-show="show_getresponse_list" 
	                                                    v-model="getresponsecontactid" class="form-control" 
	                                                    name="drp_aweberlist" id="drp_aweberlist" >
	                                                        <option value="" > -- Select List -- </option>
	                                                        <option v-for="mylist in getresponse_contact_list" 
	                                                        :value="mylist.id">{{mylist.name}}</option>                                                    
	                                                    </select><Br/>

	                                                    <span v-show="show_getresponse_list">
	                                                        <br/>
	                                                        <button @click="importcontacts('getresponse')" class="btn btn-primary"
	                                                        v-show="show_getresponse_list"  > Import </button>

	                                                        <button @click="disconnect('getresponse')" class="btn btn-danger" v-show="show_getresponse_list" > Disconnect </button>
	                                                    </span>  

	                                                    <p v-show="!show_getresponse_list" >
	                                                        Connect your account to enable integration
	                                                    </p>                                                                                                  
	                                            </div>
	                                          <!-- /.card-body -->
	                                        </div>
	                                        <!-- /.card -->
	                                    </div>
	                                    

	                                    <div class="col-md-3">
	                                        <div class="card card-danger">
	                                            <div class="card-header">
	                                                <h3 class="card-title">Constant Contact</h3>
	                                            </div>

	                                            <div class="card-body">

	                                                <p class="text-center">
	                                                <img :src="constant_contacticon" /> </p> <br/>

	                                                    <button  @click="logintoemailapis('constantcontact')" v-show="show_constcontact_login" 
	                                                    type="button" class="btn btn-block btn-primary">
	                                                        Connect                                                      </button>   
	                                                        <br/>

	                                                 <select v-show="show_constcontact_list" 
	                                                    v-model="getcccontactid" class="form-control" 
	                                                    name="drp_aweberlist" id="drp_aweberlist" >
	                                                        <option value="" > -- Select List -- </option>
	                                                        <option v-for="mylist in cc_contact_list" 
	                                                        :value="mylist.id">{{mylist.name}}</option>                                                    
	                                                    </select>

	                                                    <span v-show="show_constcontact_list">
	                                                        <br/>
	                                                        <button @click="importcontacts('constantcontact')" 
	                                                        class="btn btn-primary"
	                                                        v-show="show_constcontact_list"  > Import </button>

	                                                        <button @click="disconnect('constantcontact')" class="btn btn-danger" v-show="show_constcontact_list" > Disconnect </button>
	                                                    </span>         

	                                                <p v-show="!show_constcontact_list" >
	                                                    Connect your account to enable integration
	                                                </p>
	                                            </div>
	                                          <!-- /.card-body -->
	                                        </div>
	                                        <!-- /.card -->
	                                    </div>

	                                    <div class="col-md-3">
	                                        <div class="card card-danger">
	                                            <div class="card-header">
	                                                <h3 class="card-title">Send Grid</h3>
	                                            </div>
	                                            
	                                            <div class="card-body">

	                                                <p class="text-center"><img :src="sendgridicon" /> </p> <br/>

	                                                    <button  @click="comingsoonalert"  type="button" class="btn btn-block btn-primary">
	                                                        Connect  
	                                                    </button>   
	                                                    <br/>
	                                                    <p>
	                                                        Connect your account to enable integration
	                                                    </p>
	                                            </div>
	                                          <!-- /.card-body -->
	                                        </div>
	                                        <!-- /.card -->
	                                    </div>

	                                    <!-- <div class="col-md-3">
	                                        <div class="card card-danger">
	                                          <div class="card-header">
	                                            <h3 class="card-title">HubSpot</h3>
	                                          </div>
	                                            <div class="card-body">

	                                                <p class="text-center"><img :src="hubspoticon" /> </p> <br/>

	                                                    <button  @click="logintoemailapis('hubspot')"  
	                                                    type="button" class="btn btn-block btn-primary" 
	                                                    v-show="show_aweber_login" >
	                                                        Connect
	                                                    </button>                                                       

	                                                    <select v-show="show_aweber_list" 
	                                                    v-model="awebercontactid" class="form-control" 
	                                                    name="drp_aweberlist" id="drp_aweberlist" >
	                                                        <option value="" > -- Select List -- </option>
	                                                        <option v-for="mylist in aweber_contact_list" 
	                                                        :value="mylist.id">{{mylist.name}}</option>                                                    
	                                                    </select><Br/>

	                                                    <span v-show="show_aweber_list">
	                                                        <br/>
	                                                        <button @click="importcontacts('aweber')" class="btn btn-primary"
	                                                        v-show="show_aweber_list"  > Import </button>

	                                                        <button @click="disconnect('aweber')" class="btn btn-danger" v-show="show_aweber_list" > Disconnect </button>
	                                                    </span>
	                                                    
	                                                    <p v-show="!show_aweber_list" >
	                                                        Connect your account to enable integration
	                                                    </p>                                                
	                                            </div>                                          
	                                        </div>                                        
	                                    </div> -->
	                                </div>    
	                            </div>
	                        </div>
	                                            
	                        </div>                    
	                    </div>
                    </div>
                </div>


                <div class="row" >                    
                    <div class="modal fade" id="modalapikey" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">
                            <img :src="mevlogo" width="200" alt="MEV"class="brand-image" style="opacity: .8"/>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                            <div class="modal-body">
                            
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <form name="frmAPIKey" id="frmAPIKey" role="form" @submit.prevent="saveMyAPIKey"  enctype="multipart/form-data" >
                                                
                                                <input type="hidden" v-model="library" />

                                                <input type="text"  v-model="apikey" />

                                                <button  >Save</button>

                                            </form>

                                            

                                        </div>
                                      
                                    </div>
                                </div>    
                            </div>
                          <div class="modal-footer">
                                                                                     
                            
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancel
                            </button>

                          </div>
                        </div>
                      </div>
                    </div>
                </div>


        </section>    
    </div>    
</template>

<script> 
        
    export default {
        name:'Integration',
        data:function(){ 
            return{
                apikey:'',
                library:'',
                mailchimpicon:img_url+'mailchimp.png',
                GetResIconicon:img_url+'getres.jpg',
                awebericon:img_url+'aweber.png',
                hubspoticon:img_url+'hubspot_logo.png',
                constant_contacticon:img_url+'constant_contact.jpg',
                sendgridicon:img_url+'sendgrid.png',
                mailchimpauth:'',
                getresponseauth:'',
                ccauth:'',
                aweberauth:'', 
                show_mailchimp_login:true,
                show_mailchimp_list:false,
                show_aweber_login:true,
                show_aweber_list:false,
                show_getresponse_login:true,
                show_getresponse_list:false,
                show_constcontact_login:true,
                show_constcontact_list:false,
                getresponse_contact_list:[],
                mailchimp_contact_list:[],
                aweber_contact_list:[],
                mycontactid:'',
                awebercontactid:'',
                getresponsecontactid:'',
                getcccontactid:'',
                aweber_client_id:'',
                aweber_client_secret:'',
                aweber_token:'',
            }
        },
        mounted(){       

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

                if(response.data.status == 1 || response.data.status == '1'){ 
                                                    
                    localStorage.setItem('mevid',response.data.data.userid);
                }
            });


            this.axios
            .get(api_url+'getIntegrations') 
            .then(function(response){
                //console.log(response);      
                if(response.data.status == '1'){                    
                    thisObj.mailchimp_contact_list = response.data.data.mailchimp_contact_list;
                    //console.log(thisObj.mailchimp_contact_list);
                    thisObj.mailchimpauth = response.data.data.mailchimp_auth_url;
                    thisObj.aweberauth = response.data.data.aweber_auth_url;
                    thisObj.getresponseauth = response.data.data.getresponse_auth_url;
                    thisObj.ccauth = response.data.data.cc_auth_url;
                    thisObj.hubspotauth = response.data.data.hubspot_auth_url;

                    thisObj.aweber_client_id = response.data.data.aweber_client_id;
                    thisObj.aweber_client_secret = response.data.data.aweber_client_secret;
                    
                    if(thisObj.mailchimp_contact_list.length > 0){
                        thisObj.show_mailchimp_list = true;
                        thisObj.show_mailchimp_login = false;
                    }                    

                    if(response.data.data.mailchimp_connected == 'y'){
                        thisObj.show_mailchimp_list = true;
                        thisObj.show_mailchimp_login = false;
                    }else{
                        thisObj.show_mailchimp_list = false;
                        thisObj.show_mailchimp_login = true;
                    }                                

                    if(response.data.data.aweber_connected == 'y'){
                        thisObj.show_aweber_list = true;
                        thisObj.show_aweber_login = false; 
                        thisObj.aweber_contact_list = response.data.data.aweber_contact_list;
                    }else{
                        thisObj.show_aweber_list = false;
                        thisObj.show_aweber_login = true;
                    }                                

                    if(response.data.data.getresponse_connected == 'y'){
                        thisObj.show_getresponse_list = true;
                        thisObj.show_getresponse_login = false; 
                        thisObj.getresponse_contact_list = response.data.data.getresponse_contact_list;
                    }else{
                        thisObj.show_getresponse_list = false;
                        thisObj.show_getresponse_login = true;
                    }  

                    if(response.data.data.cc_connected == 'y'){
                        thisObj.show_constcontact_list = true;
                        thisObj.show_constcontact_login = false; 
                        thisObj.cc_contact_list = response.data.data.cc_contact_list;
                    }else{
                        thisObj.show_constcontact_list = false;
                        thisObj.show_constcontact_login = true;
                    }                                                  
                }
            });  


            var rescode = this.$route.query.code;
            var aweber_state = this.$route.query.state;
            
            if(rescode != undefined){
                                

                if(aweber_state != undefined){
                
                    this.axios
                    .post(api_url+'getMyAweberToken',{ 
                        code:rescode,
                        redirect_uri:app_url+'integration',
                        client_id:thisObj.aweber_client_id,
                        client_secret:thisObj.aweber_client_secret,
                        grant_type:'authorization_code',
                    })
                    .then(function(response){ 
                        //console.log(response);
                        if(typeof response.access_token !== 'undefined'){
                            thisObj.aweber_token = response.access_token;
                        }                                                
                    }); 

                    this.axios
                    .post(api_url+'getMyAweberAccount',{ 
                            
                    })
                    .then(function(response){  
                        //console.log(response);
                        if(response.data.status == '1' || response.data.status == 1){
                            thisObj.show_aweber_list = true;
                            thisObj.show_aweber_login = false; 
                            thisObj.aweber_contact_list = response.data.data.aweber_contact_list;        
                        }    
                    }); 
                }else{

                    this.axios
                    .post(api_url+'getToken',{rescode:rescode,em_api:'mailchimp'})
                    .then(function(response){
                        //console.log(response);      
                        if(response.data.status == '1'){
                            thisObj.show_mailchimp_login = false;
                            thisObj.show_mailchimp_list = true;

                            thisObj.mailchimp_contact_list = response.data.contactlist.lists;
                        }
                    });      
                }            
            }                    
        },          
        methods:{
            saveMyAPIKey(){

                this.axios
                    .post(api_url+'saveMyAPIKey',{library:this.library,apikey:this.apikey})
                    .then(function(response){ 
                        
                        if(response.data.status == 1 || response.data.status == '1'){

                            if(apiname == 'mailchimp'){
                                thisObj.show_mailchimp_login = true;
                                thisObj.show_mailchimp_list = false;    
                            }
                            
                            if(apiname == 'aweber'){
                                thisObj.show_aweber_login = true;
                                thisObj.show_aweber_list = false;    
                            }                            

                            if(apiname == 'getresponse'){
                                thisObj.show_getresponse_login = true;
                                thisObj.show_getresponse_list = false;    
                            } 

                            if(apiname == 'constantcontact'){
                                thisObj.show_constcontact_login = true;
                                thisObj.show_constcontact_list = false;    
                            }                            

                        }                    
                });     

            },
            connectIntegration(apiname=''){
                this.library = apiname;
            },
            disconnect(apiname=''){

                var thisObj = this;
                if(!confirm("Are you sure to Disconnect?")){
                    return false;
                }                

                this.axios
                    .post(api_url+'disconnect',{apiname:apiname})
                    .then(function(response){ 
                        
                        if(response.data.status == 1 || response.data.status == '1'){

                            if(apiname == 'mailchimp'){
                                thisObj.show_mailchimp_login = true;
                                thisObj.show_mailchimp_list = false;    
                            }
                            
                            if(apiname == 'aweber'){
                                thisObj.show_aweber_login = true;
                                thisObj.show_aweber_list = false;    
                            }                            

                            if(apiname == 'getresponse'){
                                thisObj.show_getresponse_login = true;
                                thisObj.show_getresponse_list = false;    
                            } 

                            if(apiname == 'constantcontact'){
                                thisObj.show_constcontact_login = true;
                                thisObj.show_constcontact_list = false;    
                            }                            

                        }                    
                });                            
            },
            comingsoonalert(){
                alert('Coming Soon');
            },
            logintoemailapis(libapi=""){

                if(libapi=="mailchimp"){
                    window.location=this.mailchimpauth;
                }                

                if(libapi=="aweber"){
                    window.location=this.aweberauth;
                } 

                if(libapi=="GetResponse"){
                    window.location=this.getresponseauth;
                } 

                if(libapi=="constantcontact"){
                    window.location=this.ccauth;
                }             
            },
            importcontacts(libapi=""){
                
                var thisObj = this;

                if(libapi == 'mailchimp'){
                    if(this.mycontactid == '' || this.mycontactid == undefined){
                        alert('Please select list');
                        return false;                    
                    }
                    
                    this.axios
                    .post(api_url+'getMylistInfo',{listid:thisObj.mycontactid})
                    .then(function(response){
                        //console.log(response);      
                        if(response.data.status == 1 || response.data.status == '1'){
                            alert(response.data.msg);
                        }                    
                    });                                                    
                }

                if(libapi == 'aweber'){
                    if(this.awebercontactid == '' || this.awebercontactid == undefined){
                        alert('Please select list');
                        return false;                    
                    }
                    
                    this.axios
                        .post(api_url+'getMyAweberListInfo',{listid:thisObj.awebercontactid})
                        .then(function(response){
                            //console.log(response);      
                            if(response.data.status == 1 || response.data.status == '1'){
                                alert(response.data.msg);
                            }                    
                    });                                                            
                }

                if(libapi == 'getresponse'){

                    if(this.getresponsecontactid == '' || this.getresponsecontactid == undefined){
                        alert('Please select list');
                        return false;                    
                    }
                    
                    this.axios
                        .post(api_url+'getMyGetResponseListInfo',{listid:thisObj.getresponsecontactid})
                        .then(function(response){
                            //console.log(response);      
                            alert(response.data.msg);
                            if(response.data.status == 1 || response.data.status == '1'){
                                //alert(response.data.msg);
                            }                    
                    });     
                }


                if(libapi == 'constantcontact'){

                    if(this.getcccontactid == '' || this.getcccontactid == undefined){
                        alert('Please select list');
                        return false;                    
                    }
                    
                    this.axios
                        .post(api_url+'getMyCCListInfo',{listid:thisObj.getcccontactid})
                        .then(function(response){
                            //console.log(response);      
                            alert(response.data.msg);
                            if(response.data.status == 1 || response.data.status == '1'){
                                //alert(response.data.msg);
                            }                    
                    });     
                }
                
            }                
        }       
    }
</script>
