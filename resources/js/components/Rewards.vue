<template>
    <div class="page-admin-users" > 
        <section class="content-header"> 
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Rewards Free Credits </h1>
                    </div>                                  
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->

        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <div class="col-12">                    
                        <!-- Main content -->
                        <div class="p-3 mb-3">
                          <!-- title row -->
                                                    
                          <!-- Table row -->
                            <div class="row">
     
                                <div class="col-sm-12" >
                                    <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" 
                                        :total-rows="total_rows" @on-change-query="onChangeQuery" >

                                        <template slot="reset-button-text">
                                            Reset
                                        </template>

                                        <template slot="sort-asc-icon">
                                            &uarr;
                                        </template>
                                        <template slot="sort-desc-icon">
                                            &darr;
                                        </template>
                                        <template slot="no-sort-icon">
                                            &uarr;&darr;
                                        </template>  


                                        <template slot="action-slot" slot-scope="props">                                                                                       
                                            <button type="button" class="btn btn-primary btn-sm" 
                                            @click="approveCredits(props.row)">Approve</button>

                                            <button type="button" class="btn btn-danger btn-sm" 
                                            data-toggle="modal" data-target="#rejectRewardModal" 
                                            @click="setActiveRecord(props.row)"  >
                                            Reject
                                            </button>

                                        </template>

                                        <template slot="postlink-slot" slot-scope="props">                                        
                                            <a target="_blank" :href="props.row.post_link" > 
                                            {{ props.row.post_link }} </a>                                                                                           
                                        </template>


                                    </vue-bootstrap4-table>                            
                                </div>
                               
                                <div class="nodatatxt" v-if="!rows.length" >No Data</div>
                                
                            </div>
                          <!-- /.row -->
                          
                          <!-- this row will not appear when printing -->            
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section> 



        <!-- Reject Reward  -->

        <div class="modal fade" id="rejectRewardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i><b>Pay Now</b></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">                      
                    <div class="row" >                                    
                        <div class="col-sm-12" >
                            <textarea v-model="rejected_reason"  >
                                
                            </textarea>
                        </div>                              
                    </div>                                                      
                </div>

              <div class="modal-footer">                
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="rejectCredits" >Reject</button>
              </div>
            </div>
          </div>
        </div>

        <!-- **** -->


    </div>    

</template>

<style>
    .mailcol{
        min-width: 250px;
    }

    th{

    }
    
    .datecell{        
        white-space: nowrap;
    }

    th,td{
        white-space: nowrap;   
        min-width: 130px;
    }    

    .page-admin-users .vbt-global-search{
        display: none;
    }

</style>


<script>    

    import VueBootstrap4Table from 'vue-bootstrap4-table';     

    export default {
        name:'Rewards', 
         data:function(){
            return{
                    isLoading:false,
                    is_admin:false,
                    reg_id:0,
                    logsdata:[],
                    rows:[],
                    msg:[],
                    new_credits:0,
                    amount:0,
                    rejected_reason:'',
                    record_id:0,
                    request_by:{
                        username:'',
                        useremail:'',
                    },
                    columns:[
                    {
                        label: "id",
                        name: "id",
                        visibility:false,
                    },
                    {
                        label: "User",
                        name: "username", 
                        sort: false,                       
                    },                    
                    {
                        label: "Email",
                        name: "email",
                        sort: false,                                                
                    },
                    {
                        label: "Media",
                        name: "social_media",                                                
                    },
                    {
                        label: "Credits",
                        name: "credits",                                                
                    },
                    {
                        label: "Post Link",
                        name: "post_link",
                        slot_name: "postlink-slot"                                               
                    },
                    {
                        label: "Date",
                        name: "created_at",
                        sort: true,
                    },                                                           
                    {
                        label: "Action",
                        name: "action", 
                        slot_name: "action-slot"                                               
                    },
                ],
                classes:{
                    
                },
                config: {                   
                    show_reset_button:  false,
                    show_refresh_button:  false,
                    server_mode: true,
                    multi_column_sort:true,
                    per_page:100, 
                    per_page_options:  [100,  200,  500],
                },
                queryParams: {
                    sort: ['firstname'],
                    filters: [], 
                                    
                    page: 1, 
                    per_page:100, 
                    per_page_options:  [100,  200,  500],                         
                },
                total_rows: 0,
            }
        },
        mounted(){

            /* Check User Login */
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

                if(response.data.status == 1 || response.data.status == '1'){ 
                                                    
                    localStorage.setItem('mevid',response.data.data.userid);
                }
            });

                        

            if(this.is_admin == false){
                this.$router.push({ 
                    name:'dashboard',                    
                });
            }   
            

            /* ------- */


            this.fetchData();
        },
        components:{
            VueBootstrap4Table,            
        },
        methods:{            
            rejectCredits(row){

                var thisObj = this;

                if(!confirm("Reject this request?")){
                    return false;
                }        

                this.axios
                .post(api_url+'rejectreward',{
                    record_id:thisObj.record_id,
                    rejected_reason:thisObj.rejected_reason, 
                    request_by:thisObj.request_by,                    
                })
                .then(function(response){
                                    
                    alert(response.data.msg);
                    thisObj.fetchData();                    
                });
            },                   
            setActiveRecord(row){
                this.record_id = row.id;
                this.request_by.username = row.username;
                this.request_by.useremail = row.email;
            },              
            approveCredits(row){                                 

                var thisObj = this;

                if(!confirm("Add "+row.credits+" Credits to this User's Account?")){
                    return false;
                }
                
               
                this.axios
                .post(api_url+'RewardFreeCredits',{
                    reg_id:row.userid,
                    recid:row.id, 
                    media:row.social_media,    
                    freecredits:row.credits,
                })
                .then(function(response){
                                    
                    alert(response.data.msg);
                    thisObj.fetchData();                    
                });                                                     
            },
            submitUpdateAffiliate(e){   
                
                e.preventDefault(); 
                
                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                                                            
                if(this.hasError == false){ 
 
                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'updateAffiliate',{ 
                        reg_id:thisObj.reg_id,
                        affiliate_url:thisObj.affiliate_url,                        
                    })
                    .then(function(response){
                                            
                        alert(response.data.msg);
                        thisObj.fetchData();
                        
                    }); 
                }
            },
            onAddCredit:function(row){
                
                this.reg_id = row.reg_id;                
            },
            onUpdateAfflURL:function(row){
                
                this.reg_id = row.reg_id;                
            },
            changeUserStatus:function(reg_id,event){

                let self = this;
                axios.post(api_url+'updateUserStatus',{
                        reg_id:reg_id,
                        status:event.value,               
                })
                .then(function(response){
                    alert(response.data.msg);                                                                   
                });
            },    
            fetchData:function(){

                var uid = localStorage.mevuid;
                var uid = 26;

                   
                let self = this;                
                
                axios.post(api_url+'getSharedContent',{                     
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.logs;
                    self.total_rows = response.data.total;                    
                

                })
                .catch(function(error){
                    
                });
                
                
            },
            onChangeQuery(queryParams){
                this.queryParams = queryParams;
                this.fetchData();
            },
        }       
    }
</script>
