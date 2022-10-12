<template>  
    <div class="admin-page-dashboard" > 
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard</h1>
                    </div>                   
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <p></p>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid stat-widgets" >
               
                  <!--  Users Stats  -->
                <div class="row pb-4" v-show="is_admin" >                                                                      
                    <div class="col-sm-2" >
                        <select class="form-control"  @change="getStatsFilter()"  v-model="duro_filter" >
                            <option value="today" >Today</option>
                            <option value="yesterday" >Yesterday</option>
                            <option value="7days" >Last 7 Days</option>
                            <option value="15days" >Last 15 Days</option>
                            <option value="thismon" >This Month</option>                        
                            <option value="lastmon" >Last Month</option>                      
                            <option value="3mon" >Last 3 Months</option>                        
                            <option value="6mon" >Last 6 Months</option>                        
                            <option value="12mon" >Last 12 Months</option>                        
                        </select>
                    </div>

                </div>  

                <div class="row" v-show="is_admin" >
                    <div class="col-lg-3 col-6">                
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3> {{ users.today }} </h3>

                            <p>New Registration</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-male" aria-hidden="true"></i>
                          </div>  

                            <router-link to="/users" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>
                        </div>
                    </div>
                  

                    <div class="col-lg-3 col-6">                
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>$ {{ earnings }} </h3>

                            <p>Earnings</p>
                          </div>

                          <div class="icon">
                            <i class="fa fa-university" aria-hidden="true"></i>
                          </div>      

                            <router-link to="/orders" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>                  

                        </div>
                    </div>

                    <div class="col-lg-3 col-6">                 
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3> {{ total_orders }}  </h3>
                                <p><h2>Orders</h2></p>                                
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                            </div>   

                            <router-link to="/orders" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>                                       
                        </div>
                    </div>
                                    
                </div>

                <!-- ******* -->
                
                <!-- Earnings -->
                <div class="row" v-show="is_admin" >
                    
                    <div class="col-lg-3 col-6">                   
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3> {{ users.active }} </h3>

                            <p>Users Active</p>
                          </div>
                          <div class="icon">
                           <i class="fa fa-male" aria-hidden="true"></i>
                          </div>      

                            <router-link to="/users" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>
                        </div>
                    </div>                  
                    

                    <div class="col-lg-3 col-6">                   
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3> {{ users.inactive }} </h3>

                            <p>Users Inactive</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-male" aria-hidden="true"></i>
                          </div> 

                            <router-link to="/users" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>

                        </div>
                    </div> 

                    <div class="col-lg-3 col-6">                   
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3> {{ total_files_inprogress }} </h3>

                            <p>Files Processing</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-file" aria-hidden="true"></i>
                          </div> 
                            

                        </div>
                    </div>  

                    <!-- <div class="col-lg-3 col-6">                
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>$ {{ earnings.today }} </h3>

                            <p>Today's Earnings</p>
                          </div>

                          <div class="icon">
                            <i class="fa fa-university" aria-hidden="true"></i>
                          </div>      

                            <router-link to="/orders" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>                  

                        </div>
                    </div>
                  
                    <div class="col-lg-3 col-6">
                   
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3>$ {{ earnings.thismonth }} </h3>

                            <p>This months's Earnings</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-university" aria-hidden="true"></i>
                          </div>   

                            <router-link to="/orders" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>                  


                        </div>
                    </div>   -->
                </div>            

                <!--  Orders Stats  -->

                <div class="row" v-show="!is_admin" >                                    
                    <div class="col-lg-3 col-6">                 
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3> {{ orders.today }} / {{ orders.thismonth }} </h3>
                                <p><h2>Orders</h2></p>
                                <p> Today / This Month</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                            </div>   

                            <router-link to="/orders" class="nav-item nav-link small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>                                       
                        </div>
                    </div>

                    <div class="col-lg-3 col-6" v-show="!is_admin" >                
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3> {{ apicalls.today }} / {{ apicalls.thismonth }}</h3>

                            <p><h2>API Calls</h2></p>
                            <p>Today / This Month</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-server" aria-hidden="true"></i>
                          </div>                        
                        </div>
                    </div>

                    <div class="col-lg-3 col-6" v-show="!is_admin" >                
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3> {{ uploads.inprogress }} / {{ uploads.total }} </h3>
                            <p><h2>Files</h2></p>  
                            <p>In-Progress / Total</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-file" aria-hidden="true"></i>
                          </div>                        
                        </div>
                    </div>                  
                </div>
            </div>

            <hr/>

            <div class="container-fluid" >    
                <br/>
                <!-- Recent Uploads -->
                <div class="row" v-show="!is_admin" >
                    <div class="col-sm-6 sec-recent-upload"  v-if="recentUploads.length > 0 " >
                        <div class="card"  >


                            <div class="card-header ui-sortable-handle" >
                                <h3 class="card-title">
                                    <i class="ion ion-clipboard mr-1"></i>
                                    Recent Uploads
                                </h3>                            
                            </div>

                          <!-- /.card-header -->
                            <div class="card-body">                            

                                <div class="row" v-for="myupload in  recentUploads" >
                                    <div class="col-sm-3" >
                                        <small class="badge badge-secondary"><i class="fa fa-clock"></i> 
                                        {{ myupload.timeago }}
                                        </small>
                                    </div>                             

                                    <div class="col-sm-5" >  
                                        <i class="fa fa-file-code" aria-hidden="true"></i>
                                        {{ myupload.user_given_name }}
                                    </div>

                                    <div class="col-sm-4 text-right" >
                                                                                    
                                        <a target="_blank" v-bind:href="myupload.xlsfile_url" 
                                        v-if="myupload.xlsfile_url" ><i class="fa fa-download"></i> xls </a>                                            
                                        &nbsp;&nbsp;
                                        <a target="_blank" v-bind:href="myupload.csvfile_url" v-if="myupload.csvfile_url" ><i class="fa fa-download"></i> csv </a>
                                    </div>               
                                </div>                                                                                                
                            </div>
             
                            <div class="card-footer clearfix">

                                <router-link to="/ValidateList" class="btn btn-info float-right">
                                    View All 
                                </router-link>

                                
                            </div>
                        </div>
                    </div>    

                    <div class="col-sm-6 sec-recent-order " v-if="recentOrders.length > 0 " >
                        <div class="card"  >


                            <div class="card-header ui-sortable-handle" >
                                <h3 class="card-title">
                                    <i class="ion ion-clipboard mr-1"></i>
                                    Recent Orders
                                </h3>                            
                            </div>


                          <!-- /.card-header -->
                            <div class="card-body">


                                <div class="row" v-for="myorders in  recentOrders" >
                                    <div class="col-sm-2" >
                                        <small class="badge badge-secondary"><i class="fa fa-clock"></i> 
                                        {{ myorders.timeago }}
                                        </small>
                                    </div>                             

                                    <div class="col-sm-3" >                                          
                                        $ {{ myorders.amount }} 
                                        
                                    </div>


                                    <div class="col-sm-4" >                                         
                                        {{ myorders.credits }} Credits 

                                    </div>                                         

                                    <div class="col-sm-3" >
                                                                                    
                                        <a target="_blank" v-bind:href="myorders.invoice_link" 
                                                v-if="myorders.invoice_link" ><i class="fa fa-download"></i> Invoice </a>
                                    </div>               
                                </div>                                
                            </div>
             
                            <div class="card-footer clearfix">

                                <router-link to="/Invoice" class="btn btn-info float-right">
                                    View All 
                                </router-link>                                
                            </div>
                        </div>
                    </div>    
                    <hr/>
                </div>    
                <!-- ******  -->    
                                

                <!-- Revenue Reports -->
                <div class="row" v-show="is_admin" >
                    <div class="col-sm-12" >
                        <h4><b>Revenue Report</b></h4>
                        <p style='color:brown;' > <b>Notes:</b> values shown in this chart are in ($) </p>
                        <br/>                        
                    </div>   


                    <!-- <div class="col-sm-12" >
                        <vue-bar-graph
                            :points="graphpoints"
                            :width="900"
                            :height="300"
                            :show-y-axis="true"
                            :show-x-axis="true"
                            :show-values="true"                            
                        />
                    </div>                             -->

                     <div class="col-12 col-sm-12">
                        <div class="card card-primary card-tabs">
                          <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Last 12 Months</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Last 12 Weeks</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Last 12 Days</a>
                              </li>
                              
                            </ul>
                          </div>
                          <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                              <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                
                                <vue-bar-graph
                                    :points="graphpoints"
                                    :width="900"
                                    :height="300"
                                    :show-y-axis="true"
                                    :show-x-axis="true"
                                    :show-values="true"                            
                                />     

                              </div>
                              <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                    
                                    <vue-bar-graph
                                        :points="graphpoints_12_weeks"
                                        :width="900"
                                        :height="300"
                                        :show-y-axis="true"
                                        :show-x-axis="true"
                                        :show-values="true"                            
                                    />

                              </div>
                              <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                                     <vue-bar-graph
                                        :points="graphpoints_12_days"
                                        :width="900"
                                        :height="300"
                                        :show-y-axis="true"
                                        :show-x-axis="true"
                                        :show-values="true"                            
                                    />
                              </div>
                              
                            </div>
                          </div>
                          <!-- /.card -->
                        </div>
                    </div>

                </div>                  


                <div class="row" >  
                    
                   
                </div>

                <!-- *** -->
                <hr>
                 <br/><br/><br/>
                <!-- Popular Coupons  -->
                <div class="row" v-show="mostpopularcoupons.length > 0" >

                    <div class="col-sm-8" >
                        <div class="card">
                            <div class="card-header border-0">
                                
                                <h3 class="card-title" style='color:brown;' >
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    &nbsp; Most Popular Coupon Codes &nbsp; 
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </h3>
                                                                                            
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                          <tr>
                                            <th>Coupon Code</th>
                                            <th>Orders</th>
                                            <th>Revenue</th>
                                            
                                          </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="coupon in mostpopularcoupons" >
                                            <td>
                                              {{ coupon.coupon_code }}
                                            </td>
                                            <td>{{ coupon.total_orders }} </td>
                                            <td>
                                              
                                              $ {{ coupon.revenue }}
                                            </td>                            
                                        </tr>                                                                                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>        
                </div>
                <!-- **** -->


                <br/><br/><br/><br/>

                <!-- ****** -->

            </div>          
        </section>

        <!-- <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
              
              <strong class="mr-auto">Bootstrap</strong>
              <small class="text-muted">11 mins ago</small>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="toast-body">
              Hello, world! This is a toast message.
            </div>
        </div> -->

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

    import VueBarGraph from 'vue-bar-graph';    

    export default{
        name:'Dashboard',         

        data:function(){
            return{         
                duro_filter:'today',      	
                statsmonths:[
                  
                ],
                statsyears:[
                  
                ],
                filtered_month:0,
                filtered_year:0,                          
                showtoastr:false,
                is_admin:false,
                userid:0,
                recentUploads:[],
                recentOrders:[],
                graphpoints:[],
                graphpoints_12_days:[],
                graphpoints_12_weeks:[],
                mostpopularcoupons:[],
                earnings:0,
                total_orders:0,
                total_files_inprogress:0,
                /*earnings:{
                    today:0,
                    thismonth:0,
                    total:0,
                },*/
                credits:{
                    today:0,
                    thismonth:0,
                    remaining:0,
                },
                users:{
                    today:0,
                    active:0,
                    inactive:0,
                },
                orders:{
                    today:0,
                    thismonth:0,
                    total:0,
                },
                apicalls:{
                    today:0,
                    thismonth:0,
                    total:0,
                },
                uploads:{
                    total:0,
                    inprogress:0,
                    completed:0,
                },
            }
        },        
        async mounted(){                    
            this.$parent.is_user_page=false;      
            var thisObj = this;
            var checkifadmin=0;      
            
            var thisObj = this;   

            var cookie_val = thisObj.getCookie();            
            if(cookie_val == '1'){       

                document.cookie = "gotosupport=0";

              thisObj.$router.push({  
                name:'Support',                    
              });
            }    



            var today_date = new Date();
            this.filtered_year = today_date.getFullYear();
            this.filtered_month = (today_date.getMonth()+1);
            
            //this.userid = localStorage.mevid;                  
            
            //var mevuinfo = localStorage.getItem('mevuinfo');  
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
            
            if(localStorage.is_admin == "false" || localStorage.is_admin == false){
                this.is_admin = false;    
            }

            if(localStorage.is_admin == "true" || localStorage.is_admin == true){
                this.is_admin = true;    
            }else{
                this.is_admin = false;       
            } 
            
            
              this.axios
              .post(api_url+'getStats',{'duro_filter':thisObj.duro_filter})
              .then(function(response){                
                //console.log(response);                                                         

                thisObj.earnings = response.data.data.earnings;
                thisObj.users = response.data.data.users;
                thisObj.total_files_inprogress = response.data.data.total_files_inprogress;
                thisObj.orders = response.data.data.orders;
                thisObj.total_orders = response.data.data.total_orders;
                thisObj.apicalls = response.data.data.apicalls;
                thisObj.total_logs = response.data.data.total_logs;
                //thisObj.credits = response.data.data.credits;        
                thisObj.uploads = response.data.data.uploads;   

                thisObj.recentUploads = response.data.data.recent_uploads;   
                thisObj.recentOrders = response.data.data.recent_orders;   
                
                thisObj.mostpopularcoupons = response.data.data.popular_coupons; 

                if(response.data.unread == '1'){
                    jQuery('#new_notifications').modal('show');
                }

                //thisObj.graphpoints

                $.each(response.data.data.revenue_reports, function(key, value) {			    	
    			    thisObj.graphpoints.push({ "label":value.revenuedate,"value":value.total });
    			});

                $.each(response.data.data.revenue_reports_12_days, function(key, value) {
            
                  thisObj.graphpoints_12_days.push({ "label":value.revenuedate,"value":value.total });
                  
                });

                $.each(response.data.data.revenue_reports_12_weeks, function(key, value) {
            
                  thisObj.graphpoints_12_weeks.push({ "label":value.revenuedate,"value":value.total });
                  
                });

            });
        }, 
        components: {
            VueBarGraph,
        },         
        methods:{        	
            getCookie(c_name="gotosupport") {
              var c_start = 0;
              var c_end = 0;
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
          	getStatsFilter(){
              
              var thisObj = this;

              this.axios
              .post(api_url+'getStats',{'duro_filter':thisObj.duro_filter})
              .then(function(response){                
                //console.log(response); 
                

                thisObj.earnings = response.data.data.earnings;
                thisObj.users = response.data.data.users;
                thisObj.orders = response.data.data.orders;
                thisObj.total_orders = response.data.data.total_orders;
                thisObj.apicalls = response.data.data.apicalls;
                thisObj.credits = response.data.data.credits;        
                thisObj.uploads = response.data.data.uploads;   
                thisObj.total_logs = response.data.data.total_logs;


                thisObj.recentUploads = response.data.data.recent_uploads;   
                thisObj.recentOrders = response.data.data.recent_orders;   
                
                thisObj.mostpopularcoupons = response.data.data.popular_coupons;   


                //thisObj.graphpoints

                /*$.each(response.data.data.revenue_reports, function(key, value) {
            
                  thisObj.graphpoints.push({ "label":value.revenuedate,"value":value.total });

                  
                });

                $.each(response.data.data.revenue_reports_12_days, function(key, value) {
            
                  thisObj.graphpoints_12_days.push({ "label":value.revenuedate,"value":value.total });
                  
                });*/

              });  
            },  
            getStatsFilter_old(){
              
              var thisObj = this;

              this.axios
              .post(api_url+'getStats',{'thismonth':thisObj.filtered_month,'thisyear':thisObj.filtered_year})
              .then(function(response){                
                //console.log(response); 
                thisObj.statsyears = response.data.data.allyears;                       
                thisObj.statsmonths = response.data.data.allmonths;                       

                thisObj.earnings = response.data.data.earnings;
                thisObj.users = response.data.data.users;
                thisObj.orders = response.data.data.orders;
                thisObj.apicalls = response.data.data.apicalls;
                thisObj.credits = response.data.data.credits;        
                thisObj.uploads = response.data.data.uploads;   

                thisObj.recentUploads = response.data.data.recent_uploads;   
                thisObj.recentOrders = response.data.data.recent_orders;   
                
                thisObj.mostpopularcoupons = response.data.data.popular_coupons;   


                //thisObj.graphpoints

                $.each(response.data.data.revenue_reports, function(key, value) {
            
                  thisObj.graphpoints.push({ "label":value.revenuedate,"value":value.total });
                });

              });  

          }, 
           
                      
        },        
        computed:{
            
        },
        created(){           

        },
           

    }
        

   

</script>
