<?php 
  require_once '../functions.php';

  zf_get_current_user();// 登录限制

  // 添加类别
  function add_category(){
    if (empty($_POST['name'])) {
      $GLOBALS['message'] = '请填写类别名称';
      return;
    }
    if (empty($_POST['slug'])) {
      $GLOBALS['message'] = '请填写slug名称';
      return;
    }

    $name = $_POST['name'];
    
    $slug = $_POST['slug'];
    
    //$create_row = zf_execute("insert into categories (slug,`name`) value ('{$slug}','{$name}')");
    $create_row = zf_execute("insert into categories values (null,'{$slug}','{$name}')");

    //var_dump(zf_execute($create_row));
    $GLOBALS['success'] = $create_row > 0 ;
    $GLOBALS['message'] = $create_row<= 0 ? '添加失败！' : '添加成功';

    // header('Location: http://baixiu.zf/admin/categories.php');
  } 
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add_category();
  }  


  // 先修改数据再获取

  $categories = zf_fetch_all("select * from categories;");
  
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <?php if (isset($success)&&$success): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $message ?>
          </div>  
        <?php else: ?>
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $message ?>
          </div>  
        <?php endif ?>    
      <?php endif ?>

      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $row): ?>
                <tr>
                  <td class="text-center"><input type="checkbox"></td>
                  <td><?php echo $row['name'] ?></td>
                  <td><?php echo $row['slug'] ?></td>
                  <td class="text-center">
                    <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                    <a href="/admin/category_delete.php?id=<?php echo $row['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>                
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <?php $current_page = 'categories' ?>
  <?php include 'inc/aside.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
