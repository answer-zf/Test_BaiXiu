<?php

require_once "../../functions.php";

if(empty($_GET['id'])){
	exit('缺少必要参数');
}

$id = $_GET['id'];

$delete = zf_execute("delete from comments where id in ({$id});");

// header('Location: /admin/comments.php');
header('Content-Type: application/json');
echo json_encode($delete > 0 );