 <?php 

// 引入配置信息
require_once '../config.php';

// 启动session
session_start();

function login(){

  if (empty($_POST['email'])) {
    $GLOBALS['error_message'] = '请输入邮箱';
    return;
  }

  if (empty($_POST['password'])) {
    $GLOBALS['error_message'] = '请输入密码';
    return;
  }

  $email = $_POST['email'];
  $password = $_POST['password'];

  $connect = mysqli_connect(ZF_DB_HOST, ZF_DB_USER, ZF_DB_PASSWORD, ZF_DB_NAME);
  
  if (!$connect) {
    exit('<h1>连接数据库失败</h1>');
  } 

  $query = mysqli_query($connect, "select * from users where email = '{$email}' limit 1;");
  
  if (!$query) {
    $GLOBALS['error_message'] = '登录失败请稍后再试';
    return;
  } 

  $verify = mysqli_fetch_assoc($query);

  if (!$verify){
    $GLOBALS['error_message'] = '用户名密码错误';
    return;
  }

  if ($verify['password'] !== md5($password)){
    $GLOBALS['error_message'] = '用户名密码错误';
    return;
  }

  $_SESSION['current_login_user'] = $verify;

  header('Location: /admin/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
  unset($_SESSION['current_login_user']);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="/static/assets/vendors/animate/animate.min.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" type="text/css" href="/static/assets/vendors/nprogress/nprogress.css">

</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($error_message) ? ' shake animated' : '' ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate autocomplete="off">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->

        <?php if (isset($error_message)): ?>
          <div class="alert alert-danger">          
              <strong>错误！</strong> <?php echo $error_message ?>
          </div>           
        <?php endif ?>
        

      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>

  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <!-- <script src="/static/assets/vendors/nprogress/nprogress.js"></script> -->
  <script type="text/javascript">
    $(function($){

      var reg = /^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/      

      $('#email').on('blur', function(){

        var value = $(this).val()

        if (!value || !reg.test(value) ) return

          $.get('/admin/api/avatar.php', { callback: value }, function(res){
            
            if(!res) return
            // $('.avatar').attr('src', res)
            $('.avatar').fadeOut(function() {
              $(this).on('load', function () {
                $(this).fadeIn()
              }).attr('src', res)
            })
        })
      })
    }) 
  </script>
</body>
</html>
 