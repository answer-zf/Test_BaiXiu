<?php

require_once '../../functions.php';

$page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
$size = 30;
$offset = ($page - 1) * $size;
$count = zf_fetch_one('
	select count(1) as num 
	from comments
	inner join posts on comments.post_id = posts.id	
')['num'];
// var_dump($count);
$total_pages = (int)ceil($count / $size);
$param = sprintf(" 
	select 
	comments.*,
	posts.title as title_name
	from comments
	inner join posts on comments.post_id = posts.id	
	order by comments.created desc
	limit %s, %s
 ",$offset,$size);

$comments_data = zf_fetch_all($param);

$comments_query = json_encode(array(
	'total_pages' => $total_pages,
	'comments_data' => $comments_data	
));


header("Content-Type: json");

echo $comments_query;