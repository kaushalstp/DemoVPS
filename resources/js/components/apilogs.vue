<template>
    <div class="page-apilogs" >
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>API Statistics</h1>
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
                                </vue-bootstrap4-table>                            
                            </div>

                            <!-- <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                          <th>Email</th>
                                          <th>API Key</th>    
                                          <th>Status</th>                  
                                          <th>Reason</th>
                                          <th>Date</th>                              
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="log in logsdata" >
                                            <td> {{ log.email }} </td>
                                            <td> {{ log.apikey }} </td>
                                            <td> {{ log.logstatus }} </td>
                                            <td> {{ log.reason }} </td>
                                            <td> {{ log.logdate }} </td>
                                        </tr>                                    
                                    </tbody>
                                </table>
                            </div>                
 -->
                            <div class="nodatatxt" v-if="!rows.length" >No Data</div>
                            
                        </div>
                      <!-- /.row -->
                      <b>* All API logs will be deleted automatically within 1 week.</b>
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
    
    .page-apilogs .vbt-table-wrapper .vbt-table-tools{
        display: none;
    }

    .pagination .input-group{
        display: none;   
    }

</style>

<script>

    import VueBootstrap4Table from 'vue-bootstrap4-table'; 
        
    export default {
        name:'apilogs',
        data:function(){
            return{
            	logsdata:[],
                rows:[],
                columns:[
                    {
                        label: "id",
                        name: "log_id",
                        visibility:false,
                    },
                    {
                        label: "Email",
                        name: "email",
                    },
                    {
                        label: "API Key",
                        name: "apikey",
                    },
                    {
                        label: "Status",
                        name: "status",
                    },
                    {
                        label: "Reason",
                        name: "reason",
                    },                    
                    {
                        label: "Date",
                        name: "created_at",
                    },
                ],
                config: {                   
                    server_mode: true,
                    multi_column_sort:false,
                    per_page:100, 
                    per_page_options:  [100,  200,  500],                        
                },
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: {visibility: false},                     
                    page: 1, 
                     per_page:100, 
                    per_page_options:  [100,  200,  500],                         
                },
                total_rows: 0,

            }
        },
        components:{
            VueBootstrap4Table
        },
        mounted(){        

        	var thisObj = this;

            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }
            });
            
        	var uid = localStorage.mevid;
            //var uid = 26;
            
            let self = this;            
            this.fetchData();


        	/*this.axios.get(api_url+'getLogs/'+uid)
        	.then(function(response){
        		console.log(response);
        		thisObj.logsdata = response.data.logs;
        	});        
            */
        },          
        methods:{
            fetchData:function(){

                var uid = localStorage.mevuid;                
                   
                let self = this;
                axios.post(api_url+'getLogs',{
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
