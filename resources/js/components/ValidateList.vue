<template> 
	<div>		
		<!-- Main content -->
		<section class="content page-validatelist">
										
			<div class="row" >
							
				<div class="col-sm-12 mt-4" v-show="upload_allowed=='y'" >
					<div class="row" >
						<div class="col-sm-2" >
								
						</div>

						<div class="col-sm-8" >
														
							<div class="alert alert-info col-sm-12" role="alert" style="border-radius:7px;" >
						
								<b>&#9755; &nbsp;</b> Each row should contain only one email column. 
								
							</div>								
						</div>

						<div class="col-sm-2" >
								
						</div>
					</div>	
				</div>		
										

				<div class="col-sm-12"  v-show="upload_allowed=='y'" >
					<div class="row" >
						<div class="col-sm-2" ></div>

						<div class="col-sm-8" >
							<div class="card card-primary maillistbox">
								<div class="card-header">
									<h3 class="card-title">Upload Email list</h3>
								</div>
								<!-- /.card-header -->							

								<form role="form" @submit.prevent="processMyCSV"  enctype="multipart/form-data" >

								<!-- <input type="file"  class="" 
									id="exampleInputFile" @change="selectedFile" /> -->
									<div class="card-body">
										<div class="form-group">
											<label for="exampleInputFile">(.txt) (.csv) (.xlsx)</label><br/>
											<!-- <label for="exampleInputFile">xlsx files are temporarily not permitted due to some technical reasons.</label> -->
											<div class="input-group">
												<div class="custom-file">
													<input type="file"  class="custom-file-input" 
													id="exampleInputFile" @change="selectedFile" />
													<label class="custom-file-label" 
													@change="selectedFile" for="exampleInputFile">
													{{ file_caption }}</label> 
												</div>                                      
											</div>
											
											<span class="text-danger" >
											{{ msg.csvfile }}</span>
										</div>

										<div class="form-group"> 
																						
											<a href="javascript:void(0);" class="btn btn-dark" data-toggle="modal" data-target="#helpmodal" >
												Need Help? &nbsp; <i class="fas fa-question-circle"></i>
											</a>
										</div>
									</div>
									<!-- /.card-body -->

									<div class="card-footer">
										<!-- <button type="submit" class="btn btn-primary">
											Submit to Validate
										</button>										 -->
									</div>
								</form>
							</div>
						</div>

						<div class="col-sm-2" ></div>
					</div>					
				</div>

				<div class="col-sm-12 mt-3"v-show="upload_allowed=='n'"  >
					<div class="row" >
							<div class="col-sm-2" ></div>
							<div class="col-sm-8 bg-danger text-center p-3 rounded" >
								<h2>Access Denied!</h2>
								<big>You are not authorized to verify list. Kindly contact 
									<b>support@myemailverifier.com </b>
								</big>
							</div>
							<div class="col-sm-2" ></div>
					</div>
				</div>							


			</div>    			
			<hr/>

			<div class="row mt-2" >				
				<div class="col-sm-6" > 
					<div class="ml-4 mt-2">
						<div class="input-group">
						  	&nbsp;&nbsp;&nbsp;&nbsp;
						    <input id="search-focus" v-model="searchkey" type="search"  class="form-control" @keyup="searchfile" placeholder="File Name or File ID" style="border-radius: 5px;" />					    
						  	<span style="margin-left: -20px;z-index: 999;padding-top: 7px;" ><i class="fas fa-search"></i></span>					
						</div>
					</div>

					<div class="ml-4" v-show="searching" >
						<div class="input-group">
						  	&nbsp;&nbsp;&nbsp;&nbsp;
							<span style="font-size:30px;font-weight:bold;color:grey;">...</span>
						</div>
					</div>

				</div>                       

			</div>

			<div class="row" v-show="showcharts" >
				<div class="col-sm-12" >                        

				
					<div class="card-body" >
						<div  class="validation_info "   v-for="(history,index) in historyData" >
							<div  v-bind:class="history.callout_class" >

								<div class="col-sm-12 mb-3" >
									<span v-show="history.show_start_modes" class="selectfmodetext"  >  
										Your file is not started processing yet. Please select Rapid or Relax mode to start.
									</span>
								</div>													

								<div class="col-sm-12" >
									<div class="resultinfo" > 

										<div class="row" >
											<div class="col-sm-6" >

												<div class="row" >													

													<span class="filename" >
														<i class="fa fa-file-code" aria-hidden="true"></i>
														<big>
															{{ history.filename }} 
															{{ history.integration }} 
														</big>
														<small>( {{ history.created_on }} )</small>																		
													</span>
													<span>
														
													</span>    
												</div>    

												<div class="row" >
													<div class="resultstatus" >

														<div class="row" >
															<div class="col-sm-12" >
																<div class="border-bottom" >
																<label>Total Emails: </label> {{ history.total }}
																<label>Progress: </label> {{ history.progress_percent }} 

																<label>Status: </label> {{ history.status }}
																</div>
															</div>
														</div>

														<div class="row mt-3" >
															
															<div class="col-sm-6" >

																<table border="0" >
																	
																	<tr>
																		<td><label style="color:green;" >Valid </label></td>
																		
																		<td>{{ history.valid }}</td>
																	</tr>

																	<tr>
																		<td><label style="color:red;" >Invalid </label></td>
																		
																		<td>{{ history.invalid }}</td>
																	</tr>

																	<tr>
																		<td><label style="color:#bfa062;" >Catch-all </label></td>
																		
																		<td>{{ history.catchall }}</td>
																	</tr>

																	<tr>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																	</tr>

																	<!-- <tr>
																		<td><label style="color:#3c8dbc;" >Unknown </label></td>
																		
																		<td>{{ history.unknown }}</td>
																	</tr>											 -->						
																</table>																													
															</div>  	

															<div class="col-sm-6" >

																<table border="0" >
																	<tr>
																		<td><label style="color:#3c8dbc;" >Spam Trap </label></td>

																		<td>{{ history.spam_trap }}</td>
																	</tr>

																	<tr>
																		<td><label style="color:dimgray;padding-top: 8px;" >Toxic Domains </label></td>
																		
																		<td>{{ history.toxic_domains }}</td>
																	</tr>

																	<tr>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																	</tr>

																	<tr>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																	</tr>

																	<!-- <tr>
																		<td><label style="color:dimgray;" >Duplicates </label></td>
																		
																		<td>{{ history.duplicates }}</td>
																	</tr>											 -->						
																</table>																																													
															</div>  																														
														</div>
																
														<div class="row mt-3" >

															<div class="col-sm-12" >
																
																<div class="border-top pt-3" >
																	<table>
																		<tr>																	
																			<td><label style="color:#3c8dbc;" >Unknown </label></td>
																			
																			<td>{{ history.unknown }}</td>

																			
																			<td><label style="color:dimgray;" >Duplicates </label></td>
																			
																			<td>{{ history.duplicates }}</td>
																		</tr>
																	</table>

																	<p style="font-size: 13px;">
																		&rArr; Credits not charged for Unknown and Duplicate emails. 
																	</p>
																</div>
															</div>																												
														</div>

														<br/>

														<div class="border-top pt-2" >
															<label>Credits Used: </label> 
															{{ history.creditused }}
														</div>
																																																				                        
													</div>
												</div>


												<div class="row">
													
													<div class="col-sm-3" v-show="history.deletable" >
															<button type="button" @click="deletethisfile(history.fileid)" class="btn btn-block btn-danger">Delete
															</button>
													</div>

													<div class="col-sm-12 text-left" >														
														<span class="dwnldsec"  v-show="history.downloadable"  >
															<img :src="dwnldlogo" width="22" />

															<label>Download: </label> &nbsp;

															<a target="_blank"  class="btn btn-primary btn-sm" v-bind:href="history.file_path" 
															v-show="history.file_path != ''"
															><b>All</b></a> 
															&nbsp; 

															<a target="_blank"  class="btn btn-primary btn-sm" v-bind:href="history.validcsv_file_path" 
															v-show="history.validcsv_file_path != ''"
															><b>Valid</b></a> 

															<a target="_blank"  class="btn btn-primary btn-sm" v-bind:href="history.invalid_file_path" 
															v-show="history.invalid_file_path != ''"
															><b>Invalid</b></a> 

															<a target="_blank"  class="btn btn-primary btn-sm" v-bind:href="history.catchall_file_path" 
															v-show="history.catchall_file_path != ''"
															><b>Catch-all</b></a> 

															
															<a target="_blank"  class="btn btn-primary btn-sm" v-bind:href="history.unknown_file_path" 
															v-show="history.unknown_file_path != ''"
															><b>Unknown</b></a>

																											
															</span>                                                
														</div>														
													</div>

													<button v-show="history.allowstop" 
													@click="forceStop(history.fileid)" class="btn btn-danger" >STOP</button>
												</div>

												<div class="col-sm-6 robograph" >

													<div class="row text-center ">
														<span class="w-100 text-bold" > 
															File ID: {{ history.fileid }} 
														</span>
													</div>
																									
													<div class="row mt-5" v-show="history.showmygraph" >

														<div class="col-sm-1">&nbsp;</div>

														<div class="col-sm-3 text-right text-bold chartlabels" >
															<label>Valid</label>
															<label>Invalid</label>
															<label>Catch-all</label>
															<label>Unknown</label>
															<label>Spam Trap</label>
															<label>Toxic Domains</label> 
														</div>
														<div class="col-sm-8" >
															
															<div class="progress">
																<div class="progress-bar bg-success" role="progressbar" v-bind:style="'width:'+history.percent_valid" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
																{{ history.percent_valid }}</div>
															</div> 

															<div class="progress">
															  <div class="progress-bar bg-danger" role="progressbar" v-bind:style="'width:'+history.percent_invalid" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{ history.percent_invalid }}</div>
															</div>

															<div class="progress">
															  <div class="progress-bar bg-warning" role="progressbar" v-bind:style="'width:'+history.percent_catchall" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{ history.percent_catchall }}</div>
															</div>

															<div class="progress">
																  <div class="progress-bar " role="progressbar" v-bind:style="'width:'+history.percent_unknown" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">{{ history.percent_unknown }}</div>
															</div> 

															<div class="progress">
															  <div class="progress-bar  bg-info" role="progressbar" v-bind:style="'width:'+history.percent_spam_trap" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">{{ history.percent_spam_trap }}</div>
															</div>	
															
															
															<div class="progress">
															  <div class="progress-bar  bg-secondary" role="progressbar" v-bind:style="'width:'+history.percent_toxic_domains" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">{{ history.percent_toxic_domains }}</div>
															</div>
														</div>														
															
															<div class="col-sm-12 text-right mt-2" >
																
																<span v-show="history.allowstop" class="blink_me" style="color:brown;font-weight: bold;" > Please wait. File is being processed.  <br/><br/>
																</span>	

																<!-- <span v-show="!history.allowstop" >
																	
																	<div class="arrow-1"></div>
																	<button class="btn btn-info" data-toggle="modal" data-target="#reultexplainmodal"  > &#9755; Understand the Results</button>
																</span> -->

																<button v-show="history.reprocessed == 0 && history.downloadable == 1" class="btn btn-primary btn-sm" 

																@click="reprocessUnknown(history.fileid)" >
																	Reprocess Unknown
																</button> &nbsp;&nbsp;

																<span v-show="!history.allowstop" ><button data-toggle="modal" data-target="#reultexplainmodal" class="btn btn-sm btnresultinfo float-right "> &#9755; Result Interpretation</button> </span>
															</div>														
													</div>																																							
												</div>																									
											</div>
										</div>                            
									</div>    									
								</div>
							</div>
						</div>             
					</div> 

			 	<!-- modal for upload info -->		
				<div class="row" >
					
					<div class="modal fade" id="modalcreditinfo" tabindex="-1" role="dialog">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      	<div class="modal-header">
						        <h5 class="modal-title">
						        <img :src="mevlogo" width="200" alt="MEV"class="brand-image" style="opacity: .8"/>
						    	</h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
					      	</div>
					      	<div class="modal-body">
					        
						      	<div class="container-fluid">
								    <div class="row">
								    	<div class="col-md-12">

								    		<p class="text-danger" > {{ credits_limit_err }} </p>

								      		<p><b>Available Credits: </b> {{ cdt_available }} </p>
								      		<p><b>Credits Required: </b> {{ cdt_required }} </p>

								      		<p>{{  reprocessing_filename }}</p> 

								      		<hr/>
								      		<p>
								      			<b>Your Coupon Codes</b>
								      			<br/>								      				
								      			<span v-for="coupons in coupon_codes_list" >
													 <b> {{ coupons.coupon_code }} </b> ({{ coupons.percentage }} % off.) 

													 <br/>
												</span>								      			
								      		</p>
								      	</div>								      
								    </div>
								</div>    
					      	</div>

					      	<div class="modal-footer">
					      	
			                     <button type="button" v-show="credits_limit_err != ''" @click="gotocredits()" class="btn btn-success">Buy Credits</button>

						        <button type="button" :disabled="btnStartProcessDisable"  v-show="credits_limit_err == ''" @click="startProcess()" class="btn btn-success">Start Process Now</button>
						        
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">
						        Cancel
						    	</button>
					      	</div>

					    </div>
					  </div>
					</div>

				</div>	

				<!-- Validation Help  -->

				<div  class="row">					

				  	<div class="modal fade" id="helpmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">
								<i><b>How to Validate List</b></i>
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">              		

						  	<div class="row" >

						  		<div class="col-sm-12" >
					
										<div class="custom_card" >

											<p>
												<b>Let's Get Started By Uploading Your File Here.</b>
											</p>   						

											<p>
												Upload a CSV or TXT file and click on Validate List. We will inform you via email once your list is verified. You can also download results from grid below.
											</p>

											<p>
												You will be notified via email once your entire list has been validated.
											</p>

											<p>
												You have <big><b>{{ available_credits }}</b></big> available credits 
												<!-- <div class="col-sm-2" >
													
													<router-link to="/CreditPlans" class="nav-item nav-link btn-block">

														<button class="btn btn-primary" >Buy Credits</button>

													</router-link>            
												</div>        								 -->
											</p>

											<p>
												Still have confusion? Please refer our Detailed <a href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >blog</a> about <a href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >"How it works"</a>
											</p>


											<p><b>Example of Plain text file:</b></p>
											<div class="col-sm-12 examples_text" >
												<textarea>example@domain.com&#13;&#10;example2@domain.com &#13;&#10;example3@domain.com
												</textarea>
												<p>&nbsp;<br/></p>
											</div>								

											<p><b>Example of Comma delimited file (any sequence of field is accepted, our intelligent robot will find email field automatically:</b></p>
											<div class="col-sm-12 examples_text" >
												<textarea>firstname,lastname,example1@domain.com,city,state &#13;&#10;firstname,lastname,example2@domain.com,city,state &#13;&#10;firstname,lastname,example3@domain.com,city,state &#13;&#10;firstname,lastname,example4@domain.com,city,state
												</textarea>	
											</div>

											<p> &nbsp; </p>
											<p> &nbsp; </p>
										</div>															
								</div>						  		
						  	</div>

						  </div>
						  <div class="modal-footer">                
							
						  </div>
						</div>
					  </div>
					</div>
				</div>	
				<!-- ****** -->

				<!--  Result Explainer Modal  -->
				<div class="modal fade" id="reultexplainmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
					  	<div class="modal-header">
							<h5 class="modal-title" >Verification Results</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
					  	</div>

					  	<div class="modal-body">
					  		<p><b>File Progress:</b> Unless the download button’s are visible file is in progress. Inititally it will show all emails as a valid and as it finishes the process count will update.</p>
					  		<p>Do not re-upload same file again for validation.</p>
							<p><b>Result Interpretation:</b></p>
							<p><b>Valid:</b> These are valid emails. They exist and active. safe to send (there are still chances of bounce in yahoo emails if those are not double opt-in users).</p>
							<p><b>Invalid:</b> These are invalid emails. It will bounce.</p>
							<p><b>Catch-All:</b>Valid but not 100% valid. The only way to confrim this kind of email address is to send a real email message. (Unless it is double opt-in send them email in second priority)</p>
							<p><b>Unknown:</b> Greylisted. Recipient server doesn't answer our query on first attempt. Please validate again after 1 hour. All such emails should be uploaded as a separate list for validation after 1 hour.</p>
							<p><b>Role Based:</b> Email Addresses that are not associated with a particular person, but rather with a company, department or group. It doesn’t make any sense to send email to such address.</p>
					  	</div>

					  	<div class="modal-footer">                
							<!-- <button type="button" class="btn btn-primary" data-dismiss="modal" >Cancel</button> -->
					  	</div>
					</div>
				  </div>
				</div>
										
		<!-- **** -->

				<!--  ********** -->

				<div class="">
					<loading :active.sync="isLoading" :is-full-page="true">						

						<div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
						  <span class="sr-only">Loading...</span>
						</div>

						<span style="font-size:16px;" >&nbsp;&nbsp;&nbsp; <b>Please wait it may take few minutes ...</b></span>

						<!-- <button class="btn btn-primary" type="button" disabled>
						  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
						  Please wait it may take few minutes ...
						</button> -->

					</loading>
				</div>				

			</div>  

			<b> &nbsp;&nbsp; *All files older than 30 days will be automatically deleted.</b>
			<br/><br/>			

			<!-- /.card -->
		</section>
	</div>  
</template>

<style>


.swal2-title{
	font-size: 20px !important;
}

.selectfmodetext{
	color: red;
	font-size: 15px;
	font-weight: bold;	
}

.btnresultinfo{
	background-color: #FFC107;
}




.btn-info{
	color: #fff;
}	

#reultexplainmodal .modal-body b{
	color: brown;
}


