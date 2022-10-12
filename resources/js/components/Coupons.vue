<template>
    <div class="page-admin-users" >
        <section class="content-header"> 
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Coupon Codes</h1>
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

                                <div class="col-sm-12 text-right" >
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" @click="resetFormValues" >Add New Coupon</button>
                                    <br/><br/>
                                </div>    

     
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
                                    


                                        <template slot="status-slot" slot-scope="props">

                                           <toggle-button :value="props.row.status"  
                                           @change="changeCouponStatus(props.row.id, $event)" />                                                                             
                                        </template>


                                        <template slot="action-slot" slot-scope="props">
                                           <button type="button" class="btn btn-primary btn-sm" @click="onEdit(props.row)"  
                                            data-toggle="modal" data-target="#exampleModal" >Edit</button>

                                            &nbsp;&nbsp;

                                            <button type="button" class="btn btn-danger btn-sm" @click="onDelete(props.row)">Delete</button>
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
        


         <!-- Add Credit Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" 
        aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Coupon</h5>
                        <button type="button" ref="closeBtn" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    

                        <form name="frmAddCredit" id="frmAddCredit" @submit.prevent="submitCouponData" >
                            <input type="hidden" name="id" v-model="rec_coupon.id" />
                            <div class="row" >
                                    <div class="col-sm-2" ></div>                                    
                                    <div class="col-sm-8" >                                    
                                        <div class="form-group">                                
                                                                                        
                                            <label for="coupon_code">Coupon Code</label>

                                            <input type="text" class="form-control" 
                                            v-model="rec_coupon.coupon_code" @keyup="clearmsg" 
                                            id="coupon_code" name="coupon_code" placeholder=""  />
                                        
                                             <span class="text-danger" v-if="msg.coupon_code" >
                                                {{msg.coupon_code}}
                                             </span>                                                                       
                                            
                                        </div>                                                                   

                                        <div class="form-group">
                                            
                                            <label for="amount">Percentage</label>
                                            <input type="number" class="form-control"  @keyup="clearmsg" 
                                            v-model="rec_coupon.percentage" 
                                            id="percentage" name="percentage" placeholder=""  />
                                             <span class="text-danger" v-if="msg.percentage" >
                                                {{ msg.percentage }}
                                             </span>
                                        </div>                                

                                        <div class="form-group">
                                            
                                            <label for="amount">From Credits</label>
                                            <input type="text" class="form-control"  @keyup="clearmsg" 
                                            v-model="rec_coupon.from_credits" 
                                            id="from_credits" name="from_credits" placeholder=""  />
                                             <span class="text-danger" v-if="msg.from_credits" >
                                                {{ msg.from_credits }}
                                             </span>
                                        </div>                                

                                        <div class="form-group">
                                            
                                            <label for="to_credits">To Credits</label>
                                            <input type="text" class="form-control"  @keyup="clearmsg" 
                                            v-model="rec_coupon.to_credits" 
                                            id="to_credits" name="to_credits" placeholder=""  />
                                             <span class="text-danger" v-if="msg.to_credits" >
                                                {{ msg.to_credits }}
                                             </span>
                                        </div>                                


                                        <div class="form-group">
                                            <button  name="submit" type="submit" class="btn btn-primary">
                                            {{ submit_text }}
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
        name:'Coupons', 
            data:function(){
            return{
                    is_admin:false,
                    errors:[],   
                    hasError:false,                   
                    vlds:[],
                    reg_id:0,                    
                    rows:[],
                    msg:[],
                    new_credits:0,                    
                    rec_coupon:{
                        id:0,
                        coupon_code:'',
                        percentage:'',
                        from_credits:'',
                        to_credits:'',
                        status:'',
                    },
                    amount:0,
                    columns:[
                    {
                        label: "id",
                        name: "id",
                        visibility:false,                        
                    },
                    {
                        label: "Coupon Code",
                        name: "coupon_code",                         
                    },
                    {
                        label: "Percentage",
                        name: "percentage",                        
                    },
                    {
                        label: "From Credits",
                        name: "from_credits",                                                
                    },
                    {
                        label: "To Credits",
                        name: "to_credits",                                                
                    },                    
                    {
                        label: "Active",
                        name: "status",
                        slot_name: "status-slot"
                    },
                    {
                        label: "Active",
                        name: "action",
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
        computed:{
            submit_text:function(){
                if(this.rec_coupon.id==0){
                    return "Add";
                }else{
                    return "Save";
                }
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
            clearmsg:function(){
            

                if(this.rec_coupon.coupon_code != ''){
                    this.msg.coupon_code = '';
                }                

                if(this.rec_coupon.from_credits != ''){
                    this.msg.from_credits = '';
                }               

                if(this.rec_coupon.to_credits != ''){
                    this.msg.to_credits = '';
                }               

                if(this.rec_coupon.percentage != ''){
                    this.msg.percentage = '';
                }                

                
            },
            getCouponData:function(cid=0){
                            
                var thisObj = this;    
                thisObj.modalShow=true;    

                this.axios.get(api_url+'getCouponData/'+cid)
                .then(function(response){
                    if(response.data.status=='1'){                        
                        thisObj.rec_coupon = response.data.coupon;
                        
                    }
                });                
            },
            submitCouponData:function(e){
                e.preventDefault();
                var thisObj = this; 

                
                this.errors=[];   
                this.hasError=false;   
                this.msg=[];
                this.vlds=[];

                if(!this.rec_coupon.coupon_code){                    
                    this.hasError=true;                    
                    this.msg['coupon_code'] = "please enter Coupon Code ";
                }


                if(!this.rec_coupon.from_credits){                    
                    this.hasError=true;                    
                    this.msg['from_credits'] = "please enter from credits ";
                }

                if(!this.rec_coupon.to_credits){                    
                    this.hasError=true;                    
                    this.msg['to_credits'] = "please enter from credits ";
                }

                if(!this.rec_coupon.percentage){                    
                    this.hasError=true;                    
                    this.msg['percentage'] = "please enter % discount ";
                }else{
                    if(this.rec_coupon.percentage <= 0){
                        this.hasError=true;                    
                        this.msg['percentage'] = "0 % not allowed ";   
                    }
                }
                

                if(this.hasError==false){

                    this.axios.post(api_url+'submitCouponData',this.rec_coupon)
                    .then(function(response){
                        if(response.data.status=='1'){
                            thisObj.$toast.open({
                                message: response.data.message,
                                type:'success',
                                position:'top-right'
                            });
                            
                        }else{
                            thisObj.$toast.open({
                                message: response.data.message,
                                type:'error',
                                position:'top-right'
                            });
                            
                        }

                        //alert(response.data.message);


                        thisObj.$refs.closeBtn.click();
                        window.scrollTo(0,0);
                        thisObj.fetchData();

                    });

                }

                
            },
            onAddCredit:function(row){
                
                this.reg_id = row.reg_id;                
            },
            changeCouponStatus:function(id,event){

                let self = this;
                var thisObj = this;

                axios.post(api_url+'updateCouponStatus',{
                        id:id,
                        status:event.value,               
                })
                .then(function(response){
                    
                    if(response.data.status=='1'){
                        thisObj.$toast.open({
                            message: response.data.msg,
                            type:'success',
                            position:'top-right'
                        });
                        
                    }else{
                        thisObj.$toast.open({
                            message: response.data.msg,
                            type:'error',
                            position:'top-right'
                        });
                        
                    }                                                                   
                });
            }, 
            onEdit:function(row){
                
                this.getCouponData(row.id);
            },
            onDelete:function(row){

                var thisObj = this;

                //// console.log(row.id);  
                if(!confirm('Are you sure you want to delete this coupon?')){
                    return false;
                }

                axios.get(api_url+'deleteCoupon/'+row.id,{
                        
                })
                .then(function(response){
                    /*if(response.data.status == '1'){
                        thisObj.$parent.msgsuccess=true;
                        thisObj.$parent.textmsg=response.data.message;
                    }                                         */

                    alert(response.data.message);

                    thisObj.fetchData();
                })
                .catch(function(error){
                    
                });
            },   
            fetchData:function(){

                var uid = localStorage.mevuid;
                var uid = 26;
                   
                let self = this;
                axios.post(api_url+'getAllCoupons',{ 
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                   
                })
                .then(function(response) {
                    self.rows = response.data.data;
                    self.total_rows = response.data.total;                    
                    

                })
                .catch(function(error){
                    
                });
            },
            onChangeQuery(queryParams){
                this.queryParams = queryParams;
                this.fetchData();
            },
            resetFormValues:function(){
                this.rec_coupon.coupon_code='';
                this.rec_coupon.percentage='';
                this.rec_coupon.from_credits='';
                this.rec_coupon.to_credits='';

                this.modalShow=true;
            },
        }       
    }
</script>
