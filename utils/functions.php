<?php 
require_once 'config/db_connection.php';


// start
function start_session() {
	if(session_status() == PHP_SESSION_NONE) {
		session_start();
	}
}

function countData($table,$column,$operator,$value) {
	global $db;
	$query = $db->prepare("SELECT COUNT($column) FROM $table WHERE $column $operator ?");
	$query->execute([$value]);
	$data = $query->fetch();


	if($query->rowCount() > 0) {
		foreach ($data as $result) {
			return $result;
		}
	}
}

function dashboard_count($table) {
	global $db;
	$query = $db->prepare("SELECT COUNT(id) AS i FROM $table");
	$query->execute();
	$count_data = $query->fetch();
	return $count_data['i'];
}

function fetch_stock_data() {
	global $db;
    $query = $db->query("SELECT st.id, st.drug_name, st.manufacturer, st.batch_no, st.production_date,
    st.expiry_date, st.registered_date, st.quantity, st.unity_price, st.cost_price, s.username AS 
    staff FROM t_stock AS st INNER JOIN t_staff AS s WHERE st.entered_by = s.id");
    $response = $query->fetchAll(PDO::FETCH_OBJ);
	return $response;
}

function fetch_sales_data() {
	global $db;
    $query = $db->query("SELECT sl.id, sl.customer_name, sl.customer_phone, sl.product, sl.unity_price, sl.quantity,
    sl.sale_amount, sl.dosage, sl.date_and_time, sl.done_by, s.username AS 
    staff FROM t_sales AS sl INNER JOIN t_staff AS s WHERE sl.done_by = s.id");
    $response = $query->fetchAll(PDO::FETCH_OBJ);
	return $response;
}

function fetch_user_data() {
	global $db;
	$query = $db->query("SELECT * FROM t_staff");
	$response = $query->fetchAll(PDO::FETCH_OBJ);
	return $response;
}


/*function to redict to an other page*/
function page_redirection($path) {
	header("Location: $path");
}

function field_not_empty($fields) {
	if(count($fields) != 0) {
		foreach ($fields as $field) {
			/*if empty field or if after removing spaces and the field is empty, then return false*/
			if(empty($_POST[$field]) || trim($_POST[$field]) == "") {
				return false;
			}
		}
		/*if after looping over all the field and don't find an empty field then return true*/
		return true;
	}
}

/*this functions secure fields for different type of codes injection*/
function check_field($field) {
	return htmlspecialchars(htmlentities(strip_tags(stripslashes($field))));
}

function select_all_query($id,$table) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE id=?");
	$query->execute([$id]);
	return current($query->fetchAll(PDO::FETCH_OBJ));
}

function checking_data_availability($get_id,$table) {
	if(isset($get_id)) {
        $id = intval($get_id);
        /*checking whether the id is presented within our database to allow data update*/
        $count_id = count_data($table,$id);
        if($count_id > 0) {
            /*selecting all data*/
            $data = select_all_query($id,$table);
        }
        return $data;
    }   
}

function page_access_restriction($redirect_to) {
	if(!isset($_SESSION['id'])) {
		header("Location: $redirect_to");

		/*creating a session variable that will be holding the error message of escaping access right*/
		$_SESSION['not_loged_in'] = "Please, Login to access this page";
		/*close the execution of the script*/
		exit();
	}
}

function check_data_duplication($table,$column,$value) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE $column = ?");
	$query->execute([$value]);
	/*counting the result*/
	$data_count = $query->rowCount();
	$query->closeCursor();
	/*returning the value, 1 or more : data exist, 0 : data not exist*/
	return $data_count;
}

/*this function count and return the number of rows within a table*/
function count_data($table,$value) {
	global $db;
	$query = $db->prepare("SELECT id FROM $table WHERE id = ?");
	$query->execute([$value]);
	$query->closeCursor();

	return $query->rowCount();

}

function select_all_data($table) {
    global $db;
    $query = $db->query("SELECT * FROM $table");
    $rows = $query->rowCount();

    if($rows > 0) {
      $rows = $query->fetchAll(PDO::FETCH_OBJ);
      return $rows;
    }
    else{
        return 'No_available_data';
    }
}

function select_data_greater_than_n_and_not_expired($table,$field ,$n) {
    global $db;
	$today_date = date('Y-m-d');
    $query = $db->prepare("SELECT * FROM $table WHERE $field > $n AND expiry_date > ?");
	$query->execute([$today_date]);
    $rows = $query->rowCount();

    if($rows > 0) {
      $rows = $query->fetchAll(PDO::FETCH_OBJ);
      return $rows;
    }
    else{
        return 'No_available_data';
    }
}

function get_data_by_field($table, $field, $value) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE $field = ?");
	$query->execute([$value]);
	return current($query->fetchAll(PDO::FETCH_OBJ));
}

