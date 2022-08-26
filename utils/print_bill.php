<?php

session_start();
require_once './config/db_connection.php';
require_once '../dashboard/fpdf/fpdf.php';

function get_bill_data($value) {
	global $db;
	$query = $db->prepare("SELECT * FROM t_sales WHERE id = ?");
	$query->execute([$value]);
	$query->closeCursor();

	$res = $query->rowCount();

    if($res == 1) {
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    else {
        return 0;
    }
}

$result = get_bill_data($_GET['bill_id']);

if($result != 0) {
    if(!empty($_GET['bill_id']) && !empty($_GET['cust_name'])) {
        $bill = new FPDF();
        $bill->AddPage();
        $bill->setFont("Arial",null,12);
        
        $bill->Cell(190,10,"LA GLOIRE PHARMA BILLING",1,1,"C");
        $bill->Cell(50,5,"",0,1);
        $bill->Cell(50,5,"Ets Address",0,0);
        $bill->Cell(70,5,": DRC, North-Kivu, Goma, 555 Birere",0,1);
        $bill->Cell(50,5,"Ets Phone Number",0,0);
        $bill->Cell(70,5,": +243975993, +243828755",0,1);
        $bill->Cell(50,5,"Ets Electronic Address",0,0);
        $bill->Cell(70,5,": lagloirepharma@gmail.com",0,1);
        $bill->Cell(50,5,"Notice for Customer",0,0);
        $bill->Cell(70,5,": The product sold is neither taken back nor exchanged",0,1);
        $bill->Cell(50,5,"",0,1);
        $bill->Cell(50,5,"CustomerFirst Names",0,0);
        $bill->Cell(50,5,": ".$_GET['cust_name'],0,1);
        $bill->Cell(50,5,"Customer Phone",0,0);
        $bill->Cell(50,5,": ".$_GET["cust_phone"],0,1);

        $bill->Cell(50,3,"",0,1);

        $bill->Cell(60,7,"Product Name",1,0,"L");
        $bill->Cell(20,7,"Qty",1,0,"C");
        $bill->Cell(20,7,"UP",1,0,"C");
        $bill->Cell(30,7,"Total",1,0,"C");
        $bill->cell(60,7,"Dosage", 1,1,"L");

        $query = $db->prepare("SELECT * FROM t_sales WHERE id=?");
        $query->execute([$_GET['bill_id']]);
        
        while ($data = $query->fetch(PDO::FETCH_OBJ)) {
            $bill->Cell(60,8,$data->product,1,0,"L");
            $bill->Cell(20,8,$data->quantity,1,0,"C");
            $bill->Cell(20,8,'$'.$data->unity_price,1,0,"C");
            $bill->Cell(30,8,'$'.$data->sale_amount,1,0,"C");
            $bill->Cell(60,8,$data->dosage,1,1,"L");
        }

        $bill->Cell(50,2,"",0,1);
    	$bill->Cell(180,10,"Client Signature",0,0,"L");
    	$bill->Cell(10,10,"Seller Signature",0,1,"R");

    	$bill->Cell(50,10,"",0,1);

    	$bill->output("./reports/bills/bill_on_".date("Y-d-m").".pdf","F");

        $bill->output();
    }else {
        $_SESSION['flash-sales']['danger'] = 'The Bill ID and the customer name are required';
        header('location:../dashboard/sales.php');
    }
}else {
    $_SESSION['flash-sales']['danger'] = 'Bill not found. Make sure you refer an existing bill.';
    header('location:../dashboard/sales.php');
}

?>