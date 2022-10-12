<template>  
    <div class="admin-page-settings" > 
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Free Credits</h1>
                    </div>                   
                </div>
            </div><!-- /.container-fluid -->


            <div class="callout callout-info"> 

                <h5>Share & Earn </h5>

                <!-- <p>Please share our Article on below social networks and grab Free Credits.</p>

                <p>Write an Article or Blog. Share our service on social media. Write your review on GwCrowd,Trustpilot,Capterra.  

                Provide us the link in the below claim box. Admin will review it and approve the Free Credits. </p> -->

                <p>
                    &#9755; &nbsp;  Share for us, and we will care for you! Please share your experience and feedback on social media like Facebook, Twitter, and LinkedIn by mentioning MyEmailVerifier, and we will reward you with <b>2.5k</b> credits.</b><Br/>
                    <b>Terms:</b> Multiple posts are not permitted in a single day. You may post again after a week. Each post should be different.
                </p>

                <p>
                    &#9755; &nbsp; Write a review about “MyEmailVerifier” on popular review management websites like G2Crowd, Trustpilot, and Capetra. We will reward you with <b>5k</b> free credits.
                    <br/>
                    <b>Terms:</b> (a) Your review should be approved by respected sites. (b) Multiple reviews on the same website are not allowed.
                </p>


                <p>
                    &#9755; &nbsp; Create a video testimonial of at least 1 minute about myEmailVerifier and get rewarded with <b>10k</b> credits. If your content is excellent, you may get up to 
                    <b>50k</b> free credits. 
                    <Br/>
                    <b>Terms:</b> (a) Video should display the “MyEmailVerifier” interface. (b) It could be an explainer video for “MyEmailVerifier,” or you can discuss the benefits of email verification showing the interface.
                </p>

                <p>
                    &#9755; &nbsp; Write a properly researched blog/article on any relevant topic and mentioning our service. We will reward you with 10k credits. If the content is excellent, you may get up to <b>100k</b> free credits. 
                    <Br/>
                    <b>Terms:</b> (a) The content should be published on your website or a reputed website. (b) The content must be unique and free from plagiarism. (c) We may request an update on the content.
                </p>

            </div>   
        </section>
        <p></p>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid stat-widgets" >
                <!-- <div class="row">
                    <div class="col-12">
                        <div class="callout callout-info">              
                            Share our Service and earn 1000 credits free.
                        </div>
                    </div>
                </div>     -->

                <div class="row" >

                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Article</h3>
                            </div>
                            <div class="card-body">

                                <p class="text-center">
                                    <img :src="articleimage"  width="70" />
                                </p> 
                                <br/>
                                

                                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalclaimcredit"  @click="showsocialshare('article')" >
                                    claim
                                </button> 

                                <br/>
                                <p>
                                    Write an Article and earn 10k credits free. 
                                </p>


                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>

                    
                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Facebook</h3>
                            </div>

                            <div class="card-body">

                                <p class="text-center">
                                    <img :src="fbimage"  width="70" />
                                </p> 
                                <br/>

                                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modalclaimcredit"  @click="showsocialshare('facebook')" >
                                    claim
                                </button> 

                                <br/>
                                <p>
                                    Share our Service and earn 2500 credits free. 
                                </p>
                                    <!-- <button type="button" class="btn btn-block btn-primary"
                                    @click="connectIntegration('mailchimp')" 
                                    v-show="show_mailchimp_login" data-toggle="modal" data-target="#modalapikey" >
                                        Connect                                               
                                    </button> -->                                                          
                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>

                    <div class="col-md-3">
                        <div class="card card-primary">
                          	<div class="card-header">
                            	<h3 class="card-title">Twitter</h3>
                          	</div>
                            <div class="card-body">

                                    <p class="text-center">
                                        <img :src="twitterimage"  width="90" />
                                    </p> 
                                    <br/>

                                    <button type="button" class="btn btn-block btn-primary" 
                                    data-toggle="modal" data-target="#modalclaimcredit" 
                                    @click="showsocialshare('twitter')" >
                                        claim
                                    </button> 

                                    <br/>
                                    <p>
	                                    Share our Service and earn 2500 credits free. 
	                                </p>
                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>
                    

                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">LinkedIn</h3>
                            </div>
                            <div class="card-body">

                                    <p class="text-center">
                                        <img :src="linkedin_img"  width="70" />
                                    </p> 
                                    <br/>

                                    <button type="button" class="btn btn-block btn-primary" 
                                    data-toggle="modal" data-target="#modalclaimcredit" 
                                    @click="showsocialshare('linkedin')" >
                                        claim
                                    </button> 

                                    <br/>
                                    <p>
                                        Share our Service and earn 2500 credits free. 
                                    </p>
                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>

                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">G2</h3>
                            </div>
                            <div class="card-body">

                                    <p class="text-center">
                                        <img :src="g2_img"  width="90" />
                                    </p> 
                                    <br/>

                                    <button type="button" class="btn btn-block btn-primary" 
                                    data-toggle="modal" data-target="#modalclaimcredit" 
                                    @click="showsocialshare('g2')" >
                                        claim
                                    </button> 

                                    <br/>
                                    <p>
                                        write a review and earn 5000 credits free. 
                                    </p>
                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>

                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Trustpilot</h3>
                            </div>
                            <div class="card-body">

                                    <p class="text-center">
                                        <img :src="tpilot_img"  width="90" />
                                    </p> 
                                    <br/>

                                    <button type="button" class="btn btn-block btn-primary" 
                                    data-toggle="modal" data-target="#modalclaimcredit" 
                                    @click="showsocialshare('trustpilot')" >
                                        claim
                                    </button> 

                                    <br/>
                                    <p>
                                        write a review and earn 5000 credits free. 
                                    </p>
                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>


                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Capterra</h3>
                            </div>
                            <div class="card-body">

                                    <p class="text-center">
                                        <img :src="capterra_img"  width="90" />
                                    </p> 
                                    <br/>

                                    <button type="button" class="btn btn-block btn-primary" 
                                    data-toggle="modal" data-target="#modalclaimcredit" 
                                    @click="showsocialshare('capterra')" >
                                        claim
                                    </button> 

                                    <br/>
                                    <p>
                                        write a review and earn 5000 credits free. 
                                    </p>
                            </div>
                          <!-- /.card-body -->
                        </div>                    
                    </div>


                </div>                
            </div>

           
            <!-- modal for Share -->      
                <div class="row" >
                    
                    <div class="modal fade" id="modalclaimcredit" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Free Credits
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">                            
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                Kindly submit Link of the Post you share and claim your Free Credits. Admin will verify the post you shared. Once approved, Free credits will be added to your account.
                                            </p>                                            

                                            <div v-show="show_fb_share" >

                                                <p class="text-center" >
                                                    <div class="fb-like" data-href="https://listcleaningadvice.com/" data-width="" data-layout="standard" data-action="like" data-size="large" data-share="true"></div>
                                                </p>

                                                <p style="color:red" >
                                                
                                                    Please refresh the page if you can't find Facebook share button.                                                
                                                </p>

                                                <hr/>
                                                <p>                                                    
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here"  /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('facebook',2500)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>

                                            <div v-show="show_twitter_share" >
                                                <p class="text-center" >
                                                    													
                                                    <a class="twitter-share-button" href="javascript:void(0);" 
                                                    @click="share_to_twitter();"  >

                                                      <img :src="tweet_button" width="100" />
                                                    </a>                                                                                                           
                                                </p>
                                                <hr/>
                                                <p>
                                                    
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here"  /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('twitter',2500)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>


                                            <div v-show="show_linkedin_share" >       

                                                <p class="text-center" >
                                                                                                        
                                                    <a href="https://www.linkedin.com/" target="_blank" >

                                                      <img :src="linkedin_button" width="160" />
                                                    </a>                                                                                                           
                                                </p>

                                                <hr/>

                                                <p>                                                
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here" /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('linkedin',2500)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>

                                            <div v-show="show_article_share" >                                                       
                                                <p>                                                
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here" /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('atricle',10000)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>

                                            <div v-show="show_g2_share" >       

                                                <p class="text-center" >
                                                                                                        
                                                    <a href="https://www.g2.com/" target="_blank" >

                                                      <img :src="g2_button" width="160" />
                                                    </a>                                                                                                           
                                                </p>

                                                <hr/>

                                                <p>                                                
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here" /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('g2',5000)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>


                                            <div v-show="show_tpilot_share" >       

                                                <p class="text-center" >
                                                                                                        
                                                    <a href="https://www.trustpilot.com/" target="_blank" >

                                                      <img :src="tpilot_button" width="160" />
                                                    </a>                                                                                                           
                                                </p>

                                                <hr/>

                                                <p>                                                
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here" /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('trustpilot',5000)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>

                                            <div v-show="show_capterra_share" >       

                                                <p class="text-center" >
                                                                                                        
                                                    <a href="https://www.capterra.com/" target="_blank" >

                                                      <img :src="capterra_button" width="160" />
                                                    </a>                                                                                                           
                                                </p>

                                                <hr/>

                                                <p>                                                
                                                    <input type="text" size="40" v-model="post_link" placeholder="paste your link here" /> <br/>
                                                    <button class="btn btn-primary mt-2" 
                                                    @click="saveSocialArticalLink('capterra',5000)" >
                                                    submit post link 
                                                    </button>
                                                </p>
                                            </div>


                                        </div>                                      
                                    </div>
                                </div>    
                            </div>
                            <div class="modal-footer">
                                                                                        
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Cancel
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>

                </div>  

                <!-- end Share Modal  -->                    
        </section>
    </div>    