function count_data_by_field($table, $field, $operator ,$value) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE $field $operator ?");
	$query->execute([$value]);
	return $query->rowCount();
}


function active_menu($target) {
	$page = array_pop(explode('/', $_SERVER['SCRIPT_NAME']));

	if($page == $target) {
		return 'active';
	}else{
		return '';
	}
}

function select_field($table, $field) {
	global $db;
	$query = $db->query("SELECT $field FROM $table");
	return $query;
}

function input_field_verif($regex,$field) {
	$value = preg_match($regex, $field);
	return $value;	
}

// $date1 = "2022-07-31";
// $date2 = "2022-08-5";

function get_remaining_days_between_2_dates($date1, $date2)
{
    $date1_ts = strtotime($date1);
    $date2_ts = strtotime($date2);
    $diff = $date2_ts - $date1_ts;
    return round($diff / 86400);
}

// end









































function simple_color_notification($quantity_in_db, $comparative_qty) {
	if($quantity_in_db < $comparative_qty) {
		return 'danger';
	}else{
		return 'success';
	}
}



function status_badge($status) {
	if($status == 'active') {
		return 'success';
	}
	else{
		return 'primary';
	}
}

function connectivity_status($con_status) {
	if($con_status  == 1) {
		return 'online';
	}
	else{
		return 'offline';
	}
}

function editOrderInvoice($user_id,$first_name,$last_name,$phone,$address,$sub_total,$taxe,$discount,$net_total,$paid_amount,$due_amount,$prod_names,$total_qty,$qty,$price){
	global $db;
}


function storeOrderInvoice($user_id,$first_name,$last_name,$phone,$address,$sub_total,$taxe,$discount,$net_total,$paid_amount,$due_amount,$prod_names,$total_qty,$qty,$price){

	/**
	 * inserting data related to the customer in the t_customers data, BUT this will happen if and only iff this customers is not yet get registered in our DB
	 */
	global $db;

	/**
	 * users with the same PHONE NUMBER will not be duplicate in the 't_customers' table
	 */
	// if(isset()) {}
	$is_customer = check_data_duplication('t_customers','phone',$phone);
	
	if($is_customer < 1) {
		$query = $db->prepare("INSERT INTO t_customers SET first_name = ?, last_name = ?, address = ?, 	phone = ?, user_id = ?, registration_date = NOW()");
		$query->execute([$first_name,$last_name,$address,$phone,$user_id]);
	}
	/**
	 * inserting the order overview in the database and generate a unique ID for each invoice
	 */
	$invoice_num = uniqid();
	$customer_full_name = $first_name . ' '. $last_name;
	$query = $db->prepare("INSERT INTO t_orders SET invoice_no = ?, customer_name = ?, customer_phone = ?, order_sub_total = ?, order_total_taxe = ?, order_total_reductions = ?, paid_amount = ?, due_amount = ?, order_net_amount = ?, user_id = ?, order_datetime = NOW()");
	$query->execute([$invoice_num,$customer_full_name,$phone,$sub_total,$taxe,$discount,$paid_amount,$due_amount,$net_total,$user_id]);

	/**
	 * let's retrieve the ID of the last inserted Invoice to be the reference to all products that appear on it
	 */
	$query = $db->query("SELECT LAST_INSERT_ID()");
 	$order_id  = $query->fetchColumn();
	
	 if($order_id != null) {
		 for($i = 0; $i < count($qty); $i++) {
			 //getting the remaining quantity after a given order operation
			 $actual_quantity = $total_qty[$i] - $qty[$i];

			 if($actual_quantity <= 0) {
				 return 'CANNOT_PROCESS_THIS_OPERATION';
			 }
			 else {
				 //updating the product quantity after order
				 $query = $db->prepare("UPDATE t_products SET quantity = ? WHERE name = ?");
				 $query->execute([$actual_quantity,$prod_names[$i]]);
			 }
			$order_item_final_amount = $price[$i] * $qty[$i];
			$req = $db->prepare("INSERT INTO t_order_item_details SET order_id = ?, item_name = ?, order_item_quantity = ?, order_item_price = ?, order_item_final_amount = ?");
			$req->execute([$order_id, $prod_names[$i], $qty[$i], $price[$i],$order_item_final_amount]);
		 }
	 }

	return $order_id;

}

function insert_data_in_select_box($table, $column,$target,$value) {
	global $db;
	$output = '';
	$query = $db->prepare("SELECT $column FROM $table WHERE $target = ? ORDER BY $column ASC");
	$query->execute([$value]);
	$rows = $query->fetchAll();

	foreach ($rows as $row) {
		$output .= '<option value="'.$row[$column].'">'.$row[$column].'</option>';
		}
		return $output;         	
}


