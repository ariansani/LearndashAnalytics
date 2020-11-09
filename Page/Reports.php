	<?php
add_action( 'admin_menu','Report');
if(!function_exists('Report')){
	function Report(){
		add_dashboard_page( 'Reports', 'Reports', 'manage_options', 'reports', 'generatePageReports', 3);
	}
}

if(!function_exists('generatePageReports')){
	function generatePageReports(){
		if(is_multisite()){
			if ( ! current_user_can( 'manage_options' ) ) {
    			return;
  			}
		$default_tab = null;
 	 	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>



<style>
	.disabledClick{
    pointer-events: none;
}
	
</style>

<div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      	<a href="?page=reports" class="nav-tab <?php if($tab===null || $tab === "Reports"):?> nav-tab-active disabledClick<?php endif;?>">All Reports</a>
		<a href="?page=reports&tab=users"  class="nav-tab<?php if($tab==="users"):?> nav-tab-active disabledClick<?php endif;?>">Users</a>
	  	<a href="?page=reports&tab=course"  class="nav-tab<?php if($tab==="course"):?> nav-tab-active disabledClick<?php endif;?>">Course</a>
		<a href="?page=reports&tab=Quiz"  class="nav-tab<?php if($tab==="Quiz"):?> nav-tab-active disabledClick<?php endif;?>">Quiz</a>
		<a href="?page=reports&tab=groups"  class="nav-tab<?php if($tab==="groups"):?> nav-tab-active disabledClick<?php endif;?>">Groups</a>
    </nav>
	<div class="tab-content">
    <?php
			
	
	//use $prefix instead of $wpdb->prefix to get database from different multisite.
			
	switch($tab) :
      	case 'course':
			global $wpdb;

?>
	
	<table id="testTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Month Registered</th>
                <th>Display Name</th>
                <th>User ID</th>
				<th>Group</th>
            </tr>
        </thead>
    </table>		
		
<style>
.highcharts-figure, .highcharts-data-table table {
    min-width: 310px; 
    max-width: 80%;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
</style>
		<script>
			let groupsDict={};
			
			let sqlGroupsQuery_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlGroupsQueryWithSiteSubquery.php';
			jQuery.ajax({
        		url: sqlGroupsQuery_url,
        		method: 'GET'
    		}).done(function (data) {
				let groupJson = JSON.parse(data);
					
				jQuery.each(groupJson,function(i,value){
					if(groupJson[i]['User Name'] in groupsDict == false){ //If not found in dictionary, init
					groupsDict[groupJson[i]['User Name']] = [];
					}
				groupsDict[groupJson[i]['User Name']].push(groupJson[i]['Group Name']);	
				});	
			
			
			
			let sqlCourseQuery_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlCourseQuery.php';
				jQuery.ajax({
        url: sqlCourseQuery_url,
        method: 'GET'
    	}).done(function (data) {
				//Parse the data
				let dataJson = JSON.parse(data);
					
				//create the datatable
				   var table = jQuery('#testTable').DataTable({
        			dom: 'frtipP',
					searchPanes:{
		  				threshold: 1
      				//columns: [ 1 ]
    				},
    				columnDefs: [
      				{
        			targets: 5,
        			render: function (data, type, row) {
          			if (type === 'sp') {
            			return data.split(', ')
          			}
          			return data;
        			},
        			searchPanes: {
          			orthogonal:'sp'
        			}
      				}
    				],
					data: dataJson,
					columns: [
						{data: 'Course ID'},
						{data: 'Course Name'},
						{'render': function(data,type,full,meta){
						//render a custom column
						let monthRegistered = [full['Date Registered'].slice(0, 4), '-', full['Date Registered'].slice(4)].join('');
						return monthRegistered;
						}},
						{data: 'Display Name'},
						{data: 'User ID'},
							{'render':function(data,type,full,meta){
								let userKey = full['Display Name'];
				
								if (userKey === null){
									return 'N/A';
								}else {
								//if user doesnt exist in dictionary, return N/A
								return !(userKey in groupsDict) ? 'N/A' : groupsDict[userKey];
								}
							},
						}
					]
    				});	
					
					
				//map the array and filter out unique dates
				var unique_dates_string = dataJson
				.map(dataJson => dataJson['Date Registered'])
				.filter((value,index,self) => self.indexOf(value) === index);
					
				 //convert array of strings to Number
				let unique_dates_numeric = Array.from(unique_dates_string,Number);
				
				//sort the array numerically
				let unique_dates_sorted = unique_dates_numeric.sort((a, b) => a - b); 
				
				//Find the min dates using spread 
				let minDate = Math.min(...unique_dates_sorted);
				let minDateString = minDate.toString();
				
				let todaysDate = new Date();
				let todaysYearString = todaysDate.getFullYear().toString();
				let todaysMonthString =(todaysDate.getMonth()+1).toString();
				let currentDateInt = parseInt(todaysYearString.concat(todaysMonthString));	
				let currentDateString = currentDateInt.toString();
				//let currentYearString = currentDateString.slice(0, 4);
				//let currentMonthString = currentDateString.slice(4, 6);
				
				let inputStartDateRange = [minDateString.slice(0, 4), '-', minDateString.slice(4)].join('');
				let inputCurrentDateRange = [currentDateString.slice(0, 4), '-', currentDateString.slice(4)].join('');
				
				let dateList = dateRange(inputStartDateRange,inputCurrentDateRange);	
				
				//This function returns an array containing YYYY-MM between the start date and end date inclusive
				function dateRange(startDate, endDate) {
  					var start      = startDate.split('-');
  					var end        = endDate.split('-');
  					var startYear  = parseInt(start[0]);
  					var endYear    = parseInt(end[0]);
  					var dates      = [];

  					for(var i = startYear; i <= endYear; i++) {
    					var endMonth = i != endYear ? 11 : parseInt(end[1]) - 1;
    					var startMon = i === startYear ? parseInt(start[1])-1 : 0;
    					for(var j = startMon; j <= endMonth; j = j > 12 ? j % 12 || 11 : j+1) {
      						var month = j+1;
      						var displayMonth = month < 10 ? '0'+month : month;
      						dates.push([i, displayMonth].join('-'));
    					}
  					}
  					return dates;
				}//end of date Range function
				
			
			
			let courseDict = {};
			let courseList = [];
			let dateDict = {};
					
			jQuery.each(dataJson, function(i,value){
			if (dataJson[i]['Course Name'] in courseDict == false){
			courseDict[dataJson[i]['Course Name']] = new Array(dateList.length).fill(0);
			}
				
			let rawDate = dataJson[i]['Date Registered'];
			let formattedDate = [rawDate.slice(0, 4), '-', rawDate.slice(4)].join('');
			let arrIndex = dateList.indexOf(formattedDate);
			courseDict[dataJson[i]['Course Name']][arrIndex]++;
			
			});//end of jQuery.each
			
			courseDatasets=[];
			for(key in courseDict){
			//reduce function to accumulate the numbers in the array
			let result = courseDict[key].reduce(function(r, a) {
  				r.push((r.length && r[r.length - 1] || 0) + a);
  				return r;
			}, []);
				
				let newDataset = {
				name: key,
				data: result
				};
			courseDatasets.push(newDataset);
			}
					
		var container = jQuery('<div/>').insertBefore(table.table().container());
					
		 var chart = Highcharts.chart(container[0], {
    chart: {
        type: 'area'
    },
    title: {
        text: 'Course Population by Month'
    },
    subtitle: {
        text: 'Subtitle'
    },
    xAxis: {
        categories: dateList,
        tickmarkPlacement: 'on',
        title: {
            enabled: false
        }
    },
     yAxis: {
        title: {
            text: 'No. Of Users'
        }, 
    },
    tooltip: {
        split: true,
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            lineColor: '#666666',
            lineWidth: 1,
            marker: {
                lineWidth: 1,
                lineColor: '#666666'
            }
        }
    },
    series: courseDatasets
});
		 // On each draw, update the data in the chart
   //table.on('draw', function () {
       // chart.series[0].setData(chartData(table));
    //});
				
			
				
		}); // end of course AjaxDone
				
});//end of groups Ajax Done				
			
	function chartData(table) {
    var counts = {};
 
    // Count the number of entries for each position
    table
        .column(1, { search: 'applied' })
        .data()
        .each(function (val) {
            if (counts[val]) {
                counts[val] += 1;
            } else {
                counts[val] = 1;
            }
        });
 
    // And map it to the format highcharts uses
    return jQuery.map(counts, function (val, key) {
        return {
            name: key,
            y: val,
        };
    });
}
			
			
		</script>
		<!--Remember to name the chart so can edit from there and do any Style tag on the top -->	
		
<?php
        	break;
      	case 'Quiz':
?>
	<table id="testTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>User Name</th>
				<th>Group Name</th>
                <th>Course Name</th>
                <th>Activity Status</th>
                <th>Quiz Name</th>
                <th>Quiz Score</th>
            </tr>
        </thead>
    </table>
		<style>

.highcharts-figure, .highcharts-data-table table {
    max-width: 80%;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
</style>
		
		
<script>
	let quizDict = {};
	let quizNamesList = [];
	let quizScoresList = [];
	let courseDict = {};
	let groupsDict = {};
	
	let sqlGroupsQuery_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlGroupsQueryWithSiteSubquery.php';
	jQuery.ajax({
		url: sqlGroupsQuery_url,
        method: 'GET'
	}).done(function (data){
	let groupJson = JSON.parse(data);
	jQuery.each(groupJson,function(i,value){
			if(groupJson[i]['User Name'] in groupsDict == false){ //If not found in dictionary, init
				groupsDict[groupJson[i]['User Name']] = [];
			}
			groupsDict[groupJson[i]['User Name']].push(groupJson[i]['Group Name']);	
		
		});	
		

	
	let sqlCourseInSubsite_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlCourseInSubsite.php';
	jQuery.ajax({
		url: sqlCourseInSubsite_url,
        method: 'GET'
	}).done(function (data){
		let courseJson = JSON.parse(data);
				
		jQuery.each(courseJson,function(i,value){
			if(courseJson[i]['Course ID'] in courseDict == false){ //If not found in dictionary, init
				courseDict[courseJson[i]['Course ID']] = courseJson[i]['Course Name'];
			}		
		});
				
		
	let sqlTest_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlTest.php';
				jQuery.ajax({
        url: sqlTest_url,
        method: 'GET'
    	}).done(function (data) {
				let dataJson = JSON.parse(data);
   // Create DataTable
    var table = jQuery('#testTable').DataTable({
        dom: 'frtipP',
		 searchPanes:{
      threshold: 1
      //columns: [ 1 ]
    },
    
    columnDefs: [
      {
        targets: 1,
        render: function (data, type, row) {
          if (type === 'sp') {
            return data.split(', ')
          }
          return data;
        },
        searchPanes: {
          orthogonal:'sp'
        }
      }
    ],
		data: dataJson,
		columns: [
			{data: 'User'},
			{'render':function(data,type,full,meta){
				let userKey = full['User'];
				
				if (userKey === null){
				return 'N/A';
				}else {
				//if user doesnt exist in dictionary, return N/A
				
				return !(userKey in groupsDict) ? 'N/A' : groupsDict[userKey];
				
				}
			},
			 
			 
			},
			{'render':function(data,type,full,meta){
				let courseID = full['Course ID'];
				if (courseID == 0){
					return 'DELETED COURSE';
				}
				else{
				return courseDict[courseID];
				}
			
			}},
				{'render': function(data,type,full,meta){
				let compl ='';
				(full['Activity Status']==1) ? compl = 'Completed': compl = 'Incomplete';
				return compl;
				}},
				{data: 'Quiz Name'},
				{'render': function(data,type,full,meta){
				return full['Quiz Score'] + '%';
				}
					}
		]
    });
					
	//shape the data (chart)
	jQuery.each(dataJson,function(i,value){
		if(dataJson[i]['Activity Status'] == 1){ //Loop thru completed quizzes
			if(dataJson[i]['Quiz Name'] in quizDict == false){ //If not found in dictionary, initialzie it
				quizDict[dataJson[i]['Quiz Name']] = new Array(0);
			}	
			quizDict[dataJson[i]['Quiz Name']].push(dataJson[i]['Quiz Score']); //Add quiz score to array
		}		
	});
					
	for (key in quizDict){
		 //convert array of strings to Number
		let quizScores = Array.from(quizDict[key],Number);
		
		//sort the array numerically
		let sortedQuizScores = quizScores.sort((a, b) => a - b); 
					
		quizDict[key]= buildChartData(sortedQuizScores);
		quizNamesList.push(key);
		quizScoresList.push(quizDict[key]);
					
					quizNamesList.push("test"); //test data
					quizScoresList.push([40,45,70,80,90]); //test data
					
					quizNamesList.push("test test"); //test data
					quizScoresList.push([25,55,75,80,85]); //test data
	}
					
	
					
					
					
    // Create the chart with initial data
    var container = jQuery('<div/>').insertBefore(table.table().container());
    var chart = Highcharts.chart(container[0], {
        chart: {
            type: 'boxplot',
        },
        title: {
            text: 'Quiz Results',
        },
		legend: {
        enabled: false
    	},
		 xAxis: {
        categories: quizNamesList,
        title: {
            text: 'Quiz'
        }
    },
    yAxis: {
        title: {
            text: 'Quiz Scores'
        },
		min:0,
		max:100
		
    },
       series: [{
        name: 'Quiz Scores',
        data: quizScoresList,
        tooltip: {
            headerFormat: '<em>Quiz No {point.key}</em><br/>'
        }
    }   
    ]
    });
 
    // On each draw, update the data in the chart
   // table.on('draw', function () {
    //    chart.series[0].setData(chartData(table));
    //});
					
					
});//end of Ajax.Done	
		
		
	});//end of Ajax.Done for sqlCourseInSubsite 
	
			}); // end of Groups Ajax Done()
			
 
			
			
function chartData(table) {
    var counts = {};
 
    // Count the number of entries for each position
    table
        .column(1, { search: 'applied' })
        .data()
        .each(function (val) {
            if (counts[val]) {
                counts[val] += 1;
            } else {
                counts[val] = 1;
            }
        });
 
    // And map it to the format highcharts uses
    return jQuery.map(counts, function (val, key) {
        return {
            name: key,
            y: val,
        };
    });
}
		
//THIS SECTION BELOW CONTAINS FORMULA FOR BOXPLOT
//DO NOT TOUCH
//#########################################################################
const standardDeviation = (arr, usePopulation = false) => {
  const mean = arr.reduce((acc, val) => acc + val, 0) / arr.length;
  return Math.sqrt(
    arr
      .reduce((acc, val) => acc.concat((val - mean) ** 2), [])
      .reduce((acc, val) => acc + val, 0) /
      (arr.length - (usePopulation ? 0 : 1))
  );
};


function MinOfArray(data){
 return Math.min(...data);
}

function MaxOfArray(data){
return Math.max(...data);
}
	
function Median(data) {
  return Quartile_50(data);
}

function Quartile_25(data) {
  return Quartile(data, 0.25);
}

function Quartile_50(data) {
  return Quartile(data, 0.5);
}

function Quartile_75(data) {
  return Quartile(data, 0.75);
}

function Quartile(data, q) {
  data=Array_Sort_Numbers(data);
  var pos = ((data.length) - 1) * q;
  var base = Math.floor(pos);
  var rest = pos - base;
  if( (data[base+1]!==undefined) ) {
    return data[base] + rest * (data[base+1] - data[base]);
  } else {
    return data[base];
  }
}

function Array_Sort_Numbers(inputarray){
  return inputarray.sort(function(a, b) {
    return a - b;
  });
}

function Array_Sum(t){
   return t.reduce(function(a, b) { return a + b; }, 0); 
}

function Array_Average(data) {
  return Array_Sum(data) / data.length;
}

function Array_Stdev(tab){
   var i,j,total = 0, mean = 0, diffSqredArr = [];
   for(i=0;i<tab.length;i+=1){
       total+=tab[i];
   }
   mean = total/tab.length;
   for(j=0;j<tab.length;j+=1){
       diffSqredArr.push(Math.pow((tab[j]-mean),2));
   }
   return (Math.sqrt(diffSqredArr.reduce(function(firstEl, nextEl){
            return firstEl + nextEl;
          })/tab.length));  
}
//#########################################################################

//This function returns an array of chart data, to accommodate for situations where there are < 5 data points. 
function buildChartData(data){
	var chartReadyData = new Array(5).fill();
	var valMin, valMax, val50, val25, val75 = 0;
	switch(data.length) {			
  		case 1:
			chartReadyData.fill(data[0]);
    		return chartReadyData;
  		case 2:
			  /*valMin = MinOfArray(data);
			  valMax = MaxOfArray(data);
			  val50 = (valMax + valMin) * 0.5;
			  val25 = (val50 + valMin) * 0.5;
			  val75 = (val50 + valMax) * 0.5;*/
			valMin = MinOfArray(data);
			 valMax = MaxOfArray(data);
			 val50 = Quartile_50(data);
			 val25 = Quartile_25(data);
			 val75 = Quartile_75(data);
			chartReadyData = [valMin,val25,val50,val75,valMax];
    		return chartReadyData;
		case 3:
             /*valMin = MinOfArray(data);
             valMax = MaxOfArray(data);
			 val50 = data[1];
			 val25 = (val50 + valMin) * 0.5;
			 val75 = (val50 + valMax) * 0.5;*/
			 valMin = MinOfArray(data);
			 valMax = MaxOfArray(data);
			 val50 = Quartile_50(data);
			 val25 = Quartile_25(data);
			 val75 = Quartile_75(data);
			chartReadyData = [valMin,val25,val50,val75,valMax];
			return chartReadyData;
			
		case 4:
			 /*valMin = MinOfArray(data);
			 valMax = MaxOfArray(data);
			 val50 = (data[1] + data[2]) * 0.5;
			 val25 = (val50 + valMin) * 0.5;
			 val75 = (val50 + valMax) * 0.5;*/
			 valMin = MinOfArray(data);
			 valMax = MaxOfArray(data);
			 val50 = Quartile_50(data);
			 val25 = Quartile_25(data);
			 val75 = Quartile_75(data);
			chartReadyData = [valMin,val25,val50,val75,valMax];
			return chartReadyData;
  		default:
			 valMin = MinOfArray(data);
			 valMax = MaxOfArray(data);
			 val50 = Quartile_50(data);
			 val25 = Quartile_25(data);
			 val75 = Quartile_75(data);
			chartReadyData = [valMin,val25,val50,val75,valMax];
			return chartReadyData;
	}
	
}
			
		</script>


		<!--Remember to name the chart so can edit from there and do any style tag on top-->

		
<?php
      	  	break;
	  	case 'users':
?>
<table id="testTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Month Registered</th>
                <th>Display Name</th>
                <th>User ID</th>
				<th>Group</th>
            </tr>
        </thead>
    </table>		
<style>
.highcharts-figure, .highcharts-data-table table {
    min-width: 310px; 
    max-width: 80%;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
</style>
		<script>
			let groupsDict = {};
			
			let sqlGroupQuery_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlGroupsQueryWithSiteSubquery.php';
				jQuery.ajax({
        url: sqlGroupQuery_url,
        method: 'GET'
    	}).done(function (data) {
		let groupJson = JSON.parse(data);		
					
		jQuery.each(groupJson,function(i,value){
			if(groupJson[i]['User Name'] in groupsDict == false){ //If not found in dictionary, init
				groupsDict[groupJson[i]['User Name']] = [];
			}
			groupsDict[groupJson[i]['User Name']].push(groupJson[i]['Group Name']);	
		
		});				
		
			
			
			let sqlCourseQuery_url = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlCourseQuery.php';
				jQuery.ajax({
        url: sqlCourseQuery_url,
        method: 'GET'
    	}).done(function (data) {
				let dataJson = JSON.parse(data);
			
				   var table = jQuery('#testTable').DataTable({
        			dom: 'frtipP',
					   searchPanes:{
		  				threshold: 1
      				//columns: [ 1 ]
    				},
    				columnDefs: [
      				{
        			targets: 5,
        			render: function (data, type, row) {
          			if (type === 'sp') {
            			return data.split(', ')
          			}
          			return data;
        			},
        			searchPanes: {
          			orthogonal:'sp'
        			}
      				}
    				],
					data: dataJson,
					columns: [
						{data: 'Course ID'},
						{data: 'Course Name'},
						{'render': function(data,type,full,meta){
						let monthRegistered = [full['Date Registered'].slice(0, 4), '-', full['Date Registered'].slice(4)].join('');
						return monthRegistered;
						}},
						{data: 'Display Name'},
						{data: 'User ID'},
							{'render':function(data,type,full,meta){
								let userKey = full['Display Name'];
				
								if (userKey === null){
									return 'N/A';
								}else {
								//if user doesnt exist in dictionary, return N/A
								return !(userKey in groupsDict) ? 'N/A' : groupsDict[userKey];
								}
							},
						}
						]
    				});	
				
					
				//map the array and filter out unique dates
				var unique_dates_string = dataJson
				.map(dataJson => dataJson['Date Registered'])
				.filter((value,index,self) => self.indexOf(value) === index);
					
				 //convert array of strings to Number
				let unique_dates_numeric = Array.from(unique_dates_string,Number);
				
				//sort the array numerically
				let unique_dates_sorted = unique_dates_numeric.sort((a, b) => a - b); 
				
				//Find the min dates using spread 
				let minDate = Math.min(...unique_dates_sorted);
				let minDateString = minDate.toString();
				
				let todaysDate = new Date();
				let todaysYearString = todaysDate.getFullYear().toString();
				let todaysMonthString =(todaysDate.getMonth()+1).toString();
				let currentDateInt = parseInt(todaysYearString.concat(todaysMonthString));	
				let currentDateString = currentDateInt.toString();
				//let currentYearString = currentDateString.slice(0, 4);
				//let currentMonthString = currentDateString.slice(4, 6);
				
				let inputStartDateRange = [minDateString.slice(0, 4), '-', minDateString.slice(4)].join('');
				let inputCurrentDateRange = [currentDateString.slice(0, 4), '-', currentDateString.slice(4)].join('');
				
				let dateList = dateRange(inputStartDateRange,inputCurrentDateRange);	
				
				//This function returns an array containing YYYY-MM between the start date and end date inclusive
				function dateRange(startDate, endDate) {
  					var start      = startDate.split('-');
  					var end        = endDate.split('-');
  					var startYear  = parseInt(start[0]);
  					var endYear    = parseInt(end[0]);
  					var dates      = [];

  					for(var i = startYear; i <= endYear; i++) {
    					var endMonth = i != endYear ? 11 : parseInt(end[1]) - 1;
    					var startMon = i === startYear ? parseInt(start[1])-1 : 0;
    					for(var j = startMon; j <= endMonth; j = j > 12 ? j % 12 || 11 : j+1) {
      						var month = j+1;
      						var displayMonth = month < 10 ? '0'+month : month;
      						dates.push([i, displayMonth].join('-'));
    					}
  					}
  					return dates;
				}
				
			let courseDict = {};
			let courseList = [];
			let dateDict = {};
			
			jQuery.each(dataJson, function(i,value){
			if (dataJson[i]['Course Name'] in courseDict == false){
			courseDict[dataJson[i]['Course Name']] = new Array(dateList.length).fill(0);
			}
				
			let rawDate = dataJson[i]['Date Registered'];
			let formattedDate = [rawDate.slice(0, 4), '-', rawDate.slice(4)].join('');
			let arrIndex = dateList.indexOf(formattedDate);
			courseDict[dataJson[i]['Course Name']][arrIndex]++;
			
			});//end of jQuery.each
			
			courseDatasets=[];
			for(key in courseDict){
				let newDataset = {
				name: key,
				data: courseDict[key]
				};
			courseDatasets.push(newDataset);
			}

		// Create the chart with initial data
    	var container = jQuery('<div/>').insertBefore(table.table().container());
    	var chart = Highcharts.chart(container[0], {
    	chart: {
        	type: 'area'
    	},
    	title: {
        	text: 'Users Registered in Courses By Month'
    	},
    	subtitle: {
        	text: 'Subtitle'
    	},
    	xAxis: {
        	categories: dateList,
        	tickmarkPlacement: 'on',
        	title: {
            	enabled: false
        	}
    	},
     	yAxis: {
        	title: {
            	text: 'No. Of Users'
        	}, 
    	},
    	tooltip: {
        	split: true,
    	},
    	plotOptions: {
        	area: {
            	stacking: 'normal',
            	lineColor: '#666666',
            	lineWidth: 1,
            	marker: {
                	lineWidth: 1,
                	lineColor: '#666666'
            	}
        	}
    	},
    	series: courseDatasets
	});
					
	// On each draw, update the data in the chart
   // table.on('draw', function () {
    //    chart.series[0].setData(chartData(table));
    //});
				
		}); // end of Courses Ajax Done()
					

		}); // end of Groups Ajax Done()					
			
			function chartData(table) {
    var counts = {};
 
    // Count the number of entries for each position
    table
        .column(1, { search: 'applied' })
        .data()
        .each(function (val) {
            if (counts[val]) {
                counts[val] += 1;
            } else {
                counts[val] = 1;
            }
        });
 
    // And map it to the format highcharts uses
    return jQuery.map(counts, function (val, key) {
        return {
            name: key,
            y: val,
        };
    });
}
			
			
			/*
				function xAxisLengthCounter(startDate,currentDate){
				let monthList = [];
				let startDateString = startDate.toString();
				let startYearInt = parseInt(startDateString.slice(0,4));
				let startMonthInt = parseInt(startDateString.slice(4,6));
				console.log("start", startYearInt, startMonthInt);
					
				let currentDateString = currentDate.toString();
				let currentYearInt = parseInt(currentDateString.slice(0, 4));
				let currentMonthInt = parseInt(currentDateString.slice(4, 6));
				("current", currentYearInt, currentMonthInt);
					
					do{
						monthList.append(startDateString);
						if(currentMonthInt===12){
						currentMonthInt = 0;
						currentYearInt++;
						}
						currentMonthInt++;
						
					}while(startYearInt < currentYearInt && startMonthInt < currentMonthInt);
					   
				}//end of xAxisLength Counter	
				xAxisLengthCounter(minDate,currentDateInt);
			*/
			
		</script>
		
		<!--Remember to name the chart so can edit from there and do any style tag on top-->
		
		
<?php
			break;
		case 'groups':
?>	
		
		<table id="testTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Group Name</th>
                <th>Role</th>
                <th>User Name</th>
				<th>User ID</th>
				<th>Email</th>
            </tr>
        </thead>
    </table>		
		
		<script>
			window.addEventListener('load', (event) => {
			
			let sqlQuery = "<?php echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlGroupsQueryWithSiteSubquery.php';
			
				jQuery.ajax({
        url: sqlQuery,
        method: 'GET'
    	}).done(function (data) {
				let dataJson = JSON.parse(data);
				console.log(dataJson);	
					
				 var table = jQuery('#testTable').DataTable({
        			dom: 'frtipP',
					data: dataJson,
					columns: [
						{data: 'Group Name'},
						{'render': function(data,type,full,meta){
						let roleArray = full['Role'].split("_");
						let roleName = roleArray[2];
						let roleString = roleName.replace(/^./, roleName[0].toUpperCase()).slice(0,-1); 
						return roleString;
						}},
						{data: 'User Name'},
						{data: 'User ID'},
						{data: 'Email'}
						]
    				});		
				  // Create the chart with initial data
    		var container = jQuery('<div/>').insertBefore(table.table().container());
 
    		var chart = Highcharts.chart(container[0], {
        		chart: {
            		type: 'pie',
        		},
        		title: {
            		text: 'User Groups',
        		},
        		series: [
            		{
                		data: chartData(table),
            		},
        		],
    		});
 
    	// On each draw, update the data in the chart
    	table.on('draw', function () {
        	chart.series[0].setData(chartData(table));
    	});
					
					
	});//end of Ajax Done
			
	function chartData(table) {
    	var counts = {};
 
    	// Count the number of entries for each position
    	table
        	.column(1, { search: 'applied' })
        	.data()
        	.each(function (val) {
            	if (counts[val]) {
                	counts[val] += 1;
            	} else {
                	counts[val] = 1;
            	}
        	});
 
    	// And map it to the format highcharts uses
    	return jQuery.map(counts, function (val, key) {
        	return {
            	name: key,
            	y: val,
        	};
    	});
	}
			
			
			
		/* ALTERNATIVE
		let userList =[];
		let sqlOuterQuery_url = "<?php //echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlGroupsOuterQuery.php';
		let sqlGroupsQuery_url = "<?php// echo plugin_dir_url(__DIR__)?>"+ 'Assests/chartData/sqlGroupsQuery.php';
		
			
				jQuery.ajax({
        url: sqlOuterQuery_url,
        method: 'GET'
    	}).done(function (data) {
				let dataJson = JSON.parse(data);
				
				//these users exist in site
				userList = dataJson
					.map(dataJson => dataJson['user_id'])
					.filter((value,index,self) => self.indexOf(value) === index);
				console.log(userList);
				
				jQuery.ajax({
        			url: sqlGroupsQuery_url,
        			method: 'GET'
    				}).done(function (data) {	
					let groupJson = JSON.parse(data);
					console.log('groupjson',groupJson);
					
					jQuery.each(groupJson, function(i, val) {
  						 if(groupJson[i]['ID'] in userList){ 
      					console.log(groupJson[i]['ID']);
					}
					});
					
				});//end of ajax.Done(sqlGroupsQuery)
					
					
					
		});//end of ajax.Done (sqlOuterQuery)
		*/
		
		
});//end of window.onload
		</script>
		<!--Remember to name the chart so can edit from there and do any style tag on top-->


<?php
			break;
      	default:
?>
<!-- This is the all reports tab-->
		
		
<?php
        	break;
    endswitch; ?>
   </div>
</div>

<?php	
		}
	}
}
?>

