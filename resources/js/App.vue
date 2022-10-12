<template> 
    <!-- Site wrapper --> 
    <component :is="layout">              
        <router-view :layout.sync="layout" ></router-view>                
    </component>
</template>
  

<style>


    body{
        font-family: "Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    }

    .menu-open a{
        background-color: #007bff !important;
    }

    .nav-treeview .nav-link {
        background-color: #343a40 !important;
    }

    .card-primary .card-header{
        background-color: #072c61 !important;
    }

    .card-primary .btn-primary{
        background-color: #072c61 !important;
    } 

    .sidebar .router-link-exact-active{
        background-color: #007bff !important;
        color: #fff !important;
    }

    .sidebar .has-treeview .router-link-exact-active{
        background-color: rgba(255,255,255,.9) !important;
        color: #343a40 !important; 
    }

    .has-treeview li a p {
        font-size: 14px;
        padding-left: 25px;
    }

    .toastr_suc{
        float:left;
        width: 100%;
        z-index: 9999; 
        position: fixed;
    }
    
    .nodatatxt {
        width: 100%;
        text-align: center;
        padding: 10px 0 0 0;
        float: left;        
    }
</style>

<script>
    const Userboardcomp = "userboard"; 

    /*import Header from './components/Header.vue';
    import Leftsidebar from './components/leftsidebar.vue';
    import Footer from './components/Footer.vue';*/
    
    

    export default{
        mounted(){
                                    
        },
        data(){ 
            return{ 
                logourl:img_url+"AdminLTELogo.png",
                userlogo:img_url+"user2-160x160.jpg",
                msgsuccess:false,
                msgerror:false,
                textmsg:'',                
                is_user_page:true,
                mevuid:localStorage.mevuid, 
                isRouteBack: false,
                transitionDisabled: false,           
            }
        },
        methods:{
            logout:function(){
                //localStorage.removeItem('userid');                

                this.msgsuccess=true;
                this.msgerror=false;
                this.textmsg='logged out successfully';

                this.$router.push({
                    name:'login'                            
                });
            },
            closemsg:function(){            
                this.msgsuccess=false;
                this.msgerror=false;
                this.textmsg='';
            }
        },
        components:{
            
        },
        computed:{
            layout(){
                /*if(this.$route.name == "Login" || this.$route.name == "Register" ){
                    return 'visitorboard-layout';
                }else{
                    return 'userboard-layout'; 
                }*/

                return (this.$route.meta.layout || Userboardcomp + '-layout');
            }            
        },
        created(){
            
            /*setInterval(function(){
                this.textmsg = false;                 
                this.msgsuccess = false;
                this.msgerror = false;
               

            }, 5000);*/

        }    
    }


        
    

</script>

<style>

    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
      opacity: 0;
    }

    table{
        overflow:auto;
        max-width:100%;
    }

    .alertmsgbox{         
        height:50px;
    }

    .sitefooter{
        background-color:#000;
        color:#fff;
        padding:10px;
        font-size:16px;
    }

    .container{
        min-height:500px;
    }

</style>

