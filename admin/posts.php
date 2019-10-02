<?php 
  // 登录限制
  require_once '../functions.php'; 
  zf_get_current_user();

  $where = '1 = 1';
  $search = '';    // search用来记录 筛选后的数据 ，为分页保留数据

  if (isset($_GET['category']) && $_GET['category'] !== 'all') {
    $where .= ' and posts.category_id='.$_GET['category'];
    $search .= '&category='.$_GET['category'];
  }

  if (isset($_GET['status']) && $_GET['status'] !== 'all') {
    $where .= " and posts.status = '{$_GET['status']}'";
    $search .= '&status='.$_GET['status'];
  }

  // 总页数参数  
  $size = 20;
  $page = empty($_GET['page']) ? 1 : (int)$_GET['page'];

  if ($page < 1) {
    header('Location: /admin/posts.php?page=1' . $search);
  }

  $total_count = (int)zf_fetch_one("
    select 
      count(1) as num
    from posts
    inner join categories on posts.category_id = categories.id
    inner join users on posts.user_id = users.id
    where {$where};
  ")['num'];
  $total_page = (int)ceil($total_count / $size);

  if ($page > $total_page){
    header('Location: /admin/posts.php?page=' . $total_page . $search);
  }

  // 分页查询数据、

  $offset = ($page - 1) * $size;  
  $posts_list = zf_fetch_all(" 
    select 
      posts.id,
      posts.title,
      users.nickname as user_name,
      categories.`name` as category_name,
      posts.created,
      posts.`status`
    from posts
    inner join categories on posts.category_id = categories.id
    inner join users on posts.user_id = users.id
    where {$where}
    order by created desc
    limit {$offset},{$size}
  ");

  // 分页逻辑
  $visible = 5;

  $begin = $page - ($visible - 1) / 2;
  $end = $begin + $visible - 1;

  $begin = $begin < 1 ? 1 : $begin;
  $end = $begin + $visible - 1;
  $end = $end > $total_page ? $total_page : $end;
  $begin = $end - $visible + 1;
  $begin = $begin < 1 ? 1 : $begin;


  // 分类筛选
  $category = zf_fetch_all('select * from categories');
  /**
   * 封装自定义时间格式
   * @param  [string] $create [具有一定格式的时间字符串]
   * @return [string]         [自定义格式的时间字符串]
   */
  function posts_create($create){
    $timestamp = strtotime($create);
    return date('Y年m月d日<b\r>H:i:s', $timestamp);
  }

  /**
   * 封装状态中英文转换
   * @param  [string] $status [英文状态]
   * @return [string]         [中文状态]
   */
  function posts_status($status){
    $res = array(
      'drafted' => '草稿', 
      'published' => '已发布', 
      'trashed' => '回收站' 
    );
    return empty($res[$status]) ? '未知' : $res[$status];
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
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($category as $row): ?>
              <option value="<?php echo $row['id'] ?>"<?php echo isset($_GET['category']) && $_GET['category'] === $row['id'] ? ' selected' : ''?>><?php echo $row['name'] ?></option>              
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted"<?php echo isset($_GET['status']) && $_GET['status'] === 'drafted' ? 'selected' : '' ?>>草稿</option>
            <option value="published"<?php echo isset($_GET['status']) && $_GET['status'] === 'published' ? 'selected' : '' ?>>已发布</option>
            <option value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] === 'trashed' ? 'selected' : '' ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <?php for ($i=$begin; $i <= $end; $i++) : ?>
            <li<?php echo $i === $page ? " class='active'" : ''?>><a href="?page=<?php echo $i . $search; ?>"><?php echo $i; ?></a></li>            
          <?php endfor ?> 
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
          <?php foreach ($posts_list as $item): ?>
            <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $item['title'] ?></td>
              <td><?php echo $item['user_name'] ?></td>
              <td><?php echo $item['category_name'] ?></td>
              <td class="text-center"><?php echo posts_create($item['created']) ?></td>
              <td class="text-center"><?php echo posts_status($item['status']) ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="/admin/posts-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
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
