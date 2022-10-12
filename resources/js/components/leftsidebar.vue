<template>
    <!-- Main Sidebar Container -->


    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <!-- <a href="../../index3.html" class="brand-link">
          <img :src="adminlogo"
               alt="AdminLTE Logo"
               class="brand-image elevation-3"
               style="opacity: .8">
              <span class="brand-text font-weight-light">&nbsp;</span>
        </a> --> 

        <router-link to="/Dashboard" class="nav-item nav-link brand-link">                    
                <img :src="adminlogo"
               alt="AdminLTE Logo"
               class="brand-image"
               style="opacity: .8">
              <span class="brand-text font-weight-light">&nbsp;</span>
        </router-link> 

        <!-- Sidebar -->


        <div class="sidebar">
          <!-- Sidebar user (optional) -->
            <div class="user-panel mt-2 pb-2 mb-2 d-flex">        
                <div class="info">
                    <a href="javascript:void(0);" class="d-block">Welcome {{ username }}</a> 
                </div>
            </div>
     
          <!-- Sidebar Menu -->
            <nav class="mt-2" v-if="noTodos" >
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true" >
                  <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
                    

                    <li class="nav-item">
                        <router-link to="/Dashboard" class="nav-item nav-link">                                        
                            <i class="fa fa-home" aria-hidden="true"></i>&nbsp;
                            
                            <p>Dashboard</p>
                        </router-link> 
                    </li>   


                    <li class="nav-item">                        
                        <router-link to="/users" class="nav-item nav-link">                    
                            <i class="fa fa-user" ></i>&nbsp;
                            <p>Users</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item">
                        <router-link to="/uploads" class="nav-item nav-link">                    
                            <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;
                            <p>Uploads</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item">
                        <router-link to="/orders" class="nav-item nav-link">                    
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;
                            <p>Orders</p>
                        </router-link> 
                    </li>   
                    

                    <li class="nav-item has-treeview">
                        <a href="javascript:void(0);" class="nav-link" @click="changearrow" >                  
                            <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; 
                          <p>
                            Reports
                            <i class="right fa fa-angle-left"></i>&nbsp;
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">

                                <router-link to="/Reports" class="nav-item nav-link">                    
                                  <!--<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; -->
                                  <p>Haighest Paid Users</p>
                                </router-link> 
                            
                            </li>

                            <li class="nav-item">
                                <router-link to="/ReportsHighestOrders" class="nav-item nav-link">                                                        
                                    <p>Highest Orders</p>
                                </router-link> 
                            </li>                  

                            <li class="nav-item">
                                <router-link to="/RevenueBySource" class="nav-item nav-link">                                                        
                                    <p>Revenue by Source</p>
                                </router-link> 
                            </li>                  

                            <li class="nav-item">
                                <router-link to="/ReportsSubscribers" class="nav-item nav-link">                                                        
                                    <p>Subscribers</p>
                                </router-link> 
                            </li>                  
                        </ul>
                    </li>

                    <li class="nav-item">
                        <router-link to="/coupons" class="nav-item nav-link">                    
                            <i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;
                            <p>Coupons</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item"> 
                        <router-link to="/Rewards" class="nav-item nav-link">                    
                            <i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;
                            <p>Rewards</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item"> 
                        <router-link to="/SpecialDiscountRequest" class="nav-item nav-link">                    
                            <i class="fas fa-tags"></i>&nbsp;
                            <p>Special Discount</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item">
                        <router-link to="/AdminSettings" class="nav-item nav-link">
                            <i class="fas fa-tools"></i>&nbsp;
                            <p>Settings</p>
                        </router-link> 
                    </li>

                    <li class="nav-item">
                        <router-link to="/sendalert" class="nav-item nav-link">                    
                            <i class="fa fa-bell"></i>&nbsp;
                            <p>Send Notification</p>
                        </router-link> 
                    </li>                      
                </ul>
            </nav>

            <nav class="mt-2" v-if="(!noTodos)" >
                <ul class="nav nav-pills nav-sidebar flex-column clsLeftPanelMenu" data-widget="treeview" role="menu" data-accordion="false" ref="leftPanelMenu" >
                  <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <router-link to="/Dashboard" class="nav-item nav-link">                                        
                            <i class="fa fa-home" aria-hidden="true"></i>&nbsp;
                            
                            <p>Dashboard</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item has-treeview">
                        <a href="javascript:void(0);" class="nav-link" @click="changearrow"  >          
                        	<i class="fa fa-user" ></i>&nbsp;          
                          <p>
                            My Account
                            <i class="right fa fa-angle-left"></i>&nbsp;

                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/UpdateProfile" class="nav-item nav-link">                           
                                  <p>Update Profile</p>
                                </router-link>                             
                            </li>


                            <li class="nav-item">
                                <router-link to="/CreditHistory" class="nav-item nav-link">                           
                                  <p>Credit History</p>
                                </router-link>                             
                            </li>

                            <li class="nav-item">
                                <router-link to="/ChangePassword" class="nav-item nav-link">
                                    <p>Change Password</p>
                                </router-link> 
                            </li>

                           

                            <li class="nav-item">
                                <router-link to="/DeleteAccount" class="nav-item nav-link">
                                    <p>Delete Account</p>
                                </router-link> 
                            </li>
                          
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="javascript:void(0);" class="nav-link" @click="changearrow" >                  
                        	<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;	
                          <p>
                            Email Verification
                            <i class="right fa fa-angle-left"></i>&nbsp;
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">

                                <router-link to="/ValidateEmail" class="nav-item nav-link">                    
                                  <!--<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; -->
                                  <p>Validate Email</p>
                                </router-link> 
                            
                            </li>

                            <li class="nav-item">
                                <router-link to="/ValidateList" class="nav-item nav-link">                    
                                    
                                    <p>Validate Email List</p>
                                </router-link> 
                            </li>                  
                        </ul>
                    </li>
                  
                 
                   <li class="nav-item has-treeview">
                        <a href="javascript:void(0);" class="nav-link" @click="changearrow" >    
                        	<i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;               
                          <p>
                            API
                            <i class="right fa fa-angle-left"></i>&nbsp;
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">

                                <router-link to="/apisettings" class="nav-item nav-link">                    
                                    <!-- <i class="fa fa-cogs" aria-hidden="true"></i>&nbsp; -->
                                    <p>API Settings</p>
                                </router-link> 
                            
                            </li>

                            <li class="nav-item">
                                <router-link to="/apilogs" class="nav-item nav-link">                    
                                   	<!-- <i class="fa fa-bars" aria-hidden="true"></i>&nbsp; -->
                                    <p>API Logs</p>
                                </router-link> 
                            </li>
                          
                        </ul>
                    </li>                       
                  
                    <li class="nav-item">                
                        <router-link to="/Invoice" class="nav-item nav-link">                    
                            <i class="fa fa-book" aria-hidden="true"></i>&nbsp;
                            <p>Invoice</p>
                        </router-link>                 
                    </li>   

                    <li class="nav-item">
                        <router-link to="/CreditPlans" class="nav-item nav-link">                    
                            <i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;
                            <p>
                                Buy Credits
                                <span style="border:1px dashed #fff;background-color: green;padding: 2px 5px;">New Offer</span> 
                            </p>
                        </router-link> 
                    </li>   

                    <li class="nav-item">                        
                        <router-link to="/Integration" class="nav-item nav-link">                    
                            <i class="fa fa-plug" aria-hidden="true"></i>&nbsp;
                            <p>Integration</p>
                        </router-link> 
                    </li>    
                    

                    <li class="nav-item">                        
                        <router-link to="/FAQ" class="nav-item nav-link">                       
                            <i class="fa fa-question"></i>&nbsp;
                            <p>FAQ</p>
                        </router-link>                         
                    </li>   

                    <li class="nav-item">
                        <router-link to="/Affiliate" class="nav-item nav-link"  >                                      
                            <i class="fa fa-venus-mars" aria-hidden="true"></i>&nbsp;

                            <p>Affiliate</p>
                        </router-link> 
                    </li>   

                    <li class="nav-item">
                         <router-link to="/FreeCredits" class="nav-item nav-link"  >                                      
                            <i class="fas fa-gift"></i>&nbsp;

                            <p>Free Credits</p>
                        </router-link>
                    </li>      

                    <li class="nav-item">
                         <router-link to="/SpecialDiscount" class="nav-item nav-link"  >                                      
                            <i class="fas fa-tags"></i>&nbsp;

                            <p>Special Discount</p>
                        </router-link>
                    </li>      

                    <li class="nav-item"> 
                         <router-link to="/Perks" class="nav-item nav-link"  >                                      
                            <i class="fas fa-gift"></i>&nbsp;

                            <p>Perks</p>
                        </router-link>
                    </li>      

                    <li class="nav-item">
                         <router-link to="/Support" class="nav-item nav-link"  >                                      
                            <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;

                            <p>Support</p>
                        </router-link>
                    </li>      
                    
                    <li class="nav-item">
                        <router-link to="/Settings" class="nav-item nav-link">
                            <i class="fas fa-tools"></i>&nbsp;
                            <p>Settings</p>
                        </router-link> 
                    </li>
                                             

                    <li class="nav-item">                        
                        <a href="javascript:void(0);" class="nav-item nav-link" >
                            <i class="fa fa-envelope" aria-hidden="true"></i> 
                            support@myemailverifier.com 
                            
                        </a>
                        
                    </li>

                    <li class="nav-item">
                         &nbsp;
                    </li>                                                   

                    <li class="nav-item">
                         &nbsp;
                    </li>                                                   
                </ul>
            </nav>        
          <!-- /.sidebar-menu -->
        </div>

        <!-- /.sidebar -->
    </aside>  
