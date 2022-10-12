<template>
    <div>
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
                    <div class="invoice p-3 mb-3">
                      <!-- title row -->
                                                
                      <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
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

                            <div class="nodatatxt" v-if="!logsdata.length" >No Data</div>
                            
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

<script>
        
    export default {
        name:'apilogs',
        data:function(){
            return{
            	logsdata:[],
            }
        },
        mounted(){        

        	var thisObj = this;
        	var uid = localStorage.mevuid;
            var uid = 213;
            
        	this.axios.get(api_url+'getLogs/'+uid)
        	.then(function(response){
        		console.log(response);
        		thisObj.logsdata = response.data.logs;


        	});
            
        },          
        methods:{
                        
        }       
    }
</script>
