<template>
    <div class="page-admin-orders" >
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
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

                                        

                                        <template slot="download-slot" slot-scope="props">
                                                                   
                                        <a target="_blank" v-bind:href = "props.row.downloadlink" >Print</a>
                                       
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
    </div>    
</template>

<style>

    .page-admin-orders .vbt-global-search{
        display: none;
    }
    
</style>

<script>    

    import VueBootstrap4Table from 'vue-bootstrap4-table'; 

    export default {
        name:'Orders', 
         data:function(){
            return{
                    is_admin:false,
                    invoice_id:0,
                    logsdata:[],
                    rows:[],
                    msg:[],
                    new_credits:0,
                    amount:0,
                    columns:[
                    {
                        label: "ID",
                        name: "invoice_id",
                        visibility:true,
                    },
                    { 
                        label: "First Name",
                        name: "firstname", 
                        sort: true,                       
                    },
                    { 
                        label: "Last Name",
                        name: "lastname", 
                        sort: true,                       
                    },
                    {
                        label: "Email",
                        name: "email",
                        sort: true,
                        column_classes:"mailcol",
                        filter: {
                            type: "simple",
                            placeholder: "search email"
                        },
                    },
                    {
                        label: "Amount",
                        name: "amount",                        
                    },
                    {
                        label: "Credits",
                        name: "credits",                        
                    },                                       
                    {
                        label: "Coupon Code",
                        name: "coupon_code", 
                        sort: true,
                        column_classes:"mailcol",
                        filter: {
                            type: "simple",
                            placeholder: "search coupon"
                        },                       
                    },                                       
                    {
                        label: "Source",
                        name: "source",                                                
                    },
                    {
                        label: "Invoice Date",
                        name: "created_at",
                    },                                        
                    {
                        label: "Actions",
                        name: "actions",
                        slot_name: "download-slot" 
                    },                     
                ],                
                config: {                   
                    show_reset_button:  true,
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

            if(localStorage.is_admin == "false" || localStorage.is_admin == false){
                this.is_admin = false;    
            }

            if(localStorage.is_admin == "true" || localStorage.is_admin == true){
                this.is_admin = true;    
            }
                        
            
            if(localStorage.mevid <= 0 || localStorage.mevid == '' || typeof localStorage.mevid === 'undefined'){
                this.$router.push({ 
                    name:'Login',                    
                });
            }   

            if(this.is_admin == false){
                this.$router.push({ 
                    name:'dashboard',                    
                });
            } 

            /* ------- */

           this.fetchData(); 
        }, 
        components:{
            VueBootstrap4Table
        },
        methods:{
            fetchData:function(){

                var uid = localStorage.mevuid;
                var uid = 26;
                   
                let self = this;
                axios.post(api_url+'getAllOrders',{
                    "uid":uid,
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
