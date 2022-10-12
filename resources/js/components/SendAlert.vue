<template>
    <div class="page-admin-orders" >
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Send Notification</h1>
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
     
                                <div class="col-sm-5" >                                                                                                           
                                    <form action="" @submit.prevent="submitAlertDetails"> 
                                        <vue-form-generator tag="div" :schema="schema" :options="formOptions" :model="model" />
                                        <div class="d-flex mt-3 pr-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                Send
                                            </button> 
                                            &nbsp; &nbsp;                                         
                                        </div>
                                    </form>      


                                </div> 

                                 <div class="col-sm-7" >
                                    <div class="alertlogs" >
                                        <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" 
                                            :total-rows="total_rows" @on-change-query="onChangeQuery" >
                                                                                                            

                                        </vue-bootstrap4-table>                            
                                    </div>
                                </div>


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

    .alertlogs{
        max-height: 500px;
        overflow: auto;
    }
    
</style>

<script>    

    import VueBootstrap4Table from 'vue-bootstrap4-table'; 
    import SendAlertSchema from '../forms/SendAlertSchema'; 

    export default {
        name:'SendAlert', 
         data:function(){
            return{
                    is_admin:false,
                    error:[],
                    schema: SendAlertSchema,
                    model:{         
                        subject:'',           
                        text:'',                                            
                        send_to:'all',   
                        userlist:'',                     
                    },
                    formOptions:{
                        validateAfterChanged: true
                    },
                    isSaving: false,  

                    
                    logsdata:[],
                    rows:[],
                    msg:[],
                    
                    columns:[
                    {
                        label: "ID",
                        name: "ID",
                        visibility:false,
                    },
                    { 
                        label: "Subject",
                        name: "subject", 
                        sort: false,                       
                    },
                    { 
                        label: "Sent To",
                        name: "sent_to", 
                        sort: false,                       
                    },                    
                    {
                        label: "Date",
                        name: "created_at",
                    },                                        
                                        
                ],                
                config: {                   
                    show_reset_button:  false,
                    show_refresh_button:  false,
                    server_mode: true,
                    multi_column_sort:false,
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

            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }
            });

            if(localStorage.is_admin == "false" || localStorage.is_admin == false){
                this.is_admin = false;    
            }

            if(localStorage.is_admin == "true" || localStorage.is_admin == true){
                this.is_admin = true;    
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

            submitAlertDetails:function(e){   
                e.preventDefault();

                if(!confirm("Send this Notification?")){
                    return false;
                }

                var thisObj = this;
                // console.log(thisObj.model.userid); 
                    

                this.axios.post(api_url+'sendAlert',{
                    alertdata:thisObj.model,                    
                }).then(function(response){
                    // console.log(response);
                    if(response.data.status == '1'){
                        alert(response.data.msg);
                    }
                });

            },

            fetchData:function(){

                var uid = localStorage.mevuid;
                var uid = 26;
                   
                let self = this;
                axios.post(api_url+'getAlertLogs',{
                    "uid":uid,
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.logs;
                    self.total_rows = response.data.total;                    
                    // console.log(self.rows);

                })
                .catch(function(error){
                    // console.log(error);
                });
            },
            onChangeQuery(queryParams){
                this.queryParams = queryParams;
                this.fetchData();
            },
        }       
    }
</script>
