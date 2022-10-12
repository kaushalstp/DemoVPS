<template>  
    <div class="perks-page" > 
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Perks</h1>
                    </div>                   
                </div>
            </div><!-- /.container-fluid -->        
        </section>
        <p></p>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid stat-widgets" >                

                <div class="row" >
                    
                    <div class="col-sm-12" >

                        <div class="card" style="position: relative; left: 0px; top: 0px;">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                  <i class="ion ion-clipboard mr-1"></i>
                                  Additional Discount
                                </h3>                                
                            </div>
                              <!-- /.card-header -->
                            <div class="card-body">

                                <div class="row" >    
                                   
                                    <div class="col-sm-3" v-show="first_coupon_part" >
                                        <div class="info-box bg-gradient-info">
                                             <div class="overlay" v-show="!show_first_coupon" ></div>
                                              <span class="info-box-icon"><i class="fa fa-bookmark"></i></span>

                                              <div class="info-box-content">
                                                <span class="info-box-text">Coupon</span>
                                                <span class="info-box-number" v-show="show_first_coupon" >MEVUSER65F2020</span>
                                                <span class="info-box-number" v-show="!show_first_coupon" >xxxxxxxxxxxxxx</span>

                                                <div class="progress">
                                                  <div class="progress-bar" style="width: 70%"></div>
                                                </div>
                                                <span class="progress-description"> 
                                                  65% Flat Discount
                                                </span>
                                              </div>
                                              <!-- /.info-box-content -->
                                        </div>

                                        <span class="w-100 float-left text-center" v-show="!show_first_coupon" >Available after 2 orders</span>
                                    </div>

                                    <div class="col-sm-3" v-show="sec_coupon_part" >
                                        <div class="info-box bg-gradient-info">
                                             <div class="overlay" v-show="!show_sec_coupon" ></div>
                                              <span class="info-box-icon"><i class="fa fa-bookmark"></i></span>

                                              <div class="info-box-content">
                                                <span class="info-box-text">Coupon</span>
                                                <span class="info-box-number" v-show="show_sec_coupon" >MEVUSER70F2020</span>
                                                <span class="info-box-number" v-show="!show_sec_coupon" >xxxxxxxxxxxxxx</span>

                                                <div class="progress">
                                                  <div class="progress-bar" style="width: 70%"></div>
                                                </div>
                                                <span class="progress-description"> 
                                                  70% Flat Discount
                                                </span>
                                              </div>
                                              <!-- /.info-box-content -->
                                        </div>

                                        <span class="w-100 float-left text-center" v-show="!show_sec_coupon" >Available after 4 orders</span>
                                    </div>


                                    <div class="col-sm-3" >
                                        <div class="info-box bg-gradient-info">
                                             <div class="overlay" v-show="!show_third_coupon" ></div>
                                              <span class="info-box-icon"><i class="fa fa-bookmark"></i></span>

                                              <div class="info-box-content">
                                                <span class="info-box-text">Coupon</span>
                                                <span class="info-box-number" v-show="show_third_coupon" >MEVUSER75F2020</span>
                                                <span class="info-box-number" v-show="!show_third_coupon" >xxxxxxxxxxxxxx</span>

                                                <div class="progress">
                                                  <div class="progress-bar" style="width: 70%"></div>
                                                </div>
                                                <span class="progress-description"> 
                                                  75% Flat Discount
                                                </span>
                                              </div>
                                              <!-- /.info-box-content -->
                                        </div>

                                        <span class="w-100 float-left text-center" v-show="!show_third_coupon" >Available after 5 orders</span>
                                    </div>
                                </div>    

                            </div>
                              <!-- /.card-body -->
                              <div class="card-footer clearfix">
                                
                              </div>
                        </div>

                    </div>    

                </div>                
            </div>

                                   
        </section>
    </div>    

</template>


<style>
    
    

    .perks-page .stat-widgets svg{
        font-size: 40px;  
        top: auto;
        right: auto;
        position: unset;      
    }
</style>

<script>
    

    export default{
        name:'Perks',         

        data:function(){
            return{  
                show_first_coupon:false, 
                first_coupon_part:true,

                show_sec_coupon:false, 
                sec_coupon_part:true,

                show_third_coupon:false, 

                additional_coupons:{ 

                }            	                          
            }
        },        
        mounted(){                    
            this.$parent.is_user_page=false;      
            var thisObj = this;
            
            this.axios
            .get(api_url+'checkusersession')
            .then(function(response){                
                
                if(response.data.status == 0 || response.data.status == '0'){
                    //window.location=app_url;
                    thisObj.$router.push({ 
                        name:'Login',                    
                    });
                }                
            })
            
            this.axios
            .post(api_url+'getAdditionalCoupons')
            .then(function(response){                
                                    
                if(response.data.status == 1 || response.data.status == '1'){                	
                    
                    if(parseInt(response.data.data.orders_placed) >= 2){
                        thisObj.show_first_coupon=true;
                    }

                    if(parseInt(response.data.data.orders_placed) >= 4){
                        thisObj.show_sec_coupon=true;
                    }

                    if(parseInt(response.data.data.orders_placed) >= 5){
                        thisObj.show_third_coupon=true;
                    }

                    response.data.data.used_coupons.forEach(function(item){ 
                        
                        if(item.coupon_code == 'MEVUSER65F2020'){
                            
                            if(parseInt(item.orders) >= 1){
                                thisObj.first_coupon_part=false;
                            }                                                        
                        }


                        if(item.coupon_code == 'MEVUSER70F2020'){
                            
                            if(parseInt(item.orders) >= 1){
                                thisObj.sec_coupon_part=false;
                            }                                                        
                        }                        
                    });
				}  
            });
        }, 
        components: {            

        },         
        methods:{
           
        },        
        computed:{
            
        },
        created(){            

        },           
    }
        

   

</script>
