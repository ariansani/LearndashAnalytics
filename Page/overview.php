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
	
	
	/*THIS IS CARD STYLING*/
	
.container{
  position: relative;
  height: 100%;
  width: 80%;
	left:10%;
  display: flex;
}

	.holder{
		margin-left: 25%;
    	margin-top: 45%;
  		text-align: center;
	}


.card {
  display: flex;
  height: 280px;
  width: 280px;
  background-color: #FFFFFF;
  border-radius: 10px;
  box-shadow: 1rem 1em 3rem #666;
/*   margin-left: -50px; */
  transition: 0.4s ease-out;
  position: relative;
  left: 0px;
}

.card:not(:first-child) {
    margin-left: -50px;
}

.card:hover {
  transform: translateY(-20px);
  transition: 0.4s ease-out;
}

.card:hover ~ .card {
  position: relative;
  left: 50px;
  transition: 0.4s ease-out;
}

.title {
  color: black;
  font-weight: 300;
  font-size:2em;
  position: absolute;
  left: 20px;
  top: 15px;
}

.bar {
  position: absolute;
  top: 80px;
  left: 20px;
  height: 5px;
  width: 220px;
background-color: #2e3033;
	padding:0px;
	margin-bottom:0px;
}

.emptybar {
  background-color: #2e3033;
  width: 100%;
  height: 100%;
}

.filledbar {
  position: absolute;
  top: 0px;
  z-index: 3;
  width: 0px;
  height: 100%;
  background: rgb(0,154,217);
  background: linear-gradient(90deg, rgba(0,154,217,1) 0%, rgba(230,222,0,1) 65%, rgba(255,255,0,1) 100%);
  transition: 0.6s ease-out;
}

.card:hover .filledbar {
  width: 220px;
  transition: 0.4s ease-out;
}
/*END OF CARD STYLING	*/
	
	.column {
  		float: left;
  		width: 25%;
  		padding: 0 10px;
	}


	/*.row {margin: 0 -5px;}
*/
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


	.card2 {
  		box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  		padding: 16px;
  		text-align: center;
  		background-color: #f1f1f1;
	}
	
	button {
		background-color: #52708a;
		font-size:12px;
		border-radius: 12px;
		border: 2px solid #52708a;
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
	    height: 60px;
    	font-size: 5em;
    	margin-left: -50px;
		
	}
	
</style>

<h2 style="width:100%;display:block;">Here are the selection to view the reports accordingly.</h2>
<h3 >For Learndash Analytics:</h3>
<div class="row">
	<div class="container">
  <div class="card">
    <h3 class="title">Users</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
      	<div class="holder">
    	<span class="dashicons dashicons-admin-users" height="700" width="700"></span>
      	<p><?php echo $value_of_users; echo $value_of_users > "1" ? " Users" : " User"; ?></p>
      	<button id="hideShow" onclick="window.location.href='?page=reports&tab=users'"><span>View User Analytics</span></button>
    </div>
  </div>
		
  <div class="card">
    <h3 class="title">Courses</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
  <div class="holder">
    	 <span class="dashicons dashicons-media-code"></span>
      	 <p><?php echo $value_of_course; echo $value_of_course > "1" ? " Courses" : " Course";?></p>
    <button  onclick="window.location.href='?page=reports&tab=course'"><span>View Course Analytics</span></button>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Quizzes</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
   <div class="holder">
      <span class="dashicons dashicons-backup"></span>
      <p><?php echo $value_of_quiz; echo $value_of_quiz > "1" ? " Quizzes" : " Quiz"; ?></p>
       <button id="hideShow" onclick="window.location.href='?page=reports&tab=Quiz'"><span>View Quiz Analytics</span></button>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Groups</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
	   <div class="holder">
    <span class="dashicons dashicons-groups"></span>
      <p><?php echo $value_of_groups; echo $value_of_groups > "1" ? " Groups" : " Group" ?></p>
	<button id="hideShow" onclick="window.location.href='?page=reports&tab=groups'"><span>View Groups Analytics</span></button>
    </div>
  </div>
</div>
</div>
<br>

<?php 
function products() {
        return array_map('wc_get_product', get_posts(['post_type'=>'product','nopaging'=>true]));
}
			
if (sizeof(products()) < 1){
echo "<style>.wooCommerceBlock{display:none;}</style>";
}
			
?>
<div class="wooCommerceBlock">
<h3>For WooCommerce Analytics:</h3>
<div class="row">
	<div class="container">
  <div class="card">
    <h3 class="title">Sales Overview</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
      	<div class="holder">
    	<span class="dashicons dashicons-money" height="700" width="700"></span>
			<p>Total Sales &amp; Orders</p>
				<button onclick="window.open('./admin.php?page=wc-admin','_blank')"><span>View Sales Analytics</span></button>
    </div>
  </div>
		
  <div class="card">
    <h3 class="title">Orders</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
  <div class="holder">
    	 <span class="dashicons dashicons-welcome-write-blog"></span>
	  <p>Order Status</p>
    <button onclick="window.open('./edit.php?post_type=shop_order','_blank')"><span>View Orders</span></button>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Customers</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
   <div class="holder">
      <span class="dashicons dashicons-id-alt"></span>
	   <p>Spending &amp; Info</p>
       <button onclick="window.open('./admin.php?page=wc-admin&path=%2Fcustomers','_blank')"><span>View Customer Data</span></button>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Reports</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
    </div>
	   <div class="holder">
    <span class="dashicons dashicons-chart-line"></span>
		   <p>Export Sales Data</p>
	<button onclick="window.open('./admin.php?page=wc-reports','_blank')"><span>View Reports</span></button>
    </div>
  </div>
 
</div>
	
</div>
</div>
<?php
		$user = wp_get_current_user();
		if ( in_array( 'client-instructor', (array) $user->roles ) ) {
		
			echo "<style>#hideShow{display:none;}</style>";
			}	
		}
	}
}


