<?php 
require_once '../functions.php';
if (empty($_GET['id'])) {
	exit('参数错误');
}
$id = $_GET['id'];
$delete = zf_execute("delete from users where id in ({$id})");
header('Location: /admin/users.php');
?>
