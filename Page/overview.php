<?php 



add_action('admin_menu','add_dashboard');
if(!function_exists('add_dashboard')){
	function add_dashboard(){
		add_dashboard_page( "Analytics", "Analytics", "manage_options", "analytics","generatePageOverview", 2);
	}
}

if(!function_exists('generatePageOverview')){
	function generatePageOverview(){
		if(is_multisite()){
			if ( ! current_user_can( 'manage_options' ) ) {
    			return;
  			}
			//Access Database with this query;
			global $wpdb;
			//Split and get value of interger
			$prefix = str_split($wpdb->prefix,3)[0];
			(string)$val_of_int = get_current_blog_id();
			// For Users.
			/*$value_of_users = $wpdb->get_var($wpdb->prepare("SELECT DISTINCT COUNT(ID) FROM `{$prefix}users` INNER JOIN `{$prefix}usermeta` ON `{$prefix}users`.ID = `{$prefix}usermeta`.user_id WHERE meta_key = 'primary_blog' && meta_value = '{$val_of_int}'"));*/
			$value_of_users = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `{$prefix}users` INNER JOIN `{$prefix}usermeta` ON `{$prefix}users`.ID = `{$prefix}usermeta`.user_id WHERE `{$prefix}usermeta`.meta_key='{$wpdb->prefix}capabilities'"));
			//For Course
			$value_of_course = $wpdb->get_var($wpdb->prepare("SELECT DISTINCT COUNT(ID) FROM `{$wpdb->prefix}posts` WHERE post_status = 'publish' && post_type ='sfwd-courses'"));
			//For Quiz
			$value_of_quiz = $wpdb->get_var($wpdb->prepare("SELECT DISTINCT COUNT(ID) FROM `{$wpdb->prefix}posts` WHERE post_status = 'publish' && post_type = 'sfwd-quiz'"));	
			//For Groups
			$value_of_groups = $wpdb->get_var($wpdb->prepare("SELECT DISTINCT COUNT(ID) FROM `{$wpdb->prefix}posts` WHERE post_status = 'publish' && post_type ='groups'"));	
?>

<style>
	* {
 	 	box-sizing: border-box;
	}

	body {
  		font-family: Arial, Helvetica, sans-serif;
	}

	.column {
  		float: left;
  		width: 25%;
  		padding: 0 10px;
	}


	.row {margin: 0 -5px;}

	.row:after {
  		content: "";
  		display: table;
  		clear: both;
	}	
	
	@media screen and (min-width:768px) and (max-width:1024px){
		.column {
  			float: left;
  			width: 50%;
  			padding: 0 10px;
		}
		
		.text{
	font-size:12px !important;}	
	}
	@media screen and (max-width: 600px) {
  	.column {
    	width: 100%;
    	display: block;
    	margin-bottom: 20px;
		
  		}
		
		.text{
	font-size:12px !important;}
	}


	.card {
  		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  		padding: 16px;
  		text-align: center;
  		background-color: #f1f1f1;
	}
	
	button {
		background-color: #4CAF50;
		font-size:12px;
		border-radius: 12px;
		border: 2px solid #4CAF50;
		height:35px;
		border-radius: 4px;
  		color: #FFFFFF;
  		text-align: center;
  		transition: all 0.5s;
  		cursor: pointer;
  		
	}
	
	button span {
  		cursor: pointer;
  		display: inline-block;
  		position: relative;
  		transition: 0.5s;
	}
	
	button span:after {
 		content: '\00bb';
 		position: absolute;
 		opacity: 0;
  		top: 0;
  		transition: 0.5s;
		}

	button:hover span {
  		padding-right:10px;
	}

	button:hover span:after {
  		opacity: 1;
  		right: 0;
	}
	
	.dashicons{
	    height: 70px;
    	font-size: 5em;
    	margin-left: -50px;
		
	}
	
	
	
</style>
<h2 >Here is an overview of the reports</h2>

<div class="row">
  <div class="column">
    <div class="card">
    	<span class="dashicons dashicons-admin-users" height="700" width="700"></span>
      	<p><?php echo $value_of_users; echo $value_of_users > "1" ? " Users" : " User"; ?></p>
      	
    </div>
  </div>
	
  <div class="column">
    <div class="card">
      <span class="dashicons dashicons-media-code"></span>
      <p><?php echo $value_of_course; echo $value_of_course > "1" ? " Number of Courses" : " Number of Course";?></p>
    
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <span class="dashicons dashicons-backup"></span>
      <p><?php echo $value_of_quiz; echo $value_of_quiz > "1" ? " Quizzes" : " Quiz"; ?></p>
     
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <span class="dashicons dashicons-groups"></span>
      <p><?php echo $value_of_groups; echo $value_of_groups > "1" ? " Number of Groups" : " Number of Group" ?></p>
    </div>
  </div>
</div>
<br>

<head>
	<title>Horizontal Bar Chart</title>
	
	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
</head>

	<body>
	
		<!--START OF USER CHART-->
		<!--<div id="container" style="width: 50%; display:inline-block; float:left;">
		<canvas id="courseChart"></canvas>
		</div>
		<div style ="width:50%; float:right;height:425px;"> 
		</div>
		<script>
		let courseList = [];
		let courseCount = [];
			
				jQuery.ajax({
        url: "<?php// echo plugin_dir_url(__DIR__).'Assests/chartData/sqlQuery_GetCoursesBISA.php'; ?>" ,
        method: 'GET'
    	}).done(function (data) {
				var dataJson = JSON.parse(data);
				jQuery.each(dataJson,function(i,value){
				courseList.push(dataJson[i]['Course Title']);
				});
				
				courseCount = new Array(courseList.length).fill(0);
				
				jQuery.ajax({
					url: "<?php// echo plugin_dir_url(__DIR__).'Assests/chartData/sqlQuery.php'; ?>",
					method:'GET'
				}).done(function(data){
					var userJson = JSON.parse(data);
					
					jQuery.each(userJson,function(i,value){
					let courseName = userJson[i]['Course Name'];
					let arrIndex = courseList.indexOf(courseName);
					courseCount[arrIndex]++;
					});
					
					
					var courseChart = new Chart(document.getElementById('courseChart'),{
					type:'horizontalBar',
					data:{
					labels: courseList,
					datasets:[{
                			label: 'No. Of Users',
                		backgroundColor: 'rgba(75, 192, 192, 0.4)',
						data: courseCount,
						borderWidth: 1
            		}]
				},
				options: {	
					legend:{
						onClick: function(e, legendItem) {
          		var index = legendItem.datasetIndex;
          		var ci = this.chart;
          		var alreadyHidden = (ci.getDatasetMeta(index).hidden === null) ? false : ci.getDatasetMeta(index).hidden;       
          		var anyOthersAlreadyHidden = false;
          		var allOthersHidden = true;

          		// figure out the current state of the labels
          		ci.data.datasets.forEach(function(e, i) {
            		var meta = ci.getDatasetMeta(i);
            
            		if (i !== index) {
              		if (meta.hidden) {
                		anyOthersAlreadyHidden = true;
              		} else {
                		allOthersHidden = false;
              		}
            		}
          		});
          
          		// if the label we clicked is already hidden 
          		// then we now want to unhide (with any others already unhidden)
          		if (alreadyHidden) {
            		ci.getDatasetMeta(index).hidden = null;
          		} else { 
            		// otherwise, lets figure out how to toggle visibility based upon the current state
            		ci.data.datasets.forEach(function(e, i) {
              			var meta = ci.getDatasetMeta(i);

              		if (i !== index) {
                		// handles logic when we click on visible hidden label and there is currently at least
                		// one other label that is visible and at least one other label already hidden
                		// (we want to keep those already hidden still hidden)
                		if (anyOthersAlreadyHidden && !allOthersHidden) {
                  		meta.hidden = true;
                		} else {
                  		// toggle visibility
                  		meta.hidden = meta.hidden === null ? !meta.hidden : null;
                		}
              		} else {
                		meta.hidden = null;
              		}
            		});
          		}

          		ci.update();
        		},
					},
            		title: {
                		display: true,
                		text: 'No. of Users Enrolled in Courses',
						fontSize:10
            		},
            		tooltips: {
                		mode: 'index',
                		intersect: false
            		},
            		responsive: true,
					scales:{
					 xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true
                            },
						 ticks:{
						 stepSize : 1,
					     beginAtZero: true,
							 
						 }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true
                            },
							ticks:{
							 stepSize : 1,
					     beginAtZero: true,
							autoSkip: false,
								fontSize: 7
								
								 
							}
                        }]
					}
        		}
			});
	});//end of Ajax Done(Users)							
});//end of Ajax Done(BISA)

			
		
		
		
		</script>-->
		<!--END OF USER CHART-->
	

	<!--<span class="content" >
		<div class="wrapper" style="width:50%; float:right;"><canvas id="chart-0"></canvas></div>
	</span>-->
	
	<script>

		/*window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myHorizontalBar = new Chart(ctx, {
				type: 'horizontalBar',
				data: horizontalBarChartData,
				options: {
					// Elements options apply to all of the options unless overridden in a dataset
					// In this case, we are setting the border of each horizontal bar to be 2px wide
					elements: {
						rectangle: {
							borderWidth: 2,
						}
					},
					responsive: true,
					legend: {
						position: 'right',
					},
					title: {
						display: true,
						text: 'Horizontal Bar Chart'
					}
				}
			});

		};

		});
		*/
		
	</script>
		
	<!--Here is another script for another chart-->	
	<script>
		var DATA_COUNT = 5;

		var utils = Samples.utils;

		utils.srand(110);

		function colorize(opaque, hover, ctx) {
			var v = ctx.dataset.data[ctx.dataIndex];
			var c = v < -50 ? '#D60000'
				: v < 0 ? '#F46300'
				: v < 50 ? '#0358B6'
				: '#44DE28';

			var opacity = hover ? 1 - Math.abs(v / 150) - 0.2 : 1 - Math.abs(v / 150);

			return opaque ? c : utils.transparentize(c, opacity);
		}

		function hoverColorize(ctx) {
			return colorize(false, true, ctx);
		}

		function generateData() {
			return utils.numbers({
				count: DATA_COUNT,
				min: -100,
				max: 100
			});
		}

		var data = {
			datasets: [{
				data: generateData(),
			}]
		};

		var options = {
			legend: false,
			tooltips: false,
			title: {
						display: true,
						text: 'Pie Chart'
					},
			elements: {
				arc: {
					backgroundColor: colorize.bind(null, false, false),
					hoverBackgroundColor: hoverColorize
				}
			}
		};

		var chart = new Chart('chart-0', {
			type: 'pie',
			data: data,
			options: options
			
		});

		// eslint-disable-next-line no-unused-vars
		function randomize() {
			chart.data.datasets.forEach(function(dataset) {
				dataset.data = generateData();
			});
			chart.update();
		}

		// eslint-disable-next-line no-unused-vars
		function addDataset() {
			chart.data.datasets.push({
				data: generateData()
			});
			chart.update();
		}

		// eslint-disable-next-line no-unused-vars
		function removeDataset() {
			chart.data.datasets.shift();
			chart.update();
		}

		// eslint-disable-next-line no-unused-vars
		function togglePieDoughnut() {
			if (chart.options.cutoutPercentage) {
				chart.options.cutoutPercentage = 0;
			} else {
				chart.options.cutoutPercentage = 50;
			}
			chart.update();
		}

	</script>
		
	
		
