<?php
require_once '../dashboard/fpdf/fpdf.php';
require_once './config/db_connection.php';
require_once './print_intro.php';

$file_name = 'all_sales_on_';
$title = 'LIST OF ALL SALES';

$sales_list = new FPDF();
$sales_list->AddPage("L", ['335', '120']);
$sales_list->setFont("Arial",null,12);

$date = date("Y-d-m");

$sales_list->Cell(315,10,"LA GLOIRE PHARMA",1,1,"C");
$sales_list->Cell(50,4,"",0,1);
$sales_list->Cell(50,5,"Ets Address",0,0);
$sales_list->Cell(70,5,": DRC, North-Kivu, Goma, 555 Birere",0,1);
$sales_list->Cell(50,5,"Ets Phone Number",0,0);
$sales_list->Cell(70,5,": +24399599345, +24385443344",0,1);
$sales_list->Cell(50,5,"Ets Electronic Address",0,0);
$sales_list->Cell(70,5,": lagloirepharma@gmail.com",0,1);
$sales_list->Cell(50,1,"",0,1);

$sales_list->Cell(50,1,"",0,1);
$sales_list->Cell(315,10,$title,1,1,"C");

$sales_list->Cell(8,8,"#",1,0,"L");
$sales_list->Cell(50,8,"Customer Name",1,0,"L");
$sales_list->Cell(33,8,"Cust. Phone",1,0,"L");
$sales_list->Cell(60,8,"Product Name",1,0,"L");
$sales_list->Cell(24,8,"Unity Price",1,0,"L");
$sales_list->Cell(25,8,"Quantity",1,0,"L");
$sales_list->Cell(30,8,"Sale Amount",1,0,"L");
$sales_list->Cell(52,8,"Dosage",1,0,"L");
$sales_list->Cell(33,8,"Operation Date",1,1,"L");

$num = 0;

$query = $db->prepare("SELECT * FROM t_sales");
$query->execute();

if($query->rowCount() >= 1) {
    while($sale = $query->fetch(PDO::FETCH_OBJ)) {
        $num += 1;
        $sales_list->Cell(8,7, $num,1,0,"L");
        $sales_list->Cell(50,7, $sale->customer_name,1,0,"L");
        $sales_list->Cell(33,7, $sale->customer_phone,1,0,"L");
        $sales_list->Cell(60,7, $sale->product,1,0,"L");
        $sales_list->Cell(24,7, $sale->unity_price,1,0,"L");
        $sales_list->Cell(25,7, $sale->quantity,1,0,"L");
        $sales_list->Cell(30,7, $sale->sale_amount,1,0,"L");
        $sales_list->Cell(52,7, $sale->dosage,1,0,"L");
        $sales_list->Cell(33,7, explode(" ",$sale->date_and_time)[0],1,1,"L");
    }
}
else{
    $_SESSION['flash-stock']['danger'] = 'NO AVAILABLE REPORT RELATED TO THIS REQUEST';
    header('Location:../dashboard/stock.php');
}

$sales_list->Cell(50,2,"",0,1);

$sales_list->Cell(180,10,"Signature",0,0,"L");

$sales_list->Cell(50,10,"",0,1);

$sales_list->output("./reports/sales/".$file_name."".date("Y-d-m").".pdf","F");

$sales_list->output();

?>