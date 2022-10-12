<template>
    <div class="page-admin-orders" >
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reports - Highest Orders</h1>
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
                                    From <input type="date" name="ho_from_date" id="ho_from_date"  
                                    v-model="ho_from_date" /> to 
                                    <input type="date" name="ho_to_date" id="ho_to_date" 
                                    v-model="ho_to_date"  />

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

    import VueBootstrap4Table from 'vue-bootstrap4-table';  

    export default {
        name:'ReportsHighestOrders', 
         data:function(){
            return{
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
                        label: "First Name",
                        name: "firstname",                         
                    },
                    { 
                        label: "Last Name",
                        name: "lastname",                         
                    },
                    {
                        label: "Email",
                        name: "email",                                            
                    },
                    {
                        label: "Orders",
                        name: "highestorders",                        
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
            VueBootstrap4Table
        },
        methods:{
            applyCustomFilter:function(){            

                let self = this;

                /*var d1 = new Date();
                var d2 = new Date(d1);
                var same = d1.getTime() === d2.getTime();
                var notSame = d1.getTime() !== d2.getTime();*/

                

                axios.post(api_url+'ReportsHighestOrders',{                
                    params: {                        
                        "queryParams": self.queryParams,
                        "page": self.queryParams.page,                                                    
                    },                    
                    "from_date":self.ho_from_date,
                    "to_date":self.ho_to_date,
                })
                .then(function(response) {

                    if(response.data.status == '0' && response.data.errortype == 'invalid_date_range' ){
                        alert("please select a valid date range");
                        return false;
                    }

                    self.rows = response.data.data.allpaidusers;                    
                    self.total_rows = response.data.data.total;                                                            
                })
                .catch(function(error){
                    
                });
            },
            resetCustomFilter:function(){

                //let self = this;
                this.ho_from_date="";
                this.ho_to_date="";
                this.fetchData();                
            },
            fetchData:function(){
                                
                let self = this;
                axios.post(api_url+'ReportsHighestOrders',{                
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.data.allpaidusers;
                    //console.log(self.rows);

                    self.total_rows = response.data.data.total;                    
                    

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
