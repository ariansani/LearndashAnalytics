<?php



//testing getting data from database//
/*$connection = new PDO ('mysql:host=localhost;dbname=kqwest_dev1;charset=utf8;','kqwest_dev1','6Cizm4L2h!Znrjwn');
$query = "SELECT wp_posts.id AS 'Course ID', wp_posts.post_title AS 'Course Name', wp_11_learndash_user_activity.course_id AS 'Course ID',wp_users.ID  AS 'User ID', wp_users.display_name as 'Display Name'
FROM wp_11_learndash_user_activity 
LEFT JOIN wp_posts ON wp_posts.id = wp_11_learndash_user_activity.course_id
LEFT JOIN wp_users ON wp_users.ID = wp_11_learndash_user_activity.user_id
WHERE wp_11_learndash_user_activity.activity_type = 'access' AND wp_posts.post_type='sfwd-courses' AND wp_posts.post_status='publish' AND wp_users.ID IS NOT NULL AND wp_11_learndash_user_activity.activity_status IS NULL AND wp_11_learndash_user_activity.activity_started != 0";

$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

echo json_encode($result);*/
			
	//END TEST//
?>