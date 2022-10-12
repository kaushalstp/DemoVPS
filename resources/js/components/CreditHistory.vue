<template>
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2"> 
                    <div class="col-sm-6">
                        <h1>Credit History</h1>
                    </div>                  
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <div class="row page-invoice ">
                    <div class="col-12">
                        
                    <!-- Main content -->
                    <div class="p-3 mb-3">
                      <!-- title row -->
                                            
                      <!-- Table row -->
                        <div class="row">

                            <div class="col-12">
                                <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" 
                                    :total-rows="total_rows" @on-change-query="onChangeQuery" @refresh-data="onRefreshData" >
                                    
                                    <template slot="creditschange-slot" slot-scope="props">
                                                                                                
                                        <span v-show="props.row.creditadd" class="text-bold text-success" >
                                            {{ props.row.credit_change_text }}
                                        </span>

                                        <span v-show="!props.row.creditadd" class="text-bold text-danger" >
                                            {{ props.row.credit_change_text }}
                                        </span>
                                       
                                       
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
    
    /*.page-invoice .vbt-table-tools{
        display: none;
    }*/

    .invoice.p-3.mb-3 {
        border-radius: 10px;
    }

    .page-invoice  .vbt-global-search{
        display: none;
    }

    .page-invoice .vbt-reset-button{
        display: none;   
    }
    

</style>

<script>

    import VueBootstrap4Table from 'vue-bootstrap4-table'; 
        
    export default {
        name:'CreditHistory',        
        mounted(){        
            
            var thisObj = this;
            var mevid=localStorage.mevid;

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
                                                    
                    //localStorage.setItem('mevid',response.data.data.userid);
                }
            });

            
            this.fetchData();                   
        },          
        data(){
            return{
            	msg_otp_verification:"",
            	msg:[],
                payable_amount:0,
            	otpinfo:{
					new_otp:'',
					invoice_id:'',
				},     
                sdp_data:{
                    credits:0,
                    amount:0,
                    userid:0,
                    payer_email:'',
                    paypal_response:'',
                    invoice_id:0,
                },
                paypal: {
                  sandbox:'AbDMPzSz5gKBdUMMoU7bNv0nYd9qoeIIizYsEGx8bCoQr2asBgH4b8VUXU36Fp7RrAugbhSZX5F7UaZL',
                  production:'AbDMPzSz5gKBdUMMoU7bNv0nYd9qoeIIizYsEGx8bCoQr2asBgH4b8VUXU36Fp7RrAugbhSZX5F7UaZL'
                },      
                paypal_demo: { 
                  sandbox:'AS-9_ktSUONpwGyOqjXlipNTdWsaAoydOjOaBXcdqe0EdljHXh1RHHf-dWSlI3tAys6x-MgBdIqV_GXL',
                  production:'AS-9_ktSUONpwGyOqjXlipNTdWsaAoydOjOaBXcdqe0EdljHXh1RHHf-dWSlI3tAys6x-MgBdIqV_GXL'
                },      
                creditHistory:[],
                rows:[],
                columns:[                    
                    {
                        label: "Date",
                        name: "created_at",
                    },
                    {
                        label: "Detail",
                        name: "detail",
                    },
                    {
                        label: "Change",
                        name: "credit_change_text",
                        slot_name: "creditschange-slot" 
                    },
                    {
                        label: "Balance",
                        name: "balance",
                    },                    
                ],
                config: {                   
                    server_mode: true,
                    show_refresh_button: true, 
                    multi_column_sort:false,
                    per_page:25, 
                    per_page_options:  [25,  50,  75, 100],                        
                },
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: {visibility: false},                     
                    page: 1, 
                    per_page:25,  
                    per_page_options:  [25,  50,  75, 100],                        
                },
                total_rows: 0,
            }
        },
        computed:{
            
        },
        methods:{
                    
            onRefreshData() {
                this.fetchData();       
            },
            fetchData:function(){
               
                var uid = localStorage.mevuid;
                //var uid = 26;
                   
                let self = this;
                axios.post(api_url+'creditHistory',{
                    "uid":uid,
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.creditHistory;
                    self.total_rows = response.data.total;                                        
                })
                .catch(function(error){
                    
                });
            },              
            onChangeQuery(queryParams){
                this.queryParams = queryParams;
                this.fetchData();
            },            
        },

        components:{
            VueBootstrap4Table
        },       
    }
</script>
