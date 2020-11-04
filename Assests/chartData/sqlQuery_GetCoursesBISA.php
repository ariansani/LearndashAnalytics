<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
global $wpdb;

$query = "SELECT id  AS 'Course ID',post_title AS 'Course Title' FROM {$wpdb->prefix}posts WHERE post_type ='sfwd-courses' AND post_status ='publish'";

//testing getting data from database//
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');

//$query = "SELECT id AS 'Course ID',post_title AS 'Course Title' FROM wp_11_posts WHERE post_type ='sfwd-courses' AND post_status ='publish'";
//echo $query;

$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

echo json_encode($result);



//END TEST//
?>