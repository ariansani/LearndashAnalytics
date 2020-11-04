<?php 
require_once('Page/overview.php');
require_once('Page/Reports.php');

function wp_load_dataTables() {
	wp_enqueue_script( 'dataTables-jQuery',  plugin_dir_url( __FILE__ ).'Assests/dataTables/jquery-3.5.1.js', array(), '1.0.0', false );
	wp_enqueue_script( 'dataTables-JS',  plugin_dir_url( __FILE__ ).'Assests/dataTables/jquery.dataTables.min.js', array(), '1.0.0', false );
	wp_enqueue_script( 'dataTables-searchPanes',plugin_dir_url( __FILE__ ).'Assests/dataTables/dataTables.searchPanes.min.js', array(), '1.0.0', 	false );
	wp_enqueue_script( 'dataTables-select',  plugin_dir_url( __FILE__ ).'Assests/dataTables/dataTables.select.min.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts',  plugin_dir_url( __FILE__ ).'Assests/highCharts/highcharts.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-more',  plugin_dir_url( __FILE__ ).'Assests/highCharts/highcharts-more.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-treemap',  plugin_dir_url( __FILE__ ).'Assests/highCharts/treemap.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-series-label',  plugin_dir_url( __FILE__ ).'Assests/highCharts/series-label.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-annotations',  plugin_dir_url( __FILE__ ).'Assests/highCharts/annotations.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-exporting',  plugin_dir_url( __FILE__ ).'Assests/highCharts/exporting.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-export-data',  plugin_dir_url( __FILE__ ).'Assests/highCharts/export-data.js', array(), '1.0.0', false );
	wp_enqueue_script( 'highCharts-accessibility',  plugin_dir_url( __FILE__ ).'Assests/highCharts/accessibility.js', array(), '1.0.0', false );
	wp_enqueue_style( 'dataTables-jQuery-CSS', plugin_dir_url( __FILE__ ).'Assests/dataTables/jquery.dataTables.min.css');
	wp_enqueue_style( 'dataTables-searchPanes-CSS', plugin_dir_url( __FILE__ ).'Assests/dataTables/searchPanes.dataTables.min.css' );
	wp_enqueue_style( 'dataTables-CSS', plugin_dir_url( __FILE__ ).'Assests/dataTables/select.dataTables.min.css' );
}
add_action( 'admin_enqueue_scripts', 'wp_load_dataTables' );

/*foreach(glob("wp-content/plugins/Learndash-Analytics/Assests/chartData/*.php") as $datafile){
	//Ob = Output buffering <- Arian if interested can look in this
	ob_start();
    require_once($datafile); 
	ob_end_clean();
}*/
/*
<script src="wp-content/plugins/Learndash-Analytics/Assests/dataTables/dataTables.searchPanes.min.js"></script>
<script src="wp-content/plugins/Learndash-Analytics/Assests/dataTables/dataTables.select.min.js"></script>
<script src="wp-content/plugins/Learndash-Analytics/Assests/dataTables/highcharts.js"></script>
<script src="wp-content/plugins/Learndash-Analytics/Assests/dataTables/jquery-3.5.1.js"></script>
 <link rel="stylesheet" href="wp-content/plugins/Learndash-Analytics/Assests/dataTables/jquery.dataTables.min.css">
<script src="wp-content/plugins/Learndash-Analytics/Assests/dataTables/jquery.dataTables.min.js"></script>
 <link rel="stylesheet" href="wp-content/plugins/Learndash-Analytics/Assests/dataTables/searchPanes.dataTables.min.css">
 <link rel="stylesheet" href="wp-content/plugins/Learndash-Analytics/Assests/dataTables/select.dataTables.min.css">
*/

?>