</template>


<style>
    
    .stat-widgets svg{
        font-size: 70px;
        top: 20px;
        right: 15px;
        position: absolute;
    }

    .admin-page-dashboard .bg-info{
        background-color: #17a2b8!important;
    }

    .admin-page-dashboard .bg-success{
        background-color:  #28a745!important;   
    }

    .admin-page-dashboard .bg-warning{
        background-color:  #ffc107!important;   
    }

    .small-box-footer svg{
        font-size: 15px;
        position: static;
    }

    .sec-recent-upload .todo-list li{
        background: #fff;
        border-left:none;
    }


    .sec-recent-order .todo-list li{
        background: #fff;
        border-left:none;
    }

</style>

<script>
    

    export default{
        name:'FreeCredits',         

        data:function(){
            return{   
            	admin_twitter_text:'',                
                show_article_share:false,
                show_fb_share:false,
                show_twitter_share:false,
                show_linkedin_share:false,
                show_g2_share:false,
                show_tpilot_share:false,
                show_capterra_share:false,
                fb_article_link:'',
                twitter_article_link:'',
                post_link:'',
                selected_media:'',
                fbimage:img_url+'fbimage.png',                
                articleimage:img_url+'copywriting.png',                
                twitterimage:img_url+'twitterlogo.png',                
                tweet_button:img_url+'tweet_button.png',                
                linkedin_img:img_url+'linkedin.png',                
                g2_img:img_url+'g2_img.png',                
                tpilot_img:img_url+'tpilot_img.png', 
                capterra_img:img_url+'capterra_img.png',                               
                linkedin_button:img_url+'linkedin_button.png',      
                g2_button:img_url+'g2_button.png',          
                tpilot_button:img_url+'trustpilot_button.png',              
                capterra_button:img_url+'capterra_button.png',                              
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
            });
            
            this.axios
            .post(api_url+'getAdminSettings')
            .then(function(response){                
                                    
                if(response.data.status == 1 || response.data.status == '1'){
                	thisObj.admin_twitter_text = 'https://twitter.com/intent/tweet?text='+response.data.data.admin_settings.social_article_text+'&url=https://myemailverifier.com/';
                	                   
				}  
            });
        }, 
        components: {            

        },         
        methods:{
            share_to_twitter(){
                var tweettext = this.admin_twitter_text;
                window.open(tweettext,'popup','width=700,height=300');

            },
            saveSocialArticalLink(sharemedia='',credits=0){
	            
	            var thisObj = this;

	            this.axios
	            .post(api_url+'saveSocialLink',
                    {'post_link':thisObj.post_link,'social_media':sharemedia,'freecredits':credits}
                )
	            .then(function(response){                
	                	               
	                if(response.data.status == 1 || response.data.status == '1'){ 
	                                                    
	                    thisObj.$toast.open({
							message: response.data.msg,
							type:'success',
							position:'top-right'
						});
	                }

	                jQuery('#modalclaimcredit').modal('hide');

	            });
            },
        	showsocialshare(smedia=""){

                if(smedia=="article"){                    
                    this.show_article_share=true;                    

                    this.show_linkedin_share=false;
                    this.show_fb_share=false;
                    this.show_twitter_share=false;                    
                    this.show_g2_share=false;                    
                    this.show_tpilot_share=false;                    
                    this.show_capterra_share=false;                    

                    this.selected_media='article';
                }

                if(smedia=="facebook"){
                    this.show_fb_share=true;
                    this.show_twitter_share=false;
                    this.show_linkedin_share=false;


                    this.show_g2_share=false;                    
                    this.show_tpilot_share=false;                    
                    this.show_capterra_share=false;
                    this.show_article_share=false;                    

                    this.selected_media='facebook';
                }

                if(smedia=="twitter"){
                    this.show_fb_share=false;
                    this.show_linkedin_share=false;
                    this.show_twitter_share=true;

                    this.show_g2_share=false;                    
                    this.show_tpilot_share=false;                    
                    this.show_capterra_share=false;
                    this.show_article_share=false;                    

                    this.selected_media='twitter';
                }

                if(smedia=="linkedin"){
                    this.show_linkedin_share=true;
                    this.show_fb_share=false;
                    this.show_twitter_share=false;             

                    this.show_g2_share=false;                    
                    this.show_tpilot_share=false;                    
                    this.show_capterra_share=false;
                    this.show_article_share=false;                    

                    this.selected_media='linkedin';
                }

                if(smedia=="g2"){                    
                    this.show_linkedin_share=false;
                    this.show_fb_share=false;
                    this.show_twitter_share=false;                    
                    this.show_g2_share=true;                    
                    this.show_tpilot_share=false;                    
                    this.show_capterra_share=false; 
                    this.show_article_share=false;                    

                    this.selected_media='g2';
                }

                if(smedia=="trustpilot"){
                    this.show_linkedin_share=false;
                    this.show_fb_share=false;
                    this.show_twitter_share=false;        

                    this.show_g2_share=false;                    
                    this.show_tpilot_share=true;                    
                    this.show_capterra_share=false;
                    this.show_article_share=false;                    

                    this.selected_media='trustpilot';
                }

                if(smedia=="capterra"){
                    this.show_linkedin_share=false;
                    this.show_fb_share=false;
                    this.show_twitter_share=false;                    

                    this.show_g2_share=false;                    
                    this.show_tpilot_share=false;                    
                    this.show_capterra_share=true; 
                    this.show_article_share=false;                    

                    this.selected_media='capterra';
                }

            }
        },        
        computed:{
            
        },
        created(){            

        },           
    }
        

   

</script>
