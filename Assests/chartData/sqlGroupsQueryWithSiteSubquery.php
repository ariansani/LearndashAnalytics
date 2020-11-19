<?php



//testing getting data from database
include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
$connection = new PDO ('mysql:host=localhost;dbname=wp_kqwest;charset=utf8;','wp_kqwest','b5l$L01i');
global $wpdb;


//$secondQuery = "(SELECT ID FROM {$wpdb->prefix}posts WHERE post_status='publish' AND post_type = 'groups')";


//var_dump($secondQuery); 

$query = "SELECT user.ID AS 'User ID' ,user.display_name AS 'User Name',user.user_email AS 'Email', um.meta_key AS 'Role',um.meta_value AS 'Group Number', pp.post_title AS 'Group Name',pp.post_content AS 'Group Description'
FROM wp_usermeta um 
LEFT JOIN {$wpdb->prefix}posts pp ON pp.ID = um.meta_value 
LEFT JOIN wp_users user ON user.ID = um.user_id 
WHERE um.meta_value IN 
	(SELECT ID FROM {$wpdb->prefix}posts 
	WHERE post_status = 'publish' 
	AND post_type = 'groups')";


//$result = $wpdb->get_results($wpdb->prepare($query));
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

echo json_encode($result);
	//END TEST//
?>