<?php

define('HOST','localhost');
define('DBNAME','pharma_db');
define('USER','root');
define('PASSWORD','');

try{
	$db = new PDO("mysql:host=".HOST.";dbname=".DBNAME,USER,PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	die('Connection error : '.$e->getMessage());
}