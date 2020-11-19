<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
//testing getting data from database//
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');
global $wpdb;

/*$query = "SELECT wp_users.display_name as 'User',wp_learndash_user_activity.course_id as 'Course ID',wp_learndash_user_activity.activity_status as 'Activity Status', wp_posts.post_title as 'Quiz Name'
FROM wp_learndash_user_activity
LEFT JOIN wp_posts ON wp_learndash_user_activity.post_id = wp_posts.id
LEFT JOIN wp_users ON wp_learndash_user_activity.user_id = wp_users.ID
WHERE wp_learndash_user_activity.activity_type ='quiz' AND wp_posts.post_status ='publish'";*/

$query ="SELECT wp_users.display_name as 'User',{$wpdb->prefix}learndash_user_activity.course_id as 'Course ID',{$wpdb->prefix}learndash_user_activity.activity_status as 'Activity Status', {$wpdb->prefix}posts.post_title as 'Quiz Name', {$wpdb->prefix}learndash_user_activity_meta.activity_meta_value as 'Quiz Score'
FROM {$wpdb->prefix}learndash_user_activity
LEFT JOIN {$wpdb->prefix}learndash_user_activity_meta ON {$wpdb->prefix}learndash_user_activity.activity_id = {$wpdb->prefix}learndash_user_activity_meta.activity_id
LEFT JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}learndash_user_activity.post_id = {$wpdb->prefix}posts.id
LEFT JOIN wp_users ON {$wpdb->prefix}learndash_user_activity.user_id = wp_users.ID
WHERE {$wpdb->prefix}learndash_user_activity.activity_type ='quiz' AND {$wpdb->prefix}posts.post_status ='publish' AND {$wpdb->prefix}learndash_user_activity_meta.activity_meta_key = 'percentage'";

$result = $wpdb->get_results($wpdb->prepare($query));
echo json_encode($result);

	//END TEST//
?>