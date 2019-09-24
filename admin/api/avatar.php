<?php

require_once('../../config.php');

if (empty($_GET['callback'])) {
	return;
}
$user_avatar = $_GET['callback'];
$connect = mysqli_connect(ZF_DB_HOST,ZF_DB_USER,ZF_DB_PASSWORD,ZF_DB_NAME);
if (!$connect) return;
// $query = mysqli_query($connect, "select * from users where email = '{$user_avatar}' limit 1");
$query = mysqli_query($connect, "select avatar from users where email = '{$user_avatar}' limit 1");
if (!$query) return;
$avatar = mysqli_fetch_assoc($query)['avatar'];
echo $avatar;


