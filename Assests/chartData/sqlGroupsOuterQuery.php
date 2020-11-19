<?php
//testing getting data from database
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');
global $wpdb;

$query = "SELECT user_id FROM wp_usermeta where meta_key = '{$wpdb->prefix}capabilities'";

$result = $wpdb->get_results($wpdb->prepare($query));

echo json_encode($result);
	//END TEST//
?>