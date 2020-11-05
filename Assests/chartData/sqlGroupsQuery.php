<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
//testing getting data from database//
$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');


$query = "SELECT user.ID,user.display_name,user.user_email, um.meta_key,um.meta_value, pp.post_title,pp.post_content FROM wp_usermeta um LEFT JOIN wp_posts pp ON pp.ID = um.meta_value LEFT JOIN wp_users user ON user.ID = um.user_id WHERE um.meta_key LIKE '%group%'";


$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
echo json_encode($result);

	//END TEST//
?>