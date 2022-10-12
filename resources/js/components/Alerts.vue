<template>
    <div class="page-admin-users" >
        <section class="content-header"> 
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Notifications</h1>
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
                                    @on-change-query="onChangeQuery" :total-rows="total_rows"  @refresh-data="onRefreshData" :classes="classes" >

                                        <template slot="action-slot" slot-scope="props">
                                           <button type="button" class="btn btn-primary btn-sm" @click="onEdit(props.row)"  
                                            data-toggle="modal" data-target="#exampleModal" >Edit</button>

                                            &nbsp;&nbsp;

                                            <button type="button" class="btn btn-danger btn-sm" @click="onDelete(props.row)">Delete</button>
                                        </template>          
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
                          
                          <!-- this row will not appear when printing -->            
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        


         <!-- Add Credit Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" 
        aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Credit</h5>
                        <button type="button" ref="closeBtn" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    

                        <form name="frmAddCredit" id="frmAddCredit" @submit.prevent="submitAddCredit" >
                            <div class="row" >
                                <div class="col-sm-12" >
                                    <input type="hidden" name="reg_id"  v-model="reg_id" />
                                    <div class="form-group">                                
                                        <div class="row" >
                                            <div class="col-sm-7" >
                                                <label for="new_credits">Credits</label>

                                                <input type="number" class="form-control" v-model="new_credits" 
                                                id="new_credits" name="new_credits" placeholder=""  />
                                            
                                                 <span class="text-danger" v-if="msg.new_credits" >{{msg.new_credits}}</span>
                                                 </div>

                                            <div class="col-sm-4" >
                                                <label for="amount">Amount</label>
                                                <input type="number" class="form-control" v-model="amount" 
                                                id="amount" name="amount" placeholder=""  />
                                            </div>
                                        </div>                                                                        
                                    </div>                                     
                                </div>                 

                                <div class="col-sm-12" >
                                    <br/>
                                     <div class="form-group">
                                        <button  name="submit" type="submit" class="btn btn-primary">
                                        Add
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
        name:'Alerts', 
         data:function(){
            return{
                    reg_id:0,
                    logsdata:[],
                    rows:[],
                    msg:[],
                    new_credits:0,
                    amount:0,
                    columns:[
                    {
                        label: "id",
                        name: "reg_id",
                        visibility:false,
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
                        label: "Reg Date",
                        name: "created_at",
                        sort: true,
                    },                                       
                    {
                        label: "Credits",
                        name: "credits",
                        slot_name: "credits-slot",
                        sort: false,
                    },
                    {
                        label: "IP",
                        name: "IP",
                    },
                    {
                        label: "Country",
                        name: "country",
                        sort: true,
                    },
                    {
                        label: "Title",
                        name: "title",
                        sort: true,
                    },
                    {
                        label: "Phone",
                        name: "phone",
                    },
                    {
                        label: "Website",
                        name: "company_website",
                    },
                    {
                        label: "Source",
                        name: "source",
                    },
                    {
                        label: " ",
                        name: "behalflogin",
                        slot_name: "behalflogin-slot"
                    },
                    {
                        label: "Active",
                        name: "actions",
                        slot_name: "action-slot"
                    },
                ],
                classes:{
                    cell : {                        
                        "datecell" : function(row,column,cellValue){
                            return column.name == "created_at" || column.name == "created_at"
                        }
                    },
                    /*cell : {                        
                        "addresscell" : true,
                    }*/
                },
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
            this.fetchData();
        },
        components:{
            VueBootstrap4Table
        },
        methods:{
            
            onEdit:function(row){
                // console.log("id is: "+row.id);
                this.getScheduleData(row.id);
            },
            onDelete:function(row){

                var thisObj = this;

                // console.log(row.id);  
                if(!confirm('Are you sure you want to delete this record?')){
                    return false;
                }

                axios.get(api_url+'deleteSchedule/'+row.id,{
                        
                })
                .then(function(response){
                    if(response.data.status == '1'){
                        thisObj.$parent.msgsuccess=true;
                        thisObj.$parent.textmsg=response.data.message;
                    }                                         

                    thisObj.fetchData();
                })
                .catch(function(error){
                    // console.log(error);
                });
            },
            getAlertData:function(sid=0){
                
                var thisObj = this;    
                thisObj.modalShow=true;    

                this.axios.get(api_url+'getScheduleData/'+sid)
                .then(function(response){
                    if(response.data.status=='1'){
                        thisObj.model.condition_name = response.data.schedule.condition_name; 
                        thisObj.model.description = response.data.schedule.description; 
                        thisObj.model.days = response.data.schedule.days; 
                        thisObj.model.template = response.data.schedule.template; 
                        thisObj.model.status = response.data.schedule.status; 
                        
                        thisObj.model.scheduleid = sid;                         
                    }
                });                
            },
            submitAlertData:function(e){
                e.preventDefault();
                var thisObj = this;

                this.axios.post(api_url+'submitScheduleData',this.model)
                .then(function(response){
                    if(response.data.status=='1'){

                        thisObj.$parent.msgsuccess = true;
                        thisObj.$parent.msgerror = false;
                        thisObj.$parent.textmsg = response.data.message;
                    }else{

                        thisObj.$parent.msgsuccess = false;
                        thisObj.$parent.msgerror = true;
                        thisObj.$parent.textmsg = response.data.message;
                    }

                    thisObj.$refs.closeBtn.click();
                    window.scrollTo(0,0);
                    thisObj.fetchData();
                });
            },            
            onRefreshData(){                
                this.fetchData();
            },            
            resetEmailFormValues:function(){
                this.txtmailerror="";
                this.mailerrors=false;
            },
            resetFormValues:function(){
                this.model.condition_name='';
                this.model.days='';
                this.model.description='';
                this.model.template='';
                this.model.status='';                
                this.model.scheduleid='';

                this.modalShow=true;
            },
            fetchData:function(){

                var uid = localStorage.mevuid;
                var uid = 26;
                   
                let self = this;
                axios.post(api_url+'getAllUsers',{
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
