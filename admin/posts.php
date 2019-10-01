<?php 
  require_once '../functions.php';
  zf_get_current_user();
  $page = empty($_GET['page']) ? '1' : (int)$_GET['page'];
  $size = 20;
  $offet = ($page - 1) * $size;
  $posts = zf_fetch_all("
    select 
      posts.id,
      posts.title,
      users.nickname as users_name,
      categories.`name` as category_name,
      posts.created,
      posts.`status`
    from posts
    inner join categories on posts.category_id = categories.id
    inner join users on posts.user_id = users.id
    order by posts.created desc
    limit {$offet},{$size}
  ");
  /**
   * 封装状态的中英文转换
   * @param  [string] $status [英文状态]
   * @return [string]         [中文状态]
   */
  function convert_status($status){
    $posts_status = array(
      'drafted' => '草稿', 
      'published' => '已发布', 
      'trashed' => '回收站'
    );
    return isset($posts_status[$status]) ? $posts_status[$status] : "未知";
  }
  /**
   * 封装自定义时间
   * @param  [string] $created [时间格式]
   * @return [string]          [自定义时间格式]
   */
  function convert_date($created){
    $timestamp = strtotime($created);
    return date('Y年m月d日<b\r>H:i:s', $timestamp);
  }

?> 
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $row): ?>
            <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $row['title'] ?></td>
              <td><?php echo $row['users_name'] ?></td>
              <td><?php echo $row['category_name'] ?></td>
              <td class="text-center"><?php echo convert_date($row['created']) ?></td>
              <td class="text-center"><?php echo convert_status($row['status']) ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>            
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php $current_page = 'posts' ?>
  <?php include 'inc/aside.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
