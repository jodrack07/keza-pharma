<?php
require_once '../dashboard/fpdf/fpdf.php';
require_once './config/db_connection.php';
require_once './print_intro.php';

$file_name = 'all_items_on_';
$title = 'LIST OF ALL PRODUCTS IN THE STOCK';

$stock_list = new FPDF();
$stock_list->AddPage("L", ['335', '120']);
$stock_list->setFont("Arial",null,12);

$date = date("Y-d-m");

$stock_list->Cell(315,10,"LA GLOIRE PHARMA",1,1,"C");
$stock_list->Cell(50,4,"",0,1);
$stock_list->Cell(50,5,"Ets Address",0,0);
$stock_list->Cell(70,5,": DRC, North-Kivu, Goma, 555 Birere",0,1);
$stock_list->Cell(50,5,"Ets Phone Number",0,0);
$stock_list->Cell(70,5,": +24399599345, +24385443344",0,1);
$stock_list->Cell(50,5,"Ets Electronic Address",0,0);
$stock_list->Cell(70,5,": lagloirepharma@gmail.com",0,1);
$stock_list->Cell(50,1,"",0,1);

$stock_list->Cell(50,1,"",0,1);
$stock_list->Cell(315,10,$title,1,1,"C");

$stock_list->Cell(8,8,"#",1,0,"L");
$stock_list->Cell(60,8,"Prod. Name",1,0,"L");
$stock_list->Cell(50,8,"Manifacturer.",1,0,"L");
$stock_list->Cell(37,8,"Batch No.",1,0,"L");
$stock_list->Cell(25,8,"Prod. Date",1,0,"L");
$stock_list->Cell(25,8,"Exp. Date",1,0,"L");
$stock_list->Cell(25,8,"Reg. Date",1,0,"L");
$stock_list->Cell(25,8,"Quantity",1,0,"L");
$stock_list->Cell(30,8,"Unity Price",1,0,"L");
$stock_list->Cell(30,8,"Cost Price",1,1,"L");

$num = 0;

$query = $db->prepare("SELECT * FROM t_stock");
$query->execute();

if($query->rowCount() >= 1) {
    while($stock = $query->fetch(PDO::FETCH_OBJ)) {
        $num += 1;
        $stock_list->Cell(8,7, $num,1,0,"L");
        $stock_list->Cell(60,7, $stock->drug_name,1,0,"L");
        $stock_list->Cell(50,7, $stock->manufacturer,1,0,"L");
        $stock_list->Cell(37,7, $stock->batch_no,1,0,"L");
        $stock_list->Cell(25,7, explode(" ",$stock->production_date)[0],1,0,"L");
        
        $stock_list->Cell(25,7, explode(" ",$stock->expiry_date)[0],1,0,"L");
        $stock_list->Cell(25,7, explode(" ",$stock->registered_date)[0],1,0,"L");
        $stock_list->Cell(25,7, $stock->quantity,1,0,"L");
        $stock_list->Cell(30,7, $stock->unity_price,1,0,"L");
        $stock_list->Cell(30,7, $stock->cost_price,1,1,"L");
    }
}
else{
    $_SESSION['flash-stock']['danger'] = 'NO AVAILABLE REPORT RELATED TO THIS REQUEST';
    header('Location:../dashboard/stock.php');
}

$stock_list->Cell(50,2,"",0,1);

$stock_list->Cell(180,10,"Signature",0,0,"L");

$stock_list->Cell(50,10,"",0,1);

$stock_list->output("./reports/stock/".$file_name."".date("Y-d-m").".pdf","F");

$stock_list->output();

?>