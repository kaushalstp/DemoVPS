<template>
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2"> 
                    <div class="col-sm-6">
                        <h1>Invoice History</h1>
                    </div>                  
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->

        <section class="content">
            <div class="container-fluid">
                <div class="row page-invoice ">
                    <div class="col-12">
                        <div class="callout callout-info">              
                          Welcome to your invoice history, here you can view of all your transactions. 
                        </div>


                    <!-- Main content -->
                    <div class="p-3 mb-3">
                      <!-- title row -->
                                            
                      <!-- Table row -->
                        <div class="row">

                            <div class="col-12">
                                <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" 
                                    :total-rows="total_rows" @on-change-query="onChangeQuery" @refresh-data="onRefreshData" >


                                    <template slot="download-slot" slot-scope="props">
                                                                   
                                        <a target="_blank" v-show="props.row.allowdownload" 
                                        v-bind:href = "props.row.downloadlink" > <i class="fa fa-download" ></i> Download</a>

                                        <a v-show="!props.row.allowdownload && props.row.payment_status == 'true'" href="javascript:void(0);"
                                        @click="confirmOrder(props.row.invoice_real_id)"  > <i class="fa fa-check-square" ></i> Confirm Order</a>

                                        <a href="javascript:void(0);"
                                        v-show="props.row.special_discount == 'y' && 
                                        props.row.payment_status != 'true' " 
                                        @click="finish_payment(props.row)" data-toggle="modal" data-target="#finish_payment_modal" >Finish Payment</a>
                                       
                                    </template>  

                                    <!-- <template slot="finishpayment-slot" slot-scope="props">
                                                                   
                                        <a target="_blank" 
                                        v-show="props.row.special_discount == 'y' && 
                                        props.row.payment_status == 'false' " 
                                        @click="finish_payment(props.row)" > <i class="fa fa-download" ></i> Download</a>

                                        <a v-show="!props.row.allowdownload" href="javascript:void(0);"
                                        @click="confirmOrder(props.row.invoice_real_id)"  > <i class="fa fa-check-square" ></i> Confirm Order</a>
                                       
                                    </template>  -->

                                </vue-bootstrap4-table> 
                            </div>    


                              
                            <!-- <div class="col-12 table-responsive">
                              <table class="table table-striped">
                                <thead>
                                    <tr>
                                      <th>INVOICE #</th>
                                      <th>PAYMENT DATE</th>    
                                      <th>ITEM PURCHASED</th>                  
                                      <th>AMOUNT (USD)</th>
                                      <th>STATUS</th>
                                      <th>Action</th>
                                    </tr>
                                </thead> 
                                <tbody>                                    

                                    <tr v-for="log in invoice_history" > 
                                        <td> {{ log.invoice_id }} </td>
                                        <td> {{ log.created_at }} </td>
                                        <td> {{ log.credits }} credits </td>
                                        <td> $ {{ log.amount }}  </td>
                                        <td> Active </td>
                                        <td>
                                            <a title="Download" target="_blank" v-bind:href = "log.downloadlink" > 
                                            <i class="fa fa-download" ></i> Download</a>
                                        </td>
                                    </tr>

                                </tbody>
                              </table>
                            </div>        -->

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

      

        <!-- Buy Plan popup  -->

        <div class="" >
            <div class="modal fade" id="otpVerifyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                Verify Paypal Email</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">  
                                <div class=""   >
                                    
                                    <form  name="frmVerifyOTP" @submit.prevent="submitVerifyOTP" 
                                    id="frmVerifyOTP" >
                                        <input type="hidden"  name="user_email" 
                                        id="invoice_id" v-model="otpinfo.invoice_id" />

                                        <div class="" >
                                            <span style="font-size: 14px;color:green;" 
                                            v-html="msg_otp_verification" >
                                                
                                            </span>
                                        </div>

                                        <div v-html="msg.new_otp" >
                                            
                                        </div>      

                                        <div class="row" >
                                            <div class="col-sm-1" > </div>
                                            <div class="col-sm-6" > 
                                                <div class="form-group">                                        
                                                    <input type="text" class="form-control" 
                                                    id="new_otp" 
                                                    v-model="otpinfo.new_otp"  name="new_otp" placeholder="Enter Code" 
                                                      />
                                                </div>    
                                                <br/>                                                       
                                            </div>

                                            <div class="col-sm-4" > 
                                                <button type="submit" class="btn btn-primary">Verify Code</button>
                                            </div>
                                        </div>    
                                        
                                    </form>        
                                </div>                                  
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                            
                            </div>                    
                    </div>
                  </div>
            </div>
        </div>


        <div class="modal fade" id="finish_payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i><b>Pay Now</b></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">                      

                            <div class="row" >
                                        
                                <div class="col-sm-4" >
                                    <b> {{ sdp_data.credits }} </b> 
                                </div>  

                                <div class="col-sm-3" >                                 
                                    <h3 class="planamttitle" >$ {{ sdp_data.amount }}</h3>                                      
                                </div>

                                <div class="col-sm-5" > 
                                    <paypal-checkout 
                                            v-bind:amount="payable_amount"
                                            currency="USD" 
                                            :client="paypal"
                                            env="production"  
                                            notify-url="http://client.myemailverifier.com/paypalnotify" 
                                            v-on:payment-completed="pendingpaymentcompleted" v-on:payment-authorized="pendingpaymentauthorized" >
                                    </paypal-checkout>  
                                </div>                              
                            </div>                                                      
                  </div>
                  <div class="modal-footer">                
                    <!-- <button type="button" class="btn btn-primary" data-dismiss="modal" >Cancel</button> -->
                  </div>
                </div>
            </div>
        </div>

        

            <!--  ****  -->

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
        name:'Invoice',        
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
                invoice_history:[],
                rows:[],
                columns:[                    
                    {
                        label: "INVOICE #",
                        name: "invoice_id",
                    },
                    {
                        label: "PAYMENT DATE",
                        name: "created_at",
                    },
                    {
                        label: "ITEM PURCHASED",
                        name: "credits",
                    },
                    {
                        label: "Applied Coupon Code",
                        name: "coupon_code",
                    },
                    {
                        label: "AMOUNT",
                        name: "amount",
                    },                                        
                    {
                        label: "Actions",
                        name: "actions",
                        slot_name: "download-slot" 
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
            pendingpaymentauthorized(){

            },
            pendingpaymentcompleted(event){

                //console.log(event);                 

                var thisObj = this;                
                

                /*this.sdp_data.credits = event.payer.payer_info.email; 
                this.sdp_data.amount = event.payer.payer_info.email; 
                this.sdp_data.userid = event.payer.payer_info.email; */
                this.sdp_data.payer_email = event.payer.payer_info.email; 
                this.sdp_data.paypal_response = event;  
                

                this.axios.post(api_url+'pendingPaymentAuthorized',this.sdp_data)
                .then((response)=>{
                    //console.log(response);        

                    thisObj.msg_otp_verification = response.data.msg;   
                    thisObj.otpinfo.invoice_id = response.data.data.invoice_id;


                    jQuery('#finish_payment_modal').modal('hide');
                    jQuery('#otpVerifyModal').modal('show');                    
                    
                    
                    
                    //window.location=app_url+"/Invoice";                                                                                       
                });
            },
            finish_payment(row){ 
                //console.log(row);
                //alert(row.amount);
                this.sdp_data.userid = row.user_id;
                this.sdp_data.credits = row.credits;
                this.sdp_data.amount = row.integeramount;
                this.sdp_data.invoice_id = row.invoice_real_id; 
                this.otpinfo.invoice_id = row.invoice_real_id;
                this.payable_amount = row.integeramount;

                //alert(this.sdp_data.amount);

            },
            onRefreshData() {
                this.fetchData();       
            },
            fetchData:function(){
               
                var uid = localStorage.mevuid;
                //var uid = 26;
                   
                let self = this;
                axios.post(api_url+'invoiceHistory',{
                    "uid":uid,
                    params: {                        
                        "queryParams": this.queryParams,
                        "page": this.queryParams.page,                            
                    },                    
                })
                .then(function(response) {
                    self.rows = response.data.invoiceHistory;
                    self.total_rows = response.data.total;                                        
                })
                .catch(function(error){
                    
                });
            },  
            submitVerifyOTP(e){
				e.preventDefault(); 

				this.errors=[];   
				this.hasError=false;   
				this.msg=[];
				this.vlds=[];

				if(!this.otpinfo.new_otp){
					this.hasError=true;
					this.msg['new_otp']  = "<span style='color:red;' >Please enter code</span>";              
				}

				if(this.hasError == false){  
					var thisObj = this;
										
					this.axios
					.post(api_url+'verify_payer',{
						invoice_id:thisObj.otpinfo.invoice_id,
						new_otp:thisObj.otpinfo.new_otp						
					})
					.then(function(response){
						//console.log(response);                        


						//window.location=app_url+"/Invoice";            							           		
						/*if(response.data.status==0 || response.data.status=='0'){
							thisObj.msgerror = true;            
							thisObj.textmsg=response.data.msg;            
						}*/

						if(response.data.status==1 || response.data.status=='1'){
							alert(response.data.msg);
							window.location=app_url+"/Invoice";   							                                    
						}else{
							thisObj.msg['new_otp']  = "<span style='color:red;' >Incorrect code</span>";
						} 
					});
				}        
			},
            confirmOrder:function(invoice_id){
            	
                var thisObj = this;
                thisObj.otpinfo.invoice_id = invoice_id;

                this.axios
                    .post(api_url+'confirmMyInvoice',{invoice_id:invoice_id})
                    .then(function(response){

                        thisObj.msg_otp_verification = response.data.msg;
                        jQuery('#otpVerifyModal').modal('show');

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