</body>

<h2 style="width:100%;display:block;">Here are the selection to view the reports accordingly.</h2>
<h3 >For Learndash Analytics:</h3>

<div class="row">
  <div class="column">
    <div class="card">
    	<span class="dashicons dashicons-admin-users" height="700" width="700"></span>
      	<p class="text">If you want to know more about the reports of the users? <br> Click the button below to</p>
      	<button onclick="window.location.href='?page=reports&tab=users'"><span>View the reports of Users</span></button>
    </div>
  </div>
	
  <div class="column">
    <div class="card">
      <span class="dashicons dashicons-media-code"></span>
      <p class="text">If you want to know more about the reports of the courses? <br> Click the button below to</p>
      <button onclick="window.location.href='?page=reports&tab=course'"><span>View the reports of Courses</span></button>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <span class="dashicons dashicons-backup"></span>
      <p class="text">If you want to know more about the reports of the quiz? <br> Click the button below to</p>
      <button onclick="window.location.href='?page=reports&tab=Quiz'"><span>View the reports of Quiz</span></button>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <span class="dashicons dashicons-groups"></span>
      <p class="text">If you want to know more about the reports of the groups? <br> Click the button below to</p>
      <button onclick="window.location.href='?page=reports&tab=groups'"><span>View the reports of Groups</span></button>
    </div>
  </div>
