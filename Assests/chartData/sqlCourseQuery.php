<?php
//testing getting data from database
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');
global $wpdb;

//Split and get value of interger
$prefix = str_split($wpdb->prefix,3)[0];

$blogid = preg_replace("/[^0-9]{1,4}/", '', $wpdb->prefix); 
if ($blogid > 1) {
/*$query = "SELECT wp_users.display_name AS 'User Name',wp_users.ID,wp_usermeta.meta_value AS 'Blog Id', wp_blogs.path, wp_users.user_registered
FROM wp_usermeta 
LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID 
LEFT JOIN wp_blogs ON wp_usermeta.meta_value = wp_blogs.blog_id 
WHERE wp_usermeta.meta_key = 'primary_blog' AND wp_usermeta.meta_value = '".$blogid."'";*/
	$query = "SELECT {$wpdb->prefix}posts.id AS 'Course ID', 
{$wpdb->prefix}posts.post_title AS 'Course Name', 
{$wpdb->prefix}learndash_user_activity.course_id AS 'Course ID',
wp_users.ID  AS 'User ID', 
wp_users.display_name as 'Display Name',
EXTRACT(YEAR_MONTH FROM wp_users.user_registered) as 'Date Registered'
FROM {$wpdb->prefix}learndash_user_activity 
LEFT JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}posts.id = {$wpdb->prefix}learndash_user_activity.course_id
LEFT JOIN wp_users ON wp_users.ID = {$wpdb->prefix}learndash_user_activity.user_id
WHERE {$wpdb->prefix}learndash_user_activity.activity_type = 'access' 
AND {$wpdb->prefix}posts.post_type='sfwd-courses' 
AND {$wpdb->prefix}posts.post_status='publish' 
AND wp_users.ID IS NOT NULL 
AND {$wpdb->prefix}learndash_user_activity.activity_status IS NULL 
AND {$wpdb->prefix}learndash_user_activity.activity_started != 0";
}else{
	/*$query = "SELECT wp_users.display_name AS 'User Name',wp_users.ID,wp_usermeta.meta_value AS 'Blog Id', wp_blogs.path, wp_users.user_registered
FROM wp_usermeta 
LEFT JOIN wp_users ON wp_usermeta.user_id = wp_users.ID 
LEFT JOIN wp_blogs ON wp_usermeta.meta_value = wp_blogs.blog_id 
WHERE wp_usermeta.meta_key = 'primary_blog'";*/
	$query = "SELECT {$wpdb->prefix}posts.id AS 'Course ID', 
{$wpdb->prefix}posts.post_title AS 'Course Name', 
{$wpdb->prefix}learndash_user_activity.course_id AS 'Course ID',
wp_users.ID  AS 'User ID', 
wp_users.display_name as 'Display Name',
EXTRACT(YEAR_MONTH FROM wp_users.user_registered) as 'Date Registered'
FROM {$wpdb->prefix}learndash_user_activity 
LEFT JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}posts.id = {$wpdb->prefix}learndash_user_activity.course_id
LEFT JOIN wp_users ON wp_users.ID = {$wpdb->prefix}learndash_user_activity.user_id
WHERE {$wpdb->prefix}learndash_user_activity.activity_type = 'access' 
AND {$wpdb->prefix}posts.post_type='sfwd-courses' 
AND {$wpdb->prefix}posts.post_status='publish' 
AND wp_users.ID IS NOT NULL 
AND {$wpdb->prefix}learndash_user_activity.activity_status IS NULL 
AND {$wpdb->prefix}learndash_user_activity.activity_started != 0";
}

$result = $wpdb->get_results($wpdb->prepare($query));


echo json_encode($result);
	//END TEST//
?>