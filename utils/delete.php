<?php
require_once 'config/db_connection.php';
require_once 'functions.php';

start_session();
  
page_access_restriction('../index.php'); 

function delete_data_from_db($table,$column,$target,$message,$flash_msg_desc, $redirect_to) {
	global $db;
	/*getting the user id from the URL*/
	if(isset($target)) {
		$id = intval($target);

		try{
		$query1 = $db->prepare("DELETE FROM $table WHERE $column = ?");
		$query1->execute([$id]);
		
		/** this query will altering the table in passed in parameter to provide a good ASCENDING ORDER of IDS after deleting an element within the table
		 */
		$query2 = $db->query("
		SET @autoid :=0;
		UPDATE $table SET id = @autoid := (@autoid + 1);
		ALTER TABLE $table AUTO_INCREMENT = 1;
		");	

		$_SESSION[$flash_msg_desc]['success'] =  $message;
		page_redirection($redirect_to);
		}catch(PDOException $error){
			die('An error occurred '.$error->getMessage());
		} 	
	}
};

// delete an item from the t_stock table
delete_data_from_db('t_stock','id',$_GET['del_stock_id'],'Item has been successfully deleted','flash-stock', '../dashboard/stock.php');
delete_data_from_db('t_staff','id',$_GET['del_user_id'],'User has been successfully deleted','flash-user', '../dashboard/users.php');

