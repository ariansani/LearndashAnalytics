<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
//testing getting data from database//
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');

$query ="SELECT post.ID, post.post_title, postmeta.meta_value FROM wp_posts post JOIN wp_postmeta postmeta ON post.ID = postmeta.post_id WHERE post.post_status = 'publish' AND post.post_type = 'groups'";

$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
echo json_encode($result);

	//END TEST//
?>