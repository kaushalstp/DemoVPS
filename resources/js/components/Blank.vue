<template>
    <div class="container">
        <div class="row justify-content-center">        

            <div class="col-sm-12">
                <div class="col-sm-8 justify-content-center">
                    <h2>Email Schedules</h2>
                </div>
            </div>

            <div class="col-sm-12">
 
                <div class="row" >
                    <div class="col-sm-4" >
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                          Add Condition
                        </button>
                    </div>

                    <!-- <div class="col-sm-4" >
                        <router-link to="/sheetlist" class="nav-item nav-link">
                        <h2>View Sheets</h2></router-link>                    
                    </div> -->
                    <div class="col-sm-6" >
                        &nbsp;
                    </div>

                    <div class="col-sm-2" >
                        <button type="button" class="btn btn-primary" >
                          Test Mail
                        </button>
                    </div>
                </div>    
            </div>


            <div class="row" >
                <div class="col-sm-12" >
                    <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" :total-rows="total_rows" >
                    </vue-bootstrap4-table>
                </div>
            </div>    

        </div> 

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Condition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                        
                       
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                    
                  </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
</template>

<script>

    
    
    
    export default {
        name:'Dashboard',
        mounted(){        
            //localStorage.setItem('isLoggedin','');
            if(localStorage.getItem('isLoggedin') != '1'){
                this.$router.push({
                    name:'login'                            
                });
            }

            this.fetchData();
        },  
        data(){ 
            return{                
                msgsuccess:this.$route.params.msgsuccess,
                msgerror:this.$route.params.msgerror,
                textmsg:this.$route.params.textmsg,
                model:{
                    condition_name:'',
                    description:'',
                },
                schema: conditionFormSchema,
                formOptions:{
                    validateAfterChanged: true
                },
                isSaving: false,
                rows:[
                    {
                        "id": 1,
                        "condition_name": "franecki.anastasia@gmail.com",
                        "description": "franecki.anastasia@gmail.com",
                        "days": "franecki.anastasia@gmail.com",
                        "template": "franecki.anastasia@gmail.com",
                        "status": "franecki.anastasia@gmail.com",
                    },
                ],
                columns:[
                    {
                        label: "id",
                        name: "id",
                        visibility:false,
                    },
                    {
                        label: "Condition",
                        name: "condition_name",
                    },
                    {
                        label: "Description",
                        name: "description",
                    },
                    {
                        label: "Days to Send",
                        name: "days",
                    },
                    {
                        label: "Template",
                        name: "template_name",
                    },
                    {
                        label: "Status",
                        name: "status",
                    },
                ],
               config: {                   
                    server_mode: true,
                    multi_column_sort:true,
                    per_page:5,
                    per_page_options:  [5,  10,  20,  30,  40,  50,  100],                        
                },
                queryParams: {
                    sort: [],
                    filters: [],
                    global_search: "",                    
                    page: 1,                    
                },
                total_rows: 0,
                schedulerfilter:[],                
            }   
        }, 
        methods:{
            submitConditionData:function(){

            },
            fetchData:function(){
                   
                let self = this;
                axios.post(api_url+'getallconditions',{
                    params: {
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.sheetlist;
                    self.total_rows = response.data.total;                    
                })
                .catch(function(error){
                    console.log(error);
                });
            },

        }       
    }
</script>