</template>

<script>
        
    export default {
        name:'Leftsidebar',
        beforeMount(){
            
        },
        mounted(){ 

           $('[data-widget="treeview"]').Treeview('init'); 
           var thisObj = this;

            this.axios.get(api_url+'getusername')
            .then(function(response){
                
                //thisObj.is_admin =response.data.data.is_admin; 
                thisObj.username = response.data.data.username;
                
            });

            this.is_admin = localStorage.is_admin; 
            //this.username = localStorage.username;
            
            if(this.is_admin === 'true'){
                this.todos = ['emp'];                    
            }else{
                this.todos = [];            
            }            
            
        },        
        data(){
            return{
                adminlogo:img_url+'mev-logo.svg',
                userimg:img_url+'user2-160x160.jpg',  
                is_admin:false,              
                todos: [],
                username:'',
            }
        }, 
        methods:{
            changearrow(event){               
                    var newmenuobj = event.target;                                            
                    

                    if($(newmenuobj).prop("tagName") == "A"){
                        if($(newmenuobj).children().children().hasClass('fa-angle-down')){
                            
                            $(newmenuobj).children().children().removeClass('fa-angle-down');
                            $(newmenuobj).children().children().addClass('fa-angle-left');    

                        }else{
                            
                            $(newmenuobj).children().children().addClass('fa-angle-down');
                            $(newmenuobj).children().children().removeClass('fa-angle-left');    
                        } 
                    }

                    if($(newmenuobj).prop("tagName") == "P"){
                        if($(newmenuobj).children().hasClass('fa-angle-down')){
                            
                            $(newmenuobj).children().removeClass('fa-angle-down');
                            $(newmenuobj).children().addClass('fa-angle-left');    

                        }else{
                            
                            $(newmenuobj).children().addClass('fa-angle-down');
                            $(newmenuobj).children().removeClass('fa-angle-left');    
                        } 
                    }

                    if($(newmenuobj).prop("tagName") == "svg"){
                        if($(newmenuobj).hasClass('fa-angle-down')){
                            
                            $(newmenuobj).removeClass('fa-angle-down');
                            $(newmenuobj).addClass('fa-angle-left');    

                        }else{
                            
                            $(newmenuobj).addClass('fa-angle-down');
                            $(newmenuobj).removeClass('fa-angle-left');    
                        } 
                    }

                    if($(newmenuobj).prop("tagName") == "path"){
                        if($(newmenuobj).parent().hasClass('fa-angle-down')){
                            
                            $(newmenuobj).parent().removeClass('fa-angle-down');
                            $(newmenuobj).parent().addClass('fa-angle-left');    

                        }else{
                            
                            $(newmenuobj).parent().addClass('fa-angle-down');
                            $(newmenuobj).parent().removeClass('fa-angle-left');    
                        } 
                    } 
                }                                      
            },               
        computed: {
            noTodos() {
                return this.todos.length;
            }
        }        
    }


    
</script>