function select_data_query($id,$table) {
    global $db;
    $query = $db->prepare("SELECT name FROM $table WHERE id = ?");
	$query->execute([$id]);
    $rows = $query->rowCount();

    if($rows > 0) {
      $rows = $query->fetchAll(PDO::FETCH_OBJ);
      foreach($rows as $row) {
		  return $row;
	  }
    }
}

function details_about_orders() {
	global $db;
	$query = $db->prepare("SELECT o.id, o.invoice_no, o.customer_name, o.customer_phone,o.order_datetime, od.order_id, od.item_name, od.order_item_quantity, od.order_item_price, od.order_item_final_amount FROM t_orders AS o INNER JOIN t_order_item_details AS od WHERE o.id = od.order_id");
	$query->execute();
	return $query->fetchAll(PDO::FETCH_OBJ);
}

function get_orders_data($date="", $session_id="") { 
	global $db;
	$query = "";
	if($date != "") {
		$statement = "SELECT * FROM t_orders WHERE order_datetime = ?";
    	$query = $db->prepare($statement);
    	$query->execute([$date]);
	}
	/**
	 * changing this in the near future to make it make great sense.
	 */
	elseif($session_id == 1) {
		$statement = "SELECT * FROM t_orders";
		$query = $db->prepare($statement);
		$query->execute();
	}
	else{
		$statement = "SELECT * FROM t_orders WHERE user_id = ?";
    	$query = $db->prepare($statement);
    	$query->execute([$session_id]);
	}

    $result = $query->rowCount();

    if($result > 0) {
      $rows = $query->fetchAll(PDO::FETCH_OBJ);

      return $rows;
    }
    else{
      echo '
      <div class="alert alert-primary alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            <div class="alert-message">
              <h4>No Available Orders Data</h4>
            </div>
      </div>
         '; 
    }
}

function getDailyOverview($table,$column,$target,$value,$sql='SUM') {
	global $db;
	$query = $db->prepare("SELECT $sql($column) FROM $table WHERE $target = ?");
	$query->execute([$value]);
	$data = $query->fetch();

	if($query->rowCount() > 0) {
		foreach ($data as $result) {
			return $result;
		}
	}
}

function dailyCountData($table,$column,$target,$value) {
	global $db;
	$query = $db->prepare("SELECT COUNT($column) FROM $table WHERE $target = ?");
	$query->execute([$value]);
	$data = $query->fetch();

	if($query->rowCount() > 0) {
		foreach ($data as $result) {
			return $result;
		}
	}
}

function get_product_name($order_id) {
	global $db;

	$query = $db->prepare('SELECT * FROM t_order_item_details WHERE order_id = ?');
	$query->execute([$order_id]);
	$prod_infos = $query->fetchAll(PDO::FETCH_OBJ);
	return $prod_infos;
}


function getAllUsers() {
	global $db;

	$query = $db->prepare("SELECT * FROM t_users");
	$query->execute();

	if($query->rowCount() > 0) {
		$users = $query->fetchAll();
	}
	return $users;
}

// echo ini_get('post_max_size');

function check() {
	global $db;
	$q = $db->prepare('SELECT p.entered_by, u.id FROM t_produc');
}

//alerting the use when the user when the stock quantity is under 5 items
function notifications_count($table,$column,$operator,$value) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE $column $operator $value");
	$query->execute();
	return $query->rowCount(); 
}

function stock_notif_display() {
	global $db;
	$query = $db->prepare('SELECT * FROM t_products WHERE quantity < 5');
	$query->execute();
	return $query->fetchAll(PDO::FETCH_OBJ);
	}

function notif_counter($table, $column, $value) {
	global $db;
	$query = $db->prepare("SELECT * FROM $table WHERE $column > $value");
	$query->execute();
	return $query->rowCount();
}

function get_expt_day() {
	global $db;
	$query = $db->prepare('SELECT name,quantity,manfact_date,expire_date FROM t_products');
	$query->execute();
	$results = $query->fetchAll(PDO::FETCH_OBJ);
	return $results;
}

// echo notifications_count('t_orders','due_amount','>',0);
function get_ordered_qty($prod_name) {
	global $db;
	$query = $db->prepare("SELECT item_name, order_item_quantity FROM t_order_item_details WHERE item_name = ?");
	$query->execute([$prod_name]);
	$quantities = $query->fetchAll(PDO::FETCH_OBJ);
	foreach($quantities as $quantity) {
		return $quantity->order_item_quantity;
	}
}

function get_prod_id($prod_name) {
	global $db;
	$query = $db->prepare("SELECT name, id FROM t_products WHERE name = ?");
	$query->execute([$prod_name]);
	$ids = $query->fetchAll(PDO::FETCH_OBJ);
	foreach($ids as $id) {
		return $id->id;
	}
}


?>