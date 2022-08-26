<?php

function print_intro($title) {
    $user_list = new FPDF();
    $user_list->AddPage();
    $user_list->setFont("Arial",null,12);

    $date = date("Y-d-m");

    $user_list->Cell(189,10,"LA GLOIRE PHARMA",1,1,"C");
    $user_list->Cell(50,4,"",0,1);
    $user_list->Cell(50,5,"Ets Address",0,0);
    $user_list->Cell(70,5,": DRC, North-Kivu, Goma, 555 Birere",0,1);
    $user_list->Cell(50,5,"Ets Phone Number",0,0);
    $user_list->Cell(70,5,": +243975993, +243828755",0,1);
    $user_list->Cell(50,5,"Ets Electronic Address",0,0);
    $user_list->Cell(70,5,": lagloirepharma@gmail.com",0,1);
    $user_list->Cell(50,1,"",0,1);

    $user_list->Cell(50,1,"",0,1);
    $user_list->Cell(189,10,$title,1,1,"C");

    return $user_list;
}
?>