.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

.regtext{
	text-decoration: none !important;	
	color: #000  !important;	
	font-weight: bold !important;
}

.regtext:hover{
	text-decoration: none !important;	
	color: #000  !important;	
}

#search-focus::placeholder{
	color: lightgrey;
	font-style: italic;
}


.page-validatelist .maillistbox{
	border-radius: 1.25rem !important;
}

.page-validatelist .maillistbox .card-header{
	border-top-left-radius: 15px;
	border-top-right-radius: 15px;
}

.dwnldsec a {
    color: #fff;
    text-decoration: none;
}

.progress{
	height:2rem;
}

.robograph{
	/*margin: auto;*/
	height: 100%;
	padding:10px 0 0 0;
}


.chartlabels label{
	
	width: 100%;
	font-weight: bold;
	font-size: 13px;
	padding: 4px 0 0 0;
	color:#212529;
}

.alert-info{
	float: left;
}

.examples_text textarea{
	float: left;
	width: 100%;
	text-align: left;
	border-radius: 3px;
	height: 150px;
}

.resultstatus{
	float: left;
	width: 100%;
	margin: 20px 0;
	margin: 20px 0;
	background-color: #f5f5f5;
	padding: 10px 5px;
	border-radius: 5px;
	border: 1px solid gray;
	
}

