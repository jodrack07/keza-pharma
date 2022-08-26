<?php
require_once '../dashboard/fpdf/fpdf.php';
require_once './config/db_connection.php';
require_once './print_intro.php';

$file_name = 'all_users_on_';
$user_list = print_intro('LIST OF ALL USERS');

$user_list->Cell(8,8,"#",1,0,"L");
$user_list->Cell(40,8,"Username",1,0,"L");
$user_list->Cell(76,8,"Email",1,0,"L");
$user_list->Cell(35,8,"User Type",1,0,"L");
$user_list->Cell(30,8,"Registered At",1,1,"L");

$num = 0;

$query = $db->prepare("SELECT * FROM t_staff");
$query->execute();

if($query->rowCount() >= 1) {
    while($user = $query->fetch(PDO::FETCH_OBJ)) {
        $num += 1;
        $user_list->Cell(8,7, $num,1,0,"L");
        $user_list->Cell(40,7, $user->username,1,0,"L");
        $user_list->Cell(76,7, $user->email,1,0,"L");
        $user_list->Cell(35,7, $user->type,1,0,"L");
        $user_list->Cell(30,7, explode(" ",$user->created_at)[0],1,1,"L");
    }
}
else{
    $_SESSION['flash-user']['danger'] = 'NO AVAILABLE REPORT RELATED TO THIS REQUEST';
    header('Location:../dashboard/users.php');
}

$user_list->Cell(50,2,"",0,1);

$user_list->Cell(180,10,"Signature",0,0,"L");

$user_list->Cell(50,10,"",0,1);

$user_list->output("./reports/users/".$file_name."".date("Y-d-m").".pdf","F");

$user_list->output();

?>