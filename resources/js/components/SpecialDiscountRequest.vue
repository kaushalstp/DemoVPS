<template>
    <div class="page-admin-users" > 
        <section class="content-header"> 
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Special Discount Request </h1>
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
                                            @click="onApproveDiscount(props.row)" data-toggle="modal" data-target="#exampleModal" >Approve Discount</button>
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


                  <!-- Add Credit Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" 
        aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Special Discount</h5>
                        <button type="button" ref="closeBtn" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    

                        <form name="frmApproveDiscount" id="frmApproveDiscount" 
                        @submit.prevent="submitApproveDiscount" >
                            <div class="row" >
                                &nbsp;&nbsp;&nbsp; <b> {{ required_credits  }} Credits </b>   
                                <br/><br/>                            
                                &nbsp;&nbsp;&nbsp; {{ detail  }}

                            </div>
                            <hr/>
                            <div class="row" >
                                <div class="col-sm-7" >
                                    <input type="hidden" name="reg_id"  v-model="reg_id" />
                                    <input type="hidden" name="rec_id"  v-model="rec_id" />
                                    <div class="form-group">                                
                                        
                                        
                                                Amount ($)
                                                <input type="number" class="form-control" v-model="amount" 
                                                id="amount" name="amount" placeholder="" min="1" />
                                        
                                                
                                    </div>                                     
                                </div>                 

                                <div class="col-sm-5 " >                                    
                                     <div class="form-group mt-4">
                                        <button  name="submit" type="submit" class="btn btn-primary">
                                        Approve
                                        </button>
                                    </div> 
                                </div>
                            </div>    
                        </form>                                        
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                    
                  </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->


            </div><!-- /.container-fluid -->
        </section>           
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
        name:'SpecialDiscountRequest', 
         data:function(){
            return{
                    isLoading:false,
                    is_admin:false,
                    reg_id:0,
                    rec_id:0,
                    logsdata:[],
                    rows:[],
                    msg:[],
                    required_credits:0,
                    detail:'',
                    amount:1,
                    contact_person:'',
                    contact_email:'',
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
                        label: "Organization",
                        name: "organization",                                                
                    },
                    {
                        label: "Contact Person",
                        name: "contact_person",
                    },
                    {
                        label: "Contact Email",
                        name: "contact_email",
                    },
                    {
                        label: "Credits",
                        name: "credits_required",
                    },
                    {
                        label: "One Time / Recurring",
                        name: "paytype",
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
            onApproveDiscount:function(row){ 
                
                this.reg_id = row.userid;                
                this.rec_id = row.id;     

                this.contact_person = row.contact_person;           
                this.contact_email = row.contact_email;           
                this.required_credits = row.credits_required;  
                this.detail = row.detail;  

                         
            },            
            submitApproveDiscount(){                                   

                var thisObj = this;
                                            
                this.axios
                .post(api_url+'approveSpecialDiscountRequest',{
                    rec_id:thisObj.rec_id,
                    reg_id:thisObj.reg_id,
                    amount:thisObj.amount,
                    required_credits:thisObj.required_credits,
                    contact_person:thisObj.contact_person,
                    contact_email:thisObj.contact_email,                     
                })
                .then(function(response){
                                    
                    jQuery('#exampleModal').modal('hide');                    
                    alert(response.data.msg);
                    thisObj.fetchData();                    
                });                                                     
            },
                                  
            fetchData:function(){

                var uid = localStorage.mevuid;
                var uid = 26; 
                   
                let self = this;                
                
                axios.post(api_url+'getDiscountRequest',{                     
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