.resultstatus label {
	padding: 0 0 0 12px;
}

.dwnldsec{
	padding: 5px 0 0 0;
}

.uploaddate{
	float:left;
	width:100%;
	font-style: italic;
	margin: 0 0 10px 0;        
}


.chartinfoindicator{
	height:10px;
	width:20px;
	display: inline-block;
}

.pieboxtotal{
	background-color:#f39c12;
}
.pieboxvalid{
	background-color:#00a65a;
}
.pieboxinvalid{
	background-color:#f56954;
}
.pieboxunknown{
	background-color:#3c8dbc;
}

.pieboxspam{
	background-color:#bfa062;
}

.pieboxtoxic{
	background-color:#343a40;
}

.filename big{
	color:#a52a2a;
}

.filename{        
	float:left;
	width:100%;
}

.validation_info{
	
	float: left;
	width: 100%;
	padding:10px 10px 10px 20px;
	margin: 5px 0;
	border-radius: 5px;
}

.custom_card{
	float: left;	
	border-radius: 5px;
	padding: 10px;
	margin: 0 0 15px 0;
}

.page-validatelist{
	background-color:#d3d3d3;       
}



p{
	font-size: 16px;        
}
.highlighted_para{        
	background-color: lightgrey;
	padding: 10px;
	border-radius: 5px;
}
</style>

