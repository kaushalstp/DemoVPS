<template>
    <div class="page-admin-orders" >
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Revenue by Source</h1>
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
                          	
                            <div class="row" >
                                <div class="col-sm-12 text-right" >
                                    From <input type="date" name="from_date" id="from_date"  
                                    v-model="from_date" /> to 
                                    <input type="date" name="to_date" id="to_date" v-model="to_date"  />

                                    <button @click="applyCustomFilter" class="btn btn-primary" >Apply</button>
                                    <button @click="resetCustomFilter" class="btn btn-secondary" >Reset</button>
                                </div>                            
                                <Br/> 
                                <p>&nbsp;</p>
                            </div>

                          <!-- Table row -->
                            <div class="row">                            
     
                                <div class="col-sm-12" >
                                    <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" 
                                        :total-rows="total_rows" @on-change-query="onChangeQuery" >
                                                                                                                
                                    </vue-bootstrap4-table>                            
                                </div>

                                    

                                <div class="nodatatxt" v-if="!rows.length" >No Data</div>
                                
                            </div>
                          <!-- /.row -->
                          
                          <!-- this row will not appear when printing -->            
                        </div>
                        <!-- /.invoice -->

                        <loading :active.sync="isLoading"                         
                    :is-full-page="true"></loading>

                    </div><!-- /.col -->

                </div>

                

            </div>
        </section>
    </div>    
</template>

<style> 

    .page-admin-orders .vbt-global-search{
        display: none;
    }
    
</style>

<script>    

    import Loading from 'vue-loading-overlay';    
    import 'vue-loading-overlay/dist/vue-loading.css';

    import VueBootstrap4Table from 'vue-bootstrap4-table';  
    

    export default {
        name:'RevenueBySource', 
         data:function(){
            return{
                    isLoading:false,
                    is_admin:false,
                    invoice_id:0,
                    logsdata:[],
                    rows:[],
                    msg:[],
                    new_credits:0,
                    amount:0,
                    from_date:'',
                    to_date:'',
                    columns:[                                        
                        { 
                            label: "Orders",
                            name: "orders",                         
                        },
                        {
                            label: "Users",
                            name: "users",                                            
                        },
                        {
                            label: "Revenue",
                            name: "source_revenue",                        
                        },                                       
                        { 
                            label: "Source",
                            name: "source", 
                            column_text_alignment:  "text-left",                        
                            row_text_alignment:"text-left",
                        },
                    ],                                 
                config: {                   
                    show_reset_button:  false,
                    show_refresh_button:  false,
                    server_mode: true,
                    multi_column_sort:true,
                    per_page:100, 
                    per_page_options:  [100,  200,  500],
                },
                queryParams: {
                    sort: [],
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
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }
            });

            /* ------- */

            if(this.is_admin == false){
                this.$router.push({ 
                    name:'dashboard',                    
                });
            }   

           this.fetchData(); 
        }, 
        components:{
            VueBootstrap4Table,
            Loading
        },        
        methods:{
            applyCustomFilter:function(){

                let self = this;
                self.isLoading = true;
                axios.post(api_url+'RevenueByLoginSource',{                 
                    params: {                        
                        "queryParams": self.queryParams,
                        "page": self.queryParams.page,                                                    
                    },                    
                    "from_date":self.from_date,
                    "to_date":self.to_date,
                })
                .then(function(response) {
                    self.rows = response.data.data.allpaidusers;                    
                    self.total_rows = response.data.data.total;
                    self.isLoading = false;                                                            
                })
                .catch(function(error){
                    
                });
            },
            resetCustomFilter:function(){

                //let self = this;
                this.from_date="";
                this.to_date="";
                this.fetchData();                
            },
            fetchData:function(){
                                


                let self = this;

                
                self.isLoading = true;
                axios.post(api_url+'RevenueByLoginSource',{                
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.data.allpaidusers;
                    //console.log(self.rows);

                    self.total_rows = response.data.data.total;   
                    self.isLoading = false;
                    
                    

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
