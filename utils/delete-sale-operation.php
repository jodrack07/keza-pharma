<?php
require_once 'config/db_connection.php';
require_once 'functions.php';

start_session();
  
page_access_restriction('../index.php'); 

function delete_data_from_db($table,$column,$target1,$target2,$qty,$message,$flash_msg_desc, $redirect_to) {
	global $db;
	/* getting the user id from the URL */
	if(isset($target1)) {
		$id = intval($target1);
        $qty = intval($qty);

        // getting the product mapped to this id
        $product = get_data_by_field('t_stock', 'drug_name', $target2);

        $new_quantity = $product->quantity + $qty;

        $product_id = $product->id;
		try{
            $query1 = $db->prepare("DELETE FROM $table WHERE $column = ?");
            $query1->execute([$id]);
            
            /** 
             * this query will altering the table in passed in parameter to provide a good ASCENDING ORDER of IDS after deleting an element within the table
             */
            $query2 = $db->query("
            SET @autoid :=0;
            UPDATE $table SET id = @autoid := (@autoid + 1);
            ALTER TABLE $table AUTO_INCREMENT = 1;
            ");
            $query2->closeCursor();
            
            $query3 = $db->prepare("UPDATE t_stock SET quantity = ? WHERE id = ?");
            $query3->execute([$new_quantity, $product_id]);

            $_SESSION[$flash_msg_desc]['success'] =  $message;
            page_redirection($redirect_to);
		}catch(PDOException $error){
			die('An error occurred '.$error->getMessage());
		} 	
	}
};

delete_data_from_db('t_sales','id',$_GET['del_op_id'], $_GET['prod_name'],$_GET['qty'],'Operation has been successfully deleted','flash-sales', '../dashboard/sales.php');