<?php
//testing getting data from database
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');
global $wpdb;

$query = "SELECT user_id FROM wp_usermeta where meta_key = '{$wpdb->prefix}capabilities'";

$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

echo json_encode($result);
	//END TEST//
?>