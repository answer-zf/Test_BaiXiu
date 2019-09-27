<?php 

  require_once '../functions.php';
  zf_get_current_user();

  function add_users(){
    if (empty($_POST['email'])) {
      $GLOBALS['message'] = '请输入email';
      return;
    }
    if (empty($_POST['slug'])) {
      $GLOBALS['message'] = '请输入slug';
      return;
    }
    if (empty($_POST['nickname'])) {
      $GLOBALS['message'] = '请输入nickname';
      return;
    }
    if (empty($_POST['password'])) {
      $GLOBALS['message'] = '请输入password';
      return;
    }
    $email = $_POST['email'];
    $slug = $_POST['slug'];
    $nickname = $_POST['nickname'];
    $password = md5($_POST['password']);
    if(!strpos($email,'@') || !strpos($email,'.')) {
      $GLOBALS['message'] = '请输入正确的email';
      return;
    }
    $add_mysql ="insert into users values (null,'{$slug}','{$email}','{$password}','{$nickname}','/static/assets/img/default.png',null,'unactivated')";

    // $add_mysql ="insert into users(slug,email,`password`,nickname,avatar,`status`) values ('{$slug}','{$email}','{$password}','{$nickname}','/static/assets/img/default.png','unactivated')";
    //var_dump($add_mysql);
    $add_row = zf_execute($add_mysql);
    // var_dump($add_row);
    // var_dump($email);
    $GLOBALS['success'] = $add_row > 0;
    $GLOBALS['message'] = $add_row <= 0 ? '添加失败' : '添加成功';
  }
  function edit_users(){
    if (empty($_POST['password'])) {
      $GLOBALS['message'] = '请输入password';
      return;
    }
    global $users_edit_id;
    $edit_id=$users_edit_id['id'];
    $email = empty($_POST['email']) ? $users_edit_id['email'] : $_POST['email'];
    $users_edit_id['email'] = $email;
    $slug = empty($_POST['slug']) ? $users_edit_id['slug'] : $_POST['slug'];
    $users_edit_id['slug'] = $slug;
    $nickname = empty($_POST['nickname']) ? $users_edit_id['nickname'] : $_POST['nickname'];
    $users_edit_id['nickname'] = $nickname;
    $password = md5($_POST['password']);
    if(!strpos($email,'@') || !strpos($email,'.')) {
      $GLOBALS['message'] = '请输入正确的email';
      return;
    }

    $edit_mysql ="update users set slug = '{$slug}', email = '{$email}', `password` = '{$password}' , nickname = '{$nickname}' , avatar = '/static/assets/img/default.png', `status` = 'unactivated' where id = {$edit_id}";
    var_dump($edit_mysql);
    $edit_row = zf_execute($edit_mysql);

    $GLOBALS['success'] = $edit_row > 0;
    $GLOBALS['message'] = $edit_row <= 0 ? '保存失败' : '保存成功';
  }
  if (empty($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      add_users();
    }  

  }else{
    $users_edit_id = zf_fetch_one("select * from users where id = {$_GET['id']};");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      edit_users();
    }
  }

  $users = zf_fetch_all("select * from users;");

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php' ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($success)): ?>
          <div class="alert alert-success">
          <strong>成功！</strong><?php echo $message ?>
        </div>  
      <?php else: ?>
        <?php if (isset($message)): ?>
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $message ?>
          </div>            
        <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <?php if (isset($_GET['id'])): ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $_GET['id'] ?>" method="post" novalidate>
              <h2>编辑《 <?php echo $users_edit_id['nickname'] ?> 》</h2>
              <div class="form-group">
                <label for="email">邮箱</label>
                <input id="email" class="form-control" name="email" value="<?php echo $users_edit_id['email'] ?>" type="email" placeholder="邮箱">
              </div>
              <div class="form-group">
                <label for="slug">别名</label>
                <input id="slug" class="form-control" name="slug" value="<?php echo $users_edit_id['slug'] ?>" type="text" placeholder="slug">
                <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
              </div>
              <div class="form-group">
                <label for="nickname">昵称</label>
                <input id="nickname" class="form-control" name="nickname" value="<?php echo $users_edit_id['nickname'] ?>" type="text" placeholder="昵称">
              </div>
              <div class="form-group">
                <label for="password">密码</label>
                <input id="password" class="form-control" name="password" type="password" placeholder="密码">
              </div>
              <div class="form-group">
                <button class="btn btn-primary" type="submit">保存</button>
              </div>
            </form>            
          <?php else: ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate>
              <h2>添加新用户</h2>
              <div class="form-group">
                <label for="email">邮箱</label>
                <input id="email" class="form-control" name="email" type="email" placeholder="邮箱">
              </div>
              <div class="form-group">
                <label for="slug">别名</label>
                <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
                <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
              </div>
              <div class="form-group">
                <label for="nickname">昵称</label>
                <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称">
              </div>
              <div class="form-group">
                <label for="password">密码</label>
                <input id="password" class="form-control" name="password" type="password" placeholder="密码">
              </div>
              <div class="form-group">
                <button class="btn btn-primary" type="submit">添加</button>
              </div>
            </form>            
          <?php endif ?>

        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="/admin/users_delete.php" style="display: none" id="users_all_delete_btn">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead id="users_thead">
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody id="users_tbody">
              <?php foreach ($users as $row): ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-users-id="<?php echo $row['id'] ?>"></td>
                  <td class="text-center"><img class="avatar" src="<?php echo $row['avatar'] ?>"></td>
                  <td><?php echo $row['email'] ?></td>
                  <td><?php echo $row['slug'] ?></td>
                  <td><?php echo $row['nickname'] ?></td>
                  <td><?php echo $row['status'] ?></td>
                  <td class="text-center">
                    <a href="/admin/users.php?id=<?php echo $row['id'] ?>" class="btn btn-default btn-xs">编辑</a>
                    <a href="/admin/users_delete.php?id=<?php echo $row['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>                
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $current_page = 'users' ?>
  <?php include 'inc/aside.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>

    $(function($){
      var $users_checkbox = $("#users_tbody input[type='checkbox']")
      var $users_checkbox_all = $("#users_thead input[type='checkbox']")
      var $users_all_delete_btn = $('#users_all_delete_btn')
      var users_id = []
      // console.log($users_all_delete_btn.html())
      $users_checkbox.on('change',function(){
        
        var data_users_id = $(this).data('usersId')
        if ($(this).prop('checked')) {
          users_id.push(data_users_id)
        }else{
          users_id.splice(users_id.indexOf(data_users_id),1)
        }
        //console.log(users_id.length)
        users_id.length ? $users_all_delete_btn.fadeIn() : $users_all_delete_btn.fadeOut()
        $users_all_delete_btn.prop('search',`?id=${users_id}`) 
      })
      $users_checkbox_all.on('change',function(){
        var users_checked = $(this).prop('checked')
        $users_checkbox.prop('checked', users_checked).trigger('change')
      })
    })

  </script>
</body>
</html>
