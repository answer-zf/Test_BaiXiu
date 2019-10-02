<?php

require_once "../functions.php";

if(empty($_GET['id'])){
	exit('缺少必要参数');
}

$id = $_GET['id'];

//delete from categories where id = 105;
$delete = zf_execute("delete from posts where id in ({$id});");

// if($delete<=0){
// 	exit('<h1>删除失败</h1>');
// }
header('Location: ' . $_SERVER['HTTP_REFERER']);