<?php

require_once 'config.php';

/**
 * 封装公共函数
 * 注意：定义函数名与内置函数冲突
 */

session_start();

/**
 * 获取当前用户信息，没有则返回登录界面 
 */
function zf_get_current_user () {

	if (empty($_SESSION['current_login_user'])) {
      header('Location: /admin/login.php');
      exit();
    }
    return $_SESSION['current_login_user'];

}

/**
 * 查询数据库封装
 */
function zf_fetch_all($sql){

	$connect = mysqli_connect(ZF_DB_HOST,ZF_DB_USER,ZF_DB_PASSWORD,ZF_DB_NAME);

	if (!$connect) exit('<h1>数据库连接失败</h1>');

	$query = mysqli_query($connect, $sql);
	if (!$query){		
		return false;
	}

	while ($row = mysqli_fetch_assoc($query)) {
		$data[] = $row;
	}

	mysqli_free_result($query);
	mysqli_close($connect);
	return $data;
}

function zf_fetch_one($sql){
	$res = zf_fetch_all($sql);
	return isset($res) ? $res['0'] : null;
}


/**
 *  增 删 改 语句的封装
 */
function zf_execute($sql){

	$connect = mysqli_connect(ZF_DB_HOST,ZF_DB_USER,ZF_DB_PASSWORD,ZF_DB_NAME);

	if (!$connect) exit('<h1>数据库连接失败</h1>');

	$query = mysqli_query($connect, $sql);

	if (!$query){		
		return false;
	}

	$affected_rows = mysqli_affected_rows($connect) ;

	return $affected_rows;	
}
