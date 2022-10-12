<template> 
	<div>
		<section class="content-header"> 
			<div class="container-fluid"> 
				<div class="row mb-2"> 
					<div class="col-sm-6">
						<h1>Email Verification</h1>
					</div>              
				</div>
			</div><!-- /.container-fluid -->
		</section>

		<!-- Main content -->
		<section class="content page-validatelist">

			<!-- Default box -->
			<div class="card">

				<div class="card-header">
					<h3 class="card-title"></h3> 
				</div>

				<div class="card-body"> 

					<div class="row" >
						
						<div class="col-sm-12" >
							
							<div class="custom_card" >

								<p>
									<b>Let's Get Started By Uploading Your File Here.</b>
								</p>   


								<p>
									Have confusion? Please refer our Detailed <a href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >blog</a> about <a href="https://myemailverifier.com/blog/how-myemailverifier-email-verification-service-works/" >"How it works"</a>
								</p>

								<p>
									Upload a CSV or TXT file and click on Validate List. We will inform you via email once your list is verified. You can also download results from grid below.
								</p>

								<p>
									You will be notified via email once your entire list has been validated.
								</p>

								<p>
									You have <big><b>{{ available_credits }}</b></big> available credits 
									<div class="col-sm-2" >
										
										<router-link to="/CreditPlans" class="nav-item nav-link btn-block btn-primary">
											Buy Credits
										</router-link>            
									</div>        								
								</p>


								<p><b>Example of Plain text file:</b></p>
								<div class="col-sm-12 examples_text" >
									<textarea>
										example@domain.com
										example2@domain.com
										example3@domain.com
									</textarea>
									<p>&nbsp;<br/></p>
								</div>								

								<p><b>Example of Comma delimited file (any sequence of field is accepted, our intelligent robot will find email field automatically:</b></p>
								<div class="col-sm-12 examples_text" >
									<textarea>		
										firstname,lastname,example1@domain.com,city,state
										firstname,lastname,example2@domain.com,city,state
										firstname,lastname,example3@domain.com,city,state
										firstname,lastname,example4@domain.com,city,state
									</textarea>	
								</div>

								<p> &nbsp; </p>
								<p> &nbsp; </p>
							</div>
							
							
								<div class="alert alert-info" role="alert" >
								
									<b>Start Verification Relax Mode:</b>  Processing will be slow and there will be less unknown results.<br/>
								<b>Start Verification Rapid Mode:</b> Processing will be fast and there will be more unknown results.

								</div>							
						</div>


						<div class="col-sm-12" >
							<div class="row" >
								<div class="col-sm-3" ></div>

								<div class="col-sm-6" >
									<div class="card card-primary">
										<div class="card-header">
											<h3 class="card-title">Upload Email list</h3>
										</div>
										<!-- /.card-header -->
										<!-- form start -->
										<form role="form" @submit.prevent="submitNewCSV"  enctype="multipart/form-data" >

										<!-- <input type="file"  class="" 
											id="exampleInputFile" @change="selectedFile" /> -->

											<div class="card-body">
												<div class="form-group">
													<label for="exampleInputFile">(csv)</label>
													<div class="input-group">
														<div class="custom-file">
															<input type="file"  class="custom-file-input" 
															id="exampleInputFile" @change="selectedFile" />
															<label class="custom-file-label" @change="selectedFile" for="exampleInputFile">{{ file_caption }}</label> 
														</div>                                      
													</div>
													<br/>
													<span class="text-danger" >
													{{ msg.csvfile }}</span>

												</div>
											</div>
											<!-- /.card-body -->

											<div class="card-footer">
												<button type="submit" class="btn btn-primary">Validate</button>
											</div>
										</form>
									</div>
								</div>

								<div class="col-sm-3" ></div>
							</div>
						</div>
						
					</div>    

					<p>&nbsp;</p>            

					<div class="row" >
						<div class="col-sm-10" >
							<span class="chartinfoindicator pieboxtotal" ></span> Total &nbsp;
							<span class="chartinfoindicator pieboxvalid" ></span> Valid &nbsp;
							<span class="chartinfoindicator pieboxinvalid" ></span> Invalid &nbsp;
							<span class="chartinfoindicator pieboxunknown" ></span> Unknown &nbsp;
							<span class="chartinfoindicator pieboxspam" ></span> Spam Trap &nbsp;
							<span class="chartinfoindicator pieboxtoxic" ></span> Toxic Domains &nbsp;
						</div>
						<div class="col-sm-1" ></div>
						<div class="col-sm-1" ></div>
						<div class="col-sm-10" >
							
						</div>
						<div class="col-sm-1" ></div>
					</div>

					

					<div class="row" v-show="showcharts" >
						<div class="col-sm-12" >                        
						<!-- <div class="validation_info" >
							 <canvas id="planet-chart" ></canvas>
							</div> -->

							<div  class="validation_info" v-show="shownewupload" >

								<div class="col-sm-12" >
									<div class="resultinfo" >

										<div class="row" >
											<div class="col-sm-6" >

												<div class="row"> 

													<span class="filename" >
														<i class="fa fa-file-code" aria-hidden="true"></i>
														<big>{{ newupload.filename }}</big>
														<small>( {{ newupload.created_on }} )</small>

														&nbsp;&nbsp;    
														<b>Start Mode </b> <button type="button" 
														class="btn btn-primary":disabled="modesbtndisabled" 
														@click="setmode('rapid')" >Rapid</button>
														<button type="button" class="btn btn-primary" 
														:disabled="modesbtndisabled" @click="setmode('relax')"  >Relax
													</button>

												</span>                
												<span>
													
												</span>    
											</div>    

											<div class="row" >
												<span class="resultstatus" >
													<label>Total: </label> {{ newupload.total }}
													<label style="color:green;" >Valid: </label> 
													{{ newupload.valid }}
													<label style="color:red;"  >Invalid: </label> 
													{{ newupload.invalid }}
													<label style="color:#3c8dbc;"  >Unknown: </label> {{ newupload.unknown }} 
													
													<label style="color:#bfa062;"  >Spam Trap: </label> 
													{{ newupload.spam_trap }}

													<label>Toxic Domains: </label> 
													{{ newupload.toxic_domains }}

													<br/><br/>
													<label>Credits Used: </label> 
													{{ newupload.creditused }}
													<label>Status: </label> {{ newupload.status }}
													<label>Progress: </label> 
													
													{{ newupload.progress }}                             

												</span>
											</div>

											<div class="row"  >
												<div class="col-sm-7" v-show="newupload.downloadable" >
													<span class="dwnldsec"  >
														<label>Download: </label> &nbsp;

														<a target="_blank"  class="btn btn-primary" v-bind:href="newupload.file_path"                                                         
														:disabled="downloadbtndisabled"
														><b>XLS</b></a> 
														&nbsp; 
														<a  target="_blank"  class="btn btn-primary" v-bind:href="newupload.xls_file_path" 
														:disabled="downloadbtndisabled"
														><b>CSV</b></a>

														<!--<button type="button" class="btn btn-primary" 
														:disabled="downloadbtndisabled" >XLS</button>

														<button type="button" class="btn btn-primary"
														:disabled="downloadbtndisabled" >CSV</button> -->
													</span>                                                
												</div>

												<div class="col-sm-3" v-show="newupload.deletable" >
													<button type="button" 
													@click="deletethisfile(newupload.fileid)" 
													class="btn btn-block btn-danger">Delete</button>
												</div>
											</div>
										</div>


										<div class="col-sm-6" >
											
											<span class="piechart"  >
												
												<div class="card-body" v-show="shownewchart" >

													<canvas id="planet-chart-newupload" 
													ref="myplanetchart2"  ></canvas>
												</div>
											</span>

										</div>

									</div>
								</div>                            
							</div>    						
						</div>


						<div  class="validation_info"   v-for="(history,index) in historyData" >

							<div class="col-sm-12" >
								<div class="resultinfo" >

									<div class="row" >
										<div class="col-sm-6" >

											<div class="row" >
												<span class="filename" >
													<i class="fa fa-file-code" aria-hidden="true"></i>
													<big>{{ history.filename }}</big>
													<small>( {{ history.created_on }} )</small>													
												</span>
												<span>
													
												</span>    
											</div>    

											<div class="row" >
												<span class="resultstatus" >
													<label>Total: </label> {{ history.total }}
													<label style="color:green;" >Valid: </label> 
													{{ history.valid }}
													<label style="color:red;"  >Invalid: </label> 
													{{ history.invalid }}
													<label style="color:#3c8dbc;"  >Unknown: </label> {{ history.unknown }} 



													<br/>
													<label style="color:#bfa062;" >Spam Trap: </label> 
													{{ history.spam_trap }}

													<label>Toxic Domains: </label> 
													{{ history.toxic_domains }}

													<br/><br/>
													<label>Credits Used: </label> 
													{{ history.creditused }}
													<label>Status: </label> {{ history.status }}
													<label>Progress: </label> 
													
													{{ history.progress }}                             
												</span>
											</div>

											<div class="row"  v-show="history.downloadable" >
												<div class="col-sm-7" >
													<span class="dwnldsec"  >
														<label>Download: </label> &nbsp;

														<a target="_blank"  class="btn btn-primary" v-bind:href="history.file_path" 
														v-show="history.file_path != ''"
														><b>CSV</b></a> 
														&nbsp; 
														<a  target="_blank"  class="btn btn-primary" 
														v-show="history.xls_file_path != ''"
														v-bind:href="history.xls_file_path" ><b>XLS</b></a>													
														</span>                                                
													</div>

													<div class="col-sm-3" >
														<button type="button" @click="deletethisfile(history.fileid)" class="btn btn-block btn-danger">Delete</button>
													</div>
												</div>
											</div>


											<div class="col-sm-6" >
												
												<span class="piechart"  >
													
													<div class="card-body">
														<canvas v-bind:id="'planet-chart-' + index" 
														ref="myplanetchart"  ></canvas>
													</div>
												</span>

											</div>

										</div>
									</div>                            
								</div>    
								
							</div>
						</div>                  
					</div>  

					<b>* All files older than 30 days will be automatically deleted.</b>
					
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
					
				</div>
				<!-- /.card-footer-->
			</div>
			
			<!-- /.card -->
		</section>
	</div>  
</template>

<style>


.custom_card{
	float: left;
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
	background-color:#d3d3d3;
	float: left;
	width: 100%;
	padding:20px 10px 10px 20px;
	margin: 10px 0;
	border-radius: 5px;
}

.custom_card{
	background-color:#d3d3d3;       
	border-radius: 5px;
	padding: 10px;
	margin: 0 0 15px 0;
}
.page-validatelist .card-body{
	
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

	
	// const ctx = document.getElementById('planet-chart');
	var planetChartDataVar = {
		type: 'pie',
		data: {
			/*labels: ['Total','Success','Failed','Unknown'],*/
			datasets: [ 
					{ // one line graph
						label: 'Number of Moons',
						data: [100,70,11,9,7,3],
						backgroundColor: [ 
						'#f39c12', 
						'#00a65a',
						'#f56954',                  
						'#3c8dbc',
						'#bfa062',
						'#343a40',
						],
						borderColor: [
						'#fff',
						'#fff',
						'#fff',                  
						'#fff',                  
						'#fff',                  
						'#fff',                  
						],
						borderWidth: 3
					},              
					]
				},
				options: {
					responsive: true,
					lineTension: 1,
					
				}
			}
			
			export default {
				name:'ValidateList',                
				data:function(){ 
					return{
						modesbtndisabled:true,
						downloadbtndisabled:true,
						shownewupload:false,
						is_admin:false,
						shownewchart:false,
						msg:{
							csvfile:'',
						},  
						userid:0,  
						file_caption:'Choose file',
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
						planetChartData: planetChartDataVar,         
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

					if(localStorage.is_admin == "false" || localStorage.is_admin == false){
						this.is_admin = false;    
					}

					if(localStorage.is_admin == "true" || localStorage.is_admin == true){
						this.is_admin = true;    
					}
					
					
					if(localStorage.mevid <= 0 || localStorage.mevid == '' || typeof localStorage.mevid === 'undefined'){
						this.$router.push({ 
							name:'Login',                    
						});
					}   

					/* ------- */

					let thisObj = this;
					thisObj.historyData = [];


					this.msgsuccess = localStorage.msgsuccess;            
					this.textmsg = localStorage.textmsg;       
					this.userid = localStorage.mevuid;

					if(localStorage.textmsg == ''){
						this.msgsuccess = false;
					}

					
					this.axios.get(api_url+'validationhistory')
					.then((response)=>{
						if(response.data.status == "1"){ 
							
							thisObj.historyData = response.data.data;                    
							thisObj.available_credits = response.data.credits;                
						}
					});         
				},        
				methods: {  
					setmode(mymode=""){ 

						var thisObj = this;

						this.axios
						.post(api_url+'setmode',{
							userid:thisObj.userid,
							mymode:mymode,
							fileid:thisObj.newupload.fileid
						})
						.then(function(response){                            
							if(response.data.status == '1'){                            
								thisObj.modesbtndisabled  = true;      
								thisObj.downloadbtndisabled  = false;

								alert("Start Mode set Successfully");                                                           
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
								thisObj.shownewupload  = false;                               
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

						thisObj.shownewupload  = true;   
						thisObj.modesbtndisabled = false;    

						thisObj.$toast.open({
							message: 'file Uploaded',
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
			selectedFile(event){                         
				//this.msg.csvfile="";
				this.newemailcsv = event.target.files[0];
				
				//console.log(" ------- "+this.newemailcsv.type);  
				
				if(this.newemailcsv.name != ''){
					this.file_caption = this.newemailcsv.name;
				}                



			},
			handleScroll (event) {
				
			},
			createChart(chartId, chartData) {
				console.log("chartid: "+chartId); 
				const ctx = document.getElementById(chartId);
				//const ctx = jQuery("#"+chartId); 
				//console.log("myid: "+ctx);
				const myChart = new Chart(ctx, {
					type: chartData.type,
					data: chartData.data,
					options: chartData.options,
				});
			},
			checkresults(){
				
			}
		},
		created(){
			
			window.addEventListener('scroll', this.handleScroll);
			var thisObj= this;
			
			setInterval(function(){
				
				if(localStorage.mevid <= 0 || localStorage.mevid == '' || typeof localStorage.mevid === 'undefined'){

					thisObj.$router.push({ 
						name:'Login',                    
					});

				} 
				
			}, 2000);

			setInterval(function(){
				this.textmsg = '';                  
				this.msgsuccess = false;
				this.msgerror = false;
				
			}, 5000);

			setInterval(function(){

				
				if(thisObj.shownewupload){
					if(thisObj.newupload.filename != ''){                            
						

							//console.log("cons 1");
							this.axios.get(api_url+'checkresults/'+thisObj.newupload.fileid)
							.then((response)=>{
								if(response.data.status == "1"){   
									
									thisObj.newupload.total  =  response.data.data.total;   
									thisObj.newupload.valid  =  response.data.data.valid;   
									thisObj.newupload.invalid  =  response.data.data.invalid;   
									thisObj.newupload.unknown  =  response.data.data.unknown;   

									thisObj.newupload.spam_trap  =  response.data.data.spam_trap;   
									thisObj.newupload.toxic_domains  =  response.data.data.toxic_domains;   

									thisObj.newupload.creditused  =  response.data.data.credit_used;
									thisObj.newupload.status  =  response.data.data.status;
									thisObj.newupload.progress  =  'completed';  
									thisObj.newupload.downloadable  =  response.data.data.downloadable;  
									if(response.data.data.ready_for_download > 0){
										thisObj.newupload.downloadable = true;    
									}


									thisObj.planetChartData.data.datasets[0]['data'][0] = thisObj.newupload.total;
									thisObj.planetChartData.data.datasets[0]['data'][1] = thisObj.newupload.valid;
									thisObj.planetChartData.data.datasets[0]['data'][2] = thisObj.newupload.invalid;
									thisObj.planetChartData.data.datasets[0]['data'][3] = thisObj.newupload.unknown;



									thisObj.planetChartData.data.datasets[0]['data'][4] = thisObj.newupload.spam_trap;

									thisObj.planetChartData.data.datasets[0]['data'][5] = thisObj.newupload.toxic_domains;

									if(thisObj.newupload.total > 0){
										thisObj.shownewchart = true;
									}

									thisObj.createChart('planet-chart-newupload',thisObj.planetChartData);          

								}
							});   


						}
					}  
				}, 2000);


			setTimeout(function(){
				this.textmsg = '';                  
				this.msgsuccess = false;
				this.msgerror = false;                                              
			}, 5000);        
		},
		destroyed(){
			window.removeEventListener('scroll', this.handleScroll);
		},
		updated(){
			var thisObj = this;

			let newindex = 0;            
			
			while(newindex < 3){
				

				
				var ch_total = thisObj.historyData[newindex].total;
				var ch_valid = thisObj.historyData[newindex].valid;
				var ch_invalid = thisObj.historyData[newindex].invalid;
				var ch_unknown = thisObj.historyData[newindex].unknown;
				var ch_spam_trap = thisObj.historyData[newindex].spam_trap;
				var ch_toxic_domains = thisObj.historyData[newindex].toxic_domains;

				thisObj.planetChartData.data.datasets[0]['data'][0] = ch_total;
				thisObj.planetChartData.data.datasets[0]['data'][1] = ch_valid;
				thisObj.planetChartData.data.datasets[0]['data'][2] = ch_invalid;
				thisObj.planetChartData.data.datasets[0]['data'][3] = ch_unknown;

				thisObj.planetChartData.data.datasets[0]['data'][4] = ch_spam_trap;
				thisObj.planetChartData.data.datasets[0]['data'][5] = ch_toxic_domains;

				

				thisObj.createChart('planet-chart-'+newindex, thisObj.planetChartData);        
				
				newindex += 1; 
			}
		}
	}    
</script>