</div>
<br>
<h3 >For Woocommerce Analytics:</h3>
<div class="row">
  <div class="column">
    <div class="card">
    	<h3>Placeholder for Image</h3>
      	<p class="text">If you want to know more about the reports of the users? <br> Click the button below to</p>
      	<button onclick="window.location.href='?page=reports&tab=users'"><span>View the reports of Users</span></button>
    </div>
  </div>
	
  <div class="column">
    <div class="card">
      <h3>Placeholder for Image</h3>
      <p class="text">If you want to know more about the reports of the courses? <br> Click the button below to</p>
      <button onclick="window.location.href='?page=reports&tab=course'"><span>View the reports of Courses</span></button>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <h3>Placeholder for Image</h3>
      <p class="text">If you want to know more about the reports of the quiz? <br> Click the button below to</p>
      <button onclick="window.location.href='?page=reports&tab=Quiz'"><span>View the reports of Quiz</span></button>
    </div>
  </div>
  
  <div class="column">
    <div class="card">
      <h3>Placeholder for Image</h3>
      <p class="text">If you want to know more about the reports of the groups? <br> Click the button below to</p>
      <button onclick="window.location.href='?page=reports&tab=groups'"><span>View the reports of Groups</span></button>
    </div>
  </div>
</div>

<?php	
		}
	}
}







