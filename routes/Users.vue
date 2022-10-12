<template>
    <div class="page-admin-users" >
        <section class="content-header"> 
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Users</h1>
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


                                        <template slot="credits-slot" slot-scope="props">

                                            {{ props.row.credits }} <br/>

                                            <button type="button" class="btn btn-primary btn-sm" 
                                                @click="onAddCredit(props.row)" data-toggle="modal" data-target="#exampleModal" >Add Credit
                                            </button>                                                                             
                                        </template>


                                        <template slot="action-slot" slot-scope="props">

                                           <toggle-button :value="props.row.active"  
                                           @change="changeUserStatus(props.row.reg_id, $event)" />                                                                             
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
        name:'Users', 
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
            submitAddCredit(e){ 
                
                e.preventDefault(); 
                
                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];


                
                if(!this.new_credits){                    
                    this.hasError=true;                    
                    this.msg['new_credits'] = "please enter credits ";
                }

                if(this.new_credits <= 0){
                    this.hasError=true;                    
                    this.msg['new_credits'] = "0 Credits not allowed ";   
                }
                
                
                if(this.hasError == false){ 
 
                    var thisObj = this;
 
                    this.axios
                    .post(api_url+'addcredits',{
                        reg_id:thisObj.reg_id,
                        new_credits:thisObj.new_credits,
                        amount:thisObj.amount,
                    })
                    .then(function(response){
                    
                        thisObj.$refs.closeBtn.click();
                        alert(response.data.msg);
                        thisObj.fetchData();
                        
                    }); 
                }
            },
            onAddCredit:function(row){
                console.log("id is: "+row.reg_id);
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
                    console.log(self.rows);

                })
                .catch(function(error){
                    console.log(error);
                });
            },
            onChangeQuery(queryParams){
                this.queryParams = queryParams;
                this.fetchData();
            },
        }       
    }
</script>
