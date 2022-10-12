<template>
    <!-- Site wrapper -->
    <div class="wrapper"  >
      <!-- Navbar Header -->       

            <Header></Header>
      <!-- /.navbar header -->
            <Leftsidebar></Leftsidebar>
        
              <!-- Content Wrapper. Contains page content -->
              <div class="content-wrapper">
                <!-- Content Header (Page header) -->    

                <router-view></router-view>

              </div>
              <!-- /.content-wrapper -->
            <Footer></Footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
</template>



<script>


    import Header from './../components/Header.vue';
    import Leftsidebar from './../components/leftsidebar.vue';
    import Footer from './../components/Footer.vue';    
    

    export default{
        name:"Userboard",         
        components:{
            Leftsidebar,
            Header,
            Footer,
        },
        
    }
</script>

