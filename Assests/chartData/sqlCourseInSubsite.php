<?php
//testing getting data from database
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');
global $wpdb;

/*$query = "SELECT DISTINCT {$wpdb->prefix}posts.id AS 'Course ID', {$wpdb->prefix}posts.post_title AS 'Course Name' 
	FROM {$wpdb->prefix}posts 
	WHERE  {$wpdb->prefix}posts.post_type='sfwd-courses' 
	AND {$wpdb->prefix}posts.post_status='publish'";*/

$query = "SELECT DISTINCT wp_posts.id AS 'Course ID', wp_posts.post_title AS 'Course Name' 
	FROM wp_posts 
	WHERE  wp_posts.post_type='sfwd-courses' 
	AND wp_posts.post_status='publish'";

$result = $wpdb->get_results($wpdb->prepare($query));

echo json_encode($result);
	//END TEST//
?>