<script>   

		import Loading from 'vue-loading-overlay';    
		import 'vue-loading-overlay/dist/vue-loading.css';
	
		export default {
			name:'ValidateList',                
			data:function(){ 
				return{
					reprocessing_filename:'',
					btnStartProcessDisable:false,
					searching:false,
					coupon_codes_list:[],
					credits_limit_err:'',
					dummy_fileid:'',		
					reprocessing_fileid:'',		
					mevlogo:img_url+'mev-logo-2.svg',
					dwnldlogo:img_url+'download-arrow.gif',
					cdt_available:0,
					cdt_required:0,
					isLoading:false,
					pertext:"40%",
					modesbtndisabled:true,
					downloadbtndisabled:true,
					shownewupload:false,
					is_admin:false,
					shownewchart:false,
					upload_allowed:'y',
					intervalObj:null,
					searchkey:'',
					reprocessing:0,
					msg:{
						csvfile:'',
					},  
					userid:0,  
					file_caption:'Choose file',
					vldformat:true,
					vldfile:true,
					errormsg:'',
					newupload:{
						fileid:'',
						filename:'',
						creditused:0,
						created_on:'',
						total:0,
						valid:0,
						invalid:0,
						toxic_domains:0,
						unknown:0,
						status:'',
						progress:'',                    
						newemailcsv:null,
						deletable:false,
					},
					historyData:[],						
					showloader:false,    
					showcharts:true, 
					newchartarr:[],
					available_credits:'',
				}
			},
			beforeMount(){

				
			},
			mounted(){
				
				/* check user login */
				var thisObj = this;

				if(localStorage.is_admin == "false" || localStorage.is_admin == false){
					this.is_admin = false;    
				}

				if(localStorage.is_admin == "true" || localStorage.is_admin == true){
					this.is_admin = true;    
				}
				
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

						thisObj.is_admin =response.data.data.is_admin;                                                     
	                    //localStorage.setItem('mevid',response.data.data.userid);
	                }
	            });
				
				

				/* ------- */

				//let thisObj = this;
				thisObj.historyData = [];


				this.msgsuccess = localStorage.msgsuccess;            
				this.textmsg = localStorage.textmsg;       
				this.userid = localStorage.mevuid;

				if(localStorage.textmsg == ''){
					this.msgsuccess = false;
				}



				this.axios.get(api_url+'getusercoupons')
				.then(function(response){ 
					
					if(response.data.status == '1'){
						
						thisObj.coupon_codes_list = response.data.data;
						thisObj.upload_allowed = response.data.upload_allowed;						
					}                                
				});							
			},        
			methods: {				
				searchfile(){
					
					var thisObj = this;					

					thisObj.searching = true;
					this.axios.post(api_url+'validationhistory',{search_word:thisObj.searchkey})
					.then((response)=>{
						if(response.data.status == "1"){ 
							
							thisObj.historyData = response.data.data;                    
							thisObj.available_credits = response.data.credits;                
						}
						thisObj.searching = false;
					});  
				},
				forceStop(fileid='',$event){

					var thisObj = this;
					event.target.disabled = true;

					this.axios
					.post(api_url+'stopProcess',{ 
						fileid:fileid,						
					})
					.then(function(response){                            
						if(response.data.status == '1'){                            
							
							alert(response.data.msg);														             	                                            
						}                                       
					}); 
				},
				gotocredits(){
					jQuery("#modalcreditinfo").modal('hide');

					this.$router.push({ 
						name:'CreditPlans',                    
					});
				},
				startProcess(){

					var thisObj = this;
					thisObj.btnStartProcessDisable = true;

					if(this.reprocessing == 1){
						this.axios
						.post(api_url+'restartProcess',{
							fileid:thisObj.reprocessing_fileid,											
						})
						.then(function(response){                            
							if(response.data.status == '1'){                            
								//thisObj.modesbtndisabled  = true;      
								//thisObj.downloadbtndisabled  = false;		
								thisObj.reprocessing_filename='';						
								thisObj.isLoading=false;
								jQuery("#modalcreditinfo").modal('hide');							
								/*thisObj.$swal('Successfully uploaded. <br/><br/> Please select Rapid or Relax mode to start');*/
							}                                       
						}); 
					}

					if(this.reprocessing == 0){
						this.axios
						.post(api_url+'startProcess',{
							uploadid:thisObj.dummy_fileid,												
						})
						.then(function(response){                            
							if(response.data.status == '1'){                            
								//thisObj.modesbtndisabled  = true;      
								//thisObj.downloadbtndisabled  = false;
								thisObj.isLoading=false;
								jQuery("#modalcreditinfo").modal('hide');							
								/*thisObj.$swal('Successfully uploaded. <br/><br/> Please select Rapid or Relax mode to start');*/
							}                                       
						}); 
					}					
				}, 
				setmode(mymode="",fileid=0){ 

					var thisObj = this;

					this.axios
					.post(api_url+'setmode',{
						userid:thisObj.userid,
						mymode:mymode,
						fileid:fileid
					})
					.then(function(response){                            
						if(response.data.status == '1'){                            
							//thisObj.modesbtndisabled  = true;      
							//thisObj.downloadbtndisabled  = false;

							//alert("File sucessfully started processing.");  
							thisObj.$swal('File sucessfully started processing.');     
						}                                       
					}); 
				},  
				deletethisfile(fileid=''){
					if(!confirm("This file will be deleted permenently and will not be available next time. Are you sure you want to delete?")){
						return false;
					}

					var thisObj = this;

					this.axios
					.get(api_url+'deletethisfile/'+fileid)
					.then(function(response){
						
						if(response.data.status == '1'){                            
							//thisObj.shownewupload  = false;  
							thisObj.$toast.open({
								message: response.data.msg,
								type:'success',
								position:'top-right'
							});
						}                                                                
					}); 
				},    
				submitNewCSV(e){             
					e.preventDefault();                 

					const newdata = new FormData();
					newdata.append('csvfile',this.newemailcsv);    
					newdata.append('userid',this.userid);    
					

					this.file_caption = "select new file";

					this.errors=[];   
					this.hasError=false;   
					this.msg=[];
					this.vlds=[];
					var thisObj = this;		

					const config = {
						headers: { 'Content-Type': 'multipart/form-data' }
					}    
			
					if(this.hasError == false){  
						
					}    

					this.axios
					.post(api_url+'submitemailist',newdata,config)
					.then(function(response){
							
						if(response.data.status == '1'){
							thisObj.newupload.fileid = response.data.data.fileid;
							thisObj.newupload.filename = response.data.data.filename;
							thisObj.newupload.created_on = response.data.data.created_on;
							thisObj.newupload.deletable = true;
							
							thisObj.modesbtndisabled = false;    

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
				processMyCSV(e){             
					e.preventDefault();                 

					const newdata = new FormData();
					newdata.append('csvfile',this.newemailcsv);    
					newdata.append('userid',this.userid);    
					

					this.file_caption = "select new file";

					this.errors=[];   
					this.hasError=false;   
					this.msg=[];
					this.vlds=[];
					var thisObj = this;		

					const config = {
						headers: { 'Content-Type': 'multipart/form-data' }
					}    
			
					if(this.hasError == false){  
						
					}    

					this.axios
					.post(api_url+'processemailist',newdata,config)
					.then(function(response){
						
						if(response.data.status == '1'){
							

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
				reprocessUnknown(fileid=''){        

					
					if(!confirm('Reprocess Unknown? ')){
						return false;
					}

					var thisObj = this;
					thisObj.btnStartProcessDisable = false;
					thisObj.credits_limit_err='';

					
					thisObj.isLoading=true;	
					this.axios.post(api_url+'processedFileinfo',{fileid:fileid})
					.then((response)=>{
						if(response.data.status == "1"){ 
														
							thisObj.cdt_available = response.data.data.available_credits;  
							thisObj.cdt_required = response.data.data.credits_need;
							thisObj.reprocessing = 1;
							thisObj.reprocessing_fileid = fileid;
							thisObj.reprocessing_filename="Your new filename will be "+response.data.data.reprocessing_filesname;
														

							jQuery("#modalcreditinfo").modal('show');
						}else{							

							if(response.data.error_type=='credit_limit'){
								
								thisObj.credits_limit_err=response.data.msg;
								//thisObj.cdt_available = response.data.data.available_credits;  
								//thisObj.cdt_required = response.data.data.no_of_lines;
								//thisObj.errormsg = response.data.msg;				
								jQuery("#modalcreditinfo").modal('show');										
							}							
						}					

						thisObj.isLoading=false;	
					});
																										
					
					if(this.newemailcsv.name != ''){
						this.file_caption = this.newemailcsv.name;
					}                
				},
				selectedFile(){                         
					//this.msg.csvfile="";

					var thisObj = this;
					thisObj.btnStartProcessDisable = false;
					thisObj.credits_limit_err='';

					if(!confirm("Are you sure to upload")){
						return false;
					}
										

					const newdata = new FormData();
					this.newemailcsv = event.target.files[0];
					console.log(this.newemailcsv);

					const filetypes = [
										"text/plain", 
										"text/csv",
										"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
									];

					if(!filetypes.includes(this.newemailcsv.type)){
						alert('Invalid File uploaded');
						return false;
					}

					if(this.newemailcsv.type);

					newdata.append('csvfile',this.newemailcsv);    
					newdata.append('userid',this.userid);    

					const config = {
						headers: { 'Content-Type': 'multipart/form-data' }
					}  

					
					thisObj.isLoading=true;	
					this.axios.post(api_url+'csvinfo',newdata,config)
					.then((response)=>{
						if(response.data.status == "1"){ 
							
							thisObj.historyData = response.data.data;                    
							//thisObj.available_credits = response.data.credits;    
							thisObj.cdt_available = response.data.data.available_credits;  
							thisObj.cdt_required = response.data.data.no_of_lines;
							thisObj.dummy_fileid = response.data.data.fileid;
							thisObj.reprocessing = 0;
							

							jQuery("#modalcreditinfo").modal('show');

						}else{

							//alert('error is:'+response.data.error_type);

							if(response.data.error_type=='credit_limit'){
								
								thisObj.credits_limit_err=response.data.msg;
								thisObj.cdt_available = response.data.data.available_credits;  
								thisObj.cdt_required = response.data.data.no_of_lines;
								//thisObj.errormsg = response.data.msg;				
								jQuery("#modalcreditinfo").modal('show');			
								
							}


							if(response.data.error_type=='invalid_file'){
								thisObj.vldfile	= false;
								//thisObj.errormsg = response.data.msg;
								alert(response.data.msg);							
							}

							if(response.data.error_type=='large_file'){
								thisObj.vldfile	= false;
								//thisObj.errormsg = response.data.msg;
								alert(response.data.msg);							
							}

							if(response.data.error_type=='invalid_format'){
								thisObj.vldformat	= false;
								//thisObj.errormsg = response.data.msg;							
								alert(response.data.msg);
							}
						}					

						thisObj.isLoading=false;	
					});
																																
					
					if(this.newemailcsv.name != ''){
						this.file_caption = this.newemailcsv.name;
					}                
				},
				handleScroll (event) {
					
				},					
				checkresults(){
					
				}
		},
		created(){
			
			window.addEventListener('scroll', this.handleScroll);
			var thisObj= this;
			
			thisObj.intervalObj = setInterval(function(){
				
				if(localStorage.mevid <= 0 || localStorage.mevid == '' || typeof localStorage.mevid === 'undefined'){

					thisObj.$router.push({ 
						name:'Login',                    
					});
				} 	


				thisObj.textmsg = '';                  
				thisObj.msgsuccess = false;
				thisObj.msgerror = false;

				this.axios.post(api_url+'validationhistory',{search_word:thisObj.searchkey})
				.then((response)=>{
					if(response.data.status == "1"){ 
						
						thisObj.historyData = response.data.data;                    
						thisObj.available_credits = response.data.credits;                
					}
				}); 

			}, 5000);						       
		},
		beforeDestroy () {

			clearInterval(this.intervalObj);
		},
		destroyed(){
			window.removeEventListener('scroll', this.handleScroll);
		},
		updated(){
			var thisObj = this;

		},
		components: { Loading },   
	}    
</script>
