<?php  

if(empty($_FILES['avatar'])){
	exit();
}
$avatar = $_FILES['avatar'];
if($avatar['error'] !== UPLOAD_ERR_OK){
	exit();
}
$ext = pathinfo($avatar['name'], PATHINFO_EXTENSION);
$target = '../../static/uploads/img-' . uniqid() . '.' . $ext;
if( !move_uploaded_file($avatar['tmp_name'], $target)){
	exit();
}
echo substr($target,5);